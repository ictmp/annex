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
            <li><a href="<?php echo site_url('packcompany'); ?>">Company</a></li>
            <li class="active">Package Details</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-5">

                <div class="box box-primary">
                    <div class="box-body primary">
                        <h3 class="profile-username text-center"><?php if(isset($detailcompany)) echo $detailcompany->companyname; ?></h3>
                        <input type="hidden" id="companyid" value="<?php echo $companyid; ?>">

                        <hr>

                        <strong><i class="fa fa-university margin-r-5"></i> Address</strong>
                        <p class="text-muted">
                            <?php if(isset($detailcompany)) echo $detailcompany->address1."<br>".$detailcompany->city; ?>
                        </p><br>

                        <strong><i class="fa fa-user margin-r-5"></i> PIC</strong>
                        <p class="text-muted">
                            <?php if(isset($detailcompany)) echo $detailcompany->contact; ?>
                        </p><br>

                        <strong><i class="fa fa-phone-square margin-r-5"></i> Phone</strong>
                        <p class="text-muted">
                            <?php if(isset($detailcompany)) echo $detailcompany->phone; ?>
                        </p><br>

                        <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>
                        <p class="text-muted">
                            <?php if(isset($detailcompany)) echo $detailcompany->contactemail; ?>
                        </p>

                        <div class="box box-widget widget-user-2">
                            <div class="widget-user-header bg-yellow">
                                <h5 class="widget-user-username">Result Delivery Address</h5>
                            </div>
                            <div class="box-footer no-padding">
                                <ul class="nav nav-stacked">
                                    <li><a href="#"><strong>PIC</strong> : <?php if(isset($detailcompany)) echo $detailcompany->delivery_contact; ?></a></li>
                                    <li><a href="#"><strong>Address</strong> : <?php if(isset($detailcompany)) echo $detailcompany->delivery_address; ?></a></li>
                                    <li><a href="#"><strong>Email</strong> : <?php if(isset($detailcompany)) echo $detailcompany->delivery_email; ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-7">

                <div class="box box-primary">
                    <div class="box-body primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Package Setup</h3>
                        </div>
                        <form role="form" id="form_detailpackage">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>ProjectID</label>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <input type="text" class="form-control text-center" id="projectid" value="<?php echo $projectid; ?>" disabled>
                                            <input type="hidden" id="lastid" value="<?php echo $getid; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Number of Packages</label>
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <input type="text" class="form-control text-center" id="numpackage" onkeypress="return isNumberKey1(event,'numpackage');" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Language</label>
                                    <select class="form-control" name="packlanguage" id="packlanguage">
                                        <option value="-">- Select -</option>
                                        <option value="0" <?php if(isset($detailpackage)) {
                                            if($detailpackage->packagelanguage == "0") {
                                                echo set_select('packlanguage', '0', true);
                                            } else {
                                                echo set_select('packlanguage', '0', false);
                                            }
                                        } ?> >Indonesia</option>
                                        <option value="1" <?php if(isset($detailpackage)) {
                                            if($detailpackage->packagelanguage == "1") {
                                                echo set_select('packlanguage', '1', true);
                                            } else {
                                                echo set_select('packlanguage', '1', false);
                                            }
                                        } ?> >English</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Result Type</label>
                                    <select class="form-control" name="packresult" id="packresult">
                                        <option value="-">- Select -</option>
                                        <option value="0" <?php if(isset($detailpackage)) {
                                            if($detailpackage->resulttype == "0") {
                                                echo set_select('packresult', '0', true);
                                            } else {
                                                echo set_select('packresult', '0', false);
                                            }
                                        } ?> >Hardcopy</option>
                                        <option value="1" <?php if(isset($detailpackage)) {
                                            if($detailpackage->resulttype == "1") {
                                                echo set_select('packresult', '1', true);
                                            } else {
                                                echo set_select('packresult', '1', false);
                                            }
                                        } ?> >Softcopy</option>
                                        <option value="2" <?php if(isset($detailpackage)) {
                                            if($detailpackage->resulttype == "1") {
                                                echo set_select('packresult', '1', true);
                                            } else {
                                                echo set_select('packresult', '1', false);
                                            }
                                        } ?> >Hardcopy & Softcopy</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <label>Duration (From)</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" id="startdate" value="<?php if(isset($detailpackage)) echo $detailpackage->startperiode; ?>" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <label>Duration (To)</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" id="enddate" value="<?php if(isset($detailpackage)) echo $detailpackage->endperiode; ?>" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea class="form-control" id="packnotes" rows="3" placeholder="Enter ..." style="resize: none;"><?php if(isset($detailpackage)) echo $detailpackage->packagenote; ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Term Of Payment</label>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <div class="input-group">
                                                <input type="text" class="form-control text-center" id="packtop" onkeypress="return isNumberKey1(event,'packtop');" autocomplete="off">
                                                <div class="input-group-addon">
                                                    <b>Days</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

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

                        <div class="box-footer">
                            <a href="<?php echo site_url('packcompany'); ?>" class="btn btn-danger">Back to Company List</a>
                            <button type="button" class="btn btn-primary pull-right" onclick="createpackage();">Generate Package</button>
                        </div>
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
        $('#startdate').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
        $('#enddate').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });

        $('#startdate').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy'
        });

        $('#enddate').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy'
        });

        // setTimeout("check_uploaded_file()", 1000);
    });

    Dropzone.autoDiscover = false;

    var doc_upload = new Dropzone(".dropzone",{
        url: "<?php echo site_url('packattachfile') ?>",
        maxFilesize: 10,
        method: "post",
        acceptedFiles: ".jpg,.jpeg,.pdf,.doc,.docx",
        paramName: "userfile",
        params: {
            'companyid': $('#companyid').val(),
            'projectid': $('#projectid').val()
        },
        dictInvalidFileType: "File types are not permitted",
        addRemoveLinks: true,
        success:function(file,response) {
            // alert(response);
            if(response != "-") {
                toastError(response,'');
                doc_upload.removeAllFiles(true);
            }
        }
    });

    /* Event starting upload file */
     doc_upload.on("sending",
         function(file, xhr, formData) {
             file.token = Math.random();
             /* preparing token for each file */
             formData.append("token_file", file.token);
         }
     );

    /* Event removing uploaded file */
    doc_upload.on("removedfile",function(a){
        var token = a.token;
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

    doc_upload.on("addedfile", function(file) {
        if (this.files.length) {
            var _i, _len;
            for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) {
                if(this.files[_i].name === file.name && this.files[_i].size === file.size) {
                    this.removeFile(file);
                }
            }
        }
    });

    function createpackage() {
        if($('#companyid').val() == "") {
            page = "<?php echo site_url('gpackcomplist'); ?>";
            location = page;
        } else if($('#numpackage').val() == "") {
            $('#numpackage').notify("Number of packages is required", {className: "error", position: "top"});
            $('#numpackage').focus();
        } else if($('#packlanguage').val() == "-") {
            $('#packlanguage').notify("Result language is required", {className: "error", position: "top"});
            $('#packlanguage').focus();
        } else if($('#packresult').val() == "-") {
            $('#packresult').notify("Result type is required", {className: "error", position: "top"});
            $('#packresult').focus();
        } else if($('#startdate').val() == "") {
            $('#startdate').notify("Start periode is required", {className: "error", position: "bottom"});
            $('#startdate').focus();
        } else if($('#enddate').val() == "") {
            $('#enddate').notify("End periode is required", {className: "error", position: "bottom"});
            $('#enddate').focus();
        } else if($('#packnotes').val() == "") {
            $('#packnotes').notify("Package notes is required", {className: "error", position: "top"});
            $('#packnotes').focus();
        } else if($('#packtop').val() == "") {
            $('#packtop').notify("Term of payment is required", {className: "error", position: "top"});
            $('#packtop').focus();
        } else {
            $.post("<?php echo site_url('packdescriptioncreate'); ?>", {
                "getid": $('#lastid').val(),
                "projectid": $('#projectid').val(),
                "compid": $('#companyid').val(),
                "numpackage": $('#numpackage').val(),
                "packlang": $('#packlanguage').val(),
                "resulttype": $('#packresult').val(),
                "stdate": $('#startdate').val().split('/').reverse().join('-'),
                "endate": $('#enddate').val().split('/').reverse().join('-'),
                "packnote": $('#packnotes').val(),
                "packtop": $('#packtop').val()
            }, function (data) {
                if(data.errmsg == "-") {
                    page = "<?php echo site_url(); ?>packitem/" + data.companyid + "/" + data.setupid;
                    location = page;
                } else {
                    toastError(data.errmsg,'');
                }
            }, "json");
        }
    }

    function check_uploaded_file() {
        $.ajax({
            type: "post",
            url: "<?php echo base_url('packremovetmpfile') ?>",
            cache: false,
            dataType: 'json',
            success: function(data) {
                if(data.errmsg != true) {
                    toastError(data.errmsg,'');
                }
            },
            error: function(){
                toastError('Error on ajax processing','');
            }
        });
    }
</script>

<?php
$this->load->view('templates/footer_close');
?>
