<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Loginpage</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 4.0.0 (css) -->
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-4.0.0/dist/css/bootstrap.css'); ?>">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-4.0.0/dist/css/iofrm-style.css'); ?>">
</head>
<body>
<div class="form-body">
    <div class="row">
        <div class="img-holder">
            <div class="bg"></div>
            <div class="info-holder"></div>
        </div>
        <div class="form-holder">
            <div class="form-content">
                <div class="form-items">
                    <img src="assets/bootstrap-4.0.0/dist/img/mp2016.jpg" width="260px" height="100px">
                    <br><br><br>
                    <p><h3>Please Sign In</h3></p>
                    <form id="form_login" style="margin: 0; padding: 0;" autocomplete="off">
                        <input class="form-control-sm" type="text" name="username" placeholder="username" required>
                        <input class="form-control-sm" type="password" name="password" placeholder="password" required>
                    </form>
                    <div class="form-button">
                        <button type="submit" id="btnLogin" class="ibtn" onclick="loginprocess()">Login</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery 3.3.1 -->
<script src="<?php echo base_url('assets/bootstrap-4.0.0/jquery-3.3.1.js') ?>"></script>
<!-- Bootstrap 4.0.0 -->
<script src="<?php echo base_url('assets/bootstrap-4.0.0/dist/js/bootstrap.js'); ?>"></script>
<script>
    function loginprocess() {
        $.ajax({
            url: "<?php echo site_url('accountcheck'); ?>",
            type: "POST",
            dataType: "json",
            data: $('#form_login').serialize(),
            success: function(data) {
                if(data.errmsg == "-") {
                    window.location.href = "<?php echo site_url('mainpage'); ?>";
                } else {
                    alert(data.errmsg);
                }
            }
        }); return false;
    }
</script>
</body>
</html>
