<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data['pageid'] = "dashboard";
$data['pagetitle'] = "Dashboard";

$this->load->view('templates/header_plugin',$data);
$this->load->view('templates/header');
$this->load->view('templates/menu',$data);
?>

<div class="content-wrapper">
    <br><br><br>
    <section class="content-header">
        <h1>
            Dashboard
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua-gradient"><i class="ion ion-ios-home-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Company</span>
                        <span class="info-box-number" id="total_company">0</span>

                        <a href="<?php echo site_url('dsbspv_comp'); ?>" class="small-box-footer">Show detail <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow-gradient"><i class="ion ion-ios-briefcase-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Need Approval</span>
                        <span class="info-box-number" id="total_needapproval">0</span>

                        <a href="<?php echo site_url('dsbspv_approval'); ?>" class="small-box-footer">Show detail <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green-gradient"><i class="ion ion-ios-browsers-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">MCU Package</span>
                        <span class="info-box-number" id="total_package">0</span>

                        <a href="<?php echo site_url('dsbspv_package'); ?>" class="small-box-footer">Show detail <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red-gradient"><i class="ion ion-ios-calendar-outline"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Close to expire</span>
                        <span class="info-box-number" id="total_expire">0</span>

                        <a href="<?php echo site_url('dsbspv_expire'); ?>" class="small-box-footer">Show detail <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
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
        setTimeout("check_status()", 500);
    });

    function check_status() {
        $.post("<?php echo site_url('dsbspvcheck'); ?>", {
            'test': ''
        }, function (data) {
            if(data.errmsg == "-") {
                $('#total_company').text( data.totalcomp );
                $('#total_needapproval').text( data.totalnpp );
                $('#total_package').text( data.totalpackage );
                $('#total_expire').text( data.totalexpire );
            } else {
                toastError(data.errmsg,'');
            }
        }, "json");
    }
</script>
<?php
$this->load->view('templates/footer_close');
?>