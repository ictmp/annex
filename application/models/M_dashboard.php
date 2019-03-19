<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_dashboard extends CI_Model {
    private $username;

    public function __construct() {
        parent::__construct();
        $this->username = $this->session->userdata('usn');
    }

    public function icon_company($arrdata) {
        if($arrdata['userlevel'] == "0") { /* user level 1 */
            $sql = "SELECT recid FROM master_kerjasama WHERE activestatus=? AND accountid=?";
            $query = $this->db->query($sql, array('A',$arrdata['employeeid']));

            if(!$query) {
                $dberror = $this->db->error();
                $errmsg = 'Error query total company: '.$dberror['message'];
                $totaldata = 0;
            } else {
                $errmsg = "-";
                $totaldata = $query->num_rows();
            }
        } else {
            $errmsg = "Undefined user level access";
            $totaldata = 0;
        }

        return array('errmsg' => $errmsg, 'totaldata' => $totaldata);
    }

    public function icon_package($arrdata) {
        if($arrdata['userlevel'] == "0") { /* user level 1 */
            $sql = "SELECT b.recid FROM master_kerjasama a,service_mastermcu b 
                    WHERE a.activestatus=? AND a.accountid=? AND a.companyid=b.companyid
                    AND b.activestatus=?";
            $query = $this->db->query($sql, array('A',$arrdata['employeeid'],'1'));

            if(!$query) {
                $dberror = $this->db->error();
                $errmsg = 'Error query total package: '.$dberror['message'];
                $totaldata = 0;
            } else {
                $errmsg = "-";
                $totaldata = $query->num_rows();
            }
        } else {
            $errmsg = "Undefined user level access";
            $totaldata = 0;
        }

        return array('errmsg' => $errmsg, 'totaldata' => $totaldata);
    }

    public function icon_expire($arrdata) {
        if($arrdata['userlevel'] == "0") { /* user level 1 */
            $sql = "SELECT b.recid FROM master_kerjasama a,service_mastermcu b 
                    WHERE a.activestatus=? AND a.accountid=? AND a.companyid=b.companyid
                    AND b.activestatus=? AND ( (DATEDIFF(b.lastdate, DATE_FORMAT(CURDATE(), '%Y/%m/%d'))) <= 75
                    AND (DATEDIFF(b.lastdate, DATE_FORMAT(CURDATE(), '%Y/%m/%d'))) >= -30 )";
            $query = $this->db->query($sql, array('A',$arrdata['employeeid'],'1'));

            if(!$query) {
                $dberror = $this->db->error();
                $errmsg = 'Error query total package: '.$dberror['message'];
                $totaldata = 0;
            } else {
                $errmsg = "-";
                $totaldata = $query->num_rows();
            }
        } else {
            $errmsg = "Undefined user level access";
            $totaldata = 0;
        }

        return array('errmsg' => $errmsg, 'totaldata' => $totaldata);
    }

    public function icon_draft($arrdata) {
        if($arrdata['userlevel'] == "0") { /* user level 1 */
            $sql = "SELECT a.recid FROM trx_mcupackage_setup a
                    WHERE a.draftstatus=? AND a.createuser=?";
            $query = $this->db->query($sql, array('1',$this->username));

            if(!$query) {
                $dberror = $this->db->error();
                $errmsg = 'Error query total package: '.$dberror['message'];
                $totaldata = 0;
            } else {
                $errmsg = "-";
                $totaldata = $query->num_rows();
            }
        } else {
            $errmsg = "Undefined user level access";
            $totaldata = 0;
        }

        return array('errmsg' => $errmsg, 'totaldata' => $totaldata);
    }

    function showcompanylist($arrdata) {
        if($arrdata['userlevel'] == "0") { /* user level 1 */
            $this->datatables->select("a.companyid,a.companyname,a.contact,a.contactphone,a.city");
            $this->datatables->from("master_kerjasama a");
            $this->datatables->join("master_employee b", "a.accountid=b.idkaryawan", "left");
            $this->datatables->where("a.activestatus", "A");
            $this->datatables->where("a.accountid", $arrdata['employeeid']);
        } else {
            $this->datatables->select("a.companyid,a.companyname,b.namakaryawan AS marketing,a.city");
            $this->datatables->from("master_kerjasama a");
            $this->datatables->join("master_employee b", "a.accountid=b.idkaryawan", "left");
            $this->datatables->where("a.activestatus", "A");
        }

        return $this->datatables->generate();
    }

    function showpackagelist($arrdata) {
        if($arrdata['userlevel'] == "0") { /* user level 1 */
            $array = array('a.activestatus' => '1', 'b.accountid' => $arrdata['employeeid']);
            $this->datatables->select("a.serviceid AS packageid,a.description AS packagename,
                                       DATE_FORMAT(a.lastdate, '%d/%m/%Y') AS expireddate,a.packageprice,
                                       a.companyid,
                                       b.accountid,
                                       c.namakaryawan");
            $this->datatables->from("service_mastermcu a");
            $this->datatables->join("master_kerjasama b", "a.companyid=b.companyid", "left");
            $this->datatables->join("master_employee c", "b.accountid=c.idkaryawan", "left");
            $this->datatables->where($array);
        } else {
            $this->datatables->select("a.serviceid AS packageid,a.description AS packagename,
                                       DATE_FORMAT(a.lastdate, '%d/%m/%Y') AS expireddate,a.packageprice,
                                       a.companyid,
                                       b.accountid,
                                       c.namakaryawan");
            $this->datatables->from("service_mastermcu a");
            $this->datatables->join("master_kerjasama b", "a.companyid=b.companyid", "left");
            $this->datatables->join("master_employee c", "b.accountid=c.idkaryawan", "left");
            $this->datatables->where("a.activestatus", "1");
        }

        return $this->datatables->generate();
    }

    function showexpirelist($arrdata) {
        if($arrdata['userlevel'] == "0") { /* user level 1 */
            $array = array(
                'a.activestatus' => '1',
                '(DATEDIFF(a.lastdate, DATE_FORMAT(CURDATE(), \'%Y/%m/%d\'))) <= ' => 75,
                '(DATEDIFF(a.lastdate, DATE_FORMAT(CURDATE(), \'%Y/%m/%d\'))) >= ' => -30,
                'b.accountid' => $arrdata['employeeid']
            );

            $this->datatables->select("a.serviceid AS packageid,a.description AS packagename,
                                       DATE_FORMAT(a.lastdate, '%d/%m/%Y') AS expireddate,a.packageprice,
                                       a.companyid,
                                       b.accountid,
                                       c.namakaryawan");
            $this->datatables->from("service_mastermcu a");
            $this->datatables->join("master_kerjasama b", "a.companyid=b.companyid", "left");
            $this->datatables->join("master_employee c", "b.accountid=c.idkaryawan", "left");
            $this->datatables->where($array);
            $this->db->order_by("a.lastdate", "asc");
        } else {
            $array = array(
                'a.activestatus' => '1',
                '(DATEDIFF(a.lastdate, DATE_FORMAT(CURDATE(), \'%Y/%m/%d\'))) <= ' => 75,
                '(DATEDIFF(a.lastdate, DATE_FORMAT(CURDATE(), \'%Y/%m/%d\'))) >= ' => -30
            );

            $this->datatables->select("a.serviceid AS packageid,a.description AS packagename,
                                       DATE_FORMAT(a.lastdate, '%d/%m/%Y') AS expireddate,a.packageprice,
                                       a.companyid,
                                       b.accountid,
                                       c.namakaryawan");
            $this->datatables->from("service_mastermcu a");
            $this->datatables->join("master_kerjasama b", "a.companyid=b.companyid", "left");
            $this->datatables->join("master_employee c", "b.accountid=c.idkaryawan", "left");
            $this->datatables->where($array);
            $this->db->order_by("a.lastdate", "asc");
        }

        return $this->datatables->generate();
    }

    function showdraftlist() {
        $this->datatables->select("a.recid,a.companyid,a.numofpackage,a.startperiode,a.endperiode,a.imgid,b.companyname");
        $this->datatables->from("trx_mcupackage_setup a");
        $this->datatables->join("master_kerjasama b", "a.companyid=b.companyid", "left");
        $this->datatables->where("a.draftstatus", "1");
        $this->datatables->where("a.createuser", $this->username);

        return $this->datatables->generate();
    }

    function getlist_detail2() {
        $this->datatables->select("a.serviceid AS packageid,a.description AS packagename,
                                   DATE_FORMAT(a.lastdate, '%d/%m/%Y') AS expireddate,a.packageprice,
                                   a.companyid,
                                   b.accountid,
                                   c.namakaryawan");
        $this->datatables->from("service_mastermcu a");
        $this->datatables->join("master_kerjasama b", "a.companyid=b.companyid", "left");
        $this->datatables->join("master_employee c", "b.accountid=c.idkaryawan", "left");
        $this->datatables->where("a.needapproval", "0");

        return $this->datatables->generate();
    }

//    function view_detail2($data) {
//        $select1 = "SELECT
//                      a.contractid AS imnum,a.serviceid AS packageid,a.description AS packagename,
//                      (CASE
//                        WHEN a.languageresult='0' THEN 'Indonesia'
//                        WHEN a.languageresult='1' THEN 'English'
//                      END) AS mcuresult,a.limitdata AS mcucertificate,
//                      DATE_FORMAT(a.firstdate, '%d/%m/%Y') AS periodestart,a.firstdate AS startperiode,
//                      DATE_FORMAT(a.lastdate, '%d/%m/%Y') AS periodeend,a.lastdate AS endperiode,
//                      a.notes_im AS packagenotes,a.maxdebt AS paymentduedate,a.totalcost,a.factorprice AS profitvar,
//                      a.cosharingservice AS profitval,a.cosharingpatient AS manualfee,a.packageprice AS totalprice,
//                      b.companyname
//                    FROM service_mastermcu a
//                    LEFT JOIN master_kerjasama b ON (a.companyid=b.companyid)
//                    WHERE a.companyid=? AND a.serviceid=?
//                    LIMIT 1";
//        $query_select1 = $this->db->query($select1, array($data['companyid'], $data['packageid']));
//
//        if(!$query_select1) {
//
//            $db_error = $this->db->error();
//            $arrval = array('errmsg' => $db_error['message']);
//            return $arrval; exit();
//
//        } else {
//            $imnum = $query_select1->row()->imnum;
//
//            $select2 = "SELECT
//                                a.serviceid AS packageid,a.productid AS itemcode,a.basecost AS costing,
//                                b.nama_barang AS itemname
//                            FROM mcukontrak_product a
//                            LEFT JOIN product_master b ON (a.productid=b.id_barang)
//                            WHERE a.serviceid=? AND a.contractid=?";
//            $query_select2 = $this->db->query($select2, array($data['packageid'],$imnum));
//
//            if (!$query_select2) {
//
//                $db_error = $this->db->error();
//                $arrval = array('errmsg' => $db_error['message']);
//                return $arrval; exit();
//
//            } else {
//
//                $select3 = "SELECT file_name,TO_BASE64(token_id) AS fileid FROM master_attachfilepack WHERE companyid=? AND packageid=?";
//                $query_select3 = $this->db->query($select3, array($data['companyid'], $data['packageid']));
//
//                if (!$query_select2) {
//
//                    $db_error = $this->db->error();
//                    $arrval = array('errmsg' => $db_error['message']);
//                    return $arrval; exit();
//
//                } else {
//
//                    $arrval = array(
//                        'errmsg' => true,
//                        'packagedetail' => $query_select1,
//                        'packagelist' => $query_select2,
//                        'packagefile' => $query_select3
//                    );
//
//                    return $arrval; exit();
//
//                }
//
//            }
//
//        }
//    }

    function show_packlist($data) {
        $sql = "SELECT 
                  b.packageid,b.packagename,b.certtype,b.recid AS headerid,b.totalcost,b.profitvar,b.profitval,b.manualfee,
                  (
                    SELECT
                      SUM(costing)
                    FROM trx_mcupackage_item
                    WHERE setupid=b.setupid AND companyid=b.companyid AND packageid=b.packageid 
                  ) AS packagecost,b.totalprice
               FROM trx_mcupackage_setup a
               LEFT JOIN trx_mcupackage_header b ON (a.recid=b.setupid)
               WHERE a.companyid=? AND a.imgid=?
               ORDER BY a.recid";
        $query = $this->db->query($sql,
            array(
                $data['companyid'],
                $data['imgid']
            )
        );

        return $query;
    }

//    function checkstatus() {
//        $sql_countcomp = "SELECT companyid FROM master_kerjasama WHERE activestatus='A'";
//        $query_countcomp = $this->db->query($sql_countcomp);
//
//        $sql_needapprove = "SELECT recid FROM trx_mcupackage_setup WHERE approvalstatus='1'";
//        $query_countnpp = $this->db->query($sql_needapprove);
//
//        $sql_package = "SELECT serviceid FROM service_mastermcu WHERE activestatus='1'";
//        $query_countpackage = $this->db->query($sql_package);
//
//        $sql_expire = "SELECT serviceid FROM service_mastermcu WHERE activestatus='1'
//                        AND ( (DATEDIFF(lastdate, DATE_FORMAT(CURDATE(), '%Y/%m/%d'))) <= 75
//                        AND (DATEDIFF(lastdate, DATE_FORMAT(CURDATE(), '%Y/%m/%d'))) >= -30 )";
//        $query_countexpire = $this->db->query($sql_expire);
//
//        if(!$query_countcomp) {
//            $db_error = $this->db->error();
//            $totalcomp = "-";
//            $totalnpp = "-";
//            $totalpackage = "-";
//            $totalexpire = "-";
//
//            $arr_msg = array(
//                'errmsg' => $db_error['message'],
//                'totalcomp' => $totalcomp,
//                'totalnpp' => $totalnpp,
//                'totalpackage' => $totalpackage,
//                'totalexpire' => $totalexpire
//            );
//        } elseif(!$query_countnpp) {
//            $db_error = $this->db->error();
//            $totalcomp = "-";
//            $totalnpp = "-";
//            $totalpackage = "-";
//            $totalexpire = "-";
//
//            $arr_msg = array(
//                'errmsg' => $db_error['message'],
//                'totalcomp' => $totalcomp,
//                'totalnpp' => $totalnpp,
//                'totalpackage' => $totalpackage,
//                'totalexpire' => $totalexpire
//            );
//        } elseif(!$query_countpackage) {
//            $db_error = $this->db->error();
//            $totalcomp = "-";
//            $totalnpp = "-";
//            $totalpackage = "-";
//            $totalexpire = "-";
//
//            $arr_msg = array(
//                'errmsg' => $db_error['message'],
//                'totalcomp' => $totalcomp,
//                'totalnpp' => $totalnpp,
//                'totalpackage' => $totalpackage,
//                'totalexpire' => $totalexpire
//            );
//        } elseif(!$query_countexpire) {
//            $db_error = $this->db->error();
//            $totalcomp = "-";
//            $totalnpp = "-";
//            $totalpackage = "-";
//            $totalexpire = "-";
//
//            $arr_msg = array(
//                'errmsg' => $db_error['message'],
//                'totalcomp' => $totalcomp,
//                'totalnpp' => $totalnpp,
//                'totalpackage' => $totalpackage,
//                'totalexpire' => $totalexpire
//            );
//        } else {
//            $db_error = "-";
//            $totalcomp = $query_countcomp->num_rows();
//            $totalnpp = $query_countnpp->num_rows();
//            $totalpackage = $query_countpackage->num_rows();
//            $totalexpire = $query_countexpire->num_rows();
//
//            $arr_msg = array(
//                'errmsg' => $db_error,
//                'totalcomp' => $totalcomp,
//                'totalnpp' => $totalnpp,
//                'totalpackage' => $totalpackage,
//                'totalexpire' => $totalexpire
//            );
//        }
//
//        return $arr_msg;
//    }

//    function approve_package($data) {
//        if($data['approvetype'] != "3") {
//            $activestatus = $data['approvetype'];
//
//            $sql = "UPDATE service_mastermcu
//                      SET activestatus=?,needapproval=?,approval_1=?,approval_1date=?,approval_1time=?
//                    WHERE companyid=? AND serviceid=?";
//            $query = $this->db->query($sql, array(
//                    $activestatus,
//                    $data['approvetype'],
//                    $this->username,
//                    date('Y-m-d'),
//                    date('H:i:s'),
//                    $data['companyid'],
//                    $data['packageid']
//                )
//            );
//        } else {
//            $activestatus = "0";
//
//            $sql = "UPDATE service_mastermcu
//                      SET activestatus=?,needapproval=?
//                    WHERE companyid=? AND serviceid=?";
//            $query = $this->db->query($sql, array(
//                    $activestatus,
//                    $data['approvetype'],
//                    $data['companyid'],
//                    $data['packageid']
//                )
//            );
//        }
//
//        if(!$query) {
//            $db_error = $this->db->error();
//            return $db_error['message'];
//        } else {
//            $db_error = "-";
//            return $db_error;
//        }
//    }

    function show_attachfile($arrdata) {
        $sql = "SELECT file_name,file_type,file_size,TO_BASE64(token_id) AS fileid FROM master_attachfilepack WHERE companyid=? AND imgid=?";
        $query = $this->db->query($sql,
            array(
                $arrdata['companyid'],
                $arrdata['imgid']
            )
        );

        if(!$query) {
            $dberror = $this->db->error();
            $errmsg = $dberror['message'];

            return array(
                'errmsg' => $errmsg,
                'attachfile' => ''
            );
        } else {
            return array(
                'errmsg' => true,
                'attachfile' => $query->result()
            );
        }
    }

    function getFilename($token_id) {
        $sql = "SELECT file_name FROM master_attachfilepack WHERE token_id=?";
        $query = $this->db->query($sql, array($token_id));

        return $query->row()->file_name;
    }
}
?>