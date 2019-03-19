<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data['pageid'] = "approval_requestitemsite";
$data['pagetitle'] = "Approval Request Item";

$this->load->view('templates/header_plugin',$data);
$this->load->view('templates/header');
$this->load->view('templates/menu',$data);
?>
<style>
    /*#modal-itemlist .modal-dialog {width: 50%;}*/
    /*#modal-uom .modal-dialog {width: 35%;}*/
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
            <li class="active">Approval Request Item</li>
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
                                <th style="width: 7%;">Status</th>
                                <th style="width: 20%;">#Req.</th>
                                <th>Site</th>
                                <th style="width: 8%;">Req.Date</th>
                                <th style="width: 10%;">Detail</th>
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
                        <h4 class="modal-title">Request Detail</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box box-warning">
                                    <div class="box-header with-border">
                                        <div class="form-group">
                                            <label>#Request</label>
                                            <input type="text" class="form-control input-sm" id="reqnumber" readonly>
                                            <input type="hidden" id="regjournal">
                                        </div>
                                        <div class="form-group">
                                            <label>Site</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control input-sm" id="namasite" readonly>
                                                <span class="input-group-addon" id="kodesite"></span>
                                            </div>
                                        </div>
                                    </div>
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
                { data: "regjournal", className: "text-center", sortable: false,
                    render: function ( data, type, full ) {
                        $.post("<?php echo site_url('apvreqsitelist_check'); ?>", {
                            regjournal: data
                        }, function(data) {
                            alert(data.test);
                        }, "json");

                        return type;
                    }
                },
                { data: "internalcodenumber", sortable: false, className: "text-center" },
                { data: "namagudang" },
                { data: "tRequest", className: "text-center" },
                { data: "regjournal", className: "text-center", sortable: false,
                    render: function ( data, type, full ) {
                        return "<a href=\"#\" class=\"btn btn-xs btn-success\" onclick=\"openDetail('" + full['regjournal'] + "')\">Detail</a>";
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

    function openDetail(regjournal) {
        $.post("<?php echo site_url('apvreqsite_getdetail'); ?>", {
            "regjournal": regjournal
        }, function(data) {
             if(data.errmsg == "-") {
                 $('#modal-itemlist').modal({backdrop: "static", keyboard: false});

                 $('#regjournal').val(regjournal);
                 $('#reqnumber').val(data.reqnumber);
                 $('#namasite').val(data.namasite);
                 $('#kodesite').text(data.kodesite);

                $("#tblItems").DataTable().destroy();
                $("#tblItems").dataTable({
                    ajax: {
                        url: "<?php echo site_url('apvreqsite_showitem'); ?>",
                        type: "POST",
                        data: {
                            regjournal: regjournal
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastError(jqXHR.responseText,'');
                        }
                    },
                    columns: [
                        { data: "itemcode",
                            render: function ( data, type, full ) {
                                if( full['checkitem'] == "1" ) {
                                    var setCheckedBox = "checked";
                                } else {
                                    var setCheckedBox = "";
                                }

                                return "<input type=\"checkbox\" id=\"checkbox_" + full['itemcode'] + "\" class=\"btn btn-xs btn-info\" value=\"Select\" onclick=\"selectItem('" + full['recid'] + "')\" style=\"width: 17px; height: 17px;\" " + setCheckedBox + ">";
                            }, className: "text-center", searchable: false, orderable: false},
                        { data: "itemname", className: "text-left" },
                        { data: "itemcode", className: "text-center" }
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
            } else {
                toastError(data.errmsg,'');
            }
        }, "json");
    }

    function selectItem(recid) {
        $.post("<?php echo site_url('apvreqsite_selectitem'); ?>", {
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
