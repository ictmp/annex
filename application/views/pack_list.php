<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data['pageid'] = "generatepack";
$data['pagetitle'] = "Package List";

$this->load->view('templates/header_plugin',$data);
$this->load->view('templates/header');
$this->load->view('templates/menu',$data);
?>
<br><br>
<div class="content-wrapper">
    <section class="content-header">
        <h4>
            <?php if(isset($detailcompany)) echo $detailcompany->companyname; ?>
        </h4>
        <input type="hidden" id="companyid" value="<?php echo $companyid; ?>">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('mainpage'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('packcompany'); ?>">Company</a></li>
            <li class="active">Package List</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Package List</h3>
                    </div>

                    <div class="box-body">
                        <table id="tblPackageList" class="table table-bordered" style="width: 100%;">
                            <thead>
                            <tr>
                                <th style="width: 5%;">&nbsp;</th>
                                <th>Description</th>
                                <th style="width: 5%;">Expired</th>
                                <th style="width: 10%;">Price</th>
                                <th style="width: 8%;">Status</th>
                            </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="box-footer">
                        <a href="<?php echo site_url('packcompany'); ?>" class="btn btn-danger">Back to Company List</a>
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
                                                <p><span id="packagename_preview"></span></p>
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
                                                <th style=" text-align: right;">Total:</th>
                                                <td style="text-align: right;"><span id="totalprice_preview" class="text-bold"></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- this row will not appear when printing -->
<!--                            <div class="row no-print">-->
<!--                                <div class="col-xs-12">-->
<!--                                    <a href="#" target="_blank" class="btn btn-warning btn-sm pull-right" style="margin-right: 5px;" onclick="generatepdf()"><i class="fa fa-edit"></i> Edit</a>-->
<!--                                </div>-->
<!--                            </div>-->
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
        $("#tblPackageList").dataTable({
            "ajax": {
                "url": "<?php echo site_url('packlistshow'); ?>/" + $('#companyid').val(),
                "type": "POST",
                "error": function(jqXHR, textStatus, errorThrown) {
                    toastError(jqXHR.responseText,'');
                }
            },
            "columns": [
                {"data": "packageid",
                    "render": function ( data, type, full ) {
                        return "<button id=\"view_" + full['packageid'] + "\" class=\"btn btn-primary btn-xs\" onclick=\"viewPackage('" + btoa(full['packageid']) + "')\">View</button";
                    }, "className": "text-right", "orderable": false, "searchable": false},
                {"data": "packagename", "className": "text-left"},
                {"data": "expireddate", "className": "text-center"},
                {"data": "packageprice", render: $.fn.dataTable.render.number(',', '.', ''), "className": "text-right"},
                {"data": "packageid",
                    "render": function ( data, type, full) {
                        if(full['approvedtype'] == '0') {
                            var flagType = 'label-warning';
                            var flagText = 'Waiting';
                        } else if(full['approvedtype'] == '1') {
                            var flagType = 'label-success';
                            var flagText = 'Approved';
                        } else if(full['approvedtype'] == '2') {
                            var flagType = 'label-danger';
                            var flagText = 'Rejected';
                        }
                        return "<span class=\"label " + flagType + "\">" + flagText + "</span>";
                    }, "className": "text-center", "orderable": false, "searchable": false}
            ],
            "processing": true,
            "serverSide": true,
            "order": [
                [2, "asc"]
            ]
        });
    });

    function viewPackage(packageid) {
        $('#tbl_listitem').empty();

        $.post("<?php echo site_url('packlistview'); ?>", {
            "companyid": $('#companyid').val(),
            "packageid": packageid
        }, function (data) {
            if(data.errmsg == true) {
                $('#modal-preview').modal({backdrop: "static", keyboard: false});

                $.each(data.packagedata, function(key, val) {
                    if(key == 'totalcost' || key == 'profitval' || key == 'manualfee' || key == 'totalprice') {
                        val = formatCurrency(val);
                    }
                    $('#' + key + '_preview').text(val);
                });
                
                $.each(data.packageitem, function (key, value) {
                    $('#tbl_listitem').append('<tr><td>' + value['itemname'] + '</td><td class="text-right">' + formatCurrency(value['costing']) + '</td></tr>');
                });
            } else {
                toastError(data.errmsg,'');
            }
        }, "json");
    }
</script>

<?php
$this->load->view('templates/footer_close');
?>
