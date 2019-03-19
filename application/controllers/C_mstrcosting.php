<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_mstrcosting extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->library('datatables');
        $this->load->model('m_loginpage');
        $this->load->model('m_mstrcosting');
    }

    public function index() {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();
            $query['fixedcostdata'] = $this->m_mstrcosting->viewfixedcost();

            $this->load->view('mstr_costing', $query);
        } else {
            redirect('loginarea');
        }
    }

    public function getlist() {
        header('Content-Type: application/json');
        echo $this->m_mstrcosting->getlist();
    }

    public function editrow() {
        $itemcode = $this->input->post('kodeitem');

        $data = $this->m_mstrcosting->editrow($itemcode);

        if($data['errmsg'] == "-") {
            $return['errmsg'] = $data['errmsg'];
            $return['description'] = $data['description'];
            $return['publishdesc'] = $data['publishdesc'];
            $return['price'] = $data['price'];
            $return['costing'] = $data['costing'];
            $return['linkeditem'] = $data['linkeditem'];
            $return['linkeditem_data'] = $data['linkeditem_data'];
            $return['unlinkeditem_data'] = $data['unlinkeditem_data'];
        } else {
            $return['errmsg'] = $data['errmsg'];
        }

        echo json_encode($return);
    }

    public function updaterow() {
        if(!empty($this->input->post('linkeditem'))) {
            $linkeditem = implode(",", $this->input->post('linkeditem'));
        } else {
            $linkeditem = "";
        }

        if(!empty($this->input->post('unlinkeditem'))) {
            $unlinkeditem = implode(",", $this->input->post('unlinkeditem'));
        } else {
            $unlinkeditem = "";
        }

        $data = $this->m_mstrcosting->updaterow(
            array(
                "itemcode" => $this->input->post('itemcode'),
                "publishdesc" => $this->input->post('publishdesc'),
                "costing" => $this->input->post('costing'),
                "linkeditem" => $linkeditem,
                "unlinkeditem" => $unlinkeditem
            )
        );

        $return['errmsg'] = $data;

        echo json_encode($return);
    }

    public function savefixedcost() {
        $arr_data = array(
            'medreccost' => $this->input->post('medreccost'),
            'delivercost' => $this->input->post('delivercost'),
            'laundrycost' => $this->input->post('laundrycost'),
            'renovcost' => $this->input->post('renovcost'),
            'admcost' => $this->input->post('admcost')
        );

        $query = $this->m_mstrcosting->fixedcost($arr_data);

        if($query == true) {
            $return['errmsg'] = "-";
        } else {
            $return['errmsg'] = $query;
        }

        echo json_encode($return);
    }

    function exportexcel() {
        $costinglist['costinglist'] = $this->m_mstrcosting->costinglist();

        $this->load->view('mstr_costing_xls', $costinglist);
    }

//    public function linkeditem() {
//        $arr_data = array(
//            'search' => $this->input->post('search'),
//            'itemcode' => $this->input->post('itemcode')
//        );
//
//        $query['results'] = $this->m_mstrcosting->linkeditem($arr_data);
//
//        echo json_encode($query);
//    }
}
?>