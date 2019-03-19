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

            if($query_company['errmsg'] == "-") {
                $getDateTime = date('YmdHis');
                $randString = random_string('alnum', 10);
                $projectid = substr(base64_encode($getDateTime."-".$randString),0, 20);

                $insert_setup = $this->m_packdescription->insertsetup($companyid,$projectid);

                $arrdata = array(
                    'companyid' => base64_encode($companyid),
                    'detailcompany' => $query_company['companydata'],
                    'usermenu' => $this->m_loginpage->usermenu(),
                    'projectid' => $projectid,
                    'getid' => $insert_setup['getid']
                );

                if($insert_setup['errmsg'] == "-") {
                    $this->load->view('pack_description', $arrdata);
                } else {
                    $this->load->view('pack_description', $arrdata);
                }
            } else {
                $this->load->view('pack_description', $arrdata);
            }
        } else {
            redirect('loginarea');
        }
    }

    public function createpack() {
        $arr_data = array(
            'getid' => $this->input->post('getid'),
            'projectid' => $this->input->post('projectid'),
            'compid' => base64_decode($this->input->post('compid')),
            'numpackage' => $this->input->post('numpackage'),
            'packlang' => $this->input->post('packlang'),
            'resulttype' => $this->input->post('resulttype'),
            'stdate' => $this->input->post('stdate'),
            'endate' => $this->input->post('endate'),
            'packnote' => $this->input->post('packnote'),
            'packtop' => $this->input->post('packtop')
        );

        $query = $this->m_packdescription->createpack($arr_data);

        if($query['querystatus'] == "true") {
            $return['errmsg'] = "-";
            $return['companyid'] = base64_encode($query['compid']);
            $return['setupid'] = base64_encode($query['setupid']);
        } else {
            $return['errmsg'] = $query['errmsg'];
            $return['companyid'] = "";
            $return['setupid'] = "";
        }

        echo json_encode($return);
    }

    public function fileUpload() {
        $config['upload_path']   = FCPATH.'/assets/uploads/';
        $config['allowed_types'] = 'jpg|jpeg|pdf|doc|docx';
        $config['file_ext_tolower'] = true;
        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);

        if($this->upload->do_upload('userfile')){
            $companyid = base64_decode($this->input->post('companyid'));
            $projectid = $this->input->post('projectid');
            $nama = $this->upload->data('file_name');
            $ftype = $this->upload->data('file_type');
            $fsize = $this->upload->data('file_size');
            $token = $this->input->post('token_file');

            $arr_file = array(
                'companyid' => $companyid,
                'projectid' => $projectid,
                'file_name' => $nama,
                'file_type' => $ftype,
                'file_size' => $fsize,
                'token_id' => $token
            );

            $insert_db = $this->m_packdescription->uploadfile($arr_file);

            if($insert_db == "-") {
                $uploadStatus = "-";
            } else {
                $uploadStatus = $insert_db;
            }
        } else {
            $uploadStatus = $this->upload->display_errors();
        }

        echo $uploadStatus;
    }

    public function fileRemove() {
        /* Take file token id */
        $token = $this->input->post('token');

        $query = $this->m_packdescription->removefile($token);

        if($query['errmsg'] == "true") {
            if(file_exists($file = FCPATH.'/assets/uploads/'.$query['msg'])) {
                unlink($file);
                delete_files($file);
            }

            $return['errmsg'] = true;
        } else {
            $return['errmsg'] = $query['errmsg'];
        }

        echo json_encode($return);
    }

//    public function fileRemove() {
//        $query = $this->m_packdescription->removefile();
//
//        if($query['errmsg'] == "true") {
//            if($query['msg'] != "0") {
//                foreach ($query['msg']->removefileresult() as $row) {
//                    if(file_exists($file = FCPATH.'/assets/uploads/'.$row->file_name)) {
//                        unlink($file);
//                    }
//                }
//
//                $return['errmsg'] = true;
//            } else {
//                $return['errmsg'] = true;
//            }
//        } else {
//            $return['errmsg'] = $query['errmsg'];
//        }
//
//        echo json_encode($return);
//    }
}
?>