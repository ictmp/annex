<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class c_packlist extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->library('datatables');
        $this->load->model('m_loginpage');
        $this->load->model('m_packlist');
    }

    public function index($data) {
        if($this->m_loginpage->loggedcheck()) {
            $companyid = base64_decode($data);
            $query_company = $this->m_packlist->showdetailcomp($companyid);

            $var_comp = array(
                'companyid' => base64_encode($companyid),
                'detailcompany' => $query_company,
                'usermenu' => $this->m_loginpage->usermenu()
            );

            $this->load->view('pack_list',$var_comp);
        } else {
            redirect('loginarea');
        }
    }

    public function getlist($compid) {
        $companyid = base64_decode($compid);
        header('Content-Type: application/json');
        echo $this->m_packlist->getlist($companyid);
    }

    public function viewpack() {
        $data = array(
            'companyid' => base64_decode($this->input->post('companyid')),
            'packageid' => base64_decode($this->input->post('packageid'))
        );

        $query1 = $this->m_packlist->viewpackage($data);

        $return['errmsg'] = $query1['errmsg'];
        if($query1['errmsg'] == "true") {
            $return['packagedata'] = $query1['packagedata']->row();
            $return['packageitem'] = $query1['packageitem']->result_array();
        }

        echo json_encode($return);
    }
}
?>