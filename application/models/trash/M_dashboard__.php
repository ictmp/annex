<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_dashboard extends CI_Model {
    private $username;

    public function __construct() {
        parent::__construct();
        $this->username = $this->session->userdata('usn');
    }

    function checkstatus() {
        $sql_comp = "SELECT companyid FROM master_kerjasama WHERE ";
    }
}
?>