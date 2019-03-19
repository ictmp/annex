<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_packdescription extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->model('m_loginpage');
        $this->load->model('m_packdescription');
    }

    public function show($data) {
        if($this->m_loginpage->loggedcheck()) {
            $companyid = base64_decode($data);

            $query_company = $this->m_packdescription->showcompany($companyid);
            $query_package = $this->m_packdescription->showpackage();

            $arrdata = array(
                'companyid' => base64_encode($companyid),
                'detailcompany' => $query_company,
                'detailpackage' => $query_package,
                'usermenu' => $this->m_loginpage->usermenu()
            );

            $this->load->view('pack_description', $arrdata);
        } else {
            redirect('loginarea');
        }
    }

    public function createpack() {
        $StringToAuto = date('Y-m-d')."".date('H:i:s')."".base64_decode($this->input->post('compid'));
        $AutoCode = substr(md5($StringToAuto),0,8);
        $packageid = substr(base64_decode($this->input->post('compid')),0,1)."".$AutoCode;

        $arr_data = array(
            'compid' => base64_decode($this->input->post('compid')),
            'imnum' => $this->input->post('imnum'),
            'packid' => $packageid,
            'packname' => $this->input->post('packname'),
            'packlang' => $this->input->post('packlang'),
            'certtype' => implode(",",$this->input->post('certtype')),
            'stdate' => $this->input->post('stdate'),
            'endate' => $this->input->post('endate'),
            'packnote' => $this->input->post('packnote'),
            'packtop' => $this->input->post('packtop')
        );

        $query = $this->m_packdescription->createpack($arr_data);

        if($query['querystatus'] == true) {
            $return['errmsg'] = "-";
            $return['companyid'] = base64_encode($query['compid']);
            $return['packageid'] = base64_encode($query['packid']);
        } else {
            $return['errmsg'] = $query['errmsg'];
            $return['companyid'] = "";
            $return['packageid'] = "";
        }

        echo json_encode($return);
    }

    public function fileRemove() {
        $query = $this->m_packdescription->removefile();

        if($query['errmsg'] == "true") {
            if($query['msg'] != "0") {
                foreach ($query['msg']->result() as $row) {
                    if(file_exists($file = FCPATH.'/assets/uploads/'.$row->file_name)) {
                        unlink($file);
                    }
                }

                $return['errmsg'] = true;
            } else {
                $return['errmsg'] = true;
            }
        } else {
            $return['errmsg'] = $query['errmsg'];
        }

        echo json_encode($return);
    }
}
?>