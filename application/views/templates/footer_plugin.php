<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
</div>
<!-- jQuery 3 -->
<script src="<?php echo base_url('assets/adminlte/bower_components/jquery/dist/jquery.min.js'); ?>"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url('assets/adminlte/bower_components/jquery-ui/jquery-ui.min.js'); ?>"></script>
<!-- UI tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url('assets/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
<!-- Input Mask -->
<script src="<?php echo base_url('assets/adminlte/plugins/input-mask/jquery.inputmask.js'); ?>"></script>
<script src="<?php echo base_url('assets/adminlte/plugins/input-mask/jquery.inputmask.date.extensions.js'); ?>"></script>
<script src="<?php echo base_url('assets/adminlte/plugins/input-mask/jquery.inputmask.extensions.js'); ?>"></script>
<!-- BTConfirm -->
<!--<script src="--><?php //echo base_url('assets/adminlte/plugins/btconfirm/bt3/bootstrap-confirmation.min.js'); ?><!--"></script>-->
<!-- PACE -->
<script src="<?php echo base_url('assets/adminlte/bower_components/pace/pace.min.js'); ?>"></script>
<!-- Datepicker -->
<script src="<?php echo base_url('assets/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js'); ?>"></script>
<!-- DataTables -->
<script src="<?php echo base_url('assets/adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js'); ?>"></script>
<!-- Morris.js charts -->
<script src="<?php echo base_url('assets/adminlte/bower_components/raphael/raphael.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/adminlte/bower_components/morris.js/morris.min.js'); ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url('assets/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); ?>"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url('assets/adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js'); ?>"></script>
<!-- iCheck -->
<script src="<?php echo base_url('assets/adminlte/plugins/iCheck/icheck.js'); ?>"></script>
<!-- FastClick -->
<script src="<?php echo base_url('assets/adminlte/bower_components/fastclick/lib/fastclick.js'); ?>"></script>
<!-- Select 2 -->
<script src="<?php echo base_url('assets/adminlte/bower_components/select2/dist/js/select2.full.js'); ?>"></script>
<!-- NOTIFY -->
<!--<script src="--><?php //echo base_url('assets/adminlte/plugins/notify/notify.js'); ?><!--"></script>-->
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/adminlte/dist/js/adminlte.min.js'); ?>"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url('assets/adminlte/dist/js/pages/dashboard.js'); ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('assets/adminlte/dist/js/demo.js'); ?>"></script>
<!-- Custom js -->
<script src="<?php echo base_url('assets/adminlte/dist/js/custom.js'); ?>"></script>
<!-- Toastr js -->
<script src="<?php echo base_url('assets/adminlte/plugins/toast-master/js/toastr.js'); ?>"></script>
<script src="<?php echo base_url('assets/adminlte/plugins/toast-master/js/toastr.custom.js'); ?>"></script>
<!-- Dropzone js -->
<script src="<?php echo base_url('assets/dropzone/resources/dropzone.js'); ?>"></script>
<!-- jQuery Confirm -->
<script src="<?php echo base_url('assets/adminlte/plugins/jConfirm/jquery-confirm.min.js'); ?>"></script>
<!-- jQuery loading mask -->
<script src="<?php echo base_url('assets/adminlte/bower_components/loadmask/jquery.mloading.js'); ?>"></script>
<script>
    function loading_mask(page,statusmask) {
        if(statusmask == "") {
            $(page).mLoading();
        } else {
            $(page).mLoading('hide');
        }
    }
</script>