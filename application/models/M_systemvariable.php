<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_systemvariable extends CI_Model {
    private $username;

    function __construct() {
        parent::__construct();

        $this->username = $this->session->userdata('usn');
    }

    function systeminfo() {

    }
}
?>