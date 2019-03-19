<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data['pageid'] = "generatepack";
$data['pagetitle'] = "MCU Package";

$this->load->view('templates/header_plugin_approvalmcu',$data);
$this->load->view('templates/header_approvalmcu');
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            MCU Package
            <small>Package Details</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php //echo site_url('mainpage'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Approval Page</li>
        </ol>
    </section>

    <section class="content">

        <div class="row">
            <span id="setupid" style="visibility: hidden;"><?php echo base64_encode($packagesetup['setupid']); ?></span>
            <span id="companyid" style="visibility: hidden;"><?php echo base64_encode($packagesetup['companyid']); ?></span>
            <span id="imgid" style="visibility: hidden;"><?php echo $packagesetup['imgid']; ?></span>

            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-body text-center">
                        <div class="col-md-4">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <div class="box-title">
                                        <label class="font-weight-bold text-light">Package Setup</label>
                                    </div>
                                </div>

                                <div class="box-body text-center">
                                    <a class="btn btn-app" onclick="btnApproval_all();" data-toggle="tooltip" data-placement="bottom" title="Send approval request via email">
                                        <i class="fa fa-thumbs-o-up"></i> Approval All
                                    </a>
                                    <a class="btn btn-app" onclick="btnReject_all();" data-toggle="tooltip" data-placement="bottom" title="Send approval request via email">
                                        <i class="fa fa-thumbs-o-down"></i> Reject All
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="box box-primary">
                                <div class="box-header">
                                    <div class="box-title">
                                        <label class="font-weight-bold text-light">Attachment File</label>
                                    </div>
                                </div>

                                <div class="box-body text-center">
                                    <form action="#" class="dropzone"></form>
                                </div>
