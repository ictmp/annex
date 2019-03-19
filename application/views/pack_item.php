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
            <small>Package Details</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('mainpage'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('packdescription/'.$companyid); ?>">Package Setup</a></li>
            <li class="active">Package Details</li>
        </ol>
    </section>

    <section class="content">

        <div class="row">

            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="box-title">
                            <label class="font-weight-bold text-light">Package Setup</label>
                        </div>
                    </div>

                    <div class="box-body text-center">
                        <a class="btn btn-app" onclick="btnApproval();" data-toggle="tooltip" data-placement="bottom" title="Send approval request via email">
                            <i class="fa fa-envelope"></i> Approval
                        </a>
                        <a class="btn btn-app" onclick="btnDraft();" data-toggle="tooltip" data-placement="bottom" title="Save all package setup into draft">
                            <i class="fa fa-save"></i> Draft
                        </a>
                        <a class="btn btn-app" onclick="btnPrint();" data-toggle="tooltip" data-placement="bottom" title="Print all package list">
                            <i class="fa fa-print"></i> Print
                        </a>
                    </div>
                </div>
            </div>

            <?php
            $no = 0;
            foreach ($packagelist->result() as $rowpack) {
                $no += 1;
                ?>
                <div class="col-md-6">
                    <div class="box box-primary box-solid">
                        <!-- Box Header Package -->
                        <div class="box-header with-border">
                            <div class="box-title">
                                <label class="font-light text-md">PackageID.</label>&nbsp;<span id="packlabel_<?php echo $no; ?>"><?php echo $rowpack->packageid; ?></span>
                                <span id="seqpack_<?php echo $rowpack->packageid; ?>" style="visibility: hidden;"><?php echo $no; ?></span>
                                <span id="headerid_<?php echo $no; ?>" style="visibility: hidden;"><?php echo $rowpack->headerid; ?></span>
                            </div>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </div>
                        </div>
                        <!-- Box Body Package -->
                        <div class="box-body">
                            <div class="form-group">
                                <label>Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="name_<?php echo $rowpack->packageid; ?>" value="<?php echo $rowpack->packagename; ?>" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button id="btn_name_<?php echo $rowpack->packageid; ?>" type="button" class="btn btn-danger btn-light" onclick="updatePack('<?php echo $rowpack->packageid; ?>','packname','name_<?php echo $rowpack->packageid; ?>','btn_name_');">Update</button>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Certificate</label>
                                <?php
                                $checkSplitter = strpos($rowpack->certtype,",");

                                if($checkSplitter > 0) {
                                    $cert_arr = explode(",", $rowpack->certtype);
                                    $cert_datatype = "1";
                                } else {
                                    $cert_datatype = "0";
                                }

                                if(!empty($rowpack->certtype)) {
                                    $btnCertClass = "btn-success";
                                    $btnCertTxt = "Edit";
                                    $btnDisable = "disabled";
                                } else {
                                    $btnCertClass = "btn-danger";
                                    $btnCertTxt = "Update";
                                    $btnDisable = "";
                                }
                                ?>
                                <div class="input-group">
                                    <select id="certtype_<?php echo $no; ?>" class="form-control" multiple="multiple" data-placeholder="Select Type of Certificate"
                                            style="width: 100%; border-radius: 5px;" <?php echo $btnDisable; ?>>
                                        <option value="MP (OH Based)" <?php
                                        if($cert_datatype == "1") {
                                            foreach ($cert_arr as $cert_row) {
                                                if( $cert_row == "MP (OH Based)" ) {
                                                    echo set_select('certtype','MP (OH Based)',true);
                                                } else { echo set_select('certtype','MP (OH Based)',false); }
                                            }
                                        } else {
                                            if( $rowpack->certtype == "MP (OH Based)" ) {
                                                echo set_select('certtype','MP (OH Based)',true);
                                            } else { echo set_select('certtype','MP (OH Based)',false); }
                                        }
                                        ?> >MP (OH Based)</option>
                                        <option value="OGUK" <?php
                                        if($cert_datatype == "1") {
                                            foreach ($cert_arr as $cert_row) {
                                                if( $cert_row == "OGUK" ) {
                                                    echo set_select('certtype','OGUK',true);
                                                } else { echo set_select('certtype','OGUK',false); }
                                            }
                                        } else {
                                            if( $rowpack->certtype == "OGUK" ) {
                                                echo set_select('certtype','OGUK',true);
                                            } else { echo set_select('certtype','OGUK',false); }
                                        }
                                        ?> >OGUK</option>
                                        <option value="Petronas AME" <?php
                                        if($cert_datatype == "1") {
                                            foreach ($cert_arr as $cert_row) {
                                                if( $cert_row == "Petronas AME" ) {
                                                    echo set_select('certtype','Petronas AME',true);
                                                } else { echo set_select('certtype','Petronas AME',false); }
                                            }
                                        } else {
                                            if( $rowpack->certtype == "Petronas AME" ) {
                                                echo set_select('certtype','Petronas AME',true);
                                            } else { echo set_select('certtype','Petronas AME',false); }
                                        }
                                        ?> >Petronas AME</option>
                                        <option value="DIPERLA" <?php
                                        if($cert_datatype == "1") {
                                            foreach ($cert_arr as $cert_row) {
                                                if( $cert_row == "DIPERLA" ) {
                                                    echo set_select('certtype','DIPERLA',true);
                                                } else { echo set_select('certtype','DIPERLA',false); }
                                            }
                                        } else {
                                            if( $rowpack->certtype == "DIPERLA" ) {
                                                echo set_select('certtype','DIPERLA',true);
                                            } else { echo set_select('certtype','DIPERLA',false); }
                                        }
                                        ?> >DIPERLA</option>
                                    </select>
                                    <span class="input-group-btn">
                                                    <button id="btn_cert_<?php echo $rowpack->packageid; ?>" type="button" class="btn <?php echo $btnCertClass; ?> btn-light" onclick="updatePack('<?php echo $rowpack->packageid; ?>','packcert','certtype_<?php echo $no; ?>','btn_cert_');"><?php echo $btnCertTxt; ?></button>
                                                </span>
                                </div>
                            </div>

                            <!-- Box Item Details -->
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <div class="user-block">
                                        <img class="img-circle" src="<?php echo base_url('assets/adminlte/dist/img/items.png'); ?>" alt="User Image">
                                        <span class="username"><a href="#">Item Details</a></span>
                                        <span class="description">Examination list</span>
                                    </div>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>

                                <div class="box-body no-padding">
                                    <div class="table-responsive detail_itempackage">
                                        <table class="table table-condensed">
                                            <thead>
                                            <tr>
                                                <th style="width: 10px">&nbsp;</th>
                                                <th>Description</th>
                                                <th>Cost</th>
                                            </tr>
                                            </thead>
                                            <tbody id="dtlItem_<?php echo $rowpack->packageid; ?>">
                                            <?php
                                            $check_arr = isset($dtlpackagelist[$rowpack->packageid]) ? $dtlpackagelist[$rowpack->packageid] : 0;

                                            if($check_arr != 0) {
                                                $item_perpack_seq = 0;
                                                foreach ($dtlpackagelist[$rowpack->packageid] as $get_pack) {
                                                    $item_perpack_seq += 1;
                                                    ?>
                                                    <tr id="trItem_<?php echo $get_pack['id']; ?>">
                                                        <td align="center"><button type="button" class="btn btn-default btn-xs" onclick="del_rowdtlpackage('<?php echo $rowpack->headerid; ?>','<?php echo $rowpack->packageid; ?>','<?php echo $get_pack['itemcode']; ?>');"><i class="fa fa-trash-o"></i></button></td>
                                                        <td><?php echo $get_pack['itemname']; ?></td>
                                                        <td align="right"><?php echo number_format($get_pack['itemcost']); ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="box-footer clearfix">
                                    <a href="#" class="btn btn-sm btn-social btn-dropbox" onclick="openPackageList('<?php echo $rowpack->packageid; ?>','<?php echo $rowpack->setupid; ?>','<?php echo $rowpack->headerid; ?>');">
                                        <i class="fa fa-dropbox"></i> Copy From
                                    </a>
                                    <a href="#" class="btn btn-sm btn-social btn-facebook pull-right" onclick="openItemList('<?php echo $rowpack->packageid; ?>','<?php echo $rowpack->headerid; ?>');">
                                        <i class="fa fa-plus-square"></i> Add New Item
                                    </a>
                                </div>
                            </div>

                            <!-- Box Costing Detail -->
                            <div class="box box-warning collapsed-box">
                                <div class="box-header with-border">
                                    <div class="user-block">
                                        <img class="img-circle" src="<?php echo base_url('assets/adminlte/dist/img/costing.png'); ?>" alt="User Image">
                                        <span class="username"><a href="#">Basic Costing</a></span>
                                        <span class="description">Basic costing list</span>
                                    </div>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>

                                <div class="box-body no-padding">
                                    <div class="table-responsive detail_itempackage">
                                        <table class="table table-condensed">
                                            <tr>
                                                <td><i class="fa fa-fw fa-caret-right"></i> Examination Cost</td>
                                                <td><a id="examcost_<?php echo $rowpack->packageid; ?>" class="pull-right"><?php echo number_format($rowpack->packagecost); ?></a></td>
                                            </tr>
                                            <?php
                                            $total_basiccost = $rowpack->packagecost;

                                            foreach ($fixedcost->result() as $rowfixedcost) {
                                                $total_basiccost = $total_basiccost + $rowfixedcost->costing;
                                                ?>
                                                <tr>
                                                    <td><i class="fa fa-fw fa-caret-right"></i> <?php echo $rowfixedcost->itemdesc ?></td>
                                                    <td>
                                                        <a id="<?php echo $rowfixedcost->itemcode ?>" class="pull-right"><?php echo number_format($rowfixedcost->costing); ?></a>
                                                    </td>
                                                </tr>
                                                <?php if($rowfixedcost->itemcode == "laundrycost") {
                                                    ?>
                                                    <tr>
                                                        <td><b>TOTAL BASIC COST</b></td>
                                                        <td>
                                                            <a id="basiccost_<?php echo $rowpack->packageid; ?>" class="pull-right text-bold"><?php echo number_format($total_basiccost); ?></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } ?>
                                            <tr>
                                                <td><b>TOTAL COST</b></td>
                                                <td>
                                                    <a id="totalcost_<?php echo $rowpack->packageid; ?>" class="pull-right text-bold"><?php echo number_format($total_basiccost); ?></a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Box Profit & Price Detail -->
                            <div class="box box-success">
                                <div class="box-header with-border">
                                    <div class="user-block">
                                        <img class="img-circle" src="<?php echo base_url('assets/adminlte/dist/img/profit.png'); ?>" alt="User Image">
                                        <span class="username"><a href="#">Profit & Price</a></span>
                                        <span class="description">Profit margin and final price</span>
                                    </div>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>

                                <div class="box-body no-padding">
                                    <div class="table-responsive detail_itempackage">
                                        <table class="table table-condensed">
                                            <tr>
                                                <td width="25%" style="vertical-align: middle;"><i class="fa fa-fw fa-caret-right"></i> Profit</td>
                                                <td width="25%">
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-addon">%</span>
                                                        <input class="form-control text-center" id="profitpackage_<?php echo $rowpack->packageid; ?>" value="<?php echo floatval($rowpack->profitvar); ?>" maxlength="3" onkeyup="calculate_profit('0','<?php echo $rowpack->packageid; ?>');" onkeypress="return isNumberKey1(event);" autocomplete="off">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-addon">Rp.</span>
                                                        <input class="form-control text-right" id="profitvalue_<?php echo $rowpack->packageid; ?>" value="<?php echo number_format($rowpack->profitval); ?>" maxlength="8" onkeyup="set_numberFormat('profitvalue_<?php echo $rowpack->packageid; ?>'); calculate_profit('1','<?php echo $rowpack->packageid; ?>');" onkeypress="return isNumberKey1(event);" autocomplete="off">
                                                        <span class="input-group-btn">
                                                            <button id="btn_profit_<?php echo $rowpack->packageid; ?>" type="button" class="btn btn-danger btn-light" onclick="updatePack('<?php echo $rowpack->packageid; ?>','profit','name_<?php echo $rowpack->packageid; ?>','btn_profit_');">Update</button>
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="25%" style="vertical-align: middle;"><i class="fa fa-fw fa-caret-right"></i> Manual Fee</td>
                                                <td colspan="2">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control text-right form-control-sm" id="manualfee_<?php echo $rowpack->packageid; ?>" value="<?php echo number_format($rowpack->manualfee); ?>" maxlength="8" onkeyup="set_numberFormat('manualfee_<?php echo $rowpack->packageid; ?>');" onkeypress="return isNumberKey1(event);">
                                                        <span class="input-group-btn">
                                                            <button id="btn_manualfee_<?php echo $rowpack->packageid; ?>" type="button" class="btn btn-danger btn-light" onclick="updatePack('<?php echo $rowpack->packageid; ?>','manualfee','manualfee_<?php echo $rowpack->packageid; ?>','btn_manualfee_');">Update</button>
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="25%" style="vertical-align: middle;"><i class="fa fa-fw fa-caret-right"></i> Price</td>
                                                <td colspan="2" class="text-right">
                                                    <h3 id="totalprice_<?php echo $rowpack->packageid; ?>" class="text-bold text-right">0</h3>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Box Footer Package -->
                        <div class="box-footer">
                            <a href="#" class="btn btn-sm btn-social btn-google" onclick="btnDiscard('<?php echo $no; ?>');">
                                <i class="fa fa-bitbucket"></i> DISCARD
                            </a>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <input type="hidden" id="count_package" value="<?php echo $no; ?>">
            <span id="setupid" style="visibility: hidden;"><?php echo $setupid; ?></span>
            <span id="companyid" style="visibility: hidden;"><?php echo $companyid; ?></span>
        </div>

        <!--modal window item mcu package list-->
        <div class="modal fade" id="modal-itemlist" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <h4 class="modal-title">Examination List</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <form role="form" id="form_listitem">
                                    <div class="form-group">
                                        <label>PackageID</label>
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <input type="text" class="form-control text-center" id="packageid_modal" readonly>
                                                <span id="headerid_modal" style="visibility: hidden;"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" id="packagename_modal" readonly>
                                    </div>
                                    <div class="form-group">
                                        <div class="box box-warning">
                                            <div class="box-body">
                                                <div class="table-responsive">
                                                    <table id="tblItems" class="table" style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 5% !important;">&nbsp;</th>
                                                                <th>Description</th>
                                                                <th style="width: 20% !important;">Costing</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left btn-sm" onclick="closeTblItem()">Close</button>
                        <button type="button" class="btn btn-success btn-sm" onclick="updaterow()">Set Selected Items</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!--modal window mcu package list-->
        <div class="modal fade" id="modal-packagelist" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" style="width: 45% !important;">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <h4 class="modal-title">Package List</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <form role="form" id="form_packagelist">
                                    <div class="form-group">
                                        <div class="box box-warning">
                                            <div class="box-body">
                                                <span id="packageid_modal" style="visibility: hidden;"></span>
                                                <span id="headerid_modal" style="visibility: hidden;"></span>
                                                <div class="table-responsive">
                                                    <table id="tblPackages" class="table" style="width: 100%;">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 5% !important;">&nbsp;</th>
                                                            <th>Package Name</th>
<!--                                                            <th style="width: 20% !important;">Price</th>-->
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left btn-sm" onclick="closeTblPackage()">Close</button>
                        <button type="button" class="btn btn-success btn-sm" onclick="copyselectedpackage()">Copy Selected Package</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </section>
</div>

<?php
$this->load->view('templates/footer');
$this->load->view('templates/footer_plugin');
?>
<script>
    /* Auto load javascript code */
    $(function () {
        var calc_package = $('#count_package').val();

        for(i = 1; i <= calc_package; ++i) {
            $('#certtype_' + i).select2();

            packageid = $('#packlabel_' + i).text();

            if( $('#name_' + packageid).val() != "" ) {
                $('#name_' + packageid).prop('disabled', true);
                $('#btn_name_' + packageid).text('Edit').removeClass('btn-danger').addClass('btn-success');
            }

            if( $('#profitpackage_' + packageid).val() != "0" || filterNum($('#profitvalue_' + packageid).val()) != "0" ) {
                $('#profitpackage_' + packageid).prop('disabled', true);
                $('#profitvalue_' + packageid).prop('disabled', true);
                $('#btn_profit_' + packageid).text('Edit').removeClass('btn-danger').addClass('btn-success');
            }

            if( $('#manualfee_' + packageid).val() != "0" ) {
                $('#manualfee_' + packageid).prop('disabled', true);
                $('#btn_manualfee_' + packageid).text('Edit').removeClass('btn-danger').addClass('btn-success');
            }

            setTimeout("calculate_price('" + packageid + "')", 300);
        }
    });

    function updatePack(packageid,flagid,fieldname,btnid) {
        if( $('#' + btnid + packageid).text() == "Edit" ) {
            if(flagid == "profit") {
                $('#profitpackage_' + packageid).prop('disabled', false).focus();
                $('#profitvalue_' + packageid).prop('disabled', false).focus();
                $('#' + btnid + packageid).text('Update').removeClass('btn-success').addClass('btn-danger');
            } else {
                $('#' + fieldname).prop('disabled', false).focus();
                $('#' + btnid + packageid).text('Update').removeClass('btn-success').addClass('btn-danger');
            }

            setTimeout("calculate_price('" + packageid + "')", 500);
        } else {
            if(flagid != "profit" && flagid != "manualfee") {
                var resultvalue = $('#' + fieldname).val();
            } else {
                var resultvalue = filterNum($('#' + fieldname).val());
            }

            $.post("<?php echo site_url('packupdate'); ?>", {
                "packageid": packageid,
                "flagid": flagid,
                "resultdata": resultvalue,
                "profitpercentage": $('#profitpackage_' + packageid).val(),
                "profitnominal": filterNum($('#profitvalue_' + packageid).val())
            }, function (data) {
                if (data.errmsg == "-") {
                    toastSuccess('Update Success', '');

                    if(flagid == "profit") {
                        $('#profitpackage_' + packageid).prop('disabled', true);
                        $('#profitvalue_' + packageid).prop('disabled', true);
                        $('#' + btnid + packageid).text('Edit').removeClass('btn-danger').addClass('btn-success');
                    } else {
                        $('#' + fieldname).prop('disabled', true);
                        $('#' + btnid + packageid).text('Edit').removeClass('btn-danger').addClass('btn-success');
                    }

                    /* calculate and update total basic cost after selected item has been update into frame */
                    setTimeout("calculate_price('" + packageid + "')", 300);
                } else {
                    toastError(data.errmsg, '');
                }
            }, "json");
        }
    }

    function openItemList(packageid,headerid) {
        var packagename = $('#name_' + packageid).val();
        var btn_name = $('#btn_name_' + packageid).text();

        var certtype = $('#certtype_' + $('#seqpack_' + packageid).text()).val();
        var btn_cert = $('#btn_cert_' + packageid).text();

        /* Temporary disabled */
        if(packagename == "") {
            toastError('Package name cannot be empty', '');
            $('#name_' + packageid).focus(); return false;
        } else if(btn_name == "Update") {
            toastError('Please update package name', '');
            $('#name_' + packageid).focus(); return false;
        } else if(certtype == "") {
            toastError('Certificate type cannot be empty', '');
            $('#certtype_' + $('#seqpack_' + packageid).text()).focus(); return false;
        } else if(btn_cert == "Update") {
            toastError('Please update certificate type', '');
            $('#certtype_' + $('#seqpack_' + packageid).text()).focus(); return false;
        } else {
            $('#modal-itemlist').modal({backdrop: "static", keyboard: false});

            $('#packageid_modal').val(packageid);
            $('#headerid_modal').text(headerid);
            $('#packagename_modal').val($('#name_' + packageid).val());

            $("#tblItems").DataTable().destroy();

            $("#tblItems").dataTable({
                "ajax": {
                    "url": "<?php echo site_url('packitemlist'); ?>/" + headerid + "/" + packageid,
                    "type": "POST",
                    "error": function(jqXHR, textStatus, errorThrown) {
                        toastError(jqXHR.responseText,'');
                    }
                },
                "columns": [
                    {"data": "kodeitem",
                        "render": function ( data, type, full ) {
                            return "<input type=\"checkbox\" id=\"checkitem_" + full['kodeitem'] + "\" class=\"icheckbox_minimal-blue\" onclick=\"selectItem('" + full['kodeitem'] + "','" + packageid + "','" + headerid + "')\"  style='width: 17px; height: 17px;'>";
                            $('#checkitem_' + full['kodeitem']).prop('checked', false);
                        }, "className": "text-center", "searchable": false, "orderable": false},
                    {"data": "publishdesc", "className": "text-left", "searchable": false},
                    {"data": "costing", render: $.fn.dataTable.render.number(',', '.', ''), "className": "text-right", "orderable": false, "searchable": false},
                    {"data": "keterangan", "visible": false, "orderable": false},
                    {"data": "nama_barang", "visible": false, "orderable": false}
                ],
                "processing": true,
                "serverSide": true,
                "info": false,
                "retrieve": true,
                "autoWidth": false,
                "dom": 'ftp',
                "order": [
                    [1, "asc"]
                ]
            });
        }
    }

    function selectItem(kodeitem,packageid,headerid) {
        /* set checkbox variable */
        var checkbox_status = $('#checkitem_' + kodeitem).prop('checked');

        /* set selected checkbox and update into table */
        $.post("<?php echo site_url('packitemselect'); ?>", {
            "companyid": $('#companyid').text(),
            "setupid": $('#setupid').text(),
            "headerid": headerid,
            "packageid": packageid,
            "kodeitem": kodeitem,
            "checkboxstatus": checkbox_status
        }, function (data) {
            if(data.errmsg == "-") {
                var linkeditem = data.linkeditem;
                var unlinkeditem = data.unlinkeditem;

                if(linkeditem.indexOf(",") > 0) {
                    var linkeditem_arr = linkeditem.split(',');

                    $.each(linkeditem_arr, function (index, value) {
                        $('#checkitem_' + value).prop("checked", true);
                    });
                } else {
                    $('#checkitem_' + linkeditem).prop("checked", true);
                }

                if(unlinkeditem.indexOf(",") > 0) {
                    var unlinkeditem_arr = unlinkeditem.split(',');

                    $.each(unlinkeditem_arr, function (index, value) {
                        $('#checkitem_' + value).prop("checked", false);
                    });
                } else {
                    $('#checkitem_' + unlinkeditem).prop("checked", false);
                }
            } else {
                $('#checkitem_' + kodeitem).prop('checked', false);
                /* display message error while query processing */
                toastError(data.errmsg,'');
            }
        }, "json");
    }

    function closeTblItem() {
        $('#modal-itemlist').modal('hide');
    }

    function updaterow() {
        var headerid = $('#headerid_modal').text();
        var packageid = $('#packageid_modal').val();

        $.post("<?php echo site_url('packitemupdate'); ?>", {
            'headerid': headerid,
            'packageid': packageid
        }, function(data) {
            if(data.errmsg == "-") {
                $('#dtlItem_' + packageid + ' > tr').remove();
                $.each(data.detailpackage, function (index, value) {
                    $('#dtlItem_' + packageid).append('' +
                        '<tr id=\'trItem_' + value['id'] + '\'>' +
                            '<td align=\'center\'><button type=\'button\' class=\'btn btn-default btn-xs\' onclick=\'del_rowdtlpackage("' + headerid + '","' + packageid + '","' + value['itemcode'] + '");\'><i class=\'fa fa-trash-o\'></i></button></td>' +
                            '<td>' + value['itemname'] + '</td>' +
                            '<td align=\'right\'>' + formatCurrency(value['itemcost']) + '</td>' +
                        '</tr>');

                    $('#modal-itemlist').modal('hide');
                });

                $("#tblItems").dataTable().clear().draw();
                $('#examcost_' + packageid).text(data.examcosting);

                /* calculate and update total basic cost after selected item has been update into frame */
                setTimeout("calculate_price('" + packageid + "')", 300);
            } else {
                toastError(data.errmsg,'');
            }
        }, "json");
    }

    function openPackageList(packageid,setupid,headerid) {
        $('#modal-packagelist').modal({backdrop: "static", keyboard: false});

        $('#packageid_modal').text(packageid);
        $('#headerid_modal').text(headerid);

        // $("#tblPackages").DataTable().destroy();
        $("#tblPackages").dataTable({
            "ajax": {
                "url": "<?php echo site_url('packcopylist'); ?>/" + setupid + "/" + packageid,
                "type": "POST",
                "error": function(jqXHR, textStatus, errorThrown) {
                    toastError(jqXHR.responseText,'');
                }
            },
            "columns": [
                {"data": "recid",
                    "render": function ( data, type, full ) {
                        return "<button type=\"button\" class=\"btn btn-xs btn-success\" id=\"btnpackage_" + full['recid'] + "\" onclick=\"selectPackage('" + full['recid'] + "','" + $('#headerid_modal').text() + "','" + $('#packageid_modal').text() + "')\">CHOOSE</button>";
                    }, "className": "text-center", "searchable": false, "orderable": false},
                {"data": "packagename", "className": "text-left"}
            ],
            "processing": true,
            "serverSide": true,
            "retrieve": true,
            "info": false,
            "autoWidth": false,
            "dom": 'ftp',
            "order": [
                [1, "asc"]
            ]
        });
    }

    function selectPackage(recid,headerid,packageid) {
        // alert([recid , headerid , packageid , $('#companyid').text() , $('#setupid').text()]); return false;
        /* set selected checkbox and update into table */
        $.post("<?php echo site_url('packpackageselect'); ?>", {
            "companyid": $('#companyid').text(),
            "setupid": $('#setupid').text(),
            "recid": recid,
            "headerid": headerid,
            "packageid": packageid
        }, function (data) {
            if(data.errmsg == "-") {
                $('#dtlItem_' + packageid + ' > tr').remove();
                $.each(data.detailpackage, function (index, value) {
                    $('#dtlItem_' + packageid).append('' +
                        '<tr id=\'trItem_' + value['id'] + '\'>' +
                        '<td align=\'center\'><button type=\'button\' class=\'btn btn-default btn-xs\' onclick=\'del_rowdtlpackage("' + headerid + '","' + packageid + '","' + value['itemcode'] + '");\'><i class=\'fa fa-trash-o\'></i></button></td>' +
                        '<td>' + value['itemname'] + '</td>' +
                        '<td align=\'right\'>' + formatCurrency(value['itemcost']) + '</td>' +
                        '</tr>');

                    $('#modal-itemlist').modal('hide');
                });

                $('#examcost_' + packageid).text(data.examcosting);

                /* calculate and update total basic cost after selected item has been update into frame */
                setTimeout("calculate_price('" + packageid + "')", 300);

                $('#modal-packagelist').modal('hide');
            } else {
                /* display message error while query processing */
                toastError(data.errmsg,'');
            }
        }, "json");
    }

    function closeTblPackage() {
        $('#modal-packagelist').modal('hide');
    }

    function del_rowdtlpackage(headerid,packageid,itemcode) {
        $.post("<?php echo site_url('packitemdelete'); ?>", {
            'headerid': headerid,
            'packageid': packageid,
            'itemcode': itemcode
       }, function (data) {
           if(data.errmsg == "-") {
               $('#dtlItem_' + packageid + ' > tr').remove();
               $.each(data.detailpackage, function (index, value) {
                   $('#dtlItem_' + packageid).append('' +
                       '<tr id=\'trItem_' + value['id'] + '\'>' +
                       '<td align=\'center\'><button type=\'button\' class=\'btn btn-default btn-xs\' onclick=\'del_rowdtlpackage("' + headerid + '","' + packageid + '","' + value['itemcode'] + '");\'><i class=\'fa fa-trash-o\'></i></button></td>' +
                       '<td>' + value['itemname'] + '</td>' +
                       '<td align=\'right\'>' + formatCurrency(value['itemcost']) + '</td>' +
                       '</tr>');

                   $('#modal-itemlist').modal('hide');
               });

               $('#examcost_' + packageid).text(data.examcosting);

               setTimeout("calculate_price('" + packageid + "');", 500);
           } else {
               toastError(data.errmsg,'');
           }
       }, 'json');
    }

    function calculate_profit(id,packageid) {
        if(id == "0") {
            var calc_profit = (parseFloat(filterNum($('#basiccost_' + packageid).text())) * $('#profitpackage_' + packageid).val()) / 100;
            $('#profitvalue_' + packageid).val(formatCurrency(Math.round(parseInt(calc_profit))));
        } else if(id == "1") {
            var profit_value = filterNum($('#profitvalue_' + packageid).val());
            var calc_profit = ( profit_value / parseFloat(filterNum($('#basiccost_' + packageid).text())) ) * 100;
            // var calc_profit = (parseFloat(filterNum($('#total_basiccost').text())) * $('#profitpackage').val()) / 100;
            $('#profitpackage_' + packageid).val(formatCurrency(calc_profit));
        }
    }

    function calculate_price(packageid) {
        /* ======================================== set main variable ==================================== */
        /* set total basic cost */
        var set_totalbasiccost = parseFloat(filterNum($('#examcost_' + packageid).text())) + parseFloat(filterNum($('#medreccost').text())) + parseFloat(filterNum($('#delivercost').text())) + parseFloat(filterNum($('#laundrycost').text()));
        $('#basiccost_' + packageid).text(formatCurrency(set_totalbasiccost));

        /* set total cost and put it into totalcost (span) */
        var totalcost = parseFloat(filterNum($('#basiccost_' + packageid).text())) + parseFloat(filterNum($('#renovcost').text())) + parseFloat(filterNum($('#admcost').text()));
        $('#totalcost_' + packageid).text(formatCurrency(totalcost));

        /* calculate profit and show it into profit box */
        if( $('#btn_profit_' + packageid).text() == "Edit" ) {
            var profit = filterNum($('#profitvalue_' + packageid).val());
        } else {
            var profit = 0;
        }

        /* set manual fee (free entry by sales PIC) */
        if( $('#btn_manualfee_' + packageid).text() == "Edit" ) {
            var manualfee = filterNum($('#manualfee_' + packageid).val());
        } else {
            var manualfee = 0;
        }

        /* ================================= calculate price using main variable ================================= */
        /* calculate price and set to string */
        var calc_packageprice = (parseInt(totalcost) + parseInt(profit) + parseInt(manualfee)).toString();

        /* set rounding variable */
        var digitnominal = parseInt(calc_packageprice.substring(calc_packageprice.length - 3));
        var adding_digit = 1000 - digitnominal;

        /* adding calculate price and rounding variable (round up) */
        var packageprice = parseInt(calc_packageprice) + adding_digit;

        $.post("<?php echo site_url('packprice'); ?>", {
            "setupid": $('#setupid').text(),
            "companyid": $('#companyid').text(),
            "packageid": packageid,
            "packageprice": packageprice
        }, function(data) {
            if(data.errmsg == "-") {
                $('#totalprice_' + packageid).text( formatCurrency(packageprice) );
            } else {
                toastError(data.errmsg,'');
            }
        }, 'json');
    }

    function btnDiscard(id) {
        $.confirm({
            theme: 'material',
            title: 'Confirm Deletion',
            content: 'Are you sure to continue?',
            autoClose: 'cancel|7000',
            buttons: {
                confirm: {
                    btnClass: 'btn-success',
                    action: function() {
                        $.post("<?php echo site_url('packdelete'); ?>", {
                            'headerid': $('#headerid_' + id).text()
                        }, function(data) {
                            if(data.errmsg == "-") {
                                toastSuccess('Package successfully deleted','');
                                setTimeout("redirectToPackagePage('" + $('#companyid').text() + "','" + $('#setupid').text() + "')", 1200);
                            } else {
                                toastError(data.errmsg,'');
                            }
                        }, "json");
                    }
                },
                cancel: {
                    btnClass: 'btn-danger'
                }
            }
        });
    }

    function btnApproval() {
        $.confirm({
            theme: 'material',
            title: 'Confirm Requesting Approval',
            content: 'Are you sure to continue?',
            autoClose: 'cancel|9000',
            buttons: {
                confirm: {
                    btnClass: 'btn-success',
                    action: function() {
                        $.post("<?php echo site_url('packapproval'); ?>", {
                            'setupid': $('#setupid').text(),
                            'companyid': $('#companyid').text()
                        }, function(data) {
                            if(data.errmsg == "-") {
                                if(data.emailmsg[0] == "0") {
                                    toastWarning(data.emailmsg[1],'');
                                } else {
                                    toastSuccess(data.emailmsg[1],'');
                                }

                                // toastSuccess('Your package successfully save to draft, please wait until we redirect you to main page','');
                                // setTimeout("redirectToSetupPage('" + $('#companyid').text() + "')", 1200);
                            } else {
                                toastError(data.errmsg,'');
                            }
                        }, "json");
                    }
                },
                cancel: {
                    btnClass: 'btn-danger'
                }
            }
        });
    }

    function btnDraft() {
        $.confirm({
            theme: 'material',
            title: 'Save your package setup to draft',
            content: 'Are you sure to continue?',
            autoClose: 'cancel|9000',
            buttons: {
                confirm: {
                    btnClass: 'btn-success',
                    action: function() {
                        $.post("<?php echo site_url('packdraft'); ?>", {
                            'setupid': $('#setupid').text(),
                            'companyid': $('#companyid').text()
                        }, function(data) {
                            if(data.errmsg == "-") {
                                toastSuccess('Your package successfully save to draft, please wait until we redirect you to main page','');
                                setTimeout("redirectToSetupPage('" + $('#companyid').text() + "')", 1200);
                            } else {
                                toastError(data.errmsg,'');
                            }
                        }, "json");
                    }
                },
                cancel: {
                    btnClass: 'btn-danger'
                }
            }
        });
    }

    function redirectToPackagePage(companyid,setupid) {
        window.location = "<?php echo site_url('packitem'); ?>/" + companyid + "/" + setupid;
    }

    function redirectToSetupPage(companyid) {
        window.location = "<?php echo site_url('packdescription'); ?>/" + companyid;
    }

    /* open pdf window on new tab */
    function btnPrint() {
        window.open("<?php echo site_url(''); ?>packtopdf/" + $('#companyid').text() + "/" + $('#setupid').text(), '_blank');
    }

    //function btn_preview() {
       /* reset table content (item list) */
    //    $('#tbl_listitem').empty();
       /* open modal window */
    //    $('#modal-preview').modal({backdrop: "static", keyboard: false});
    //    $('#preview_compname').text($('#companyname').text());
       /* load company data and detail item */
    //    $.post("<?php //echo site_url('packpreview'); ?>//", {
    //        "companyid": $('#companyid').val(),
    //        "packageid": $('#packageid').val(),
    //        "totalcost": filterNum($('#total_cost').text()),
    //        "profitvar": $('#profitpackage').val(),
    //        "profitval": filterNum($('#profitvalue').text()),
    //        "manualfee": filterNum($('#manualfee').val()),
    //        "totalprice": filterNum($('#packageprice').text())
    //    }, function (data) {
    //        if(data.errmsg == "-") {
    //            $('#preview_packagename').text(data.packagename);
    //            $('#preview_imnum').text(data.imnum);
    //            $('#preview_periode').text(data.startperiode + " - " + data.endperiode);
    //            $('#preview_language').text(data.packagelanguage);
    //            $('#preview_certificate').text(data.certtype);
    //            $('#preview_top').text(data.packtop);
    //
    //            $('#preview_cost').text($('#total_cost').text());
    //            $('#preview_profit').text("(" + $('#profitpackage').val() + " %)");
    //            $('#preview_profitvalue').text($('#profitvalue').text());
    //            $('#preview_manualfee').text($('#manualfee').val());
    //            $('#preview_price').text($('#packageprice').text());
    //
               /* draw detail item into table content */
    //            $.each(data.packitem, function(key, value) {
    //                $('#tbl_listitem').append('<tr><td>' + value['itemname'] + '</td><td class="text-right">' + formatCurrency(value['costing']) + '</td></tr>');
    //            });
    //        } else {
               /* display error message while query process */
    //            toastError(data.errmsg,'');
    //        }
    //    }, "json");
    //}

    //function btn_submit() {
    //    $.post("<?php //echo site_url('submitpackage'); ?>//", {
    //        "companyid": $('#companyid').val(),
    //        "packageid": $('#packageid').val(),
    //        "totalprice": filterNum($('#packageprice').text())
    //    }, function(data) {
    //        if(data.errmsg == "-") {
    //            toastSuccess('Your package successfully submitted, please wait until we redirect you to main page','');
    //
    //            setTimeout("redirectPage()", 1000);
    //        } else {
    //            toastError(data.errmsg,'');
    //        }
    //    }, "json");
    //}
    //
    //function redirectPage() {
    //    window.location = "<?php //echo site_url('redirectpackage/'); ?>//" + $('#companyid').val();
    //}
</script>

<?php
$this->load->view('templates/footer_close');
?>
