<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_mstrexam extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->library('datatables');
        $this->load->model('m_loginpage');
        $this->load->model('m_mstrexam');
    }

    public function index() {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();

            $this->load->view('mstr_exam', $query);
        } else {
            redirect('loginarea');
        }
    }

    public function getlist() {
        header('Content-Type: application/json');
        echo $this->m_mstrexam->getlist();
    }

    public function updaterow() {
        $itemcode = $this->input->post('itemcode');

        $data = $this->m_mstrexam->updaterow($itemcode);

        $return['errmsg'] = $data['errmsg'];
        $return['statusdata'] = $data['statusdata'];

        echo json_encode($return);
    }
}
?>