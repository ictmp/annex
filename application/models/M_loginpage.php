<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_loginpage extends CI_Model {
//    private $db_serverbop;
//
//    public function __construct()
//    {
//        parent::__construct();
//        $this->db_serverbop = $this->load->database('serverbop', TRUE);
//    }

    function loggedcheck() {
        return $this->session->userdata('usn');
    }

    function useraccountcheck($data) {
        $sql = "SELECT username,useraccess FROM user_manager WHERE username=? AND password=? LIMIT 1";
        $query = $this->db->query($sql,array($data['username'],$data['password']));
        if($query->num_rows() == 1) {
            $row = $query->row();
            $array_data = array(
                'username' => $row->username,
                'useraccess' => $row->useraccess
            );

            return $array_data;
        } else {
            return false;
        }
    }

    function getWhCode($menuid) {
        $sql = "SELECT b.sapcode AS whscode FROM system_variabel a LEFT JOIN master_warehouse b ON (a.kodegudang=b.kodegudang) WHERE a.kodemenu=?";
        $query = $this->db->query($sql, array(str_replace(".","",$menuid)));
        if(!$query) {
            $dberror = $this->db->error();
            return $dberror['message'];
        } else {
            return $query->row()->whscode;
        }
    }

//    function usermenu() {
//        $sql_menu = "SELECT
//                        a.`MenuID`,b.`MenuDescription`,b.`MenuLink`,b.`MenuType`
//                    FROM `master_privileges` a
//                    LEFT JOIN menu_manager b ON (a.`MenuID`=b.`MenuID`)
//                    WHERE a.`Username`=? AND b.`MenuCategory`=?
//                    ORDER BY a.`MenuID` ASC;";
//        $query = $this->db->query($sql_menu, array($this->session->userdata('usn'), '1'));
//
//        if($query->num_rows() != 0) {
//            foreach ($query->result() as $row) {
//                $arr_menu[] = array(
//                    'menu_id' => $row->MenuID,
//                    'menu_text' => $row->MenuDescription,
//                    'menu_url' => $row->MenuLink,
//                    'menu_type' => $row->MenuType
//                );
//            }
//        } else {
//            $sql_all = "SELECT
//                            `MenuID`,`MenuDescription`,`MenuLink`,`MenuType`
//                        FROM `menu_manager`
//                        WHERE `MenuCategory`=?
//                        ORDER BY `MenuID` ASC";
//            $query_all = $this->db->query($sql_all, array('1'));
//
//            foreach ($query_all->result() as $row) {
//                $arr_menu[] = array(
//                    'menu_id' => $row->MenuID,
//                    'menu_text' => $row->MenuDescription,
//                    'menu_url' => $row->MenuLink,
//                    'menu_type' => $row->MenuType
//                );
//            }
//        }
//
//        $arr = array(
//            'useraccess' => $this->session->userdata('uac'),
//            'usermenu' => $arr_menu
//        );
//
//        return $arr;
//    }

    function usermenu() {
        if( $this->session->userdata('uac') != "3" && $this->session->userdata('uac') != "4" ) {
            $sql_menu = "SELECT
                        a.`MenuID`,b.`MenuDescription`,b.`MenuLink`,b.`MenuType`
                    FROM `master_privileges` a 
                    LEFT JOIN menu_manager b ON (a.`MenuID`=b.`MenuID`)
                    WHERE a.`Username`=? AND b.`MenuCategory`=?
                    ORDER BY a.`MenuID` ASC;";
            $query = $this->db->query($sql_menu, array($this->session->userdata('usn'), '1'));

            if($query->num_rows() != 0) {
                foreach ($query->result() as $row) {
                    $arr_menu[] = array(
                        'menu_id' => $row->MenuID,
                        'menu_text' => $row->MenuDescription,
                        'menu_url' => $row->MenuLink,
                        'menu_type' => $row->MenuType
                    );
                }
            } else {
                $arr_menu = array();
            }
        } else {
            $sql_all = "SELECT
                            `MenuID`,`MenuDescription`,`MenuLink`,`MenuType`
                        FROM `menu_manager`
                        WHERE `MenuCategory`=?
                        ORDER BY `MenuID` ASC";
            $query_all = $this->db->query($sql_all, array('1'));

            if( $query_all->num_rows() > 0 ) {
                foreach ($query_all->result() as $row) {
                    $arr_menu[] = array(
                        'menu_id' => $row->MenuID,
                        'menu_text' => $row->MenuDescription,
                        'menu_url' => $row->MenuLink,
                        'menu_type' => $row->MenuType
                    );
                }
            } else {
                $arr_menu = array();
            }
        }

        $arr = array(
            'useraccess' => $this->session->userdata('uac'),
            'usermenu' => $arr_menu
        );

        return $arr;
    }

    function userlevelaccess() {
        return $this->session->userdata('uac');
    }
}
?>