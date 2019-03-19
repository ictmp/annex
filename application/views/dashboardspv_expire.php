<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data['pageid'] = "dashboard";
$data['pagetitle'] = "Package (Expired) List";

$this->load->view('templates/header_plugin',$data);
$this->load->view('templates/header');
$this->load->view('templates/menu',$data);
?>
<br><br>
<div class="content-wrapper">
    <section class="content-header">
        <h4>Package (Expired) List</h4>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('mainpage'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Package List</li>
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
                                <th>Description</th>
                                <th style="width: 5%;">Expired</th>
                                <th style="width: 10%;">Price</th>
                                <th style="width: 20%;">PIC</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-preview" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <h4 class="modal-title">Preview</h4>
                    </div>
                    <div class="modal-body">
                        <section class="invoice">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <span id="companyid_preview" style="visibility: hidden;"></span>
                                    <h2 class="page-header">
                                        <i class="fa fa-university"></i>&nbsp;
                                        <span id="companyname_preview"></span>
                                    </h2>
                                </div>
                            </div>
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-10 invoice-col">
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <blockquote>
                                                <p><span id="packagename_preview"></span> <span id="packageid_preview" style="visibility: hidden;"></p>
                                                <small><cite>#IM</cite> : <span id="imnum_preview" class="text-bold"></span></small>
                                                <small><cite>Active Periode</cite> : <u><span id="periodestart_preview" class="text-bold text-success"></span> - <span id="periodeend_preview" class="text-bold text-danger"></u></small>
                                                <small><cite>MCU Result</cite> : <span id="mcuresult_preview" class="text-bold"></span></small>
                                                <small><cite>Certificate</cite> : <span id="mcucertificate_preview" class="text-bold"></span></small>
                                            </blockquote>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table row -->
                            <div class="row">
                                <div class="col-xs-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Costing</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbl_listitem"></tbody>
                                    </table>
                                </div>
                            </div><br>

                            <!-- Footer row -->
                            <div class="row">
                                <!-- accepted payments column -->
                                <div class="col-xs-6">
                                    <p class="lead">Payment Due: <span id="paymentduedate_preview" class="text-bold"></span> days</p>
                                    Notes :

                                    <p class="text-muted well well-sm no-shadow" id="packagenotes_preview" style="margin-top: 10px;">
                                        blank
                                    </p>
                                </div>
                                <div class="col-xs-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th style="width:67%; text-align: right;">Total Cost:</th>
                                                <td style="text-align: right;"><span id="totalcost_preview"></span></td>
                                            </tr>
                                            <tr>
                                                <th style=" text-align: right;">Profit <span id="profitvar_preview"></span></th>
                                                <td style="text-align: right;"><span id="profitval_preview"></span></td>
                                            </tr>
                                            <tr>
                                                <th style=" text-align: right;">Manual Fee</th>
                                                <td style="text-align: right;"><span id="manualfee_preview"></span></td>
                                            </tr>
                                            <tr>
                                                <th style=" text-align: right;"><h4>Total:</h4></th>
                                                <td style="text-align: right;"><h4><span id="totalprice_preview" class="text-bold"></span></h4></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- this row will not appear when printing -->
                            <div class="row no-print">
                                <div class="col-xs-12">
                                    <button type="button" class="btn btn-danger btn-sm pull-right" style="margin-right: 5px;" onclick="btnApproval('3')"><i class="fa fa-hand-stop-o"></i> Not Approve</button>
                                    <button type="button" class="btn btn-success btn-sm pull-right" style="margin-right: 5px;" onclick="btnApproval('1')"><i class="fa fa-thumbs-o-up"></i> Approve</button>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left btn-sm" data-dismiss="modal">CLOSE</button>
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
                "url": "<?php echo site_url('dsbspv_expirelist'); ?>",
                "type": "POST",
                "error": function(jqXHR, textStatus, errorThrown) {
                    toastError(jqXHR.responseText,'');
                }
            },
            "columns": [
                {"data": "packageid",
                    "render": function ( data, type, full ) {
                        return "<button id=\"view_" + full['packageid'] + "\" class=\"btn btn-primary btn-xs\" onclick=\"viewPackage('" + btoa(full['companyid']) + "','" + btoa(full['packageid']) + "')\">View</button";
                    }, "className": "text-right", "orderable": false, "searchable": false, "sortable": false},
                {"data": "packagename", "className": "text-left"},
                {"data": "expireddate", "className": "text-center"},
                {"data": "packageprice", render: $.fn.dataTable.render.number(',', '.', ''), "className": "text-right"},
                {"data": "namakaryawan", "className": "text-center", "orderable": false, "searchable": false}
            ],
            "processing": true,
            "serverSide": true,
            "order": [[1, "asc"]]
        });
    });

    function viewPackage(companyid,packageid) {
        $('#tbl_listitem').empty();

        $.post("<?php echo site_url('detail2view'); ?>", {
            'companyid': companyid,
            'packageid': packageid
        }, function (data) {
            if(data.errmsg == true) {
                $('#modal-preview').modal({backdrop: "static", keyboard: false});

                $.each(data.packagedetail, function(key, val) {
                    if(key == 'totalcost' || key == 'profitval' || key == 'manualfee' || key == 'totalprice') {
                        val = formatCurrency(val);
                    } else if(key == 'profitvar') {
                        val = "( " + val + "% )";
                    }

                    $('#' + key + '_preview').text(val);
                });

                $.each(data.packagelist, function (key, value) {
                    $('#tbl_listitem').append('<tr><td>' + value['itemname'] + '</td><td class="text-right">' + formatCurrency(value['costing']) + '</td></tr>');
                });

                $('#companyid_preview').text(data.companyid);
                $('#packageid_preview').text(data.packageid);
            } else {
                toastError(data.errmsg,'');
            }
        }, "json");
    }
</script>

<?php
$this->load->view('templates/footer_close');
?>
