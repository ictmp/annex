<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class c_packitem extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->library('datatables');
        $this->load->model('m_loginpage');
        $this->load->model('m_packitem');
    }

//    public function index() {
//        if($this->m_loginpage->loggedcheck()) {
//            $query['usermenu'] = $this->m_loginpage->usermenu();
//
//            $this->load->view('pack_item', $query);
//        } else {
//            redirect('loginarea');
//        }
//    }

    public function getlist($headerid,$packageid) {
        $arr_data = array(
            'headerid' => $headerid,
            'packageid' => $packageid
        );
        header('Content-Type: application/json');
        echo $this->m_packitem->getlist($arr_data);
    }

    public function getpackagelist($setupid,$packageid) {
        $arr_data = array(
            'setupid' => $setupid,
            'packageid' => $packageid
        );
        header('Content-Type: application/json');
        echo $this->m_packitem->getpackagelist($arr_data);
    }

    public function show($compid,$setupid) {
        if($this->m_loginpage->loggedcheck()) {
            $arrdata = array(
                'companyid' => base64_decode($compid),
                'setupid' => base64_decode($setupid)
            );

            $loadpackage = $this->m_packitem->show_packlist($arrdata);

            $query['usermenu'] = $this->m_loginpage->usermenu();

            if($loadpackage['errmsg'] == "true") {
                $dtl_perpackage = array();
                foreach ($loadpackage['msg']->result() as $rowpack) {
                    $arrdata_dtl = array(
                        'companyid' => base64_decode($compid),
                        'setupid' => base64_decode($setupid),
                        'packageid' => $rowpack->packageid
                    );

                    $loadpackage_dtl = $this->m_packitem->show_packdtllist($arrdata_dtl);

                    if($loadpackage_dtl['errmsg'] == "true") {
                        foreach ($loadpackage_dtl['msg']->result() as $rowitem) {
                            $dtl_perpackage[$rowpack->packageid][] = array(
                                'id' => $rowitem->id,
                                'itemcode' => $rowitem->itemcode,
                                'itemname' => $rowitem->itemname,
                                'itemcost' => $rowitem->itemcost
                            );
                        }
                    }
                }

                $loadfixedcost = $this->m_packitem->fixedcost();

                $query['setupid'] = $setupid;
                $query['companyid'] = $compid;
                $query['packagelist'] = $loadpackage['msg'];
                $query['dtlpackagelist'] = $dtl_perpackage;
                $query['fixedcost'] = $loadfixedcost;

                $this->load->view('pack_item', $query);
            } else {
                $query['errmsg'] = $loadpackage['errmsg'];
                $query['companyid'] = $compid;

                $this->load->view('pack_description', $query);
            }
        } else {
            redirect('loginarea');
        }
    }

    public function selectitem() {
        $arrdata = array(
            'setupid' => base64_decode($this->input->post('setupid')),
            'companyid' => base64_decode($this->input->post('companyid')),
            'headerid' => $this->input->post('headerid'),
            'packageid' => $this->input->post('packageid'),
            'itemcode' => $this->input->post('kodeitem'),
            'itemstatus' => $this->input->post('checkboxstatus')
        );

        $query = $this->m_packitem->selectitem($arrdata);

        if($query['errmsg'] == "true") {
            $return['errmsg'] = "-";
            $return['linkeditem'] = $query['linkeditem'];
            $return['unlinkeditem'] = $query['unlinkeditem'];
            $return['errmsg'] = "-";
        } else {
            $return['errmsg'] = $query['errmsg'];
        }

        echo json_encode($return);
    }

    public function selectpackage() {
        $arr_data = array(
            'companyid' => base64_decode($this->input->post('companyid')),
            'setupid' => base64_decode($this->input->post('setupid')),
            'headerid' => $this->input->post('headerid'),
            'packageid' => $this->input->post('packageid'),
            'recid' => $this->input->post('recid')
        );

        $query = $this->m_packitem->selectpackage($arr_data);

        $return['errmsg'] = $query['errmsg'];
        $return['detailpackage'] = $query['data']->result_array();
        $return['examcosting'] = $query['examcosting'];

        echo json_encode($return);
    }

    public function updateitempackage() {
        $arr_data = array(
            'headerid' => $this->input->post('headerid'),
            'packageid' => $this->input->post('packageid')
        );

        $query = $this->m_packitem->selectitem_perpackage($arr_data);

        $return['errmsg'] = $query['errmsg'];
        $return['detailpackage'] = $query['data']->result_array();
        $return['examcosting'] = $query['examcosting'];

        echo json_encode($return);
    }

    public function deleteitem() {
        $arr_data = array(
            'headerid' => $this->input->post('headerid'),
            'packageid' => $this->input->post('packageid'),
            'itemcode' => $this->input->post('itemcode')
        );

        $query = $this->m_packitem->deleteitem_perpackage($arr_data);

        $return['errmsg'] = $query['errmsg'];
        $return['detailpackage'] = $query['detailpackage']->result_array();
        $return['examcosting'] = number_format($query['examcosting']);

        echo json_encode($return);
    }

    public function packageprice() {
        $arr_data = array(
            'setupid' => base64_decode($this->input->post('setupid')),
            'companyid' => base64_decode($this->input->post('companyid')),
            'packageid' => $this->input->post('packageid'),
            'packageprice' => $this->input->post('packageprice')
        );

        $query = $this->m_packitem->updatepackageprice($arr_data);

        $return['errmsg'] = $query;

        echo json_encode($return);
    }

    public function updatepackage() {
        if($this->input->post('flagid') == "packcert") {
            $resultdata = implode(",", $this->input->post('resultdata'));
        } else {
            $resultdata = $this->input->post('resultdata');
        }

        $arr_data = array(
            'packageid' => $this->input->post('packageid'),
            'flagid' => $this->input->post('flagid'),
            'resultdata' => $resultdata,
            'profitpercentage' => $this->input->post('profitpercentage'),
            'profitnominal' => $this->input->post('profitnominal')
        );

        $query = $this->m_packitem->updatepackage($arr_data);
        $return['errmsg'] = $query;

        echo json_encode($return);
    }

    public function deletepackage() {
        $query = $this->m_packitem->deletepackage($this->input->post('headerid'));

        $return['errmsg'] = $query;

        echo json_encode($return);
    }

    public function sendapproval() {
        $arr_val = array(
            'setupid' => base64_decode($this->input->post('setupid')),
            'companyid' => base64_decode($this->input->post('companyid'))
        );
        $checkpackstatus = $this->m_packitem->checkpackagestatus($arr_val);

        $return['errmsg'] = $checkpackstatus['errmsg'];

        if(strpos($checkpackstatus['status'], "0") > 0) {
            $return['emailmsg'] = array("0","Incomplete package details");
        } else {
            $updatestatus = $this->m_packitem->updatepackagestatus($arr_val);

            if($updatestatus['status'] == true) {
                $email_config = array(
                    'protocol' => 'smtp',
                    'smtp_host' => 'mail.medikaplaza.com',
                    'smtp_user' => 'drhardian@medikaplaza.com',
                    'smtp_password' => 'Passw0rd',
                    'smtp_port' => 25,
                    'mailtype' => 'html',
                    'çharset' => 'utf-8',
                    'crlf' => "\r\n",
                    'newline' => "\r\n"
                );

                $this->email->initialize($email_config);

                $logofilename = FCPATH.'/assets/bootstrap-4.0.0/dist/img/mp2016.jpg';
                $this->email->attach($logofilename);
                $cid = $this->email->attachment_cid($logofilename);

                $query = $this->m_packitem->packdesc_preview($arr_val);

                $data['logo'] = '<img src="cid:'.$cid.'" width="210px" height="65px" />';
                $data['companyname'] = $query['packageheader']->companyname;
                $data['activeperiode'] = date('d M Y', strtotime($query['packageheader']->startperiode))." - ".date('d M Y', strtotime($query['packageheader']->endperiode));
                $data['termofpayment'] = $query['packageheader']->packtop." days";
                $data['picmarketing'] = $query['packageheader']->namakaryawan;
                $data['detailpackage'] = $query['packagedetail_email'];
                $data['addrapprove'] = "http://localhost/mp_bop/approvalpackage/".$query['packageheader']->imgid."/".$this->input->post('companyid')."/".$this->input->post('setupid');

                $this->email->from('drhardian@medikaplaza.com', 'Dondon');
                $this->email->to('drhardian@medikaplaza.com');
                $this->email->subject('test email');
                $body = $this->load->view('email_template_approval1', $data, true);
                $this->email->message($body);

                if ($this->email->send()) {
                    $return['emailmsg'] = array("1", "Approval request successfully send");
                } else {
                    $return['emailmsg'] = array("0", "Error Email: " . $this->email->print_debugger());
                }

                $return['emailmsg'] = array("1", "Approval request successfully send");
            } elseif($updatestatus['status'] == false) {
                $return['emailmsg'] = array("0", $updatestatus['errmsg']);
            } else {
                $return['emailmsg'] = array("0", "Ünknown error status");
            }
        }

        echo json_encode($return);
    }

    function savetodraft() {
        $arr_val = array(
            'setupid' => base64_decode($this->input->post('setupid')),
            'companyid' => base64_decode($this->input->post('companyid'))
        );

        $query1 = $this->m_packitem->checkapprovalstatus($arr_val);

        if($query1['errmsg'] == "-") {
            if($query1['approvalstatus'] == "0") {
                $query1 = $this->m_packitem->savetodraft($arr_val);
                $return['errmsg'] = $query1;
            } else {
                $return['errmsg'] = "Package approval is in process";
            }
        } else {
            $return['errmsg'] = $query1['errmsg'];
        }

        echo json_encode($return);
    }

    public function pdf_paketmcu($companyid,$setupid) {
        $this->load->library('Pdf');

        $var_array = array(
            'companyid' => base64_decode($companyid),
            'setupid' => base64_decode($setupid)
        );

        $query = $this->m_packitem->packdesc_preview($var_array);
//        $query['packdetail'] = $this->m_packitem->packdetail_preview($var_array);

        $this->load->view('contoh', $query);
    }


