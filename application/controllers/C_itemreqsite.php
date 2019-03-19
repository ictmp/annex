<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_itemreqsite extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->library('datatables');
        $this->load->model('m_loginpage');
        $this->load->model('m_itemreqsite');
        $this->load->model('m_numberingformat');
    }

    public function index($menuid) {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();
            $query['kodegudang'] = $this->m_loginpage->getWhCode($menuid);
            $query['kodemenu'] = $menuid;
            $query['badgeHistory'] = $this->totalHistory( array('menuid' => $menuid, 'whscode'=> $query['kodegudang']) );

            $this->load->view('itemreqsite', $query);
        } else {
            redirect('loginarea');
        }
    }

    public function getlist($kodegudang) {
        header('Content-Type: application/json');
        echo $this->m_itemreqsite->getorderlist($kodegudang);
    }

    public function showitems($kodegudang) {
        header('Content-Type: application/json');
        echo $this->m_itemreqsite->getitemlist($kodegudang);
    }

    public function showuom($itemcode) {
        header('Content-Type: application/json');
        $query['data'] = $this->m_itemreqsite->getuomlist($itemcode);
        echo json_encode($query);
    }

    public function selectitem() {
        $arr_data = array(
            'ItemCode' => $this->input->post('ItemCode'),
            'ItemName' => $this->input->post('ItemName'),
            'WhsCode' => $this->input->post('WhsCode'),
            'CheckBoxStatus' => $this->input->post('CheckBoxStatus')
        );

        $query['errmsg'] = $this->m_itemreqsite->selectitem($arr_data);

        echo json_encode($query);
    }

    public function selectuom() {
        $arr_data = array(
            'ItemCode' => $this->input->post('ItemCode'),
            'Qty' => $this->input->post('Qty'),
            'UnitPack' => $this->input->post('UnitPack'),
            'QtyPack' => $this->input->post('QtyPack'),
            'WhsCode' => $this->input->post('WhsCode')
        );

        $query['errmsg'] = $this->m_itemreqsite->selectuom($arr_data);

        echo json_encode($query);
    }

    public function updateqty() {
        $arr_data = array(
            'ItemCode' => $this->input->post('ItemCode'),
            'RowValue' => $this->input->post('RowValue'),
            'WhsCode' => $this->input->post('WhsCode')
        );

        $query['errmsg'] = $this->m_itemreqsite->updateqty($arr_data);

        echo json_encode($query);
    }

    function exportexcel($kodegudang) {
        $query = $this->m_itemreqsite->exportxls($kodegudang);

        $this->load->view('itemreqsite_xls', $query);
    }

    function postingreq() {
        $MenuID = str_replace(".","",$this->input->post('MenuID'));
        $WhsCode = $this->input->post('WhsCode');
        $IpAddr = $this->input->ip_address();

        $numberingformat = $this->m_numberingformat->numberingformat($MenuID);

        if($numberingformat['errmsg'] == "-") {
            $regjournal = $this->m_numberingformat->getnumberbyid('RegJournal');

            if( $regjournal['errmsg'] == "-" ) {
                $query = $this->m_itemreqsite->postingreq(
                    array(
                        'WhsCode' => $WhsCode,
                        'NumberingCode' => $numberingformat['numcode'],
                        'NumberingReset' => $numberingformat['numreset'],
                        'DocumentNumber' => $numberingformat['documentnumber'],
                        'TransactionID' => $regjournal['NewID'],
                        'SiteCode' => $numberingformat['sitecode'],
                        'IPAddress' => $IpAddr
                    )
                );
            } else {
                $query['errmsg'] = $regjournal['errmsg'];
            }
        } else {
            $query['errmsg'] = $numberingformat['errmsg'];
        }

        $query['badgeHistory'] = $this->totalHistory( array('menuid' => $this->input->post('MenuID'), 'whscode'=> $WhsCode) );

        echo json_encode($query);
    }

    function totalHistory($arr_data) {
        $menuid = $arr_data['menuid'];
        $whscode = $arr_data['whscode'];

        $numberingformat = $this->m_numberingformat->numberingformat( str_replace(".","", $menuid) );

        $arr_data = array('numberingformat' => $numberingformat, 'whscode' => $whscode);
        $queryBadgeHistory = $this->m_itemreqsite->getReqHistory($arr_data);

        if($queryBadgeHistory['errmsg'] == "-") {
            $totalHistory = $queryBadgeHistory['totalReq'];
        } else {
            $totalHistory = 0;
        }

        return $totalHistory;
    }
}
?>