<?php
/**
 * Created by PhpStorm.
 * User: ICT-ADMIN
 * Date: 28/02/2019
 * Time: 11:26
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_mstritem extends CI_Model {
    private $username;
    private $dbsap;

    function __construct() {
        parent::__construct();

        $this->username = $this->session->userdata('usn');
        $this->dbsap = $this->load->database('dbsqlsrv', TRUE);
    }

    function getlist() {
        $this->datatables->select("NEW_ItemCode AS ItemCode,ItemName,PurcUOM,Content,InvUOM");
        $this->datatables->from("sap_itemmaster_ver9");

        return $this->datatables->generate();
    }

    function downloadsap() {
        $sql_sap = "SELECT 
                      ItemCode,ItemName,SalUnitMsr,NumInSale,InvntryUom,ItmsGrpCod,U_SOL_KODLAM 
                    FROM OITM 
                    WHERE (ItmsGrpCod!='100' AND ItmsGrpCod!='112' AND ItmsGrpCod!='114' AND ItmsGrpCod!='116')";
        $query_sap = $this->dbsap->query($sql_sap);

        if( $query_sap->num_rows() > 0 ) {
            foreach ($query_sap->result() AS $row) {
                $sql_slct = "SELECT * FROM sap_itemmaster_ver9 WHERE old_itemcode=? OR new_itemcode=?";
                $query_slct = $this->db->query($sql_slct,
                    array(
                        $row->U_SOL_KODLAM,
                        $row->ItemCode
                    )
                );

                /* update */
                if( $query_slct->num_rows() > 0 ) {
                    $sql_upd = "UPDATE sap_itemmaster_ver9 
                                  SET old_itemcode=?,itemname=?,purcuom=?,content=?,invuom=?,itemgrpscode=?,updatedate=?,updatetime=?
                                WHERE new_itemcode=?";
                    $query_upd = $this->db->query($sql_upd,
                        array(
                            $row->U_SOL_KODLAM,
                            $row->ItemName,
                            $row->SalUnitMsr,
                            $row->NumInSale,
                            $row->InvntryUom,
                            $row->ItmsGrpCod,
                            DATE('Y-m-d'),
                            DATE('H:i:s'),
                            $row->ItemCode
                        )
                    );
                 /* insert */
                } else {
                    $sql_ins = "INSERT INTO sap_itemmaster_ver9(old_itemcode,new_itemcode,itemname,purcuom,content,invuom,itemgrpscode,createdate,createtime) 
                                VALUES(?,?,?,?,?,?,?,?,?)";
                    $query_ins = $this->db->query($sql_ins,
                        array(
                            $row->U_SOL_KODLAM,
                            $row->ItemCode,
                            $row->ItemName,
                            $row->SalUnitMsr,
                            $row->NumInSale,
                            $row->InvntryUom,
                            $row->ItmsGrpCod,
                            DATE('Y-m-d'),
                            DATE('H:i:s')
                        )
                    );
                }
            }

            return "-";
        } else {
            return "Record Not Found";
        }
    }
}
?>
