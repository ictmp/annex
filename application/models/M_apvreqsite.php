<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_apvreqsite extends CI_Model {
    private $username;

    function __construct() {
        parent::__construct();

        $this->username = $this->session->userdata('usn');
    }

    function getreqlist() {
        $this->datatables->select("a.regjournal,a.internalcodenumber,a.sourceid,DATE_FORMAT(a.transactdate, '%d/%m/%Y') AS tRequest,b.namagudang,a.transactdate");
        $this->datatables->from("xt0 a");
        $this->datatables->join("master_warehouse b", "(a.sourceid=b.kodegudang OR a.sourceid=b.sapcode)", "left");
        $this->datatables->where("a.departmentid","REQ");

        return $this->datatables->generate();
    }

    function getitemlist($regjournal) {
        $this->datatables->select("recid,itemcode,itemname,qty,qtyunit,unitpackage,convfactor,itemseq,checkitem,approvalstatus");
        $this->datatables->from("xtorderitem");
        $this->datatables->where("regjournal",$regjournal);

        return $this->datatables->generate();
    }

    function checkstatus($regjournal) {
        $sql = "SELECT COUNT(recid) AS totaluncheck FROM xtorderitem WHERE regjournal=? AND checkitem=?";
        $query = $this->db->query($sql, array($regjournal, '0'));

        if( !$query ) {
            $reqstatus = "btn-danger";
        } else {
            $row = $query->row();

            if(isset($row)) {
                /* complete */
                if( $row->totaluncheck == 0 ) {
                    $reqstatus = "btn-success";
                    /* incomplete */
                } else {
                    $reqstatus = "btn-warning";
                }
            } else {
                $reqstatus = "btn-danger";
            }
        }

        return $reqstatus;
    }

    function getdetail($regjournal) {
        $sql = "SELECT 
                  a.domainid AS kodesite,b.namasite,a.internalcodenumber AS norequest   
                FROM xt0 a
                LEFT JOIN master_site b ON (a.domainid=b.kodesite OR a.domainid=b.sapcode)
                WHERE regjournal=?";
        $query = $this->db->query($sql, array($regjournal));

        if(!$query) {
            $dberror = $this->db->error();
            $return_array = array(
                'errmsg' => $dberror['message'],
                'norequest' => '-',
                'kodesite' => '-',
                'namasite' => '-'
            );
        } else {
            $row = $query->row();

            $return_array = array(
                'errmsg' => '-',
                'norequest' => $row->norequest,
                'kodesite' => $row->kodesite,
                'namasite' => $row->namasite
            );
        }

        return $return_array;
    }

    function selectitem($recid) {
        if( $arr_data['CheckBoxStatus'] == "true" ) {
            $sql_check = "SELECT recid FROM trx_orderitem WHERE kodeitem=? AND kodegudang=?";
            $query_check = $this->db->query($sql_check,
                array(
                    $arr_data['ItemCode'],
                    $arr_data['WhsCode']
                )
            );

            if($query_check->num_rows() == 0) {
                $sql_max = "SELECT MAX(urutanobat) AS maxseq FROM trx_orderitem WHERE kodegudang=?";
                $query_max = $this->db->query($sql_max,
                    array(
                        $arr_data['WhsCode']
                    )
                );

                $seqnum = intval($query_max->row()->maxseq) + 1;

                $sql_ins = "INSERT INTO trx_orderitem(tujuan,kodeitem,namaitem,kodegudang,urutanobat,createuser,createdate,createtime) VALUES(?,?,?,?,?,?,?,?)";
                $query_ins = $this->db->query($sql_ins,
                    array(
                        'JKMP0001',
                        $arr_data['ItemCode'],
                        $arr_data['ItemName'],
                        $arr_data['WhsCode'],
                        $seqnum,
                        $this->username,
                        date('Y-m-d'),
                        date('H:i:s')
                    )
                );

                if(!$query_ins) {
                    $dberror = $this->db->error();
                    return $dberror['message'];
                } else {
                    return "-";
                }
            }
        } elseif( $arr_data['CheckBoxStatus'] == "false" ) {
            $sql_del = "DELETE FROM trx_orderitem WHERE kodeitem=? AND kodegudang=?";
            $query_del = $this->db->query($sql_del,
                array(
                    $arr_data['ItemCode'],
                    $arr_data['WhsCode']
                )
            );

            if(!$query_del) {
                $dberror = $this->db->error();
                return $dberror['message'];
            } else {
                return "-";
            }
        }
    }

    function selectuom($arr_data) {
        $QtyUnit = intval($arr_data['QtyPack']) * intval($arr_data['Qty']);

        $sql = "UPDATE trx_orderitem SET keteranganunit=?,convfactor=?,qtyunit=? WHERE kodeitem=? AND kodegudang=?";
        $query = $this->db->query($sql,
            array(
                $arr_data['UnitPack'],
                $arr_data['QtyPack'],
                $QtyUnit,
                $arr_data['ItemCode'],
                $arr_data['WhsCode']
            )
        );

        if( !$query ) {
            $dberror = $this->db->error();
            return $dberror['message'];
        } else {
            return "-";
        }


    }

    function updateqty($arr_data) {
        $sql_qty = "SELECT qtyunit FROM trx_orderitem WHERE kodeitem=? AND kodegudang=?";
        $query_qty = $this->db->query($sql_qty,
            array(
                $arr_data['ItemCode'],
                $arr_data['WhsCode']
            )
        );
        $rowQty = $query_qty->row();

        if(isset($rowQty)) {
            if(!empty($rowQty->qtyunit)) {
                $QtyUnit = intval($arr_data['RowValue']) * intval($rowQty->qtyunit);
            } else {
                $QtyUnit = intval($arr_data['RowValue']);
            }
        }

        $sql = "UPDATE trx_orderitem SET jumlah=?,qtyunit=? WHERE kodeitem=? AND kodegudang=?";
        $query = $this->db->query($sql,
            array(
                $arr_data['RowValue'],
                $QtyUnit,
                $arr_data['ItemCode'],
                $arr_data['WhsCode']
            )
        );

        if( !$query ) {
            $dberror = $this->db->error();
            return $dberror['message'];
        } else {
            return "-";
        }
    }

    function exportxls($kodegudang) {
        $sql_tujuan = "SELECT a.tujuan,b.namagudang FROM trx_orderitem a 
                       LEFT JOIN master_warehouse b ON (a.tujuan=b.sapcode) 
                       WHERE a.kodegudang=? GROUP BY a.kodegudang";
        $query_tujuan = $this->db->query($sql_tujuan, array($kodegudang));
        $row_tujuan = $query_tujuan->row();

        if(isset($row_tujuan)) {
            $gudangtujuan = $row_tujuan->namagudang;
        }

        $sql_asal = "SELECT namagudang FROM master_warehouse WHERE sapcode=?";
        $query_asal = $this->db->query($sql_asal, array($kodegudang));
        $row_asal = $query_asal->row();

        if(isset($row_asal)) {
            $gudangasal = $row_asal->namagudang;
        }

        $sql_detail = "SELECT kodeitem,namaitem,jumlah,keteranganunit FROM trx_orderitem WHERE kodegudang=?";
        $query_detail = $this->db->query($sql_detail, array($kodegudang));

        return array('reqtujuan' => $gudangtujuan, 'reqasal' => $gudangasal, 'reqdetail' => $query_detail);
    }

    function postingreq($arr_data) {
        $sql_header = "INSERT INTO 
                          xt0(DomainID,DepartmentID,RegJournal,InternalCodeNumber,SourceID,TargetID,NotesA,CreateDate,
                          CreateTime,TransactDate,TransactTime,MakerUID,MakerSID) 
                        VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $query_header = $this->db->query($sql_header,
            array(
                $arr_data['SiteCode'],
                $arr_data['NumberingCode'],
                $arr_data['TransactionID'],
                $arr_data['DocumentNumber'],
                $arr_data['WhsCode'],
                'JKMP0001',
                '',
                date('Y-m-d'),
                date('H:i:s'),
                date('Y-m-d'),
                date('H:i:s'),
                $this->username,
                $arr_data['IPAddress']
            )
        );

        if( !$query_header ) {
            $dberror = $this->db->error();
            $return_array = array('errmsg' => 'Mitemreqsite.1: '.$dberror['message']);
        } else {
            $headerId = $this->db->insert_id();

            $sql_detail = "INSERT INTO 
                              xtorderitem(whscode_source,whscode_dest,itemcode,itemname,qty,qtyunit,unitpackage,convfactor,
                              itemseq,createuser,createdate,createtime,recid_header,regjournal,domainid)
                           SELECT
                              ?,tujuan,kodeitem,namaitem,jumlah,qtyunit,keteranganunit,convfactor,
                              urutanobat,?,?,?,?,?,?
                           FROM trx_orderitem
                           WHERE kodegudang=?
                           ORDER BY urutanobat";
            $query_detail = $this->db->query($sql_detail,
                array(
                    $arr_data['WhsCode'],
                    $this->username,
                    date('Y-m-d'),
                    date('H:i:s'),
                    $headerId,
                    $arr_data['TransactionID'],
                    $arr_data['SiteCode'],
                    $arr_data['WhsCode']
                )
            );

            if ( !$query_detail ) {
                $dberror = $this->db->error();
                $return_array = array('errmsg' => 'Mitemreqsite.2: '.$dberror['message']);
            } else {
                $sql_del = "DELETE FROM trx_orderitem WHERE kodegudang=?";
                $query_del = $this->db->query($sql_del, array($arr_data['WhsCode']));

                if( !$query_del ) {
                    $dberror = $this->db->error();
                    $return_array = array('errmsg' => 'Mitemreqsite.3: '.$dberror['message']);
                } else {
                    $return_array = array('errmsg' => '-');
                }
            }
        }

        return $return_array;
    }

    function getReqHistory($arr_data) {
        $sql = "SELECT COUNT(recid) AS TotalReq FROM xt0 WHERE DepartmentID=? AND SourceID=? AND DomainID=?";
        $query = $this->db->query($sql,
            array(
                $arr_data['numberingformat']['numcode'],
                $arr_data['whscode'],
                $arr_data['numberingformat']['sitecode']
            )
        );

        if( !$query ) {
            $dberror = $this->db->error();
            $return_arr = array('errmsg' => $dberror['message'], 'totalReq' => '0');
        } else {
            $row = $query->row();

            if(isset($row)) {
                $totalReq = $row->TotalReq;
            } else {
                $totalReq = 0;
            }

            $return_arr = array('errmsg' => '-', 'totalReq' => $totalReq);
        }

        return $return_arr;
    }
}
?>