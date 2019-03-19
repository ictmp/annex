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
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Fixed Costing</h3>
                    </div>
                    <form id="form_fixedcosting">
                        <div class="box-body">
                            <?php
                            foreach ($fixedcostdata as $row):
                                ?>
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1" id="fixedcost_label"><?php echo $row->itemdesc; ?></label>
                                        <input type="email" class="form-control" id="<?php echo $row->itemcode; ?>" value="<?php echo number_format($row->costing); ?>" style="text-align: right;" onkeypress="return isNumberKey1(event);" onkeyup="set_numberFormat('<?php echo $row->itemcode ?>')">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </form>

                    <div class="box-footer">
                        <button type="button" class="btn btn-sm btn-success pull-right" onclick="savefixedcost()">Save Changes</button>
                    </div>
                </div>
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Costing by Examination List</h3>

                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-sm btn-warning">Export to</button>
                            <button type="button" class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo site_url('costingmasterlist_xls'); ?>">Excel</a></li>
                            </ul>
                        </div>

                    </div>

                    <div class="box-body">
                        <table id="tblCosting" class="table table-bordered" style="width: 100%;">
                            <thead>
                            <tr>
                                <th style="width: 30%;">Medical Description</th>
                                <th style="width: 30%;">Publish Description</th>
                                <th style="width: 7%;">Price</th>
                                <th style="width: 8%;">Costing</th>
                                <th style="width: 8%;">Update</th>
                                <th style="width: 8%;">Setup</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-setcosting" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-header-primary">
                        <h4 class="modal-title">Setup Cost</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <form role="form" id="setupcosting">
                                    <div class="form-group">
                                        <label>Medical Description</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="description" readonly>
                                            <span class="input-group-addon" id="itemcode"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Publish Description</label>
                                        <input type="text" class="form-control" id="publishdesc" onkeyup="strUpper('publishdesc')">
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <label>Price</label>
                                                <input type="text" class="form-control text-right" id="price" readonly>
                                            </div>
                                            <div class="col-xs-6">
                                                <label>Cost</label>
                                                <input type="text" class="form-control text-right" id="cost" onkeypress="return isNumberKey1(event);" onkeyup="set_numberFormat('cost')">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Linked Items</label>
                                        <select class="form-control" name="linkeditem" id="linkeditem" multiple="multiple" data-placeholder="Select items to be linked" style="width: 100%;"></select>
                                    </div>
                                    <div class="form-group">
                                        <label>UnLinked Items</label>
                                        <select class="form-control" name="unlinkeditem" id="unlinkeditem" multiple="multiple" data-placeholder="Select items to be unlinked" style="width: 100%;"></select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left btn-sm" onclick="closeSetupModal()">CLOSE</button>
                        <button type="button" class="btn btn-success btn-sm" onclick="updaterow()">SAVE CHANGES</button>
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
        $('#linkeditem').select2();
        // $('#linkeditem').append("<option value=\"3620194\" selected=\"selected\">select2/select2</option>");
        // $('#linkeditem').select2().val(["3620194"]).trigger("change"); === ok ===
        //
        $('#linkeditem').select2({
            placeholder: 'select',
            ajax: {
                url: '<?php echo site_url('loadlinkeditem'); ?>',
                type: 'post',
                dataType: 'json',
                data: function (params) {
                    var query = {
                        search: params.term,
                        itemcode: $('#itemcode').text()
                    };

                    return query;
                }
            }
        });

        $("#tblCosting").dataTable({
            "ajax": {
                "url": "<?php echo site_url('costingmasterlist'); ?>",
                "type": "POST",
                "error": function(jqXHR, textStatus, errorThrown) {
                    toastError(jqXHR.responseText,'');
                }
            },
            "columns": [
                {"data": "nama_barang"},
                {"data": "keterangan", "searchable": false,
                    "render": function( data, type, full ) {
                        return "<span id=\"rowPublishDesc_" + full['kodeitem'] + "\">" + full['keterangan'] + "</span>";
                    }},
                {"data": "tarif", render: $.fn.dataTable.render.number(',', '.', ''), "className": "text-right"},
                {"data": "kodeitem", render: $.fn.dataTable.render.number(',', '.', ''),
                    "render": function ( data, type, full ) {
                        return "<span id=\"rowCosting_" + full['kodeitem'] + "\">" + formatCurrency(full['costing']) + "</span>";
                    }, "className": "text-right"},
                {"data": "updatedate", "searchable": false, "className": "text-center"},
                {"data": "kodeitem",
                    "render": function ( data, type, full ) {
                        return "<a class=\"btn btn-xs btn-primary\" onclick=\"editrow('" + full['kodeitem'] + "')\"><i class=\"fa fa-edit\"></i> Setup</a>";
                    }, "className": "text-center", "searchable": false, "orderable": false
                },
                {"data": "updatetime", "visible": false, "searchable": false},
                {"data": "publishdesc", "visible": false}
            ],
            "processing": true,
            "serverSide": true,
            "order": [
                [3, "desc"],[5, "desc"]
            ]
        });
    });

    function editrow(kodeitem) {
        $.post("<?php echo site_url('costingmasteredit'); ?>", {
            "kodeitem": kodeitem
        }, function (data) {
            if(data.errmsg == "-") {
                $('#modal-setcosting').modal({backdrop: "static", keyboard: false});
                $('#itemcode').text(kodeitem);
                $('#description').val(data.description);
                $('#publishdesc').val(data.publishdesc);
                $('#price').val(data.price);
                $('#cost').val(data.costing);

                $('#linkeditem').select2();
                $('#linkeditem').select2().val(null).trigger('change');
                $.each(data.linkeditem, function (i, val) {
                    $('#linkeditem').append('<option value="' + val.kodeitem + '">' + val.nama_barang + '</option>');
                });

                linkeditem_data = data.linkeditem_data.split(",");
                arr_linkeditem = [];
                $.each(linkeditem_data, function(index, value) {
                    arr_linkeditem.push(value);
                });

                $('#linkeditem').select2().val(arr_linkeditem).trigger("change");

                $('#unlinkeditem').select2();
                $('#unlinkeditem').select2().val(null).trigger('change');
                $.each(data.linkeditem, function (i, val) {
                    $('#unlinkeditem').append('<option value="' + val.kodeitem + '">' + val.nama_barang + '</option>');
                });

                unlinkeditem_data = data.unlinkeditem_data.split(",");
                arr_unlinkeditem = [];
                $.each(unlinkeditem_data, function(index, value) {
                    arr_unlinkeditem.push(value);
                });

                $('#unlinkeditem').select2().val(arr_unlinkeditem).trigger("change");
            } else {
                toastError(data.errmsg,'');
            }
        }, "json");
    }

    function updaterow() {
        if($('#cost').val() == "") {
            toastError('Costing field cannot be empty','');
            $('#cost').focus();
        } else {
            $.post("<?php echo site_url('costingmasterupdate'); ?>", {
                "itemcode": $('#itemcode').text(),
                "publishdesc": $('#publishdesc').val(),
                "costing": filterNum($('#cost').val()),
                "linkeditem": $('#linkeditem').val(),
                "unlinkeditem": $('#unlinkeditem').val()
            }, function (data) {
                if(data.errmsg == "-") {
                    toastSuccess('Update success','');
                    $('#rowPublishDesc_' + $('#itemcode').text()).text($('#publishdesc').val());
                    $('#rowCosting_' + $('#itemcode').text()).text(formatCurrency($('#cost').val()));

                    setTimeout("closeSetupModal()", 500);
                } else {
                    toastError(data.errmsg,'');
                }
            }, "json");
        }
    }

    function closeSetupModal() {
        $('#linkeditem').empty().trigger('change');
        $('#unlinkeditem').empty().trigger('change');

        $('#modal-setcosting').modal('hide');
    }

    function savefixedcost() {
        $.post("<?php echo site_url('costingfixedcost'); ?>", {
            "renovcost": filterNum($('#renovcost').val()),
            "admcost": filterNum($('#admcost').val()),
            "medreccost": filterNum($('#medreccost').val()),
            "delivercost": filterNum($('#delivercost').val()),
            "laundrycost": filterNum($('#laundrycost').val())
        }, function (data) {
            if(data.errmsg == "-") {
                toastSuccess('Update success','');
            } else {
                toastError(data.errmsg,'');
            }
        }, 'json');
    }
</script>

<?php
$this->load->view('templates/footer_close');
?>
