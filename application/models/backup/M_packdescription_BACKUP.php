<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_packdescription extends CI_Model {
    private $username;

    public function __construct() {
        $this->username = $this->session->userdata('usn');
    }

    function showcompany($data) {
        $del_pack = "DELETE FROM trx_mcupackage WHERE createuser=?";
        $query_delpackage = $this->db->query($del_pack, array($this->username));

        $del_packitem = "DELETE FROM trx_mcupackage_item WHERE createuser=?";
        $query_delpackageitem = $this->db->query($del_packitem, array($this->username));

        $sql = "SELECT 
                  companyname,address1,city,phone,contact,contactemail 
                FROM master_kerjasama 
                WHERE companyid=? LIMIT 1";
        $query = $this->db->query($sql, array($data));

        return $query->row();
    }

    function showpackage() {
        $sql = "SELECT
                  imnum,packagename,packagelanguage,certtype,DATE_FORMAT(startperiode, '%d/%m/%Y') AS startperiode,
                  DATE_FORMAT(endperiode, '%d/%m/%Y') AS endperiode,packagenote
                FROM trx_mcupackage
                WHERE createuser=?";
        $query = $this->db->query($sql, array($this->username));

        return $query->row();
    }

    function createpack($data) {
        $sql = "INSERT INTO
                  trx_mcupackage(companyid,imnum,packageid,packagename,packagelanguage,certtype,
                  startperiode,endperiode,packagenote,packtop,createuser,createdate,createtime)
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $query = $this->db->query($sql, array($data['compid'],$data['imnum'],$data['packid'],$data['packname'],$data['packlang'],$data['certtype'],$data['stdate'],$data['endate'],$data['packnote'],$data['packtop'],$this->username,date('Y-m-d'),date('H:i:s')));

        if($query) {
            $return_val = array(
                'querystatus' => true,
                'compid' => $data['compid'],
                'packid' => $data['packid'],
                'errmsg' => ''
            );
        } else {
            $return_val = array(
                'querystatus' => false,
                'compid' => '',
                'packid' => '',
                'errmsg' => $this->db->error()
            );
        }
        return $return_val;
    }

    function removefile() {
        $sql = "SELECT file_name FROM master_attachfilepack WHERE uploaduser=?";
        $query1 = $this->db->query($sql, array($this->username));

        if($query1->num_rows() > 0) {
            $del = "DELETE FROM master_attachfilepack WHERE uploaduser=?";
            $query2 = $this->db->query($del, array($this->username));

            if(!$query2) {
                $db_error = $this->db->error();
                $arr_msg = array(
                    'errmsg' => $db_error['message'],
                    'msg' => ''
                );

                return $arr_msg;
            } else {
                $arr_msg = array(
                    'errmsg' => true,
                    'msg' => $query1
                );
                return $arr_msg;
            }
        } else {
            $arr_msg = array(
                'errmsg' => true,
                'msg' => "0"
            );
            return $arr_msg;
        }
    }
}
?>