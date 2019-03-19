<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data['pageid'] = "approval_requestitemsite_detail";
$data['pagetitle'] = "Approval Request Detail";

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
            Approval
            <small>Request Item</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('mainpage'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('apvreqsite'); ?>/<?php echo $kodemenu; ?>">Approval Request Item</a></li>
            <li class="active">Approval Request Detail</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <input type="hidden" class="form-control" id="kodemenu" value="<?php echo $kodemenu; ?>">

                <div class="box box-primary">
                    <div class="box-body">
                        <table id="tblReqList" class="table table-bordered" style="width: 100%;">
                            <thead>
                            <tr>
                                <th style="width: 20%;">#Req.</th>
                                <th>Site</th>
                                <th style="width: 8%;">Req.Date</th>
                                <th style="width: 10%;">Status</th>
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
        loadReqTable();
    });

    function loadReqTable() {
        $("#tblReqList").dataTable({
            ajax: {
                url: "<?php echo site_url('apvreqsitelist'); ?>",
                type: "POST",
                error: function(jqXHR, textStatus, errorThrown) {
                    toastError(jqXHR.responseText,'');
                }
            },
            columns: [
                { data: "internalcodenumber", sortable: false, className: "text-center" },
                { data: "namagudang" },
                { data: "tRequest", className: "text-center" },
                { data: "regjournal", className: "text-center", sortable: false,
                    render: function ( data, type, full ) {
                        return "<a href='#' class='btn btn-xs btn-info'>status</a>";
                    }
                },
                { data: "transactdate", visible: false }
            ],
            processing: true,
            serverSide: true,
            order: [
                [4, "desc"]
            ]
        });
    }

    //function open_masterdata() {
    //    $('#modal-itemlist').modal({backdrop: "static", keyboard: false});
    //
    //    $("#tblItems").DataTable().destroy();
    //    $("#tblItems").dataTable({
    //        ajax: {
    //            url: "<?php //echo site_url('itemreqsitelist_showitem'); ?>///" + $('#kodegudang').val(),
    //            type: "POST",
    //            error: function(jqXHR, textStatus, errorThrown) {
    //                console.log(jqXHR.responseText);
    //                toastError(jqXHR.responseText,'');
    //            }
    //        },
    //        columns: [
    //            { data: "ItemCode",
    //                render: function ( data, type, full ) {
    //                    if( full['checkitem'] == "1" ) {
    //                        var setCheckedBox = "checked";
    //                    } else {
    //                        var setCheckedBox = "";
    //                    }
    //
    //                    return "<input type=\"checkbox\" id=\"checkbox_" + full['ItemCode'] + "\" class=\"btn btn-xs btn-info\" value=\"Select\" onclick=\"selectItem('" + full['ItemCode'] + "','" + full['ItemName'] + "')\" style=\"width: 17px; height: 17px;\" " + setCheckedBox + ">";
    //                }, className: "text-center", searchable: false, orderable: false},
    //            { data: "ItemName", className: "text-left" },
    //            { data: "ItemCode", className: "text-center" }
    //        ],
    //        processing: true,
    //        serverSide: true,
    //        info: false,
    //        autoWidth: false,
    //        dom: 'ftp',
    //        order: [
    //            [1, "asc"]
    //        ]
    //    });
    //}
    //
    //function open_masteruom(ItemCode) {
    //    if( $('#qty_' + ItemCode).val() == "" ) {
    //        toastError('Quantity column cannot be empty','');
    //    } else {
    //        $('#modal-uom').modal({backdrop: "static", keyboard: false});
    //
    //        $("#tblUom").DataTable().destroy();
    //        $("#tblUom").dataTable({
    //            ajax: {
    //                url: "<?php //echo site_url('itemreqsitelist_uom'); ?>///" + ItemCode
    //            },
    //            columns: [
    //                { data: "unitpack", className: "text-center", sortable: false, searchable: false },
    //                { data: "qtypack", className: "text-center", sortable: false, searchable: false },
    //                { data: "qtypack", className: "text-center", sortable: false, searchable: false,
    //                    render: function ( data, type, full ) {
    //                        return "<button type=\"button\" class=\"btn btn-xs btn-info\" onclick=\"selectUom('" + ItemCode + "','" + full['unitpack'] + "','" + full['qtypack'] + "')\">Select</button>";
    //                    }
    //                }
    //            ],
    //            processing: true,
    //            info: false,
    //            autoWidth: false,
    //            dom: 't',
    //            order: [
    //                [2, "asc"]
    //            ]
    //        });
    //    }
    //}

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
