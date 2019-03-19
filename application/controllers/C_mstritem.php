<?php
/**
 * Created by PhpStorm.
 * User: ICT-ADMIN
 * Date: 28/02/2019
 * Time: 11:24
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_mstritem extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->library('datatables');
        $this->load->model('m_loginpage');
        $this->load->model('m_mstritem');
    }

    public function index() {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();

            $this->load->view('mstr_item', $query);
        } else {
            redirect('loginarea');
        }
    }

    public function getlist() {
        header('Content-Type: application/json');
        echo $this->m_mstritem->getlist();
    }

    public function downloadsap() {
        $query['downloadstatus'] = $this->m_mstritem->downloadsap();

        echo json_encode($query);
    }
}
?>