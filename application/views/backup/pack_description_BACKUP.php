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
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <label>Internal Memo Number</label>
                                            <input type="text" class="form-control" id="imnumber" value="<?php if(isset($detailpackage)) echo $detailpackage->imnum; ?>" placeholder="Enter Internal Memo Number">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Package Name</label>
                                    <input type="text" class="form-control" id="packname" value="<?php if(isset($detailpackage)) echo $detailpackage->packagename; ?>" placeholder="Enter Package Name">
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
                                    <label>Certificate</label>
                                    <select class="form-control select2" name="certtype" id="certtype" multiple="multiple" data-placeholder="Select Type of Certificate"
                                            style="width: 100%;">
                                        <option <?php if(isset($detailpackage)) {
                                            if($detailpackage->certtype == "OH Based") {
                                                echo set_select('certtype','OH Based',true);
                                            } else { echo set_select('certtype','OH Based',false); }
                                        } ?> >OH Based</option>
                                        <option <?php if(isset($detailpackage)) {
                                            if($detailpackage->certtype == "OGUK") {
                                                echo set_select('certtype','OGUK',true);
                                            } else { echo set_select('certtype','OGUK',false); }
                                        } ?> >OGUK</option>
                                        <option <?php if(isset($detailpackage)) {
                                            if($detailpackage->certtype == "Petronas AME") {
                                                echo set_select('certtype','Petronas AME',true);
                                            } else { echo set_select('certtype','Petronas AME',false); }
                                        } ?> >Petronas AME</option>
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
                                    <div class="row">
                                        <div class="col-xs-5">
                                            <label>Term Of Payment</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control text-center" id="packtop" value="">
                                                <div class="input-group-addon">
                                                    <b>Days</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="box-footer">
                            <a href="<?php echo site_url('packcompany'); ?>" class="btn btn-danger">Back to Company List</a>
                            <button type="button" class="btn btn-primary pull-right" onclick="createpackage();">Detail Package</button>
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
        $('.select2').select2();

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

        setTimeout("check_uploaded_file()", 1000);
    });

    function createpackage() {
        if($('#companyid').val() == "") {
            page = "<?php echo site_url('gpackcomplist'); ?>";
            location = page;
        } else if($('#imnumber').val() == "") {
            $('#imnumber').notify("Internal memo number is required", {className: "error", position: "top"});
            $('#imnumber').focus();
        } else if($('#packname').val() == "") {
            $('#packname').notify("Package name is required", {className: "error", position: "top"});
            $('#packname').focus();
        } else if($('#packlanguage').val() == "-") {
            $('#packlanguage').notify("Result language is required", {className: "error", position: "top"});
            $('#packlanguage').focus();
        } else if($('#certtype').val() == "") {
            $('#certtype').notify("Certificate type is required", {className: "error", position: "top"});
            $('#certtype').focus();
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
            var cert_type = [];
            $.each($("#certtype option:selected"), function(){
                cert_type.push($(this).val());
            });

            $.post("<?php echo site_url('packdescriptioncreate'); ?>", {
                "compid": $('#companyid').val(),
                "imnum": $('#imnumber').val(),
                "packname": $('#packname').val(),
                "packlang": $('#packlanguage').val(),
                "certtype": cert_type,
                "stdate": $('#startdate').val().split('/').reverse().join('-'),
                "endate": $('#enddate').val().split('/').reverse().join('-'),
                "packnote": $('#packnotes').val(),
                "packtop": $('#packtop').val()
            }, function (data) {
                if(data.errmsg == "-") {
                    page = "<?php echo site_url(); ?>packitem/" + data.companyid + "/" + data.packageid;
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
