<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_packdescription extends CI_Model {
    private $username;

    public function __construct() {
        $this->username = $this->session->userdata('usn');
    }

    function showcompany($data) {
        $sql1 = "SELECT recid,imgid FROM trx_mcupackage_setup WHERE createuser=? AND approvalstatus=? AND draftstatus=?";
        $query1 = $this->db->query($sql1, array($this->username,'0','0'));
        if(!$query1) {
            $dberror = $this->db->error();
            $errmsg = $dberror['message'];
            $companydata = "";
        } else {
            foreach ($query1->result() as $row) {
                $del_pack = "DELETE FROM trx_mcupackage_setup WHERE recid=?";
                $query_delpackage = $this->db->query($del_pack, array($row->recid));

                $del_packheader = "DELETE FROM trx_mcupackage_header WHERE setupid=?";
                $query_delpackage = $this->db->query($del_packheader, array($row->recid));

                $del_packitem = "DELETE FROM trx_mcupackage_item WHERE setupid=?";
                $query_delpackageitem = $this->db->query($del_packitem, array($row->recid));

                $sql3 = "SELECT recid,file_name FROM master_attachfilepack WHERE imgid=? AND companyid=?";
                $query3 = $this->db->query($sql3, array($row->imgid,$data));
                if($query3->num_rows() > 0) {
                    foreach ($query3->result() as $row1) {
                        if(file_exists($file = FCPATH.'/assets/uploads/'.$row1->file_name)) {
                            unlink($file);
                            delete_files($file);

                            $sql4 = "DELETE FROM master_attachfilepack WHERE recid=?";
                            $query4 = $this->db->query($sql4, array($row1->recid));
                        }
                    }
                }
            }

            $sql2 = "SELECT 
                      companyname,address1,city,phone,contact,contactemail,delivery_contact,delivery_address,delivery_email 
                    FROM master_kerjasama 
                    WHERE companyid=? LIMIT 1";
            $query2 = $this->db->query($sql2, array($data));
            if(!$query2) {
                $dberror = $this->db->error();
                $errmsg = $dberror['message'];
                $companydata = "";
            } else {
                $errmsg = "-";
                $companydata = $query2->row();
            }
        }

        $arrdata = array(
            'errmsg' => $errmsg,
            'companydata' => $companydata
        );

        return $arrdata;
    }

    function insertsetup($companyid,$projectid) {
        $sql = "INSERT INTO
                  trx_mcupackage_setup(companyid,imgid,createuser,createdate,createtime)
                VALUES(?,?,?,?,?)";
        $query = $this->db->query($sql, array(
            $companyid,
            $projectid,
            $this->username,
            date('Y-m-d'),
            date('H:i:s')));
        if(!$query) {
            $dberror = $this->db->error();
            $errmsg = $dberror['message'];
            $getid = "-";
        } else {
            $errmsg = "-";
            $getid = $this->db->insert_id();
        }

        $arr_return = array(
            'errmsg' => $errmsg,
            'getid' => $getid
        );

        return $arr_return;
    }

    function createpack($data) {
        $sql1 = "UPDATE trx_mcupackage_setup 
                  SET numofpackage=?,packagelanguage=?,resulttype=?,startperiode=?,endperiode=?,packtop=?,packagenote=?
                WHERE companyid=? AND imgid=? AND createuser=?";
//        $sql1 = "INSERT INTO
//                  trx_mcupackage_setup(companyid,numofpackage,packagelanguage,
//                  startperiode,endperiode,packtop,packagenote,imgid,createuser,createdate,createtime)
//                VALUES(?,?,?,?,?,?,?,?,?,?,?)";

        $sql2 = "INSERT INTO 
                    trx_mcupackage_header(setupid,companyid,packageid,basiccost,createuser,createdate,createtime)
                VALUES(?,?,?,?,?,?,?)";

        $sql3 = "SELECT SUM(costing) AS fixedcost FROM master_costingmcu_fixed";
        $query3 = $this->db->query($sql3);
        $row3 = $query3->row();

        $this->db->trans_begin();

        $query1 = $this->db->query($sql1,
            array(
                $data['numpackage'],
                $data['packlang'],
                $data['resulttype'],
                $data['stdate'],
                $data['endate'],
                $data['packtop'],
                $data['packnote'],
                $data['compid'],
                $data['projectid'],
                $this->username
            )
        );
//        $query1_id = $this->db->insert_id();
        $query1_id = $data['getid'];

        for($i = 1; $i <= intval($data['numpackage']); $i++) {
            $StringToAuto = date('Y-m-d')."".date('H:i:s')."".base64_decode($this->input->post('compid'))."".$i;
            $AutoCode = substr(md5($StringToAuto),0,10);
            $packageid = substr(base64_decode($this->input->post('compid')),0,1)."".$AutoCode;

            $query2 = $this->db->query($sql2,
                array(
                    $query1_id,
                    $data['compid'],
                    $packageid,
                    $row3->fixedcost,
                    $this->username,
                    date('Y-m-d'),
                    date('H:i:s')
                )
            );
        }

        if($this->db->trans_status() == false) {
            $this->db->trans_rollback();

            $return_val = array(
                'querystatus' => false,
                'compid' => '',
                'setupid' => '',
                'errmsg' => $this->db->error()
            );
        } else {
            $this->db->trans_commit();

            $return_val = array(
                'querystatus' => true,
                'compid' => $data['compid'],
                'setupid' => $query1_id,
                'errmsg' => ''
            );
        }
        return $return_val;
    }

    function uploadfile($data) {
        $sql1 = "SELECT recid FROM trx_mcupackage_setup WHERE imgid=? AND companyid=?";
        $query1 = $this->db->query($sql1,array($data['projectid'],$data['companyid']));

        if($query1->num_rows() != 0) {
            $sql2 = "INSERT INTO 
                      master_attachfilepack(imgid,companyid,file_name,file_type,file_size,token_id,uploaduser,uploaddate,uploadtime) 
                    VALUES(?,?,?,?,?,?,?,?,?)";
            $query2 = $this->db->query($sql2,
                array(
                    $data['projectid'],
                    $data['companyid'],
                    $data['file_name'],
                    $data['file_type'],
                    $data['file_size'],
                    $data['token_id'],
                    $this->username,
                    date('Y-m-d'),
                    date('H:i:s')
                ));

            if(!$query2) {
                $dberror = $this->db->error();
                $errmsg = $dberror['message'];
            } else {
                $errmsg = "-";
            }
        } else {
            if(file_exists($file = FCPATH.'/assets/uploads/'.$data['file_name'])) {
                unlink($file);
                delete_files($file);
            }

            $errmsg = "Incomplete setup data";
        }

        return $errmsg;
    }

    function removefile($token) {
        $sql = "SELECT file_name FROM master_attachfilepack WHERE token_id=?";
        $query1 = $this->db->query($sql, array($token));

        if($query1->num_rows() > 0) {
            $del = "DELETE FROM master_attachfilepack WHERE token_id=?";
            $query2 = $this->db->query($del, array($token));

            if(!$query2) {
                $db_error = $this->db->error();
                $arr_msg = array(
                    'errmsg' => $db_error['message'],
                    'msg' => ''
                );

                return $arr_msg;
            } else {
                $namafile = $query1->row()->file_name;

                $arr_msg = array(
                    'errmsg' => true,
                    'msg' => $namafile
                );
                return $arr_msg;
            }
        }
    }

//    function removefile() {
//        $sql = "SELECT file_name FROM master_attachfilepack WHERE uploaduser=?";
//        $query1 = $this->db->query($sql, array($this->username));
//
//        if($query1->num_rows() > 0) {
//            $del = "DELETE FROM master_attachfilepack WHERE uploaduser=?";
//            $query2 = $this->db->query($del, array($this->username));
//
//            if(!$query2) {
//                $db_error = $this->db->error();
//                $arr_msg = array(
//                    'errmsg' => $db_error['message'],
//                    'msg' => ''
//                );
//
//                return $arr_msg;
//            } else {
//                $arr_msg = array(
//                    'errmsg' => true,
//                    'msg' => $query1
//                );
//                return $arr_msg;
//            }
//        } else {
//            $arr_msg = array(
//                'errmsg' => true,
//                'msg' => "0"
//            );
//            return $arr_msg;
//        }
//    }
}
?>