<?php
/**
 * Created by PhpStorm.
 * User: ICT-ADMIN
 * Date: 28/02/2019
 * Time: 11:26
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_mstrbp extends CI_Model {
    private $username;
    private $dbsap;

    function __construct() {
        parent::__construct();

        $this->username = $this->session->userdata('usn');
        $this->dbsap = $this->load->database('dbsqlsrv', TRUE);
    }

    function getlist() {
        $sql = "SELECT cardcode,cardname,address,zipcode,phone1,fax,cntctprsn,city,E_mail FROM OCRD WHERE CardType='C' AND frozenfor='N'";
        $query = $this->dbsap->query($sql);

        $arr_data['data'] = $query->result_array();

        return $arr_data;
    }

    function exportexcel() {
        $sql = "SELECT cardcode,cardname,address,zipcode,phone1,fax,cntctprsn,city,E_mail FROM OCRD WHERE CardType='C' AND frozenfor='N'";
        $query = $this->dbsap->query($sql);

        return $query;
    }
}
?>
