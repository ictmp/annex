<?php
/**
 * Created by PhpStorm.
 * User: ICT-ADMIN
 * Date: 01/03/2019
 * Time: 10:59
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_invreceipt extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->library('datatables');
        $this->load->model('m_loginpage');
        $this->load->model('m_invreceipt');
    }

    public function index() {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();

            $this->load->view('invreceipt', $query);
        } else {
            redirect('loginarea');
        }
    }

    public function getlist() {
        header('Content-Type: application/json');
        $query = $this->m_invreceipt->getlist();
        echo json_encode($query);
    }

    public function getdocnum() {
        $arrdata = array(
            'docnum' => $this->input->post('docnum'),
            'cbstatus' => $this->input->post('cbstatus')
        );

        $query['errmsg'] = $this->m_invreceipt->getdocnum($arrdata);

        echo json_encode($query);
    }

    public function receiptpdf() {
        $query['invheader'] = $this->m_invreceipt->getheaderinv();
        $query['invdetail'] = $this->m_invreceipt->getdetailinv();

        $this->load->view('invreceipt_pdf', $query);
    }
}
?>