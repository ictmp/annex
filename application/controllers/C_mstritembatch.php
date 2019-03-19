<?php
/**
 * Created by PhpStorm.
 * User: ICT-ADMIN
 * Date: 28/02/2019
 * Time: 11:24
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_mstritembatch extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->library('datatables');
        $this->load->model('m_loginpage');
        $this->load->model('m_mstritembatch');
    }

    public function index() {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();

            $this->load->view('mstr_itembatch', $query);
        } else {
            redirect('loginarea');
        }
    }

    public function getlist() {
        header('Content-Type: application/json');
        echo $this->m_mstritembatch->getlist();
    }

    public function downloadsap() {
        $query = $this->m_mstritembatch->downloadsap();

        echo json_encode(array('errmsg' => $query['errmsg'], 'updatestatus' => $query['updatestatus']));
    }
}
?>