<!--                                <div class="box-body text-center">-->
<!--                                    <div id="attachfile" class="col-xs-12">-->
<!--                                        --><?php
//                                        foreach ($attachfile as $rowfile) {
//                                                echo $rowfile->file_name;
//                                        }
//                                        ?>
<!--                                    </div>-->
<!--                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $no = 0;
            foreach ($packagelist->result() as $rowpack) {
                $no += 1;
                ?>
                <div class="col-md-4">
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
                                <input type="text" class="form-control" id="name_<?php echo $rowpack->packageid; ?>" value="<?php echo $rowpack->packagename; ?>" disabled>
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
                                ?>
                                <select id="certtype_<?php echo $no; ?>" class="form-control" multiple="multiple" data-placeholder="Select Type of Certificate"
                                        style="width: 100%; border-radius: 5px;" disabled>
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
                                        if( $rowpack->certtype == "Petronas AME" ) {
                                            echo set_select('certtype','DIPERLA',true);
                                        } else { echo set_select('certtype','DIPERLA',false); }
                                    }
                                    ?> >DIPERLA</option>
                                </select>
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
                                                <td width="30%" style="vertical-align: middle;"><i class="fa fa-fw fa-caret-right"></i> Profit</td>
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
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="30%" style="vertical-align: middle;"><i class="fa fa-fw fa-caret-right"></i> Manual Fee</td>
                                                <td colspan="2">
                                                    <div class="input-group input-group-sm">
                                                        <input class="form-control text-right form-control-sm" id="manualfee_<?php echo $rowpack->packageid; ?>" value="<?php echo number_format($rowpack->manualfee); ?>" maxlength="8" onkeyup="set_numberFormat('manualfee_<?php echo $rowpack->packageid; ?>');" onkeypress="return isNumberKey1(event);">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="25%" style="vertical-align: middle;"><i class="fa fa-fw fa-caret-right"></i> Price</td>
                                                <td colspan="2" class="text-right">
                                                    <h3 class="text-bold text-right"><?php echo number_format($rowpack->totalprice); ?></h3>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Box Footer Package -->
                        <div class="box-footer">
                            <a href="#" class="btn btn-sm btn-social btn-google" onclick="btnReject('<?php echo $no; ?>');">
                                <i class="fa fa-thumbs-down"></i> REJECT
                            </a>
                            <a href="#" class="btn btn-sm btn-social btn-instagram pull-right" onclick="btnApprove('<?php echo $no; ?>');">
                                <i class="fa fa-thumbs-up"></i> APPROVE
                            </a>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <input type="hidden" id="count_package" value="<?php echo $no; ?>">
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
        }

        // setTimeout("viewPackage('" + $('#setupid').text() + "','" + $('#companyid').text() + "','" + $('#imgid').text() + "')", 500);
    });

    //function viewPackage(setupid,companyid,imgid) {
    //    $.post("<?php //echo site_url('packagefile'); ?>//", {
    //        'setupid': setupid,
    //        'companyid': companyid,
    //        'imgid': imgid
    //    }, function (data) {
    //        if(data.errmsg == true) {
    //            $.each(data.packagefile, function (key, value) {
    //                alert('');
    //                $('#attachfile').html("" +
    //                    "<div class='col-md-3'>" +
    //                    "<embed src='<?php //echo base_url('assets/uploads/'); ?>//" + value['file_name'] + "' width='100%' height='180'>" +
    //                    "<div class='btn-group btn-group-justified' role='group'>" +
    //                    "<a href='<?php //echo site_url('downloadfile/'); ?>//" + value['file_name'] + "' class='btn btn-sm btn-success'>Download</a>" +
    //                    "</div>" +
    //                    "</div>");
    //            });
    //        } else {
    //            toastError(data.errmsg,'');
    //        }
    //    }, "json");
    //}

    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone(".dropzone", {
        init: function() {
            var me = this;
            $.get("<?php echo site_url('packagefile');?>", function(data) { //alert(data);
                // if any files already in server show all here
                if (data.length > 0) {
                    $.each(data, function(key, value) {
                        var mockFile = value;
                        me.emit("addedfile", mockFile);
                        me.emit("thumbnail", mockFile, "<?php echo ROOTURL; ?>/foto/real/" + value.name);
                        me.emit("complete", mockFile);

                        var a = document.createElement('a');
                        a.setAttribute('href',"<?php echo ROOTURL; ?>/foto/real/" + value.name);
                        a.innerHTML = "Download<b>";
                        value.previewTemplate.appendChild(a);

                    });
                }
            });
        }
    });

    //function updatePack(packageid,flagid,fieldname,btnid) {
    //    if( $('#' + btnid + packageid).text() == "Edit" ) {
    //        if(flagid == "profit") {
    //            $('#profitpackage_' + packageid).prop('disabled', false).focus();
    //            $('#profitvalue_' + packageid).prop('disabled', false).focus();
    //            $('#' + btnid + packageid).text('Update').removeClass('btn-success').addClass('btn-danger');
    //        } else {
    //            $('#' + fieldname).prop('disabled', false).focus();
    //            $('#' + btnid + packageid).text('Update').removeClass('btn-success').addClass('btn-danger');
    //        }
    //
    //        setTimeout("calculate_price('" + packageid + "')", 500);
    //    } else {
    //        if(flagid != "profit" && flagid != "manualfee") {
    //            var resultvalue = $('#' + fieldname).val();
    //        } else {
    //            var resultvalue = filterNum($('#' + fieldname).val());
    //        }
    //
    //        $.post("<?php //echo site_url('packupdate'); ?>//", {
    //            "packageid": packageid,
    //            "flagid": flagid,
    //            "resultdata": resultvalue,
    //            "profitpercentage": $('#profitpackage_' + packageid).val(),
    //            "profitnominal": filterNum($('#profitvalue_' + packageid).val())
    //        }, function (data) {
    //            if (data.errmsg == "-") {
    //                toastSuccess('Update Success', '');
    //
    //                if(flagid == "profit") {
    //                    $('#profitpackage_' + packageid).prop('disabled', true);
    //                    $('#profitvalue_' + packageid).prop('disabled', true);
    //                    $('#' + btnid + packageid).text('Edit').removeClass('btn-danger').addClass('btn-success');
    //                } else {
    //                    $('#' + fieldname).prop('disabled', true);
    //                    $('#' + btnid + packageid).text('Edit').removeClass('btn-danger').addClass('btn-success');
    //                }
    //
                   /* calculate and update total basic cost after selected item has been update into frame */
    //                setTimeout("calculate_price('" + packageid + "')", 300);
    //            } else {
    //                toastError(data.errmsg, '');
    //            }
    //        }, "json");
    //    }
    //}

    function btnApprove(id) {
        $.confirm({
            theme: 'material',
            title: 'Confirm Approve MCU Package',
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

    function btnReject(id) {
        $.confirm({
            theme: 'material',
            title: 'Confirm Reject MCU Package',
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

    function redirectToPackagePage(companyid,setupid) {
        window.location = "<?php echo site_url('packitem'); ?>/" + companyid + "/" + setupid;
    }
</script>

<?php
$this->load->view('templates/footer_close');
?>