//    public function preview_package() {
//        $arrdata = array(
//            'companyid' => base64_decode($this->input->post('companyid')),
//            'packageid' => base64_decode($this->input->post('packageid')),
//            'totalcost' => $this->input->post('totalcost'),
//            'profitvar' => $this->input->post('profitvar'),
//            'profitval' => $this->input->post('profitval'),
//            'manualfee' => $this->input->post('manualfee'),
//            'totalprice' => $this->input->post('totalprice'),
//            'vartype' => 'preview'
//        );
//
//        $q_detail = $this->m_packitem->packdesc_preview($arrdata);
//
//        if($q_detail['errmsg'] == true) {
//            $return['errmsg'] = "-";
//            $return['imnum'] = $q_detail['imnum'];
//            $return['packagename'] = $q_detail['packagename'];
//            $return['packagelanguage'] = $q_detail['packagelanguage'];
//            $return['certtype'] = $q_detail['certtype'];
//            $return['startperiode'] = $q_detail['startperiode'];
//            $return['endperiode'] = $q_detail['endperiode'];
//            $return['packtop'] = $q_detail['packtop'];
//            $return['packitem'] = $this->m_packitem->packdetail_preview($arrdata);
//        } else {
//            $return['errmsg'] = $q_detail['errmsg'];
//        }
//
//        echo json_encode($return);
//    }

//    public function submit_package() {
//        $arr_var = array(
//            'companyid' => base64_decode($this->input->post('companyid')),
//            'packageid' => base64_decode($this->input->post('packageid')),
//            'packageprice' => $this->input->post('totalprice')
//        );
//
//        $query = $this->m_packitem->submitpackage($arr_var);
//        $return['errmsg'] = $query;
//
//        echo json_encode($return);
//    }
}
?>