<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_packlist extends CI_Model {
    private $username;

    public function __construct() {
        parent::__construct();
        $this->username = $this->session->userdata('usn');
    }

    function showdetailcomp($data) {
        $sql = "SELECT
                  companyname,address1,city,phone,contact,contactemail
                FROM master_kerjasama
                WHERE companyid=? LIMIT 1";
        $query = $this->db->query($sql, array($data));

        return $query->row();
    }

    function getlist($companyid) {
        $this->datatables->select("serviceid AS packageid,description AS packagename,
                                   DATE_FORMAT(lastdate, '%d/%m/%Y') AS expireddate,packageprice,
                                   (CASE
                                        WHEN needapproval IS NULL THEN '1'
                                        WHEN needapproval IS NOT NULL THEN needapproval   
                                   END) AS approvedtype");
        $this->datatables->from("service_mastermcu");
        $this->datatables->where("companyid", $companyid);
        $this->datatables->where("packageprice !=", "");

        return $this->datatables->generate();
    }

    function viewpackage($data) {
        $del1 = "DELETE trx_mcupackage,trx_mcupackage_item 
                FROM trx_mcupackage
                INNER JOIN trx_mcupackage_item ON trx_mcupackage.companyid=trx_mcupackage_item.companyid AND trx_mcupackage.packageid=trx_mcupackage_item.packageid
                WHERE trx_mcupackage.companyid=? 
                AND trx_mcupackage.packageid=?";
        $query_del1 = $this->db->query($del1,
            array(
                $data['companyid'],
                $data['packageid']
            )
        );

        if(!$query_del1) {
            $db_error = $this->db->error();
            $returnmsg = array('errmsg' => $db_error['message']);
            return $returnmsg; exit();
        } else {
            $insert1 = "INSERT INTO
                          trx_mcupackage(companyid,imnum,packageid,packagename,packagelanguage,certtype,startperiode,endperiode,
                          packagenote,packtop,totalcost,profitvar,profitval,manualfee,totalprice,createuser,createdate,createtime)
                        SELECT
                            ?,contractid,serviceid,description,languageresult,limitdata,
                            firstdate,lastdate,notes_im,maxdebt,totalcost,factorprice,
                            cosharingservice,cosharingpatient,packageprice,?,?,?
                        FROM service_mastermcu
                        WHERE companyid=? AND serviceid=?
                        LIMIT 1";
            $query_insert1 = $this->db->query($insert1,
                array(
                    $data['companyid'],
                    $this->username,
                    date('Y-m-d'),
                    date('H:i:s'),
                    $data['companyid'],
                    $data['packageid']
                )
            );

            if (!$query_insert1) {
                $db_error = $this->db->error();
                $returnmsg = array('errmsg' => $db_error['message']);
                return $returnmsg; exit();
            } else {
                $insert2 = "INSERT INTO
                              trx_mcupackage_item(companyid,packageid,itemcode,costing,
                               createuser,createdate,createtime)
                            SELECT
                                ?,serviceid,productid,basecost,?,?,?
                            FROM mcukontrak_product
                            WHERE serviceid=?";
                $query_insert2 = $this->db->query($insert2, array(
                        $data['companyid'],
                        $this->username,
                        date('Y-m-d'),
                        date('H:i:s'),
                        $data['packageid']
                    )
                );

                if(!$query_insert2) {
                    $db_error = $this->db->error();
                    $returnmsg = array('errmsg' => $db_error['message']);
                    return $returnmsg; exit();
                } else {
                    $sql_tmp1 = "SELECT
                                    a.companyid,b.companyname,a.imnum,
                                    DATE_FORMAT(a.startperiode, '%d/%m/%Y') AS periodestart,
                                    DATE_FORMAT(a.endperiode, '%d/%m/%Y') AS periodeend,
                                    (CASE
                                      WHEN a.packagelanguage='0' THEN 'Indonesia'
                                      WHEN a.packagelanguage='1' THEN 'English'
                                    END) AS mcuresult,a.certtype,
                                    a.packtop AS paymentduedate,a.packagenote AS packagenotes,
                                    a.totalcost,a.profitvar,a.profitval,a.manualfee,a.totalprice 
                                  FROM trx_mcupackage a
                                  LEFT JOIN master_kerjasama b ON (a.companyid=b.companyid) 
                                  WHERE a.createuser=? AND a.companyid=? AND a.packageid=?";
                    $query_select1 = $this->db->query($sql_tmp1,
                        array(
                            $this->username,
                            $data['companyid'],
                            $data['packageid']
                        )
                    );

                    if(!$query_select1) {
                        $db_error = $this->db->error();
                        $returnmsg = array('errmsg' => $db_error['message']);
                        return $returnmsg; exit();
                    } else {
                        $sql_tmp2 = "SELECT
                                    a.packageid,a.itemcode,b.nama_barang AS itemname,a.costing
                                FROM trx_mcupackage_item a
                                LEFT JOIN product_master b ON (a.itemcode=b.id_barang)
                                WHERE a.companyid=? AND a.packageid=? AND a.createuser=?";
                        $query_select2 = $this->db->query($sql_tmp2,
                            array(
                                $data['companyid'],
                                $data['packageid'],
                                $this->username
                            )
                        );

                        if(!$query_select2) {
                            $db_error = $this->db->error();
                            $returnmsg = array('errmsg' => $db_error['message']);
                            return $returnmsg; exit();
                        } else {
                            $returnmsg = array(
                                'errmsg' => true,
                                'packagedata' => $query_select1,
                                'packageitem' => $query_select2
                            );
                            return $returnmsg;
                        }
                    }
                }
            }
        }

//            $select1 = "SELECT
////                            a.contractid AS imnum,a.serviceid AS packageid,a.description AS packagename,
////                            (CASE
////                              WHEN a.languageresult='0' THEN 'Indonesia'
////                              WHEN a.languageresult='1' THEN 'English'
////                            END) AS mcuresult,a.limitdata AS mcucertificate,
////                            DATE_FORMAT(a.firstdate, '%d/%m/%Y') AS periodestart,a.firstdate AS startperiode,
////                            DATE_FORMAT(a.lastdate, '%d/%m/%Y') AS periodeend,a.lastdate AS endperiode,
////                            a.notes_im AS packagenotes,a.maxdebt AS paymentduedate,a.totalcost,a.factorprice AS profitvar,
////                            a.cosharingservice AS profitval,a.cosharingpatient AS manualfee,a.packageprice AS totalprice,
////                            b.companyname
////                        FROM service_mastermcu a
////                        LEFT JOIN master_kerjasama b ON (a.companyid=b.companyid)
////                        WHERE a.companyid=? AND a.serviceid=?
////                        LIMIT 1";
//            $query_select1 = $this->db->query($select1, array($data['companyid'], $data['packageid']));
//
//            if(!$query_select1) {
//                $db_error = $this->db->error();
//                $returnmsg = array('errmsg' => $db_error['message']);
//                return $returnmsg; exit();
//            } else {
//                $select2 = "SELECT
//                                a.serviceid AS packageid,a.productid AS itemcode,a.basecost AS costing,
//                                b.nama_barang AS itemname
//                            FROM mcukontrak_product a
//                            LEFT JOIN product_master b ON (a.productid=b.id_barang)
//                            WHERE a.serviceid=?";
//                $query_select2 = $this->db->query($select2, array($query_select1->row()->packageid));
//
//                if(!$query_select2) {
//                    $db_error = $this->db->error();
//                    $returnmsg = array('errmsg' => $db_error['message']);
//                    return $returnmsg; exit();
//                } else {
//                    if ($query_select1->num_rows() > 0) {
//                        $insert1 = "INSERT INTO
//                                        trx_mcupackage(companyid,imnum,packageid,packagename,packagelanguage,certtype,startperiode,endperiode,
//                                        packagenote,packtop,totalcost,profitvar,profitval,manualfee,totalprice,createuser,createdate,createtime)
//                                   VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
//                        $query_insert1 = $this->db->query($insert1, array(
//                            $data['companyid'],
//                            $query_select1->row()->imnum,
//                            $data['packageid'],
//                            $query_select1->row()->packagename,
//                            $query_select1->row()->mcuresult,
//                            $query_select1->row()->mcucertificate,
//                            $query_select1->row()->startperiode,
//                            $query_select1->row()->endperiode,
//                            $query_select1->row()->packagenotes,
//                            $query_select1->row()->paymentduedate,
//                            $query_select1->row()->totalcost,
//                            $query_select1->row()->profitvar,
//                            $query_select1->row()->profitval,
//                            $query_select1->row()->manualfee,
//                            $query_select1->row()->totalprice,
//                            $this->username, date('Y-m-d'), date('H:i:s')
//                        ));
//
//                        if (!$query_insert1) {
//                            $db_error = $this->db->error();
//                            $returnmsg = array('errmsg' => $db_error['message']);
//                            return $returnmsg;
//                            exit();
//                        } else {
//                            foreach ($query_select2->result() as $rowitem) {
//                                $insert2 = "INSERT INTO
//                                                trx_mcupackage_item(companyid,packageid,itemcode,costing,
//                                                createuser,createdate,createtime)
//                                            VALUES(?,?,?,?,?,?,?)";
//                                $query_insert2 = $this->db->query($insert2, array(
//                                        $data['companyid'],
//                                        $data['packageid'],
//                                        $rowitem->itemcode,
//                                        $rowitem->costing,
//                                        $this->username, date('Y-m-d'), date('H:i:s')
//                                    )
//                                );
//                            }
//
//                            $returnmsg = array(
//                                'errmsg' => true,
//                                'packagedata' => $query_select1,
//                                'packageitem' => $query_select2
//                            );
//                            return $returnmsg;
//                        }
//                    }
//                }
//            }
//        }
    }
}
?>