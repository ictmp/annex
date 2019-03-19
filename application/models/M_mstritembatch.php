<?php
/**
 * Created by PhpStorm.
 * User: ICT-ADMIN
 * Date: 28/02/2019
 * Time: 11:26
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_mstritembatch extends CI_Model {
    private $username;
    private $dbsap;

    function __construct() {
        parent::__construct();

        $this->username = $this->session->userdata('usn');
        $this->dbsap = $this->load->database('dbsqlsrv', TRUE);
    }

    function getlist() {
        $this->datatables->select("a.itemcode,a.sysnumber,a.distnumber,a.mnfserial,DATE_FORMAT(a.expdate, '%d/%m/%Y') AS expireddate,b.itemname");
        $this->datatables->from("product_batch a");
        $this->datatables->join("sap_itemmaster_ver9 b","a.itemcode=b.new_itemcode","left");

        return $this->datatables->generate();
    }

    function downloadsap() {
        $sql_del = "DELETE FROM product_batch";
        $query_del = $this->db->query($sql_del);

        if($query_del) {
            $sql_alter = "ALTER TABLE product_batch AUTO_INCREMENT=1";
            $query_alter = $this->db->query($sql_alter);

            $sql_sap = "SELECT 
                          ItemCode,SysNumber,DistNumber,MnfSerial,ExpDate,MnfDate,InDate,CreateDate
                        FROM OBTN";
            $query_sap = $this->dbsap->query($sql_sap);

            if( $query_sap->num_rows() > 0 ) {
                $no = 0;
                foreach ($query_sap->result() AS $row) {
                    $sql_ins = "INSERT INTO product_batch(itemcode,sysnumber,distnumber,mnfserial,expdate,mnfdate,indate,createdate) 
                                VALUES(?,?,?,?,?,?,?,?)";
                    $query_ins = $this->db->query($sql_ins,
                        array(
                            $row->ItemCode,
                            $row->SysNumber,
                            $row->DistNumber,
                            $row->MnfSerial,
                            $row->ExpDate,
                            $row->MnfDate,
                            $row->InDate,
                            DATE('Y-m-d')
                        )
                    );

                    $no += 1;
                }

                $errmsg = "-";
                $updatestatus = $no;

                return array("errmsg" => $errmsg, "updatestatus" => $updatestatus);
            } else {
                $errmsg = "Record Not Found";
                $updatestatus = "-";

                return array("errmsg" => $errmsg, "updatestatus" => $updatestatus);
            }
        } else {
            $errmsg = "Deleting process failed";
            $updatestatus = "-";

            return array("errmsg" => $errmsg, "updatestatus" => $updatestatus);
        }
    }
}
?>
