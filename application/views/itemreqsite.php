<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data['pageid'] = "requestitemsite";
$data['pagetitle'] = "Request Item List";

$this->load->view('templates/header_plugin',$data);
$this->load->view('templates/header');
$this->load->view('templates/menu',$data);
?>
<style>
    #modal-itemlist .modal-dialog {width: 70%;}
    #modal-uom .modal-dialog {width: 35%;}
</style>
<div class="content-wrapper">
    <br><br>
    <section class="content-header">
        <h1>
            Request
            <small>Item List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('mainpage'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Request Item</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <input type="hidden" class="form-control" id="kodegudang" value="<?php echo $kodegudang; ?>">
                <input type="hidden" class="form-control" id="kodemenu" value="<?php echo $kodemenu; ?>">

                <div class="box box-primary">
                    <div class="box-header">
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger" style="font-weight: bold;" onclick="postingRequest()">POSTING</button>
                            <a href="<?php echo site_url('itemreqsite_xls')."/".$kodegudang; ?>" class="btn btn-success" style="width: 90px; font-weight: bold;">EXPORT</a>
                            <button type="button" class="btn btn-facebook" style="font-weight: bold;">HISTORY <span class="badge badge-light" id="badgeHistory"><?php echo $badgeHistory; ?></span></button>
                        </div>

                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-info" style="font-weight: bold;" onclick="open_masterdata()">MASTER SAP</button>
                            <button type="button" class="btn btn-warning" style="font-weight: bold;">MANUAL</button>
                        </div>
                    </div>

                    <div class="box-body">
                        <table id="tblOrderList" class="table table-bordered" style="width: 100%;">
                            <thead>
                            <tr>
                                <th style="width: 11%;">Code</th>
                                <th>Description</th>
                                <th style="width: 8%;">Qty</th>
                                <th style="width: 10%;">UOM</th>
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
                                                    <th style="width: 5% !important;">&nbsp;</th>
                                                    <th>Description</th>
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
                        <button type="button" class="btn btn-danger pull-left btn-sm" onclick="closeModalWin('ITEM')">Close</button>
                        <button type="button" class="btn btn-success btn-sm" onclick="sendSelectedItems('ITEM')">Set Selected Items</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!--modal window uom -->
        <div class="modal fade" id="modal-uom" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <h4 class="modal-title">UOM</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box box-warning">
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table id="tblUom" class="table" style="width: 100%;">
                                                <thead>
                                                <tr>
                                                    <th style="width: 10% !important;">Unit Of Measure</th>
                                                    <th style="width: 5% !important;">Content</th>
                                                    <th style="width: 5% !important;"> </th>
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
                        <button type="button" class="btn btn-danger pull-left btn-sm" onclick="closeModalWin('UOM')">Close</button>
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
        loadOrderTable();
    });

    function loadOrderTable() {
        $("#tblOrderList").dataTable({
            ajax: {
                url: "<?php echo site_url('itemreqsitelist'); ?>/" + $('#kodegudang').val(),
                type: "POST",
                error: function(jqXHR, textStatus, errorThrown) {
                    toastError(jqXHR.responseText,'');
                }
            },
            columns: [
                { data: "ItemCode", sortable: false, className: "text-center" },
                { data: "ItemName" },
                { data: "ItemCode", sortable: false, className: "text-center",
                    render: function ( data, type, full ) {
                        if( full['Qty'] == null ) {
                            var Qty = "";
                        } else {
                            var Qty = full['Qty'];
                        }

                        return "<div class=\"form-group form-group-sm\"><input class=\"form-control text-center\" id=\"qty_" + full['ItemCode'] + "\" style=\"width: 60px; font-size: 1.1em;\" value=\"" + Qty + "\" onkeyup=\"updateQty('" + full['ItemCode'] + "')\" onkeypress=\"return isNumberKey1(event);\" autocomplete=\"off\"></div>";
                    }
                },
                { data: "ItemCode", sortable: false, className: "text-center",
                    render: function ( data, type, full ) {
                        if( full['UnitPackage'] == null ) {
                            var UnitPackage = "";
                        } else {
                            var UnitPackage = full['UnitPackage'];
                        }

                        return "<div class=\"input-group input-group-sm\"><input class=\"form-control text-center\" id=\"uom_" + full['ItemCode'] + "\" style=\"width: 80px; font-size: 1.1em;\" value=\"" + UnitPackage + "\" readonly><span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-info btn-flat btn-xs\" onclick=\"open_masteruom('" + full['ItemCode'] + "')\">Select</button></span></div>";
                    }
                }
            ],
            processing: true,
            serverSide: true,
            order: [
                [1, "asc"]
            ]
        });
    }

    function open_masterdata() {
        $('#modal-itemlist').modal({backdrop: "static", keyboard: false});

        $("#tblItems").DataTable().destroy();
        $("#tblItems").dataTable({
            ajax: {
                url: "<?php echo site_url('itemreqsitelist_showitem'); ?>/" + $('#kodegudang').val(),
                type: "POST",
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                    toastError(jqXHR.responseText,'');
                }
            },
            columns: [
                { data: "ItemCode",
                    render: function ( data, type, full ) {
                        if( full['checkitem'] == "1" ) {
                            var setCheckedBox = "checked";
                        } else {
                            var setCheckedBox = "";
                        }

                        return "<input type=\"checkbox\" id=\"checkbox_" + full['ItemCode'] + "\" class=\"btn btn-xs btn-info\" value=\"Select\" onclick=\"selectItem('" + full['ItemCode'] + "','" + full['ItemName'] + "')\" style=\"width: 17px; height: 17px;\" " + setCheckedBox + ">";
                    }, className: "text-center", searchable: false, orderable: false},
                { data: "ItemName", className: "text-left" },
                { data: "ItemCode", className: "text-center" }
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

    function open_masteruom(ItemCode) {
        if( $('#qty_' + ItemCode).val() == "" ) {
            toastError('Quantity column cannot be empty','');
        } else {
            $('#modal-uom').modal({backdrop: "static", keyboard: false});

            $("#tblUom").DataTable().destroy();
            $("#tblUom").dataTable({
                ajax: {
                    url: "<?php echo site_url('itemreqsitelist_uom'); ?>/" + ItemCode
                },
                columns: [
                    { data: "unitpack", className: "text-center", sortable: false, searchable: false },
                    { data: "qtypack", className: "text-center", sortable: false, searchable: false },
                    { data: "qtypack", className: "text-center", sortable: false, searchable: false,
                        render: function ( data, type, full ) {
                            return "<button type=\"button\" class=\"btn btn-xs btn-info\" onclick=\"selectUom('" + ItemCode + "','" + full['unitpack'] + "','" + full['qtypack'] + "')\">Select</button>";
                        }
                    }
                ],
                processing: true,
                info: false,
                autoWidth: false,
                dom: 't',
                order: [
                    [2, "asc"]
                ]
            });
        }
    }

    function selectItem(ItemCode,ItemName) {
        $.post("<?php echo site_url('itemreqsitelist_selectitem'); ?>", {
            "ItemCode": ItemCode,
            "ItemName": ItemName,
            "WhsCode": $('#kodegudang').val(),
            "CheckBoxStatus": $('#checkbox_' + ItemCode).prop('checked'),
        }, function(data) {
            if(data.errmsg != "-") {
                toastError(data.errmsg,'');
            }
        }, 'json');
    }

    function selectUom(ItemCode,UnitPack,QtyPack) {
        $.post("<?php echo site_url('itemreqsitelist_selectuom'); ?>", {
            "ItemCode": ItemCode,
            "Qty": $('#qty_' + ItemCode).val(),
            "UnitPack": UnitPack,
            "QtyPack": QtyPack,
            "WhsCode": $('#kodegudang').val()
        }, function(data) {
            if(data.errmsg != "-") {
                toastError(data.errmsg,'');
            } else {
                $('#uom_' + ItemCode).val( UnitPack );
                setTimeout("sendSelectedItems('UOM')", 500);
            }
        }, 'json');
    }

    function updateQty(ItemCode) {
        var rowValue = $('#qty_' + ItemCode).val();

        $.post("<?php echo site_url('itemreqsitelist_updateqty'); ?>", {
            "ItemCode": ItemCode,
            "RowValue": rowValue,
            "WhsCode": $('#kodegudang').val()
        }, function(data) {
            if(data.errmsg != "-") {
                toastError(data.errmsg,'');
            }
        }, 'json');
    }

    function closeModalWin(ID) {
        if( ID == "ITEM" ) {
            $('#modal-itemlist').modal('hide');
        } else if( ID == "UOM" ) {
            $('#modal-uom').modal('hide');
        }
    }

    function sendSelectedItems(winID) {
        $("#tblOrderList").DataTable().destroy();
        setTimeout("loadOrderTable(); closeModalWin('" + winID + "');", 500);
    }

    function postingRequest() {
        $.confirm({
            theme: 'material',
            title: 'Confirm Posting',
            content: 'Make sure your that request list is correct, the process cannot be canceled, continue?',
            autoClose: 'cancel|9000',
            buttons: {
                confirm: {
                    btnClass: 'btn-success',
                    action: function() {
                        $.post("<?php echo site_url('itemreqsite_posting'); ?>", {
                            'MenuID': $('#kodemenu').val(),
                            'WhsCode': $('#kodegudang').val()
                        }, function(data) {
                            if(data.errmsg == "-") {
                                $('#badgeHistory').text(data.badgeHistory);

                                $("#tblOrderList").DataTable().destroy();
                                setTimeout("loadOrderTable();", 500);

                                toastSuccess('Request item successfuly send to main warehouse');
                            } else {
                                toastError(data.errmsg,'');
                            }
                        }, "json");
                    }
                },
                cancel: {
                    btnClass: 'btn-danger'
                }
            }
        });
    }
</script>

<?php
$this->load->view('templates/footer_close');
?>
