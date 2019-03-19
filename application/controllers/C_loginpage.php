<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_loginpage extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('m_loginpage');
    }

    public function index() {
        if($this->m_loginpage->loggedcheck()) {
            redirect('mainpage');
        } else {
            $this->load->view('loginpage');
        }
	}

	public function loginprocess() {
        $username = $this->input->post("username");
        $password = md5($this->input->post('password'));

        $checking = $this->m_loginpage->useraccountcheck(array('username' => $username, 'password' => $password));

        if($checking == false) {
            $return['errmsg'] = "User account not found";
        } else {
            $sessiondata = array(
                'usn' => $checking['username'],
                'uac' => $checking['useraccess']
            );
            $this->session->set_userdata($sessiondata);
            $return['errmsg'] = "-";
        }

        echo json_encode($return);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('loginarea');
    }
}
?>