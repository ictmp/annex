<?php
/**
 * Created by PhpStorm.
 * User: ICT-ADMIN
 * Date: 28/02/2019
 * Time: 11:23
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$data['pageid'] = "masterbp";
$data['pagetitle'] = "Master Business Partner";

$this->load->view('templates/header_plugin',$data);
$this->load->view('templates/header');
$this->load->view('templates/menu',$data);
?>
<div class="content-wrapper">
    <br><br>
    <section class="content-header">
        <h1>
            Master Data
            <small>Business Partner List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('mainpage'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Business Partner</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-warning">Actions</button>
                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo site_url('masterbplist_xls'); ?>">Export to excel</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="box-body">
                        <table id="tblBp" class="table table-bordered" style="width: 100%;">
                            <thead>
                            <tr>
                                <th style="width: 10%;">Code</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th style="width: 15%;">City</th>
                                <th style="width: 10%;">Phone</th>
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
        $("#tblBp").DataTable({
            ajax: {
                url: "<?php echo site_url('masterbplist'); ?>"
            },
            columns: [
                { data: "cardcode", className: "text-center", orderable: false },
                { data: "cardname" },
                { data: "address" },
                { data: "city", className: "text-center" },
                { data: "phone1", className: "text-center", orderable: false }
            ],
            processing: true,
            info: false,
            order: [
                [1, 'asc']
            ]
        });
    });
</script>

<?php
$this->load->view('templates/footer_close');
?>
