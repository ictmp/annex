<?php
/**
 * Created by PhpStorm.
 * User: ICT-ADMIN
 * Date: 01/03/2019
 * Time: 11:02
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$data['pageid'] = "invreceipt";
$data['pagetitle'] = "AR Invoice (Receipt)";

$this->load->view('templates/header_plugin',$data);
$this->load->view('templates/header');
$this->load->view('templates/menu',$data);
?>
<div class="content-wrapper">
    <br><br>
    <section class="content-header">
        <h1>
            Preview
            <small>AR Invoice (Receipt)</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('mainpage'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">AR Invoice</li>
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
                                <li><a onclick="btnPrint();">Export to PDF</a></li>
                                <li class="divider"></li>
                                <li><a onclick="btnReset();">Reset all selected document</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="box-body">
                        <table id="tblInv" class="table table-bordered" style="width: 100%;">
                            <thead>
                            <tr>
                                <th style="width: 10px;"></th>
                                <th style="width: 20px;">DocNum</th>
                                <th style="width: 10px;">PostingDate</th>
                                <th>BP Description</th>
                                <th style="width: 12%;">Total Invoice</th>
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
        $("#tblInv").DataTable({
            ajax: {
                url: "<?php echo site_url('invreceiptlist'); ?>"
            },
            columns: [
                { data: "docnum", searchable: false, className: "text-center", orderable: false,
                    "render": function( data, type, full ) {
                        return "<input type='checkbox' style='width: 14px; height: 14px;' onclick='getDocnum(\"" + full['docnum'] + "\",$(this).prop(\"checked\"));'>";
                    }
                },
                { data: "docnum", className: "text-center" },
                { data: "docdate1", className: "text-center" },
                { data: "cardname" },
                { data: "doctotal", render: $.fn.dataTable.render.number(',', '.', ''), className: "text-right" },
                { data: "docdate", visible: false, searchable: false }
            ],
            processing: true,
            info: false,
            order: [
                [5, "desc"]
            ]
        });
    });

    function getDocnum(docnum,cbstatus) {
        $.post("<?php echo site_url('invdocnum'); ?>", {
            "docnum": docnum,
            "cbstatus": cbstatus
        }, function(data) {
            if(data.errmsg != "-") {
                toastError(data.errmsg,'');
            }
        }, 'json');
    }

    function btnPrint() {
        window.open("<?php echo site_url('invreceiptpdf'); ?>", '_blank');
    }

    function btnReset() {
        window.location = "<?php echo site_url('invreceipt'); ?>";
    }
</script>

<?php
$this->load->view('templates/footer_close');
?>
