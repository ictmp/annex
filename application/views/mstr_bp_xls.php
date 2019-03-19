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
        <td class="tdHeader">Code</td>
        <td class="tdHeader">Name</td>
        <td class="tdHeader">Address</td>
        <td class="tdHeader">City</td>
        <td class="tdHeader">ZipCode</td>
        <td class="tdHeader">Phone</td>
        <td class="tdHeader">Contact</td>
        <td class="tdHeader">Email</td>
    </tr>
    <?php
    $no = 0;
    foreach ($bplist->result() as $row) {
        $no += 1;
    ?>
        <tr class="trDetail">
            <td align="center"><?php echo $no; ?></td>
            <td align="center"><?php echo $row->cardcode; ?></td>
            <td><?php echo $row->cardname; ?></td>
            <td><?php echo $row->address; ?></td>
            <td align="center"><?php echo $row->city; ?></td>
            <td align="center"><?php echo $row->zipcode; ?></td>
            <td align="center"><?php echo $row->phone1; ?></td>
            <td><?php echo $row->cntctprsn; ?></td>
            <td><?php echo $row->E_mail; ?></td>
        </tr>
    <?php
    }
    ?>
</table>
