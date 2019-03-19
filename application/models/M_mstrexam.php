<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_mstrexam extends CI_Model {
    function getlist() {
        $this->datatables->select("a.nama_barang,b.description,a.tarif,a.id_barang,
                    (CASE
                        WHEN c.kodeitem IS NULL THEN '0'
                        WHEN c.kodeitem IS NOT NULL THEN '1'
                    END) AS statusdata");
        $this->datatables->from("product_master a");
        $this->datatables->join("master_clinic b", "a.product_group=b.clinicid", "left");
        $this->datatables->join("master_costingmcu c", "a.id_barang=c.kodeitem", "left");
        $this->datatables->where("a.kategori_barang", "Service");
        $this->datatables->where("a.activestatus", "0");

        return $this->datatables->generate();
    }

    function updaterow($data) {
        $sql1 = "SELECT recid FROM master_costingmcu WHERE kodeitem=?";
        $query1 = $this->db->query($sql1,array($data));
        if($query1->num_rows() > 0) {
            $sql2 = "DELETE FROM master_costingmcu WHERE kodeitem=?";
            $query2 = $this->db->query($sql2, array($data));
            if($query2) {
                $status = array("errmsg" => "-", "statusdata" => "0");
                return $status;
            } else {
                $db_error = $this->db->error();
                $status = array("errmsg" => $db_error['message'], "statusdata" => "-");
                return $status;
            }
        } else {
            $username = $this->session->userdata('usn');
            $sql2 = "INSERT INTO master_costingmcu(kodeitem,createuser,createdate,createtime)
               VALUES(?,?,?,?)";
            $query2 = $this->db->query($sql2, array($data,$username,date('Ymd'),date('H:i:s')));
            if($query2) {
                $status = array("errmsg" => "-", "statusdata" => "1");
                return $status;
            } else {
                $db_error = $this->db->error();
                $status = array("errmsg" => $db_error['message'], "statusdata" => "-");
                return $status;
            }
        }
    }
}
?>