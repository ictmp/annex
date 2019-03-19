<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data['pageid'] = "generatepack";
$data['pagetitle'] = "MCU Package";

$this->load->view('templates/header_plugin',$data);
$this->load->view('templates/header');
$this->load->view('templates/menu',$data);
?>
<div class="content-wrapper">
    <br><br>
    <section class="content-header">
        <h1>
            MCU Package
            <small>Item Details</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('mainpage'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('packdescription/'.$companyid); ?>">Package Details</a></li>
            <li class="active">Item Details</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-4">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="profile-username text-center" id="companyname"><?php echo $companyname; ?></h3>
                        <input type="hidden" id="companyid" value="<?php echo $companyid; ?>">
                        <input type="hidden" id="packageid" value="<?php echo $packageid; ?>">
                    </div>
                    <div class="box-body">
                        <strong><i class="fa fa-chevron-circle-right"></i> Package Name</strong>

                        <p class="text-muted text-center">
                            <?php echo $packagename; ?>
                        </p>
                        <hr>
                        <strong><i class="fa fa-calendar"></i> Activation Periode</strong>
                        <p class="text-muted">
                        <ul>
                            <li><b>From</b> <a class="pull-right text-success text-bold"><?php echo $periodestart; ?></a></li>
                            <li><b>To</b> <a class="pull-right text-danger text-bold"><?php echo $periodeend; ?></a></li>
                        </ul>
                        </p>
                        <hr>
                        <strong><i class="fa fa-money"></i> Cost Details</strong>
                        <p class="text-muted">
                        <ul>
                            <li><b>Examination Cost</b> <a id="basic_cost" class="pull-right"><?php echo $basic_cost; ?></a></li>
                            <?php
                            $total_basiccost = 0;

                            foreach ($fixedcost as $row) {
                                $total_basiccost = $total_basiccost + $row->costing;
                                ?>
                                <li><b><?php echo $row->itemdesc ?></b> <a id="<?php echo $row->itemcode ?>" class="pull-right"><?php echo number_format($row->costing); ?></a></li>
                                <?php if($row->itemcode == "laundrycost") {
                                    ?>
                                    <hr style="margin: 2mm;">
                                    <span><b>Total Basic Cost</b> <a id="total_basiccost" class="pull-right text-bold"><?php echo number_format($total_basiccost); ?></a></span>
                                    <hr style="margin: 2mm;">
                                    <?php
                                }
                            } ?>
                            <hr style="margin: 2mm;">
                            <span><b>Total Cost</b> <a id="total_cost" class="pull-right text-bold">0</a></span>
                        </ul>
                        </p>
                        <hr>
                        <strong><i class="fa fa-tags"></i> Profit</strong>
                        <div class="row">
                            <div class="col-xs-2">&nbsp;</div>
                            <div class="col-xs-5">
                                <div class="input-group">
                                    <select id="profitpackage" class="form-control" onchange="calculate_price()">
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                        <option value="25">25</option>
                                        <option value="30">30</option>
                                        <option value="35">35</option>
                                        <option value="40">40</option>
                                        <option value="45">45</option>
                                        <option value="50">50</option>
                                        <option value="55">55</option>
                                        <option value="60">60</option>
                                    </select><span class="input-group-addon">%</span>
                                </div>
                            </div>
                            <div class="col-xs-5">
                                <span id="profitvalue" class="pull-right"><?php echo $profit; ?></span>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-xs-2"></div>
                            <div class="col-xs-5">
                                <span class="text-bold">Manual Fee</span>
                            </div>
                            <div class="col-xs-5">
                                <input type="text" class="form-control input-sm text-right" id="manualfee" style="font-size: small;" value="0" onkeyup="set_numberFormat('manualfee'); calculate_price();">
                            </div>
                        </div>
                        <hr>
                        <strong><i class="fa fa-check-square-o"></i> Package Price</strong>
                        <h3 id="packageprice" class="text-bold text-right">0</h3>
                    </div>
                    <div class="box-footer">
                        <button type="button" class="btn btn-sm btn-twitter pull-right" onclick="btn_preview()">Preview</button>
                    </div>
                </div>

            </div>

            <div class="col-md-8">

                <div class="box box-warning">
                    <div class="box-body primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Attachment Document</h3>
                        </div>

                        <div class="box-body">
                            <form action="#" class="dropzone" id="fileupload" enctype="multipart/form-data" method="post"></form>
                        </div>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-body">
                        <table id="tblItems" class="table table-bordered" style="width: 100%;">
                            <thead>
                            <tr>
                                <th style="width: 5%">&nbsp;</th>
                                <th>Description</th>
                                <th style="width: 13%;">Costing</th>
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
                                    <h2 class="page-header">
                                        <i class="fa fa-university"></i>&nbsp;
                                        <span id="preview_compname"></span>
                                        <!--set to current date-->
                                        <small class="pull-right" id="preview_curdate"></small>
                                    </h2>
                                </div>
                            </div>
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-10 invoice-col">
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <blockquote>
                                                <p><span id="preview_packagename"></span></p>
                                                <small>#IM : <cite><span id="preview_imnum" class="text-bold"></span></cite></small>
                                                <small>Active Periode : <cite><u><span id="preview_periode" class="text-bold"></span></u></cite></small>
                                                <small>MCU Result : <cite><span id="preview_language" class="text-bold"></span></cite></small>
                                                <small>Certificate : <cite><span id="preview_certificate" class="text-bold"></span></cite></small>
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
                                    <p class="lead">Payment Due: <span id="preview_top" class="text-bold"></span> days</p>
                                    Notes :

                                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                        blank
                                    </p>
                                </div>
                                <div class="col-xs-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th style="width:67%; text-align: right;">Total Cost:</th>
                                                <td style="text-align: right;"><span id="preview_cost"></span></td>
                                            </tr>
                                            <tr>
                                                <th style=" text-align: right;">Profit <span id="preview_profit"></span></th>
                                                <td style="text-align: right;"><span id="preview_profitvalue"></span></td>
                                            </tr>
                                            <tr>
                                                <th style=" text-align: right;">Manual Fee</th>
                                                <td style="text-align: right;"><span id="preview_manualfee"></span></td>
                                            </tr>
                                            <tr>
                                                <th style=" text-align: right;">Total:</th>
                                                <td style="text-align: right;"><span id="preview_price" class="text-bold"></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- this row will not appear when printing -->
                            <div class="row no-print">
                                <div class="col-xs-12">
                                    <button type="button" class="btn btn-success btn-sm pull-right"
                                            data-toggle="confirmation"
                                            data-btn-ok-label="Continue"
                                            data-btn-ok-class="btn-success btn-sm"
                                            data-btn-ok-icon-class="material-icons"
                                            data-btn-icon-content="check"
                                            data-btn-cancel-label="No"
                                            data-btn-cancel-class="btn-danger btn-sm"
                                            data-btn-cancel-icon-class="material-icons"
                                            data-btn-cancel-icon-content="close"
                                            data-title="Confirmation"
                                            data-content="You will send this MCU package to get approval"><i class="fa fa-credit-card"></i> Submit Package
                                    </button>
                                    <a href="#" target="_parent" class="btn btn-primary btn-sm pull-right" style="margin-right: 5px;" onclick="generatepdf()"><i class="fa fa-download"></i> Generate PDF</a>
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
        // datatable plugin
        $("#tblItems").dataTable({
            "ajax": {
                "url": "<?php echo site_url('packitemlist'); ?>",
                "type": "POST",
                "error": function(jqXHR, textStatus, errorThrown) {
                    toastError(jqXHR.responseText,'');
                }
            },
            "columns": [
                {"data": "kodeitem",
                    "render": function ( data, type, full ) {
                        if(full['checkitem'] == "0") {
                            set_checkbox = "";
                        } else {
                            set_checkbox = "checked";
                        }

                        return "<input type=\"checkbox\" id=\"checkitem_" + full['kodeitem'] + "\" class=\"icheckbox_minimal-blue\" onclick=\"selectItem('" + full['kodeitem'] + "')\" " + set_checkbox + ">";
                    }, "className": "text-center", "searchable": false, "orderable": false},
                {"data": "nama_barang", "className": "text-left"},
                {"data": "costing", render: $.fn.dataTable.render.number(',', '.', ''), "className": "text-right", "orderable": false, "searchable": false}
            ],
            "processing": true,
            "serverSide": true,
            "order": [
                [1, "asc"]
            ]
        });

        // calculate price after table rendered
        setTimeout("calculate_price()", 300);
    });

    function selectItem(kodeitem) {
        // set checkbox variable
        var checkbox_status = $('#checkitem_' + kodeitem).prop('checked');

        // set selected checkbox and update into table
        $.post("<?php echo site_url('packitemselect'); ?>", {
            "companyid": $('#companyid').val(),
            "packageid": $('#packageid').val(),
            "kodeitem": kodeitem,
            "checkboxstatus": checkbox_status
        }, function (data) {
            if(data.errmsg == "-") {
                $('#basic_cost').text(data.basic_cost);

                // calculate price after checkbox selected
                setTimeout("calculate_price()", 300);
            } else {
                // display message error while query processing
                toastError(data.errmsg,'');
            }
        }, "json");
    }

    function calculate_price() {
        /* ======================================== set main variable ==================================== */
        // set total basic cost
        var set_totalbasiccost = parseFloat(filterNum($('#basic_cost').text())) + parseFloat(filterNum($('#medreccost').text())) + parseFloat(filterNum($('#delivercost').text())) + parseFloat(filterNum($('#laundrycost').text()));
        $('#total_basiccost').text(formatCurrency(set_totalbasiccost));
        // set total cost and put it into totalcost (span)
        var totalcost = parseFloat(filterNum($('#total_basiccost').text())) + parseFloat(filterNum($('#renovcost').text())) + parseFloat(filterNum($('#admcost').text()));
        $('#total_cost').text(formatCurrency(totalcost));
        // calculate profit and show it into profit box
        var calc_profit = (parseFloat(filterNum($('#total_basiccost').text())) * $('#profitpackage').val()) / 100;
        $('#profitvalue').text(formatCurrency(Math.round(parseInt(calc_profit))));
        // set manual fee (free entry by sales PIC)
        var manualfee = filterNum($('#manualfee').val());

        /* ================================= calculate price using main variable ================================= */
        // calculate price and set to string
        var calc_packageprice = (parseInt(totalcost) + parseInt(calc_profit) + parseInt(manualfee)).toString();
        // set rounding variable
        var digitnominal = parseInt(calc_packageprice.substring(calc_packageprice.length - 3));
        var adding_digit = 1000 - digitnominal;
        // adding calculate price and rounding variable (round up)
        var packageprice = parseInt(calc_packageprice) + adding_digit;
        $('#packageprice').text( formatCurrency(packageprice) );
    }

    function btn_preview() {
        // reset table content (item list)
        $('#tbl_listitem').empty();
        // open modal window
        $('#modal-preview').modal({backdrop: "static", keyboard: false});
        $('#preview_compname').text($('#companyname').text());
        // load company data and detail item
        $.post("<?php echo site_url('packpreview'); ?>", {
            "companyid": $('#companyid').val(),
            "packageid": $('#packageid').val(),
            "totalcost": filterNum($('#total_cost').text()),
            "profitvar": $('#profitpackage').val(),
            "profitval": filterNum($('#profitvalue').text()),
            "manualfee": filterNum($('#manualfee').val()),
            "totalprice": filterNum($('#packageprice').text())
        }, function (data) {
            if(data.errmsg == "-") {
                $('#preview_packagename').text(data.packagename);
                $('#preview_imnum').text(data.imnum);
                $('#preview_periode').text(data.startperiode + " - " + data.endperiode);
                $('#preview_language').text(data.packagelanguage);
                $('#preview_certificate').text(data.certtype);
                $('#preview_top').text(data.packtop);

                $('#preview_cost').text($('#total_cost').text());
                $('#preview_profit').text("(" + $('#profitpackage').val() + " %)");
                $('#preview_profitvalue').text($('#profitvalue').text());
                $('#preview_manualfee').text($('#manualfee').val());
                $('#preview_price').text($('#packageprice').text());

                // draw detail item into table content
                $.each(data.packitem, function(key, value) {
                    $('#tbl_listitem').append('<tr><td>' + value['itemname'] + '</td><td class="text-right">' + formatCurrency(value['costing']) + '</td></tr>');
                });
            } else {
                // display error message while query process
                toastError(data.errmsg,'');
            }
        }, "json");
    }

    // open pdf window on new tab
    function generatepdf() {
        window.open("<?php echo site_url(''); ?>packtopdf/" + $('#companyid').val() + "/" + $('#packageid').val(), '_blank');
    }

    // button action after pressing confirm button
    $('[data-toggle=confirmation]').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        container: 'body',
        onConfirm: function() {
            btn_submit();
        }
    });

    function btn_submit() {
        $.post("<?php echo site_url('submitpackage'); ?>", {
            "companyid": $('#companyid').val(),
            "packageid": $('#packageid').val(),
            "totalprice": filterNum($('#packageprice').text())
        }, function(data) {
            if(data.errmsg == "-") {
                toastSuccess('Your package successfully submitted, please wait until we redirect you to main page','');

                setTimeout("redirectPage()", 1000);
            } else {
                toastError(data.errmsg,'');
            }
        }, "json");
    }

    function redirectPage() {
        window.location = "<?php echo site_url('redirectpackage/'); ?>" + $('#companyid').val();
    }

    Dropzone.autoDiscover = false;

    var foto_upload = new Dropzone(".dropzone",{
        url: "<?php echo site_url('packattachfile') ?>",
        maxFilesize: 10,
        method: "post",
        acceptedFiles: ".jpg,.jpeg,.pdf,.doc,.docx",
        paramName: "userfile",
        params: {
            'companyid': $('#companyid').val(),
            'packageid': $('#packageid').val()
        },
        dictInvalidFileType: "File types are not permitted",
        addRemoveLinks: true
    });

    //Event starting upload file
    foto_upload.on("sending",
        function(file, xhr, formData) {
            file.token = Math.random();
            formData.append("token_file", file.token); // preparing token for each file
        }
    );

    //Event removing uploaded file
    foto_upload.on("removedfile",function(a){
        var token=a.token;
        $.ajax({
            type: "post",
            data: {token:token},
            url: "<?php echo base_url('packremovefile') ?>",
            cache: false,
            dataType: 'json',
            success: function(data) {
                if(data.errmsg == true) {
                    toastSuccess('File successfully removed','');
                } else {
                    toastError(data.errmsg,'');
                }
            },
            error: function(){
                toastError('Error on ajax processing','');
            }
        });
    });

    foto_upload.on("addedfile", function(file) {
        if (this.files.length) {
            var _i, _len;
            for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) {
                if(this.files[_i].name === file.name && this.files[_i].size === file.size) {
                    this.removeFile(file);
                }
            }
        }
    });
</script>

<?php
$this->load->view('templates/footer_close');
?>
