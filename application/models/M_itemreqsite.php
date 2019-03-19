<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_itemreqsite extends CI_Model {
    private $username;

    function __construct() {
        parent::__construct();

        $this->username = $this->session->userdata('usn');
    }

    function getorderlist($kodegudang) {
        $this->datatables->select("kodeitem AS ItemCode,namaitem AS ItemName,jumlah AS Qty,keteranganunit AS UnitPackage");
        $this->datatables->from("trx_orderitem");
        $this->datatables->where("kodegudang",$kodegudang);

        return $this->datatables->generate();
    }

    function getitemlist($kodegudang) {
        $this->datatables->select("a.NEW_ItemCode AS ItemCode,REPLACE(a.ItemName, '\'','`') AS ItemName,a.PurcUOM,a.Content,a.InvUOM,(CASE WHEN b.recid IS NULL THEN '0' WHEN b.recid IS NOT NULL THEN '1' END) AS checkitem");
        $this->datatables->from("sap_itemmaster_ver9 a");
        $this->datatables->join("trx_orderitem b", "a.NEW_ItemCode=b.kodeitem AND b.kodegudang='".$kodegudang."'", "left");

        return $this->datatables->generate();
    }

    function getuomlist($itemcode) {
        $sql = "SELECT '1',purcuom,content,invuom FROM sap_itemmaster_ver9 WHERE NEW_ItemCode=?";
        $query = $this->db->query($sql, array($itemcode));
        $row = $query->row();

        $qtypack1 = ""; $unitpack1 = "";
        $qtypack2 = ""; $unitpack2 = "";

        if(isset($row)) {
            $qtypack1 = "1"; $unitpack1 = $row->invuom;
            $qtypack2 = $row->content; $unitpack2 = $row->purcuom;
        }

        $returndata = array(
                        array('unitpack' => $unitpack1,'qtypack' => $qtypack1),
                        array('unitpack' => $unitpack2,'qtypack' => $qtypack2)
                    );

        return $returndata;
    }

    function selectitem($arr_data) {
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
                              itemseq,createuser,createdate,createtime,recid_header,regjournal,domainid,checkitem)
                           SELECT
                              ?,tujuan,kodeitem,namaitem,jumlah,qtyunit,keteranganunit,convfactor,
                              urutanobat,?,?,?,?,?,?,?
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
                    '0'
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