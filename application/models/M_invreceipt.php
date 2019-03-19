<?php
/**
 * Created by PhpStorm.
 * User: ICT-ADMIN
 * Date: 01/03/2019
 * Time: 11:00
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_invreceipt extends CI_Model {
    private $username;
    private $dbsap;

    function __construct() {
        parent::__construct();

        $this->username = $this->session->userdata('usn');
        $this->dbsap = $this->load->database('dbsqlsrv', TRUE);
    }

    function getlist() {
        $sql1 = "DELETE FROM tmp_invreceipt WHERE createuser=?";
        $query1 = $this->db->query($sql1, array($this->username));
        if($query1) {
            $sql = "SELECT docnum,convert(VARCHAR , docdate, 103) AS docdate1,docdate,cardname,doctotal FROM OINV";
            $query = $this->dbsap->query($sql);

            $arr_data['data'] = $query->result_array();
        }

        return $arr_data;
    }

    function getdocnum($arrdata) {
        if($arrdata['cbstatus'] == "true") {
            $sql1 = "SELECT
                        a.docnum,a.docdate,a.docduedate,a.doctotal,a.doccur,a.cardcode,b.cardname,c.name AS attentionto
                     FROM OINV a
                     LEFT JOIN OCRD b ON (a.cardcode=b.cardcode)
                     LEFT JOIN OCPR c ON (b.cardcode=c.cardcode AND (c.position LIKE '%FIN%' OR c.position LIKE '%FINANCE%'))
                     WHERE a.docnum=?";
            $query1 = $this->dbsap->query($sql1, array($arrdata['docnum']));
            if(!$query1) {
                $dberror = $this->dbsap->error();
            } else {
                $row1 = $query1->row();

                $sql2 = "INSERT INTO tmp_invreceipt(docnum,docdate,docduedate,amount,currtype,cardcode,cardname,attentionto,createuser,createdate,createtime)
                        VALUES(?,?,?,?,?,?,?,?,?,?,?)";
                $query2 = $this->db->query($sql2,
                    array(
                        $row1->docnum,
                        $row1->docdate,
                        $row1->docduedate,
                        $row1->doctotal,
                        $row1->doccur,
                        $row1->cardcode,
                        $row1->cardname,
                        $row1->attentionto,
                        $this->username,
                        date('Ymd'),
                        date('H:i:s')
                    )
                );

                if(!$query2) {
                    $dberror = $this->db->error();
                } else {
                    $dberror = "-";
                }
            }
        } else {
            $sql1 = "DELETE FROM tmp_invreceipt WHERE docnum=?";
            $query1 = $this->db->query($sql1, array($arrdata['docnum']));
            if(!$query1) {
                $dberror = $this->db->error();
            } else {
                $dberror = "-";
            }
        }

        return $dberror;
    }

    function getheaderinv() {
        $sql1 = "SELECT cardcode,cardname,attentionto FROM tmp_invreceipt WHERE createuser=? GROUP BY cardcode";
        $query1 = $this->db->query($sql1, array($this->username));

        $sql2 = "SELECT docnum,docdate,docduedate,amount,currtype FROM tmp_invreceipt WHERE createuser=?";
        $query2 = $this->db->query($sql2, array($this->username));

        $arr_result = array('header1' => $query1->row(), 'header2' => $query2->result());

        return $arr_result;
    }

    function getdetailinv() {
        $sql1 = "SELECT docnum FROM tmp_invreceipt WHERE createuser=?";
        $query1 = $this->db->query($sql1, array($this->username));

        foreach ($query1->result() as $row1) {
            $sql2 = "SELECT b.Dscription AS invdesc FROM OINV a 
                      LEFT JOIN INV1 b ON (a.docentry=b.docentry) WHERE a.docnum=?";
            $query2 = $this->dbsap->query($sql2, array($row1->docnum));
            $row2 = $query2->row();

            $arr_desc[] = array('invdesc' => $row2->invdesc);
        }

        return $arr_desc;
    }
}
?>