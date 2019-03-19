<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_packitem extends CI_Model {
    private $username;

    public function __construct() {
        parent::__construct();
        $this->username = $this->session->userdata('usn');
    }

    function getlist($arr_data) {
        $this->datatables->select("(CASE
                                WHEN a.keterangan IS NULL THEN b.nama_barang
                                WHEN a.keterangan IS NOT NULL THEN a.keterangan
                            END) AS publishdesc,a.keterangan,b.nama_barang,
                            (CASE
                                WHEN a.harga IS NOT NULL OR a.harga!='' THEN a.harga
                                WHEN a.harga IS NULL OR a.harga='' THEN '0'
                            END) AS costing,a.kodeitem,
                            (CASE
                                WHEN c.itemcode IS NOT NULL OR c.itemcode!='' THEN '1'
                                WHEN c.itemcode IS NULL OR c.itemcode='' THEN '0'
                            END) AS checkitem");
        $this->datatables->from("master_costingmcu a");
        $this->datatables->join("product_master b", "a.kodeitem=b.id_barang", "left");
        $this->datatables->join("trx_mcupackage_item c", "a.kodeitem=c.itemcode AND c.headerid='".$arr_data['headerid']."' AND c.packageid='".$arr_data['packageid']."'", "left");

        return $this->datatables->generate();
    }

    function getpackagelist($arr_data) {
        $this->datatables->select("recid,packagename,totalprice AS packageprice");
        $this->datatables->from("trx_mcupackage_header");
        $this->datatables->where("setupid", $arr_data['setupid']);
        $this->datatables->where("packageid != ", $arr_data['packageid']);

        return $this->datatables->generate();
    }

    function show_packlist($data) {
        $sql = "SELECT 
                  a.packageid,a.packagename,a.certtype,a.recid AS headerid,a.totalcost,a.profitvar,a.profitval,a.manualfee,
                  (
                    SELECT
                      SUM(costing)
                    FROM trx_mcupackage_item
                    WHERE setupid=a.setupid AND companyid=a.companyid AND packageid=a.packageid 
                  ) AS packagecost,a.setupid
               FROM trx_mcupackage_header a
               LEFT JOIN master_kerjasama b ON (a.companyid=b.companyid)
               WHERE a.companyid=? AND a.setupid=? AND a.createuser=?
               ORDER BY a.recid";
        $query = $this->db->query($sql,
            array(
                $data['companyid'],
                $data['setupid'],
                $this->username
            )
        );

        if(!$query) {
            $db_error = $this->db->error();
            $return_arr = array(
                'errmsg' => $db_error['message'],
                'msg' => ''
            );
        } else {
            $return_arr = array(
                'errmsg' => true,
                'msg' => $query
            );
        }

        return $return_arr;
    }

    function show_packdtllist($data) {
        $sql = "SELECT 
                  a.recid AS id,a.itemcode,b.nama_barang AS itemname,a.costing AS itemcost
               FROM trx_mcupackage_item a
               LEFT JOIN product_master b ON (a.itemcode=b.id_barang)
               WHERE a.companyid=? AND a.setupid=? AND a.packageid=? AND a.createuser=?
               ORDER BY a.recid DESC";
        $query = $this->db->query($sql,
            array(
                $data['companyid'],
                $data['setupid'],
                $data['packageid'],
                $this->username
            )
        );

        if(!$query) {
            $db_error = $this->db->error();
            $return_arr = array(
                'errmsg' => $db_error['message'],
                'msg' => ''
            );
        } else {
            $return_arr = array(
                'errmsg' => true,
                'msg' => $query
            );
        }

        return $return_arr;
    }

    function updatepackage($data) {
        if($data['flagid'] == "packname") {
            $updateField = "packagename=?";
            $queryType = "1";
        } elseif($data['flagid'] == "packcert") {
            $updateField = "certtype=?";
            $queryType = "1";
        } elseif($data['flagid'] == "manualfee") {
            $updateField = "manualfee=?";
            $queryType = "1";
        } elseif($data['flagid'] == "profit") {
            $updateField = "profitvar=?,profitval=?";
            $queryType = "2";
        }

        if($queryType == "1") {
            $sql = "UPDATE trx_mcupackage_header SET ".$updateField." WHERE packageid=? AND createuser=?";
            $query = $this->db->query($sql,
                array(
                    $data['resultdata'],
                    $data['packageid'],
                    $this->username
                )
            );
        } elseif($queryType == "2") {
            $sql = "UPDATE trx_mcupackage_header SET ".$updateField." WHERE packageid=? AND createuser=?";
            $query = $this->db->query($sql,
                array(
                    $data['profitpercentage'],
                    $data['profitnominal'],
                    $data['packageid'],
                    $this->username
                )
            );
        }

        if(!$query) {
            $db_error = $this->db->error();
            $return_var = $db_error['message'];
        } else {
            $return_var = "-";
        }

        return $return_var;
    }

    function selectitem($data) {
        if($data['itemstatus'] == "true") {
            $sql_cost = "SELECT harga,linkeditem,unlinkeditem FROM master_costingmcu WHERE kodeitem=?";
            $query_cost = $this->db->query($sql_cost, array($data['itemcode']));
            $costing = $query_cost->row()->harga;

            /* next process when linked item were founded */
            if (!empty($query_cost->row()->linkeditem) || $query_cost->row()->linkeditem != "") {
                /* check string splitter (,) on linked item */
                if (strpos($query_cost->row()->linkeditem, ",") > 0) {
                    /* set linked item as array variable */
                    $linkeditem = explode(",", $query_cost->row()->linkeditem);

                    /* looping linked array variable */
                    foreach ($linkeditem as $rowlink) {
                        $sql_checklink = "SELECT recid FROM trx_mcupackage_item WHERE setupid=? AND headerid=? AND companyid=? AND packageid=? AND itemcode=?";
                        $query_checklink = $this->db->query($sql_checklink,
                            array(
                                $data['setupid'],
                                $data['headerid'],
                                $data['companyid'],
                                $data['packageid'],
                                $rowlink
                            )
                        );

                        if ($query_checklink->num_rows() == 0) {
                            $sql_costlink = "SELECT harga FROM master_costingmcu WHERE kodeitem=?";
                            $query_costlink = $this->db->query($sql_costlink,
                                array(
                                    $rowlink
                                )
                            );
                            $costing_link = $query_costlink->row()->harga;

                            $sql_link = "INSERT INTO trx_mcupackage_item(setupid,headerid,companyid,packageid,itemcode,costing,createuser,createdate,createtime)
                                          VALUES(?,?,?,?,?,?,?,?,?)";
                            $query_link = $this->db->query($sql_link,
                                array(
                                    $data['setupid'],
                                    $data['headerid'],
                                    $data['companyid'],
                                    $data['packageid'],
                                    $rowlink,
                                    $costing_link,
                                    $this->username,
                                    date('Y-m-d'),
                                    date('H:i:s')
                                )
                            );
                        }
                    }
                } else {
                    /* set linked item as single variable */
                    $linkeditem = $query_cost->row()->linkeditem;

                    $sql_checklink = "SELECT recid FROM trx_mcupackage_item WHERE setupid=? AND headerid=? AND companyid=? AND packageid=? AND itemcode=?";
                    $query_checklink = $this->db->query($sql_checklink,
                        array(
                            $data['setupid'],
                            $data['headerid'],
                            $data['companyid'],
                            $data['packageid'],
                            $linkeditem
                        )
                    );

                    if ($query_checklink->num_rows() == 0) {
                        $sql_costlink = "SELECT harga FROM master_costingmcu WHERE kodeitem=?";
                        $query_costlink = $this->db->query($sql_costlink,
                            array(
                                $linkeditem
                            )
                        );
                        $costing_link = $query_costlink->row()->harga;

                        $sql_link = "INSERT INTO trx_mcupackage_item(setupid,headerid,companyid,packageid,itemcode,costing,createuser,createdate,createtime)
                                      VALUES(?,?,?,?,?,?,?,?,?)";
                        $query_link = $this->db->query($sql_link,
                            array(
                                $data['setupid'],
                                $data['headerid'],
                                $data['companyid'],
                                $data['packageid'],
                                $linkeditem,
                                $costing_link,
                                $this->username,
                                date('Y-m-d'),
                                date('H:i:s')
                            )
                        );
                    }
                }
            }

            /* next process when unlinked item were founded */
            if (!empty($query_cost->row()->unlinkeditem) || $query_cost->row()->unlinkeditem != "") {
                /* check string splitter (,) on unlinked item */
                if (strpos($query_cost->row()->unlinkeditem, ",") > 0) {
                    /* set unlinked item as array variable */
                    $unlinkeditem = explode(",", $query_cost->row()->unlinkeditem);

                    /* looping unlinked array variable */
                    foreach ($unlinkeditem as $rowunlink) {
                        /* delete unlinked item from table */
                        $sql_unlink = "DELETE FROM trx_mcupackage_item WHERE setupid=? AND headerid=? AND companyid=? AND packageid=? AND itemcode=?";
                        $query_unlink = $this->db->query($sql_unlink,
                            array(
                                $data['setupid'],
                                $data['headerid'],
                                $data['companyid'],
                                $data['packageid'],
                                $rowunlink
                            )
                        );
                    }
                } else {
                    $unlinkeditem = $query_cost->row()->unlinkeditem;

                    /* delete unlinked item from table */
                    $sql_unlink = "DELETE FROM trx_mcupackage_item WHERE setupid=? AND headerid=? AND companyid=? AND packageid=? AND itemcode=?";
                    $query_unlink = $this->db->query($sql_unlink,
                        array(
                            $data['setupid'],
                            $data['headerid'],
                            $data['companyid'],
                            $data['packageid'],
                            $unlinkeditem
                        )
                    );
                }
            }

            $sql_check = "INSERT INTO trx_mcupackage_item(setupid,headerid,companyid,packageid,itemcode,costing,createuser,createdate,createtime) 
                          VALUES(?,?,?,?,?,?,?,?,?)";
            $query = $this->db->query($sql_check,
                array(
                    $data['setupid'],
                    $data['headerid'],
                    $data['companyid'],
                    $data['packageid'],
                    $data['itemcode'],
                    $costing,
                    $this->username,
                    date('Y-m-d'),
                    date('H:i:s')
                )
            );
        } elseif($data['itemstatus'] == "false") {
            $sql_uncheck = "DELETE FROM trx_mcupackage_item WHERE itemcode=? AND createuser=? AND packageid=?";
            $query = $this->db->query($sql_uncheck, array($data['itemcode'],$this->username,$data['packageid']));
        }

        if($query) {
            $sql_basiccost = "SELECT SUM(costing) AS basiccosting FROM trx_mcupackage_item WHERE createuser=?";
            $query_basiccost = $this->db->query($sql_basiccost, array($this->username));
            $setbasic = $query_basiccost->row()->basiccosting;

            $db_error = true;
            $basic_cost = $setbasic;
        } else {
            $get_errmsg = $this->db->error();
            $db_error = $get_errmsg['message'];
            $basic_cost = 0;
        }

        $arr_data = array(
            'errmsg' => $db_error,
            'linkeditem' => $query_cost->row()->linkeditem,
            'unlinkeditem' => $query_cost->row()->unlinkeditem,
            'basic_cost' => number_format($basic_cost)
        );

        return $arr_data;
    }

    function selectitem_perpackage($arr_data) {
        $sql1 = "SELECT 
                  a.recid AS id,a.itemcode,b.nama_barang AS itemname,a.costing AS itemcost 
                FROM trx_mcupackage_item a
                LEFT JOIN product_master b ON (a.itemcode=b.id_barang) 
                WHERE a.headerid=? AND a.packageid=?
                ORDER BY a.recid DESC";
        $query1 = $this->db->query($sql1,
            array(
                $arr_data['headerid'],
                $arr_data['packageid']
            )
        );

        if($query1->num_rows() > 0) {
            if(!$query1) {
                $db_error = $this->db->error();
                $arr_return = array(
                    'errmsg' => $db_error['message'],
                    'data' => '',
                    'examcosting' => ''
                );
            } else {
                $sql2 = "SELECT SUM(costing) AS examcosting FROM trx_mcupackage_item WHERE headerid=? AND packageid=?";
                $query2 = $this->db->query($sql2,
                    array(
                        $arr_data['headerid'],
                        $arr_data['packageid']
                    )
                );
                if(!$query2) {
                    $db_error = $this->db->error();
                    $arr_return = array(
                        'errmsg' => $db_error['message'],
                        'data' => '',
                        'examcosting' => ''
                    );
                } else {
                    $row2 = $query2->row();

                    $sql3 = "UPDATE trx_mcupackage_header SET examcost=? WHERE recid=? AND packageid=?";
                    $query3 = $this->db->query($sql3,
                        array(
                            $row2->examcosting,
                            $arr_data['headerid'],
                            $arr_data['packageid']
                        )
                    );

                    if(!$query3) {
                        $db_error = $this->db->error();
                        $arr_return = array(
                            'errmsg' => $db_error['message'],
                            'data' => '',
                            'examcosting' => ''
                        );
                    } else {
                        $arr_return = array(
                            'errmsg' => '-',
                            'data' => $query1,
                            'examcosting' => number_format($row2->examcosting)
                        );
                    }
                }
            }
        } else {
            $arr_return = array(
                'errmsg' => 'Record not found',
                'data' => '',
                'examcosting' => ''
            );
        }

        return $arr_return;
    }

    function deleteitem_perpackage($arr_data) {
        /* get linked item from master table */
        $sql_link = "SELECT linkeditem FROM master_costingmcu WHERE kodeitem=?";
        $query_link = $this->db->query($sql_link,
            array(
                $arr_data['itemcode']
            )
        );

        if(!$query_link) {
            $db_error = $this->db->error();
            $return_arr = array(
                'errmsg' => $db_error['message'],
                'detailpackage' => '',
                'examcosting' => '0'
            );
        } else {
            /* next process when linked item were founded */
            if( !empty($query_link->row()->linkeditem) || $query_link->row()->linkeditem != "" ) {
                /* check string splitter (,) on linked item */
                if (strpos($query_link->row()->linkeditem, ",") > 0) {
                    /* set linked item as array variable */
                    $linkeditem = explode(",", $query_link->row()->linkeditem);

                    /* looping linked array variable */
                    foreach ($linkeditem as $rowlink) {
                        $sql_dellink = "DELETE FROM trx_mcupackage_item WHERE headerid=? AND packageid=? AND itemcode=?";
                        $query_dellink = $this->db->query($sql_dellink,
                            array(
                                $arr_data['headerid'],
                                $arr_data['packageid'],
                                $rowlink
                            )
                        );
                    }
                } else {
                    /* set linked item as single variable */
                    $linkeditem = $query_link->row()->linkeditem;

                    $sql_dellink = "DELETE FROM trx_mcupackage_item WHERE headerid=? AND packageid=? AND itemcode=?";
                    $query_dellink = $this->db->query($sql_dellink,
                        array(
                            $arr_data['headerid'],
                            $arr_data['packageid'],
                            $linkeditem
                        )
                    );
                }
            }

            $sql_dellink = "DELETE FROM trx_mcupackage_item WHERE headerid=? AND packageid=? AND itemcode=?";
            $query_dellink = $this->db->query($sql_dellink,
                array(
                    $arr_data['headerid'],
                    $arr_data['packageid'],
                    $arr_data['itemcode']
                )
            );

            $sql2 = "SELECT SUM(costing) AS examcosting FROM trx_mcupackage_item WHERE headerid=? AND packageid=?";
            $query2 = $this->db->query($sql2,
                array(
                    $arr_data['headerid'],
                    $arr_data['packageid']
                )
            );
            if(!$query2) {
                $db_error = $this->db->error();
                $return_arr = array(
                    'errmsg' => $db_error['message'],
                    'detailpackage' => '',
                    'examcosting' => '0'
                );
            } else {
                $row2 = $query2->row();

                $sql3 = "SELECT 
                          a.recid AS id,a.itemcode,b.nama_barang AS itemname,a.costing AS itemcost 
                        FROM trx_mcupackage_item a
                        LEFT JOIN product_master b ON (a.itemcode=b.id_barang) 
                        WHERE a.headerid=? AND a.packageid=?
                        ORDER BY a.recid DESC";
                $query3 = $this->db->query($sql3,
                    array(
                        $arr_data['headerid'],
                        $arr_data['packageid']
                    )
                );

                if(!$query3) {
                    $db_error = $this->db->error();
                    $return_arr = array(
                        'errmsg' => $db_error['message'],
                        'detailpackage' => '',
                        'examcosting' => '0'
                    );
                } else {
                    if(!empty($row2->examcosting) || $row2->examcosting != "") {
                        $examcosting = $row2->examcosting;
                    } else {
                        $examcosting = 0;
                    }

                    $updatefield = array(
                        'examcost' => $examcosting
                    );

                    $wherecondition = array(
                        'recid' => $arr_data['headerid'],
                        'packageid' => $arr_data['packageid']
                    );

                    $sql_examcost = $this->db->update('trx_mcupackage_header',$updatefield,$wherecondition);

                    if(!$sql_examcost) {
                        $db_error = $this->db->error();
                        $return_arr = array(
                            'errmsg' => $db_error['message'],
                            'detailpackage' => '',
                            'examcosting' => '0'
                        );
                    } else {
                        $db_error = "-";
                        $return_arr = array(
                            'errmsg' => $db_error,
                            'detailpackage' => $query3,
                            'examcosting' => $row2->examcosting
                        );
                    }
                }
            }
        }

        return $return_arr;
    }

    function selectpackage($arr_data) {
        $sql0 = "DELETE FROM trx_mcupackage_item WHERE headerid=? AND setupid=? AND packageid=?";
        $query0 = $this->db->query($sql0,
            array(
                $arr_data['headerid'],
                $arr_data['setupid'],
                $arr_data['packageid']
            )
        );

        if(!$query0) {
            $db_error = $this->db->error();
            $arr_return = array(
                'errmsg' => $db_error['message'],
                'data' => '',
                'examcosting' => ''
            );
        } else {
            $sql = "INSERT INTO 
                        trx_mcupackage_item(setupid,headerid,companyid,packageid,itemcode,costing,createuser,createdate,createtime)
                   SELECT
                        setupid,?,companyid,?,itemcode,costing,?,?,?
                   FROM trx_mcupackage_item 
                   WHERE headerid=? AND setupid=?";
            $query = $this->db->query($sql,
                array(
                    $arr_data['headerid'],
                    $arr_data['packageid'],
                    $this->username,
                    date('Y-m-d'),
                    date('H:i:s'),
                    $arr_data['recid'],
                    $arr_data['setupid']
                )
            );

            if (!$query) {
                $db_error = $this->db->error();
                $arr_return = array(
                    'errmsg' => $db_error['message'],
                    'data' => '',
                    'examcosting' => ''
                );
            } else {
                $sql1 = "SELECT 
                          a.recid AS id,a.itemcode,b.nama_barang AS itemname,a.costing AS itemcost 
                        FROM trx_mcupackage_item a
                        LEFT JOIN product_master b ON (a.itemcode=b.id_barang) 
                        WHERE a.headerid=? AND a.packageid=?
                        ORDER BY a.recid DESC";
                $query1 = $this->db->query($sql1,
                    array(
                        $arr_data['headerid'],
                        $arr_data['packageid']
                    )
                );

                if ($query1->num_rows() > 0) {
                    if (!$query1) {
                        $db_error = $this->db->error();
                        $arr_return = array(
                            'errmsg' => $db_error['message'],
                            'data' => '',
                            'examcosting' => ''
                        );
                    } else {
                        $sql2 = "SELECT SUM(costing) AS examcosting FROM trx_mcupackage_item WHERE headerid=? AND packageid=?";
                        $query2 = $this->db->query($sql2,
                            array(
                                $arr_data['headerid'],
                                $arr_data['packageid']
                            )
                        );
                        if (!$query2) {
                            $db_error = $this->db->error();
                            $arr_return = array(
                                'errmsg' => $db_error['message'],
                                'data' => '',
                                'examcosting' => ''
                            );
                        } else {
                            $row2 = $query2->row();

                            $sql3 = "UPDATE trx_mcupackage_header SET examcost=? WHERE recid=? AND packageid=?";
                            $query3 = $this->db->query($sql3,
                                array(
                                    $row2->examcosting,
                                    $arr_data['headerid'],
                                    $arr_data['packageid']
                                )
                            );

                            if (!$query3) {
                                $db_error = $this->db->error();
                                $arr_return = array(
                                    'errmsg' => $db_error['message'],
                                    'data' => '',
                                    'examcosting' => ''
                                );
                            } else {
                                $arr_return = array(
                                    'errmsg' => '-',
                                    'data' => $query1,
                                    'examcosting' => number_format($row2->examcosting)
                                );
                            }
                        }
                    }
                } else {
                    $arr_return = array(
                        'errmsg' => 'Record not found',
                        'data' => '',
                        'examcosting' => ''
                    );
                }
            }
        }

        return $arr_return;
    }

    function fixedcost() {
        $sql = "SELECT 
                  itemcode,costing,
                  (CASE
                      WHEN itemcode='medreccost' THEN 'Medical Report (Hardcopy)'
                      WHEN itemcode='delivercost' THEN 'Delivery Cost'
                      WHEN itemcode='laundrycost' THEN 'Laundry Cost'
                      WHEN itemcode='renovcost' THEN 'Renovation Cost'
                      WHEN itemcode='admcost' THEN 'Clinic Adm Allocation'
                  END) as itemdesc 
                FROM master_costingmcu_fixed 
                ORDER BY recid ASC";
        $query = $this->db->query($sql);

        if(!$query) {
            $db_error = $this->db->error();
            return $db_error['message'];
        } else {
            return $query;
        }
    }

    function updatepackageprice($arr_val) {
        $updatefield = array(
            'totalprice' => $arr_val['packageprice']
        );

        $wherecondition = array(
            'setupid' => $arr_val['setupid'],
            'companyid' => $arr_val['companyid'],
            'packageid' => $arr_val['packageid']
        );

        $query = $this->db->update('trx_mcupackage_header',$updatefield,$wherecondition);

        if(!$query) {
            $db_error = $this->db->error();
            $errmsg = $db_error['message'];
        } else {
            $errmsg = "-";
        }

        return $errmsg;
    }

    function deletepackage($headerid) {
        $sql1 = "DELETE FROM trx_mcupackage_header WHERE recid=?";
        $query1 = $this->db->query($sql1,
            array(
                $headerid
            )
        );

        if(!$query1) {
            $db_error = $this->db->error();
            $errmsg = $db_error['message'];
        } else {
            $sql2 = "DELETE FROM trx_mcupackage_item WHERE headerid=?";
            $query2 = $this->db->query($sql2,
                array(
                    $headerid
                )
            );

            if(!$query2) {
                $db_error = $this->db->error();
                $errmsg = $db_error['message'];
            } else {
                $errmsg = "-";
            }
        }

        return $errmsg;
    }

    function checkpackagestatus($arr) {
        $sql = "SELECT
                    (CASE
                        WHEN ( (a.`packagename` IS NULL OR a.`packagename`='') 
                            OR 
                              (a.`certtype` IS NULL OR a.`certtype`='')
                            OR
                              (COUNT(b.`itemcode`) = 0) ) THEN '0'
                        WHEN ( (a.`packagename` IS NOT NULL OR a.`packagename`!='') 
                            OR 
                              (a.`certtype` IS NOT NULL OR a.`certtype`!='')
                            OR
                              (COUNT(b.`itemcode`) != 0) ) THEN '1'      
                    END) AS getstatus
                FROM `trx_mcupackage_header` a 
                LEFT JOIN `trx_mcupackage_item` b ON (a.`recid`=b.`headerid`)
                WHERE a.setupid=? AND a.companyid=? AND a.createuser=?
                GROUP BY a.`packageid`";
        $query = $this->db->query($sql,
            array(
                $arr['setupid'],
                $arr['companyid'],
                $this->username
            )
        );

        if(!$query) {
            $db_error = $this->db->error();
            $errmsg = $db_error['message'];
            $status = "-";
        } else {
            if($query->num_rows() > 0) {
                foreach ($query->result() as $item) {
                    $arr_status[] = array($item->getstatus);
                }

                $status = json_encode($arr_status);
            } else {
                $status = "-";
            }
            $errmsg = "-";
        }

        $arr_var = array(
            'errmsg' => $errmsg,
            'status' => $status
        );

        return $arr_var;
    }

    function updatepackagestatus($arr) {
        $select1 = "SELECT 
                      packagelanguage,startperiode,endperiode,packtop,packagenote 
                    FROM trx_mcupackage_setup 
                    WHERE recid=? AND companyid=?";
        $query1 = $this->db->query($select1,
            array(
                $arr['setupid'],
                $arr['companyid']
            )
        );

        if(!$query1) {
            $dberr = $this->db->error();
            $arr_var = array('errmsg' => $dberr['message'],'status' => false);
        } else {
            $row1 = $query1->row();

            $select2 = "SELECT 
                            packageid,packagename,certtype,
                            examcost,basiccost,totalcost,profitvar,profitval,
                            manualfee,totalprice,createuser,createdate,
                            createtime 
                        FROM trx_mcupackage_header 
                        WHERE setupid=? AND companyid=?";
            $query2 = $this->db->query($select2,
                array(
                    $arr['setupid'],
                    $arr['companyid']
                )
            );

            if(!$query2) {
                $dberr = $this->db->error();
                $arr_var = array('errmsg' => $dberr['message'],'status' => false);
            } else {
                if($query2->num_rows()) {
                    foreach ($query2->result_array() as $row2) {
                        $insert2_1 = "INSERT INTO
                                        service_mastermcu(activestatus,contractid,companyid,serviceid,description,notes_im,languageresult,
                                        firstdate,lastdate,maxdebt,packageprice,createuser,createdate,createtime,needapproval)
                                     VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                        $query2_1 = $this->db->query($insert2_1,
                            array(
                                '0',
                                $arr['setupid'],
                                $arr['companyid'],
                                $row2['packageid'],
                                $row2['packagename'],
                                $row1->packagenote,
                                $row1->packagelanguage,
                                $row1->startperiode,
                                $row1->endperiode,
                                $row1->packtop,
                                $row2['totalprice'],
                                $this->username,
                                date('Y-m-d'),
                                date('H:i:s'),
                                '1'
                            )
                        );

                        if (!$query2_1) {
                            $dberr = $this->db->error();
                            $arr_var = array('errmsg' => $dberr['message'], 'status' => false);
                            break;
                        } else {
                            $select3 = "SELECT
                                            setupid,headerid,companyid,packageid,
                                            itemcode,costing,createuser,createdate,
                                            createtime
                                        FROM
                                            trx_mcupackage_item
                                        WHERE setupid=? AND companyid=? AND packageid=?";
                            $query3 = $this->db->query($select3,
                                array(
                                    $arr['setupid'],
                                    $arr['companyid'],
                                    $row2['packageid']
                                )
                            );

                            if(!$query3) {
                                $dberr = $this->db->error();
                                $arr_var = array('errmsg' => $dberr['message'], 'status' => false);
                                break;
                            } else {
                                if($query3->num_rows()) {
                                    $select4 = "SELECT SUM(b.tarif) AS totaltarif FROM trx_mcupackage_item a
                                                LEFT JOIN product_master b ON (a.itemcode=b.id_barang)
                                                WHERE a.setupid=? AND a.companyid=? AND a.packageid=?";
                                    $query4 = $this->db->query($select4,
                                        array(
                                            $arr['setupid'],
                                            $arr['companyid'],
                                            $row2['packageid']
                                        )
                                    );
                                    $row4 = $query4->row();
                                    $TotalNormalPrice = $row4->totaltarif;

                                    foreach ($query3->result_array() as $row3) {
                                        $item = "SELECT tarif FROM product_master WHERE id_barang=?";
                                        $query_item = $this->db->query($item,
                                            array($row3['itemcode'])
                                        );
                                        if ($query_item->num_rows() > 0) {
                                            $row_item = $query_item->row();
                                            $tarif = $row_item->tarif;
                                        } else {
                                            $tarif = 0;
                                        }

                                        $count = $query3->num_rows();
                                        $Counter = 1;
                                        $Sum = 0;

                                        $Pembagi = doubleval($TotalNormalPrice);
                                        $NormalCost = doubleval($tarif);
                                        $Calculation = (round(($NormalCost / $Pembagi) * $row2['totalprice'] / 500, 0) * 500);
                                        if ($count != $Counter) {
                                            $Sum = $Sum + $Calculation;
                                        } else {
                                            $Calculation = $row2['totalprice'] - $Sum;
                                        }

                                        $insert3_1 = "INSERT INTO
                                                        mcukontrak_product(ContractID,ServiceID,ProductID,
                                                        BaseCost,NormalPrice,ContractPrice,PaketName,PaketID,
                                                        CreateUser,CreateDate,CreateTime)
                                                      VALUES(?,?,?,?,?,?,?,?,?,?,?)";
                                        $query3_1 = $this->db->query($insert3_1,
                                            array(
                                                $row3['setupid'],
                                                $row3['packageid'],
                                                $row3['itemcode'],
                                                $row3['costing'],
                                                $tarif,
                                                $Calculation,
                                                $row2['packagename'],
                                                $row3['packageid'],
                                                $row3['createuser'],
                                                $row3['createdate'],
                                                $row3['createtime']
                                            )
                                        );

                                        if (!$query3_1) {
                                            $dberr = $this->db->error();
                                            $arr_var = array('errmsg' => $dberr['message'], 'status' => false);
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $update = "UPDATE trx_mcupackage_setup SET approvalstatus=? WHERE recid=?";
                    $query_update = $this->db->query($update,
                        array(
                            "1",
                            $arr['setupid']
                        )
                    );

                    if(!$query_update) {
                        $dberr = $this->db->error();
                        $arr_var = array('errmsg' => $dberr['message'],'status' => false);
                    } else {
                        $arr_var = array('errmsg' => "-",'status' => true);
                    }
                } else {
                    $arr_var = array('errmsg' => 'Record not found','status' => false);
                }
            }
        }

        return $arr_var;
    }

    function checkapprovalstatus($arr) {
        $sql = "SELECT approvalstatus FROM trx_mcupackage_setup WHERE recid=? AND companyid=?";
        $query = $this->db->query($sql, array(
            $arr['setupid'],
            $arr['companyid']
        ));

        if(!$query) {
            $dberror = $this->db->error();
            $errmsg = $dberror['message'];
            $approvalstatus = "-";
        } else {
            $row = $query->row();
            $errmsg = "-";
            $approvalstatus = $row->approvalstatus;
        }

        $arrmsg = array(
            'errmsg' => $errmsg,
            'approvalstatus' => $approvalstatus
        );

        return $arrmsg;
    }

    function savetodraft($arr) {
        $sql = "UPDATE trx_mcupackage_setup SET draftstatus=? WHERE recid=? AND companyid=?";
        $query = $this->db->query($sql,
            array(
                '1',
                $arr['setupid'],
                $arr['companyid']
            )
        );

        if(!$query) {
            $dberror = $this->db->error();
            $errmsg = $dberror['message'];
        } else {
            $errmsg = "-";
        }

        return $errmsg;
    }

    function packdesc_preview($data) {
        $sql1 = "SELECT 
                    a.startperiode,a.endperiode,a.packagelanguage,a.resulttype,a.packtop,a.imgid,b.companyname,c.namakaryawan 
                FROM trx_mcupackage_setup a
                LEFT JOIN master_kerjasama b ON (a.companyid=b.companyid)
                LEFT JOIN master_employee c ON (b.accountid=c.idkaryawan)
                WHERE a.recid=? AND a.companyid=?";
        $query1 = $this->db->query($sql1,
            array(
                $data['setupid'],
                $data['companyid']
            )
        );

        if($query1) {
            $sql2 = "SELECT packagename,totalprice FROM trx_mcupackage_header WHERE setupid=? AND companyid=?";
            $query2 = $this->db->query($sql2,
                array(
                    $data['setupid'],
                    $data['companyid']
                )
            );

            $sql3 = "SELECT
                    a.`packageid`,a.`packagename`,c.`keterangan`,a.`totalprice`
                FROM `trx_mcupackage_header` a
                LEFT JOIN `trx_mcupackage_item` b ON (a.`recid`=b.`headerid`)
                LEFT JOIN `master_costingmcu` c ON (b.`itemcode`=c.`kodeitem`)
                WHERE a.`setupid`=? AND a.`companyid`=?
                ORDER BY a.`packageid`,c.`keterangan`";
            $query3 = $this->db->query($sql3,
                array(
                    $data['setupid'],
                    $data['companyid']
                )
            );

            if($query3) {
                $return = array(
                    'errmsg' => "-",
                    'packageheader' => $query1->row(),
                    'packagedetail_email' => $query2->result(),
                    'packagedetail' => $query3->result()
                );
            } else {
                $dberror = $this->db->error();
                $return = array(
                    'errmsg' => $dberror['message'],
                    'packagedata' => ""
                );
            }
        } else {
            $dberror = $this->db->error();
            $return = array(
                'errmsg' => $dberror['message'],
                'packagedata' => ""
            );
        }

        return $return;
    }

//    function showpack($data) {
//        $sql = "SELECT
//                  a.imnum,a.packagename,DATE_FORMAT(a.startperiode, '%d/%m/%Y') AS stperiode,
//                  DATE_FORMAT(a.endperiode, '%d/%m/%Y') AS edperiode,
//                  b.companyname
//               FROM trx_mcupackage_header a
//               LEFT JOIN master_kerjasama b ON (a.companyid=b.companyid)
//               WHERE a.companyid=? AND a.setupid=? AND a.createuser=?";
//        $query = $this->db->query($sql, array($data['companyid'],$data['setupid'],$this->username));
//        if($query->num_rows() == 1) {
//            $row = $query->row();
//
//            $sql_basiccost = "SELECT SUM(costing) AS basiccosting FROM trx_mcupackage_item WHERE createuser=?";
//            $query_basiccost = $this->db->query($sql_basiccost, array($this->username));
//            $basic_cost = $query_basiccost->row()->basiccosting;
//
//            if($basic_cost != 0) {
//                $fixed_cost = "SELECT
//                                  costing,itemcode
//                                FROM master_costingmcu_fixed
//                                ORDER BY recid ASC";
//                $query_fixedcost = $this->db->query($fixed_cost);
//
//                $medreccost = 0; $delivercost = 0; $laundrycost = 0;
//                foreach ($query_fixedcost->result() as $rowfixcost) {
//                    if($rowfixcost->itemcode == "medreccost") {
//                        $medreccost = $rowfixcost->costing;
//                    } elseif($rowfixcost->itemcode == "delivercost") {
//                        $delivercost = $rowfixcost->costing;
//                    } elseif($rowfixcost->itemcode == "laundrycost") {
//                        $laundrycost = $rowfixcost->costing;
//                    }
//                }
//                $totalcost = doubleval($basic_cost) + doubleval($medreccost) + doubleval($delivercost) + doubleval($laundrycost);
//                $set_profit = ($totalcost * 10) / 100;
//            } else {
//                $set_profit = 0;
//            }
//
//            $arr_data = array(
//                'imnum' => $row->imnum,
//                'packname' => $row->packagename,
//                'stperiode' => $row->stperiode,
//                'edperiode' => $row->edperiode,
//                'compname' => $row->companyname,
//                'basic_cost' => $basic_cost,
//                'profit' => intval($set_profit)
//            );
//
//            return $arr_data;
//        } else {
//            return false;
//        }
//    }

//    function packdetail_preview($data) {
//        $sql = "SELECT
//                  b.nama_barang AS itemname,a.costing
//                FROM trx_mcupackage_item a
//                LEFT JOIN product_master b ON (a.itemcode=b.id_barang)
//                WHERE a.companyid=? AND a.packageid=? AND a.createuser=?
//                ORDER BY a.recid DESC";
//        $query = $this->db->query($sql, array($data['companyid'],$data['packageid'],$this->username));
//
//        if($query) {
//            return $query->result();
//        } else {
//            $db_error = $this->db->error();
//            return $db_error['message'];
//        }
//    }

    function submitpackage($data) {
        $sql1 = "INSERT INTO 
                      service_mastermcu(activestatus,companyid,serviceid,description,contractid,firstdate,lastdate,languageresult,
                      limitdata,maxdebt,notes_im,totalcost,factorprice,cosharingservice,cosharingpatient,packageprice,
                      createuser,createdate,createtime,needapproval)
                SELECT
                      ?,companyid,packageid,packagename,imnum,startperiode,endperiode,packagelanguage,
                      certtype,packtop,packagenote,totalcost,profitvar,profitval,manualfee,totalprice,
                      ?,?,?,?
                FROM trx_mcupackage 
                WHERE companyid=? AND packageid=? AND createuser=?";
        $query1 = $this->db->query($sql1,
            array(
                '0',
                $this->username,
                date('Y-m-d'),
                date('H:i:s'),
                '0',
                $data['companyid'],
                $data['packageid'],
                $this->username
            )
        );

        if(!$query1) {
            $db_error = $this->db->error();
            $err_msg = $db_error['code'].": ".$db_error['message'];
        } else {
            $sql_slc2 = "SELECT
                            b.imnum,a.itemcode,a.costing,c.tarif,b.packagename
                        FROM trx_mcupackage_item a
                            LEFT JOIN trx_mcupackage b ON (a.companyid=b.companyid AND a.packageid=b.packageid)
                            LEFT JOIN product_master c ON (a.itemcode=c.id_barang)
                        WHERE a.companyid=? 
                            AND a.packageid=? 
                            AND a.createuser=?";
            $query_slc2 = $this->db->query($sql_slc2, array($data['companyid'], $data['packageid'], $this->username));

            $sql_tarif = "SELECT
                            sum(b.tarif) AS totaltarif
                        FROM trx_mcupackage_item a
                            LEFT JOIN product_master b ON (a.itemcode=b.id_barang)
                        WHERE a.companyid=? 
                            AND a.packageid=? 
                            AND a.createuser=?";
            $query_tarif = $this->db->query($sql_tarif, array($data['companyid'], $data['packageid'], $this->username));
            $total_normalprice = doubleval($query_tarif->row()->totaltarif);

            $count_tarif = $query_slc2->num_rows();
            $counter = 1;
            $sum = 0;
            foreach ($query_slc2->result() as $row_slc2) {
                $normalprice = doubleval($row_slc2->tarif);
                $calc_price = (round(($normalprice / $total_normalprice) * doubleval($data['packageprice']) / 500, 0) * 500);

                if($count_tarif != $counter) {
                    $sum = $sum + $calc_price;
                } else {
                    $calc_price = $data['packageprice'] - $sum;
                }

                $sql_ins2 = "INSERT INTO
                                mcukontrak_product(contractid,serviceid,productid,basecost,
                                normalprice,contractprice,paketid,paketname,createuser,createdate,createtime)
                             VALUES(?,?,?,?,?,?,?,?,?,?,?)";
                $query_ins2 = $this->db->query($sql_ins2,
                    array(
                        $row_slc2->imnum,
                        $data['packageid'],
                        $row_slc2->itemcode,
                        $row_slc2->costing,
                        $row_slc2->tarif,
                        $calc_price,
                        $data['packageid'],
                        $row_slc2->packagename,
                        $this->username,
                        date('Y-m-d'),
                        date('H:i:s')
                    )
                );
                $counter++;
            }

            if(!$query_slc2) {
                $db_error = $this->db->error();
                $err_msg = $db_error['code'].": ".$db_error['message'];
            } else {
                $err_msg = "-";
            }
        }

        return $err_msg;
    }
}
?>