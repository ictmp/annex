<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data['pageid'] = "costingmaster";
$data['pagetitle'] = "Examination List";

$this->load->view('templates/header_plugin',$data);
$this->load->view('templates/header');
$this->load->view('templates/menu',$data);
?>
<div class="content-wrapper">
    <br><br>
    <section class="content-header">
        <h1>
            Master Data
            <small>Costing List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('mainpage'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Costing</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-7">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title" title="<?php echo $kodegudang; ?>">Order Item</h3>
                    </div>
                    <form id="form_orderitem">
                        <input type="hidden" class="form-control" id="kodegudang" value="<?php echo $kodegudang; ?>">
                        <div class="box-body">
                            <div class="col-xs-5">
                                <div class="form-group">
                                    <label for="itemcode" id="itemcode_label">ItemCode</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" id="itemcode" style="text-align: center;" readonly>
                                        <div class="input-group-btn">
                                            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">Action
                                                <span class="fa fa-caret-down"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" onclick="select_masterdata()">Search Master Data</a></li>
                                                <li><a href="#" onclick="select_manualinput()">Manual Input</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group form-group-sm">
                                    <label for="itemname" id="itemcode_label">ItemName</label>
                                    <input type="text" class="form-control" id="itemname" onkeyup="strUpper('itemname')" readonly>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="row">
                                    <div class="col-xs-2">
                                        <div class="form-group form-group-sm">
                                            <label for="qty" id="qty_label">Quantity</label>
                                            <input type="text" class="form-control" id="qty" style="text-align: center;">
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="form-group form-group-sm">
                                            <label for="qty" id="qty_label">Package</label>
                                            <select class="form-control" id="itempackage" style="width: 100%;">
                                                <option value="-" selected="selected">- Choose Package -</option>
                                                <option value="TAB">TAB</option>
                                                <option value="BTL">BTL</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="box-footer">
                        <button type="button" class="btn btn-sm btn-success" style="width: 70px;" onclick="btn_saveorder()">Save</button>
                        <button type="button" class="btn btn-sm btn-danger pull-right" style="width: 70px;" onclick="btn_cancelorder()">Cancel</button>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Order Item List</h3>
                    </div>

                    <div class="box-body">
                        <table id="tblOrderList" class="table table-bordered" style="width: 100%;">
                            <thead>
                            <tr>
                                <th style="width: 30%;">Description</th>
                                <th style="width: 30%;">Qty</th>
                                <th style="width: 7%;">Package</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xs-5">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">Order History</h3>
                    </div>

                    <div class="box-body">
                        <table id="tblOrderHistory" class="table table-bordered" style="width: 100%;">
                            <thead>
                            <tr>
                                <th style="width: 10%;">OrderNum</th>
                                <th style="width: 10%;">Date</th>
                                <th>Destination</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 7%;">User</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--modal window item list-->
        <div class="modal fade" id="modal-itemlist" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <h4 class="modal-title">Item List</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box box-warning">
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table id="tblItems" class="table" style="width: 100%;">
                                                <thead>
                                                <tr>
                                                    <th style="width: 8% !important;">&nbsp;</th>
                                                    <th style="width: 67% !important;">Description</th>
                                                    <th style="width: 10% !important;">Code</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left btn-sm" onclick="closeTblItem()">Close</button>
<!--                        <button type="button" class="btn btn-success btn-sm" onclick="updaterow()">Set Selected Items</button>-->
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </section>
</div>
<?php
$this->load->view('templates/footer');
$this->load->view('templates/footer_plugin');
?>
<script>
    $(function () {
        $("#tblOrderList").dataTable({
            ajax: {
                url: "<?php echo site_url('itemreqsitelist'); ?>/" + $('#kodegudang').val(),
                type: "POST",
                error: function(jqXHR, textStatus, errorThrown) {
                    toastError(jqXHR.responseText,'');
                }
            },
            columns: [
                { data: "ItemName" },
                { data: "ItemCode" },
                { data: "ItemCode" }
            ],
            processing: true,
            serverSide: true,
            order: [
                [0, "desc"]
            ]
        });
    });

    //function btn_cancelorder() {
    //    $("#tblOrderList").DataTable().destroy();
    //    $("#tblOrderList").dataTable({
    //        ajax: {
    //            url: "<?php //echo site_url('itemreqsitelist'); ?>///" + $('#kodegudang').val(),
    //            type: "POST",
    //            error: function(jqXHR, textStatus, errorThrown) {
    //                toastError(jqXHR.responseText,'');
    //            }
    //        },
    //        columns: [
    //            { data: "ItemName" },
    //            { data: "ItemCode" },
    //            { data: "ItemCode" }
    //        ],
    //        processing: true,
    //        serverSide: true,
    //        order: [
    //            [0, "desc"]
    //        ]
    //    });
    //}

    function select_masterdata() {
        $('#itemname').prop('readonly', true);
        $('#itempackage').prop('disabled', false);

        $('#modal-itemlist').modal({backdrop: "static", keyboard: false});

        $("#tblItems").DataTable().destroy();
        $("#tblItems").dataTable({
            ajax: {
                url: "<?php echo site_url('itemreqsitelist_showitem'); ?>",
                type: "POST",
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                    toastError(jqXHR.responseText,'');
                }
            },
            columns: [
                { data: "ItemCode",
                    render: function ( data, type, full ) {
                        return "<input type=\"button\" id=\"btnitem_" + full['ItemCode'] + "\" class=\"btn btn-xs btn-info\" value=\"Select\" onclick=\"selectItem('" + full['ItemCode'] + "')\">";
                    }, className: "text-center", searchable: false, orderable: false},
                { data: "ItemName", className: "text-left" },
                { data: "ItemCode", className: "text-center", className: "" }
            ],
            processing: true,
            serverSide: true,
            info: false,
            autoWidth: false,
            dom: 'ftp',
            order: [
                [1, "asc"]
            ]
        });
    }

    function selectItem(ItemCode) {
        $.post("<?php echo site_url('itemreqsitelist_selectitem'); ?>", {
            "ItemCode": ItemCode,
            "WhsCode": $('#kodegudang').val()
        }, function(data) {
            if(data.errmsg == "-") {

            }  else {

            }
        }, ',json');
    }

    function closeTblItem() {
        $('#modal-itemlist').modal('hide');
    }

    function select_manualinput() {
       $('#itemname').prop('readonly', false);
       $('#itempackage').prop('disabled', true);
       $('#itemname').focus();
    }
</script>

<?php
$this->load->view('templates/footer_close');
?>
