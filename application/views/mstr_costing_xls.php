<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Expires: Mon, 26 Jul 2006 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Disposition: attachment; filename=master_costing.xls");
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
<table align="center" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="tdHeader">No</td>
        <td class="tdHeader">Publish Description</td>
        <td class="tdHeader">Costing Description</td>
        <td class="tdHeader">Publish Price</td>
        <td class="tdHeader">Costing</td>
    </tr>
    <?php
    $no = 0;
    foreach ($costinglist->result() as $row) {
        $no += 1;
    ?>
        <tr class="trDetail">
            <td align="center"><?php echo $no; ?></td>
            <td><?php echo $row->nama_barang; ?></td>
            <td><?php echo $row->keterangan; ?></td>
            <td align="right"><?php echo number_format($row->tarif); ?></td>
            <td align="right"><?php echo number_format($row->costing); ?></td>
        </tr>
    <?php
    }
    ?>
</table>
