<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->library('datatables');
        $this->load->model('m_loginpage');
        $this->load->model('m_dashboard');
        $this->load->model('m_userdata');
        $this->load->model('m_packitem');
    }

//    public function testpage() {
//        $this->load->view('email_template_approval1.php');
//    }

    public function index() {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();

//            echo var_dump($query['usermenu']); exit();

            if($query['usermenu']['useraccess'] == "0") {
                $this->load->view('dashboardmkt1', $query);
            } else {
                $this->load->view('dashboardmkt2', $query);
            }
        } else {
            redirect('loginarea');
        }
    }

    public function dashboardmkticon() {
        $userlevel = $this->m_loginpage->userlevelaccess();
        $employeeid = $this->m_userdata->employeeid();

        if($employeeid['errmsg'] == "-") {
            $arr_data = array(
                'userlevel' => $userlevel,
                'employeeid' => $employeeid['resultdata']
            );

            $query_company = $this->m_dashboard->icon_company($arr_data);
            $query_package = $this->m_dashboard->icon_package($arr_data);
            $query_expire = $this->m_dashboard->icon_expire($arr_data);
            $query_draft = $this->m_dashboard->icon_draft($arr_data);

            if($query_company['errmsg'] != "-") {
                $return['errmsg'] = $query_company['errmsg'];
                $return['totalcompany'] = 0;
                $return['totalpackage'] = 0;
                $return['totalexpire'] = 0;
                $return['totaldraft'] = 0;
            } elseif($query_package['errmsg'] != "-") {
                $return['errmsg'] = $query_package['errmsg'];
                $return['totalcompany'] = 0;
                $return['totalpackage'] = 0;
                $return['totalexpire'] = 0;
                $return['totaldraft'] = 0;
            } elseif($query_expire['errmsg'] != "-") {
                $return['errmsg'] = $query_expire['errmsg'];
                $return['totalcompany'] = 0;
                $return['totalpackage'] = 0;
                $return['totalexpire'] = 0;
                $return['totaldraft'] = 0;
            } elseif($query_draft['errmsg'] != "-") {
                $return['errmsg'] = $query_draft['errmsg'];
                $return['totalcompany'] = 0;
                $return['totalpackage'] = 0;
                $return['totalexpire'] = 0;
                $return['totaldraft'] = 0;
            } else {
                $return['errmsg'] = "-";
                $return['totalcompany'] = $query_company['totaldata'];
                $return['totalpackage'] = $query_package['totaldata'];
                $return['totalexpire'] = $query_expire['totaldata'];
                $return['totaldraft'] = $query_draft['totaldata'];
            }
        } else {
            $return['errmsg'] = $employeeid['errmsg'];
            $return['totalcompany'] = 0;
            $return['totalpackage'] = 0;
            $return['totalexpire'] = 0;
            $return['totaldraft'] = 0;
        }

        echo json_encode($return);
    }

    public function dashboardicon_company() {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();
            $this->load->view('dashboardmkt_company_list1', $query);
        } else {
            redirect('loginarea');
        }
    }

    public function dashboardicon_company_list() {
        header('Content-Type: application/json');

        $userlevel = $this->m_loginpage->userlevelaccess();
        $employeeid = $this->m_userdata->employeeid();

        if($employeeid['errmsg'] == "-") {
            $arr_data = array(
                'userlevel' => $userlevel,
                'employeeid' => $employeeid['resultdata']
            );

            echo $this->m_dashboard->showcompanylist($arr_data);
        }
    }

    public function dashboardicon_package() {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();
            $this->load->view('dashboardmkt_package_list1', $query);
        } else {
            redirect('loginarea');
        }
    }

    public function dashboardicon_package_list() {
        header('Content-Type: application/json');

        $userlevel = $this->m_loginpage->userlevelaccess();
        $employeeid = $this->m_userdata->employeeid();

        if($employeeid['errmsg'] == "-") {
            $arr_data = array(
                'userlevel' => $userlevel,
                'employeeid' => $employeeid['resultdata']
            );

            echo $this->m_dashboard->showpackagelist($arr_data);
        }
    }

    public function dashboardicon_expire() {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();
            $this->load->view('dashboardmkt_expire_list1', $query);
        } else {
            redirect('loginarea');
        }
    }

    public function dashboardicon_expire_list() {
        header('Content-Type: application/json');

        $userlevel = $this->m_loginpage->userlevelaccess();
        $employeeid = $this->m_userdata->employeeid();

        if($employeeid['errmsg'] == "-") {
            $arr_data = array(
                'userlevel' => $userlevel,
                'employeeid' => $employeeid['resultdata']
            );

            echo $this->m_dashboard->showexpirelist($arr_data);
        }
    }

    public function dashboardicon_draft() {
        if($this->m_loginpage->loggedcheck()) {
            $query['usermenu'] = $this->m_loginpage->usermenu();
            $this->load->view('dashboardmkt_draft_list1', $query);
        } else {
            redirect('loginarea');
        }
    }

    public function dashboardicon_draft_list() {
        header('Content-Type: application/json');
        echo $this->m_dashboard->showdraftlist();
    }

