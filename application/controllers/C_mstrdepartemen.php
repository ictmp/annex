<?php
/**
 * Created by PhpStorm.
 * User: ICT-ADMIN
 * Date: 28/02/2019
 * Time: 11:24
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_mstrdepartemen extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->library('datatables');
        $this->load->model('m_loginpage');
        $this->load->model('m_mstrdepartemen');
    }

    public function index() {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();

            $this->load->view('mstr_departemen', $query);
        } else {
            redirect('loginarea');
        }
    }

    public function getlist() {
        header('Content-Type: application/json');
        $query = $this->m_mstrdepartemen->getlist();
        echo json_encode($query);
    }
}
?>