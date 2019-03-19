<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $pagetitle; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css'); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/bower_components/font-awesome/css/font-awesome.min.css'); ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/bower_components/Ionicons/css/ionicons.css'); ?>">
    <!-- Datepicker -->
    <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css'); ?>">
    <!-- Select 2 -->
    <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/bower_components/select2/dist/css/select2.min.css'); ?>">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/plugins/iCheck/flat/blue.css'); ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'); ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/dist/css/AdminLTE.min.css'); ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/dist/css/skins/_all-skins.min.css'); ?>">
    <!-- Pace style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/plugins/pace/pace.css'); ?>">
    <!-- Toastr style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/plugins/toast-master/css/toastr.css'); ?>">
    <!-- Text editor -->
    <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); ?>">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- Dropzone -->
    <link rel="stylesheet" href="<?php echo base_url('assets/dropzone/resources/dropzone.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/dropzone/resources/basic.css'); ?>">
    <!-- jQuery Confirm -->
    <link rel="stylesheet" href="<?php echo base_url('assets/adminlte/plugins/jConfirm/jquery-confirm.min.css'); ?>">
    <style>
        .table th {
            text-align: center;
        }
    </style>
</head>
<body class="hold-transition skin-blue layout-top-nav">

<div class="wrapper">
