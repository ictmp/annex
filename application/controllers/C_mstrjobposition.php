<?php
/**
 * Created by PhpStorm.
 * User: ICT-ADMIN
 * Date: 05/03/2019
 * Time: 15:17
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_mstrjobposition extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->library('datatables');
        $this->load->model('m_loginpage');
        $this->load->model('m_mstrjobposition');
    }

    public function index() {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();

            $this->load->view('mstr_jobposition', $query);
        } else {
            redirect('loginarea');
        }
    }

    public function getlist() {
        header('Content-Type: application/json');
        $query = $this->m_mstrjobposition->getlist();
        echo json_encode($query);
    }

    public function exporttoxls() {
        $query = $this->m_mstrjobposition->downloaddata();

        $this->view->load();
    }
}
?>