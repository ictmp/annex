<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Expires: Mon, 26 Jul 2006 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Disposition: attachment; filename=itemreq_gudang.xls");
header("Content-Type: application/octet-stream");
?>
<style>
    .tdHeader {
        background-color: #0b58a2;
        color: white;
        font-family: "Courier New", Courier, monospace;
        font-weight: bold;
        font-size: 10pt;
        text-align: center;
    }

    .trDetail {
        font-family: "Courier New", Courier, monospace;
        font-size: 10pt;
    }
</style>
<table align="center" border="0" cellpadding="1" cellspacing="1">
    <tr>
        <td colspan="2">Gudang Tujuan</td>
        <td colspan="3">: <b><?php echo $reqtujuan; ?></b></td>
    </tr>
    <tr>
        <td colspan="2">Gudang Asal</td>
        <td colspan="3">: <b><?php echo $reqasal; ?></b></td>
    </tr>
    <tr>
        <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
        <td class="tdHeader">No</td>
        <td class="tdHeader">ItemCode</td>
        <td class="tdHeader">ItemName</td>
        <td class="tdHeader">Qty</td>
        <td class="tdHeader">UOM</td>
    </tr>
    <?php
    $no = 0;
    foreach ($reqdetail->result() as $row) {
        $no += 1;
    ?>
        <tr class="trDetail" style="border-bottom: #89cdef;">
            <td align="center"><?php echo $no; ?></td>
            <td align="center"><?php echo $row->kodeitem; ?></td>
            <td><?php echo $row->namaitem; ?></td>
            <td align="center"><?php echo $row->jumlah; ?></td>
            <td align="center"><?php echo $row->keteranganunit; ?></td>
        </tr>
    <?php
    }
    ?>
</table>
