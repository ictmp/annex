<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data['pageid'] = "dashboard";
$data['pagetitle'] = "Company List";

$this->load->view('templates/header_plugin',$data);
$this->load->view('templates/header');
$this->load->view('templates/menu',$data);
?>
<div class="content-wrapper">
    <br><br>
    <section class="content-header">
        <h4>MCU Package (Draft) List</h4>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('mainpage'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Package Draft List</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-body">
                        <table id="tblCompanyList" class="table table-bordered" style="width: 100%;">
                            <thead>
                            <tr>
                                <th style="width: 5%;">&nbsp;</th>
                                <th style="vertical-align: middle;">Company Name</th>
                                <th style="width: 10%;">Num Of Package</th>
                                <th style="width: 8%;">Active Date</th>
                                <th style="width: 8%;">Expired Date</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<?php
$this->load->view('templates/footer');
$this->load->view('templates/footer_plugin');
?>
<script>
    $(function () {
        $("#tblCompanyList").dataTable({
            "ajax": {
                "url": "<?php echo site_url('dashboarddraftlist_show'); ?>",
                "type": "POST",
                "error": function(jqXHR, textStatus, errorThrown) {
                    toastError(jqXHR.responseText,'');
                }
            },
            "columns": [
                {"data": "companyid",
                    "render": function ( data, type, full ) {
                        return "<button id=\"view_" + full['recid'] + "\" class=\"btn btn-primary btn-xs\" onclick=\"viewPackage('" + btoa(full['recid']) + "','" + btoa(full['companyid']) + "')\">View</button";
                    }, "className": "text-center", "orderable": false, "searchable": false},
                {"data": "companyname", "className": "text-left"},
                {"data": "numofpackage", "searchable": false, "className": "text-center"},
                {"data": "startperiode",
                    "render": function ( data, type, full ) {
                        return setnewdateformat(full['startperiode']);
                    }, "className": "text-center", "orderable": false, "searchable": false},
                {"data": "endperiode",
                    "render": function ( data, type, full ) {
                        return setnewdateformat(full['endperiode']);
                    }, "className": "text-center", "orderable": false, "searchable": false}
            ],
            "processing": true,
            "serverSide": true,
            "order": [
                [2, "asc"]
            ]
        });
    });

    function viewPackage(recid,companyid) {
        window.location = "<?php echo site_url('packitem'); ?>/" + companyid + "/" + recid;
    }
</script>

<?php
$this->load->view('templates/footer_close');
?>
