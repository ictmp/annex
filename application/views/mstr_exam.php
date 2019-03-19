<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data['pageid'] = "masterexam";
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
            <small>Examination List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('mainpage'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Examination</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <table id="tblExam" class="table table-bordered" style="width: 100%;">
                            <thead>
                            <tr>
                                <th>Description</th>
                                <th style="width: 17%;">Category</th>
                                <th style="width: 10%;">Price</th>
                                <th style="width: 10%;">Costing</th>
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
        $("#tblExam").dataTable({
            "ajax": {
                "url": "<?php echo site_url('exammasterlist'); ?>",
                "type": "POST",
                "error": function(jqXHR, textStatus, errorThrown) {
                    toastError(jqXHR.responseText,'');
                }
            },
            "columns": [
                {"data": "nama_barang"},
                {"data": "description", "className": "text-center"},
                {"data": "tarif", render: $.fn.dataTable.render.number(',', '.', ''), "className": "text-right"},
                {"data": "id_barang",
                    "render": function ( data, type, full ) {
                        if(full['statusdata'] == "0") {
                            btnType = "btn-primary";
                            iconType = "<i class=\"fa fa-edit\"></i> Add";
                        } else if(full['statusdata'] == "1") {
                            btnType = "btn-danger";
                            iconType = "<i class=\"fa fa-bitbucket\"></i> Remove";
                        }

                        return "<a class=\"btn btn-xs " + btnType + "\" id=\"btnCosting_" + full['id_barang'] + "\" onclick=\"updateBtn('" + full['id_barang'] + "')\">" + iconType + "</a>";
                    }, "className": "text-center"
                }
            ],
            "processing": true,
            "serverSide": true,
            "order": [0, 'asc']
        });
    });

    function updateBtn(itemcode) {
        $.post("<?php echo site_url('exammasterupdate'); ?>", {
            "itemcode": itemcode
        }, function (data) {
            if(data.errmsg == "-") {
                if(data.statusdata == "0") {
                    iconType = "<i class=\"fa fa-edit\"></i> Add";
                    $('#btnCosting_' + itemcode).removeClass('btn-danger').addClass('btn-primary').html(iconType);
                } else if(data.statusdata == "1") {
                    iconType = "<i class=\"fa fa-bitbucket\"></i> Remove";
                    $('#btnCosting_' + itemcode).removeClass('btn-primary').addClass('btn-danger').html(iconType);
                }
            } else {
                toastError(data.errmsg,'');
            }
        }, 'json');
    }
</script>

<?php
$this->load->view('templates/footer_close');
?>
