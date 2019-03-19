<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_numberingformat extends CI_Model {
    private $username;

    function __construct() {
        parent::__construct();

        $this->username = $this->session->userdata('usn');
    }

    function numberingformat($MenuID) {
        $sql = "SELECT 
                  a.CodeID,a.NumID,a.FormatNomor,b.sapcode 
                FROM numberingformat a
                LEFT JOIN master_site b ON (a.ID_Form=b.kodesite)
                WHERE FormID=?";
        $query = $this->db->query($sql, array($MenuID));
        if( !$query ) {
            $dberror = $this->db->error();
            $return_array = array(
                'errmsg' => $dberror['message'],
                'numcode' => '',
                'numreset' => '',
                'sitecode' => '',
                'documentnumber' => ''
            );
        } else {
            $row = $query->row();

            if($query->num_rows() > 0) {
                if(isset($row)) {
                    $NumberFormat = $row->CodeID."/".$row->sapcode;
                    $ResetDuration = $row->NumID;

                    if($ResetDuration==1) { // hari
                        $FormatDateBySetup = date('d.m.Y');
                    } elseif($ResetDuration==2) { // bulan
                        $FormatDateBySetup = date('m.Y');
                    } elseif($ResetDuration==3) { // tahun
                        $FormatDateBySetup = date('Y');
                    }

                    $PrefixNumber = $NumberFormat."/".$FormatDateBySetup;
                    $TempNumber = $this->getnumberbyid($PrefixNumber);
                    $lengthRec = strlen($TempNumber['NewID']);

                    if($lengthRec == 1) {
                        $NumberID = "000".$TempNumber['NewID'];
                    } elseif($lengthRec == 2) {
                        $NumberID = "00".$TempNumber['NewID'];
                    } elseif($lengthRec == 3) {
                        $NumberID = "0".$TempNumber['NewID'];
                    } else {
                        $NumberID = $TempNumber['NewID'];
                    }

                    $docnum = $PrefixNumber."/".$NumberID;

                    $return_array = array(
                        'errmsg' => '-',
                        'numcode' => $row->CodeID,
                        'numreset' => $row->NumID,
                        'sitecode' => $row->sapcode,
                        'documentnumber' => $docnum
                    );
                } else {
                    $return_array = array(
                        'errmsg' => '-',
                        'numcode' => '',
                        'numreset' => '',
                        'sitecode' => '',
                        'documentnumber' => ''
                    );
                }
            } else {
                $return_array = array(
                    'errmsg' => '-',
                    'numcode' => '',
                    'numreset' => '',
                    'sitecode' => '',
                    'documentnumber' => ''
                );
            }
        }

        return $return_array;
    }

    function getnumberbyid($PrefixNumber) {
        $sql = "SELECT MAX(ValueID) AS LastID FROM auto_numbering WHERE VariableID=?";
        $query = $this->db->query($sql, array($PrefixNumber));
        $row = $query->row();

        if(isset($row)) {
            if( empty($row->LastID) ) {
                $NewID = intval($row->LastID) + 1;
                $sql_insert = "INSERT INTO auto_numbering(VariableID,ValueID) VALUES(?,?)";
                $query_insert = $this->db->query($sql_insert, array($PrefixNumber,$NewID));

                if( !$query_insert ) {
                    $dberror = $this->db->error();
                    $return_msg = array('errmsg' => $dberror['message'], 'NewID' => '');
                } else {
                    $return_msg = array('errmsg' => '-', 'NewID' => $NewID);
                }
            } else {
                $NewID = intval($row->LastID) + 1;
                $sql_update = "UPDATE auto_numbering SET ValueID=? WHERE VariableID=?";
                $query_update = $this->db->query($sql_update, array($NewID,$PrefixNumber));

                if( !$query_update ) {
                    $dberror = $this->db->error();
                    $return_msg = array('errmsg' => $dberror['message'], 'NewID' => '');
                } else {
                    $return_msg = array('errmsg' => '-', 'NewID' => $NewID);
                }
            }
        }

        return $return_msg;
    }
}
?>