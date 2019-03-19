<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('m_loginpage');
        $this->load->model('m_dashboard');
    }

    public function index() {
        if($this->m_loginpage->loggedcheck()) {
            $this->load->view('dashboard');
        } else {
            redirect('loginarea');
        }
    }

    public function checkstatus() {
        $query = $this->m_dashboard->checkstatus();
    }
}
?>