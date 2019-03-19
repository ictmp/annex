<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_mstrcosting extends CI_Model {
    private $username;

    function __construct() {
        parent::__construct();

        $this->username = $this->session->userdata('usn');
    }

    function viewfixedcost() {
        $sql = "SELECT itemcode,costing,
                (CASE
                  WHEN itemcode='medreccost' THEN 'Medical Report (Hardcopy)'
                  WHEN itemcode='delivercost' THEN 'Delivery Cost'
                  WHEN itemcode='laundrycost' THEN 'Laundry Cost'
                  WHEN itemcode='renovcost' THEN 'Renovation Cost'
                  WHEN itemcode='admcost' THEN 'Clinic Adm Allocation'
                END) as itemdesc
                FROM master_costingmcu_fixed
                ORDER BY recid ASC";
        $query = $this->db->query($sql);
        if($query) {
            return $query->result();
        } else {
            $db_error = $this->db->error();
            return $db_error['message'];
        }
    }

    function getlist() {
        $this->datatables->select("(CASE
                                WHEN a.keterangan IS NULL THEN ''
                                WHEN a.keterangan IS NOT NULL THEN a.keterangan
                            END) AS keterangan,a.keterangan AS publishdesc,b.nama_barang,b.tarif,
                            (CASE
                                WHEN a.harga IS NOT NULL OR a.harga!='' THEN a.harga
                                WHEN a.harga IS NULL OR a.harga='' THEN '0'
                            END) AS costing,a.kodeitem,DATE_FORMAT(a.updatedate, '%d/%m/%Y') AS updatedate,a.updatetime");
        $this->datatables->from("master_costingmcu a");
        $this->datatables->join("product_master b", "a.kodeitem=b.id_barang", "left");
        $this->datatables->where("b.activestatus","0");

        return $this->datatables->generate();
    }

    function editrow($data) {
        $sql = "SELECT 
                  a.keterangan,b.nama_barang,b.tarif,a.harga AS costing,a.linkeditem,a.unlinkeditem 
                FROM master_costingmcu a
                LEFT JOIN product_master b ON (a.kodeitem=b.id_barang) 
                WHERE a.kodeitem=?";
        $query = $this->db->query($sql, array($data));

        $sql1= "SELECT 
                  a.kodeitem,b.nama_barang 
                FROM master_costingmcu a
                LEFT JOIN product_master b ON (a.kodeitem=b.id_barang)
                WHERE b.kategori_barang='Service' AND b.activestatus='0' AND a.kodeitem <> ?";
        $query1 = $this->db->query($sql1, array($data));

        if($query) {
            if($query->num_rows() > 0) {
                $row = $query->row();

                $array_data = array(
                    "errmsg" => "-",
                    "description" => $row->nama_barang,
                    "publishdesc" => $row->keterangan,
                    "price" => number_format($row->tarif),
                    "costing" => number_format($row->costing),
                    "linkeditem" => $query1->result(),
                    "linkeditem_data" => $row->linkeditem,
                    "unlinkeditem_data" => $row->unlinkeditem
                );

                return $array_data;
            } else {
                $array_data = array(
                    "errmsg" => "Record not found"
                );
                return $array_data;
            }
        } else {
            $db_error = $this->db->error();
            $array_data = array(
                "errmsg" => $db_error['message']
            );
            return $array_data;
        }
    }

    function updaterow($data) {
        $sql = "UPDATE master_costingmcu 
                  SET keterangan=?,harga=?,linkeditem=?,unlinkeditem=?,updateuser=?,updatedate=?,updatetime=? 
                WHERE kodeitem=?";
        $query = $this->db->query($sql,
            array(
                $data['publishdesc'],
                $data['costing'],
                $data['linkeditem'],
                $data['unlinkeditem'],
                $this->username,
                date('Y-m-d'),
                date('H:i:s'),
                $data['itemcode']
            )
        );

        if($query) {
            $db_error = "-";
            return $db_error;
        } else {
            $db_error = $this->db->error();
            return $db_error['message'];
        }
    }

    function fixedcost($data) {
        $sql_del = $this->db->empty_table('master_costingmcu_fixed');

        if($sql_del) {
            foreach ($data as $key => $val) {
                $arrdata[] = array(
                    'itemcode' => $key,
                    'costing' => $val,
                    'createuser' => $this->username,
                    'createdate' => date('Y-m-d'),
                    'createtime' => date('H:i:s')
                );
            }

            $query = $this->db->insert_batch('master_costingmcu_fixed', $arrdata);

            if($query) {
                return true;
            } else {
                $db_error = $this->db->error();
                return $db_error['message'];
            }
        } else {
            $db_error = $this->db->error();
            return $db_error['message'];
        }
    }

    function linkeditem($data) {
        $search = $data['search'];
        $sql = "SELECT 
                  a.kodeitem,b.nama_barang 
                FROM master_costingmcu a
                LEFT JOIN product_master b ON (a.kodeitem=b.id_barang)
                WHERE a.kodeitem <> ? AND b.nama_barang LIKE '%".$this->db->escape_like_str($search)."%' ESCAPE '!'
                AND b.kategori_barang='Service' AND b.activestatus='0'";
        $query = $this->db->query($sql,
            array(
                $data['itemcode']
            )
        );

        if($query) {
            if($query->num_rows() > 0) {
                $send_data = array();
                foreach ($query->result() as $row) {
                    $send_data[] = array(
                        'id' => $row->kodeitem,
                        'text' => $row->nama_barang
                    );
                }

                return $send_data;
            } else {
                return array();
            }
        } else {
            $db_error = $this->db->error();
            return $db_error['message'];
        }
    }

    function costinglist() {
        $sql = "SELECT 
                  (CASE
                        WHEN a.keterangan IS NULL THEN ''
                        WHEN a.keterangan IS NOT NULL THEN a.keterangan
                    END) AS keterangan,a.keterangan AS publishdesc,b.nama_barang,b.tarif,
                    (CASE
                        WHEN a.harga IS NOT NULL OR a.harga!='' THEN a.harga
                        WHEN a.harga IS NULL OR a.harga='' THEN '0'
                    END) AS costing,a.kodeitem,DATE_FORMAT(a.updatedate, '%d/%m/%Y') AS updatedate,a.updatetime
                FROM master_costingmcu a 
                LEFT JOIN product_master b ON (a.kodeitem=b.id_barang)
                WHERE b.activestatus=? 
                ORDER BY b.nama_barang ASC";
        $query = $this->db->query($sql, array('0'));

        return $query;
    }
}
?>