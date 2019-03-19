<?php
/**
 * Created by PhpStorm.
 * User: ICT-ADMIN
 * Date: 05/03/2019
 * Time: 15:16
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_mstrjobposition extends CI_Model {
    private $username;
    private $dbsap;

    function __construct() {
        parent::__construct();

        $this->username = $this->session->userdata('usn');
        $this->dbsap = $this->load->database('dbsqlsrv', TRUE);
    }

    function getlist() {
        $sql = "SELECT posid AS kode,descriptio AS keterangan FROM OHPS";
        $query = $this->dbsap->query($sql);

        $arr_data['data'] = $query->result_array();

        return $arr_data;
    }
}
?>