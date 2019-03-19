<?php
/**
 * Created by PhpStorm.
 * User: ICT-ADMIN
 * Date: 12/02/2019
 * Time: 09:44
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Test Email Body</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
</head>
<body>

<table align="center" border="0" cellpadding="5" cellspacing="5" width="60%">
    <tr>
        <td colspan="3" style="text-align: left; padding: 20px;">
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td>
                        <span style="font-weight: bold; font-size: 2em;">Autoemail Persetujuan</span>
                        <br>
                        <span style=" font-size: 1.5em;">Aktivasi Paket MCU</span>
                    </td>
                    <td align="right">
                        <?php echo $logo; ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="background-color: #89cdef;">
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td width="120px">
            <label>Perusahaan</label>
        </td>
        <td width="3px"><label>:</label></td>
        <td><?php echo $companyname; ?></td>
    </tr>
    <tr>
        <td>
            <label>Active Period</label>
        </td>
        <td><label>:</label></td>
        <td><?php echo $activeperiode; ?></td>
    </tr>
    <tr>
        <td>
            <label>Term of Payment</label>
        </td>
        <td><label>:</label></td>
        <td><?php echo $termofpayment; ?></td>
    </tr>
    <tr>
        <td>
            <label>PIC Marketing</label>
        </td>
        <td><label>:</label></td>
        <td><?php echo $picmarketing; ?></td>
    </tr>
    <tr>
        <td colspan="3">
            <table align="center" width="100%" border="0" cellpadding="3" cellspacing="1">
                <tr style="background-color: #0b3e6f; color: white;">
                    <td align="center" width="6%">No</td>
                    <td align="center" width="75%">Paket</td>
                    <td align="center" colspan="2">Harga</td>
                </tr>
                <?php
                $no = 0;
                foreach ($detailpackage as $row) {
                    $no += 1;
                    ?>
                    <tr>
                        <td align="center"><?php echo $no; ?></td>
                        <td><?php echo $row->packagename; ?></td>
                        <td align="left" width="5%">Rp.</td>
                        <td align="right"><?php echo number_format($row->totalprice); ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr style="background-color: #0b3e6f; color: white;">
                    <td align="center"></td>
                    <td align="center"></td>
                    <td align="center" colspan="2"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" align="center">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3" align="center">
            <a href="<?php echo $addrapprove; ?>"> Konfirmasi Persetujuan </a>
        </td>
    </tr>
</table>

</body>
</html>
