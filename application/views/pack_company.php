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
            <small>Company List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('mainpage'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Company</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <table id="tblCompany" class="table table-bordered" style="width: 100%;">
                            <thead>
                            <tr>
                                <th>Description</th>
                                <th style="width: 11%;">Package</th>
                            </tr>
                            </thead>
                        </table>
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
        $("#tblCompany").dataTable({
            "ajax": {
                "url": "<?php echo site_url('packcompanylist'); ?>",
                "type": "POST",
                "error": function(jqXHR, textStatus, errorThrown) {
                    $.notify(jqXHR.responseText, {className: "error"});
                }
            },
            "columns": [
                {"data": "companyname"},
                {"data": "selectbtn",
                    "orderable": false,
                    "className": "text-center",
                    "searchable": false,
                    "render": function ( data, type, full ) {
                        return "<a href=\"<?php echo site_url(); ?>packdescription/" + btoa(full['companyid']) + "\" class=\"btn btn-xs btn-primary\">Create</a>" +
                            " <a href=\"<?php echo site_url(); ?>packlist/" + btoa(full['companyid']) + "\" class=\"btn btn-xs btn-success\">View</a>";
                    }
                }
            ],
            "processing": true,
            "serverSide": true,
            "order": [0, "asc"]
        });
    });

</script>

<?php
$this->load->view('templates/footer_close');
?>
