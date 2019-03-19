<?php
/**
 * Created by PhpStorm.
 * User: ICT-ADMIN
 * Date: 28/02/2019
 * Time: 11:23
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$data['pageid'] = "masteritembatch";
$data['pagetitle'] = "Master Batch";

$this->load->view('templates/header_plugin',$data);
$this->load->view('templates/header');
$this->load->view('templates/menu',$data);
?>
<div class="content-wrapper">
    <br><br>
    <section class="content-header">
        <h1>
            Master Data
            <small>Item Batch List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('mainpage'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Item Batch</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-10">
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
                        <table id="tblItemBatch" class="table table-bordered" style="width: 100%;">
                            <thead>
                            <tr>
                                <th style="width: 10%;">Code</th>
                                <th>Description</th>
                                <th style="width: 7%;">Seq.</th>
                                <th style="width: 10%;">BatchID</th>
                                <th style="width: 10%;">MnfSerial</th>
                                <th style="width: 10%;">Exp.Date</th>
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
        $("#tblItemBatch").dataTable({
            ajax: {
                url: "<?php echo site_url('masteritembatchlist'); ?>",
                type: "POST",
                error: function(jqXHR, textStatus, errorThrown) {
                    toastError(jqXHR.responseText,'');
                }
            },
            columns: [
                { data: "itemcode", className: "text-center" },
                { data: "itemname" },
                { data: "sysnumber", className: "text-center", searchable: false },
                { data: "distnumber", className: "text-center" },
                { data: "mnfserial", className: "text-center" },
                { data: "expireddate", className: "text-center" }
            ],
            processing: true,
            serverSide: true,
            info: true,
            order: [
                [0, "asc"],
                [2, "asc"]
            ]
        });
    });

    function btnDownload() {
        loading_mask(".content-wrapper","");

        $.post("masteritembatchdnl", {
            "test": "-"
        }, function(data) {
            loading_mask(".content-wrapper","hide");

            if( data.errmsg == "-" ) {
                toastSuccess('Update Success, ' + data.errmsg + ' items successfully inserted','');

                $("#tblItemBatch").DataTable().destroy();
                $("#tblItemBatch").dataTable({
                    ajax: {
                        url: "<?php echo site_url('masteritembatchlist'); ?>",
                        type: "POST",
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastError(jqXHR.responseText,'');
                        }
                    },
                    columns: [
                        { data: "itemcode", className: "text-center" },
                        { data: "itemname" },
                        { data: "sysnumber", className: "text-center", searchable: false },
                        { data: "distnumber", className: "text-center" },
                        { data: "mnfserial", className: "text-center" },
                        { data: "expireddate", className: "text-center", searchable: false }
                    ],
                    processing: true,
                    serverSide: true,
                    info: true
                });
                $("#tblItemBatch").dataTable().clear().draw();
            } else {
                toastError(data.errmsg,'');
            }
        }, "json");
    }
</script>
<?php
$this->load->view('templates/footer_close');
?>
