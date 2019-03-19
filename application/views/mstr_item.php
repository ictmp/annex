<?php
/**
 * Created by PhpStorm.
 * User: ICT-ADMIN
 * Date: 28/02/2019
 * Time: 11:23
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$data['pageid'] = "masteritem";
$data['pagetitle'] = "Master Items";

$this->load->view('templates/header_plugin',$data);
$this->load->view('templates/header');
$this->load->view('templates/menu',$data);
?>
<div class="content-wrapper">
    <br><br>
    <section class="content-header">
        <h1>
            Master Data
            <small>Item List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('mainpage'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Item</li>
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
                                <li><a onclick="btnPrint();">Export to excel</a></li>
                                <li class="divider"></li>
                                <li><a onclick="btnDownload();">Update from SAP</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="box-body">
                        <table id="tblItems" class="table table-bordered" style="width: 100%;">
                            <thead>
                            <tr>
                                <th>Description</th>
                                <th style="width: 10%;">Purchasing</th>
                                <th style="width: 6%;">Content</th>
                                <th style="width: 10%;">Inventory</th>
                                <th style="width: 10%;">Code</th>
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
        $("#tblItems").dataTable({
            ajax: {
                url: "<?php echo site_url('masteritemlist'); ?>",
                type: "POST",
                error: function(jqXHR, textStatus, errorThrown) {
                    toastError(jqXHR.responseText,'');
                }
            },
            columns: [
                { data: "ItemName" },
                { data: "PurcUOM", searchable: false, className: "text-center" },
                { data: "Content", searchable: false, className: "text-center" },
                { data: "InvUOM", searchable: false, className: "text-center" },
                { data: "ItemCode", searchable: false, className: "text-center" }
            ],
            processing: true,
            serverSide: true,
            info: true,
            order: [
                [0, "asc"]
            ]
        });
    });

    function btnDownload() {
        loading_mask(".content-wrapper","");

        $.post("masteritemdnl", {
            "test": "-"
        }, function(data) {
            loading_mask(".content-wrapper","hide");

            if( data.downloadstatus == "-" ) {
                toastSuccess('Download Success','');

                $("#tblItems").DataTable().destroy();
                $("#tblItems").dataTable({
                    ajax: {
                        url: "<?php echo site_url('masteritemlist'); ?>",
                        type: "POST",
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastError(jqXHR.responseText,'');
                        }
                    },
                    columns: [
                        { data: "ItemName" },
                        { data: "PurcUOM", searchable: false, className: "text-center" },
                        { data: "Content", searchable: false, className: "text-center" },
                        { data: "InvUOM", searchable: false, className: "text-center" },
                        { data: "ItemCode", searchable: false, className: "text-center" }
                    ],
                    processing: true,
                    serverSide: true,
                    info: true,
                    order: [
                        [0, "asc"]
                    ]
                });
                $("#tblItems").dataTable().clear().draw();
            } else {
                toastError(data.downloadstatus,'');
            }
        }, "json");
    }
</script>

<?php
$this->load->view('templates/footer_close');
?>