//    public function show_detail2() {
//        if($this->m_loginpage->loggedcheck()) {
//            $query['usermenu'] = $this->m_loginpage->usermenu();
//            $this->load->view('dashboardspv_detail2', $query);
//        } else {
//            redirect('loginarea');
//        }
//    }
//
//    public function getlist_detail2() {
//        header('Content-Type: application/json');
//        echo $this->m_dashboard->getlist_detail2();
//    }

    public function attachfile() {
        $data = array(
            'setupid' => base64_decode($this->input->post('setupid')),
            'companyid' => base64_decode($this->input->post('companyid')),
            'imgid' => $this->input->post('imgid')
        );

        $query = $this->m_dashboard->showAttachfile($data);

        if($query['errmsg'] != "true") {
            $arr_val = array(
                'errmsg' => $query['errmsg']
            );
        } else {
            $arr_val = array(
                'errmsg' => $query['errmsg'],
                'packagefile' => $query['packagefile']->result_array()
            );
        }

        echo json_encode($arr_val);
    }

    public function approval_mcupackage($imgid,$companyid,$setupid) {
        $arr_data = array(
            'imgid' => $imgid,
            'companyid' => base64_decode($companyid),
            'setupid' => base64_decode($setupid)
        );

        $send_array['packagesetup'] = $arr_data;
        $send_array['packagelist'] = $this->m_dashboard->show_packlist($arr_data);
        $send_array['fixedcost'] = $this->m_packitem->fixedcost();
        $send_array['attachfile'] = $this->m_dashboard->show_attachfile($arr_data);

        $this->load->view('approval_mcupackage', $send_array);
    }

    public function downloadpackfile($filename) {
//        $data = $this->m_dashboard->getFilename(base64_decode($id));
//        $filename = $data;
        $filecontent = file_get_contents(base_url('assets/uploads/'.$filename));
        force_download($filename, $filecontent);
    }

//    public function downloadpackfile($id) {
//        $data = $this->m_dashboard->getFilename(base64_decode($id));
//        $filename = $data;
//        $filecontent = file_get_contents(base_url('assets/uploads/'.$filename));
//        force_download($filename, $filecontent);
//    }

//        'packagedetail' => $query['packagedetail']->row(),
//        'packagelist' => $query['packagelist']->result_array(),
//        'packagefile' => $query['packagefile']->result_array(),
//        'companyid' => $this->input->post('companyid'),
//        'packageid' => $this->input->post('packageid')

//    public function show_comp() {
//        if($this->m_loginpage->loggedcheck()) {
//            $query['usermenu'] = $this->m_loginpage->usermenu();
//            $this->load->view('dashboardspv_company', $query);
//        } else {
//            redirect('loginarea');
//        }
//    }

//    public function approve_package() {
//        $data = array(
//            'approvetype' => $this->input->post('approval'),
//            'companyid' => base64_decode($this->input->post('companyid')),
//            'packageid' => base64_decode($this->input->post('packageid'))
//        );
//
//        $query = $this->m_dashboard->approve_package($data);
//
//        $arr_val = array(
//            'errmsg' => $query
//        );
//
//        echo json_encode($arr_val);
//    }

}
?>