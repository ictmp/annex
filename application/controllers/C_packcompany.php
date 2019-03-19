<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_packcompany extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->library('datatables');
        $this->load->model('m_loginpage');
        $this->load->model('m_packcompany');
        $this->load->model('m_userdata');
    }

    public function index() {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();

            $this->load->view('pack_company', $query);
        } else {
            redirect('loginarea');
        }
    }

    public function getlist() {
        header('Content-Type: application/json');
        $employeeid = $this->m_userdata->employeeid();

        if($employeeid['errmsg'] == "-") {
            echo $this->m_packcompany->getlist($employeeid['resultdata']);
        }
    }
}
?>