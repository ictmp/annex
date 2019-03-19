<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_packcompany extends CI_Model {
    function getlist($employeeid) {
        $this->datatables->select("companyname,companyid");
        $this->datatables->from("master_kerjasama");
        $this->datatables->where("activestatus", "A");
        $this->datatables->where("accountid", $employeeid);

        return $this->datatables->generate();
    }
}
?>