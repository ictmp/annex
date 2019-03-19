<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_apvreqsite extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->library('datatables');
        $this->load->model('m_loginpage');
        $this->load->model('m_apvreqsite');
        $this->load->model('m_numberingformat');
    }

    public function index($menuid) {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();
            $query['kodemenu'] = $menuid;

            $this->load->view('apvreqsite', $query);
        } else {
            redirect('loginarea');
        }
    }

    public function detail($menuid) {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();
            $query['kodemenu'] = $menuid;

            $this->load->view('apvreqsite_detail', $query);
        } else {
            redirect('loginarea');
        }
    }

    public function getlist() {
        header('Content-Type: application/json');
        echo $this->m_apvreqsite->getreqlist();
    }

    public function getlist_checkstatus() {
//        $query = $this->m_apvreqsite->checkstatus($regjournal);
        $return['test'] = $this->input->post('regjournal');

        echo json_encode($return);
    }

    public function showitems() {
        header('Content-Type: application/json');
        echo $this->m_apvreqsite->getitemlist($this->input->post('regjournal'));
    }

    public function getdetail() {
        $query = $this->m_apvreqsite->getdetail($this->input->post('regjournal'));

        $return['errmsg'] = $query['errmsg'];

        if($query['errmsg'] == "-") {
            $return['reqnumber'] = $query['norequest'];
            $return['namasite'] = $query['namasite'];
            $return['kodesite'] = $query['kodesite'];
        }

        echo json_encode($return);
    }

    public function selectitem() {
        $query['errmsg'] = $this->m_itemreqsite->selectitem($this->input->post('recid'));

        echo json_encode($query);
    }
}
?>