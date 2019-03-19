<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_userdata extends CI_Model {
    private $username;

    public function __construct() {
        parent::__construct();
        $this->username = $this->session->userdata('usn');
    }

    public function employeeid() {
        $sql = "SELECT iduser FROM user_manager WHERE username=?";
        $query = $this->db->query($sql, array($this->username));

        if(!$query) {
            $dberror = $this->db->error();
            $errmsg = $dberror['message'];
            $resultdata = "-";
        } else {
            if($query->num_rows() == 0) {
                $resultdata = "Record not found";
                $errmsg = $resultdata;
            } elseif($query->num_rows() == 1) {
                $resultdata = $query->row()->iduser;
                $errmsg = "-";
            } else {
                $resultdata = "Multiple record";
                $errmsg = $resultdata;
            }
        }

        return array('errmsg' => $errmsg, 'resultdata' => $resultdata);
    }
}
?>