<?php
require_once APPPATH."third_party/tcpdf/tcpdf.php";

class MYPDF extends TCPDF {
//    private $imnum;
    private $arr_wdesctable;
    private $arr_desctable;

//    public function setIMNum($imnum) {
//        $this->imnum = $imnum;
//    }

    public function Header() {
        $image_file = K_PATH_IMAGES.'img/mp2016.jpg';
        $this->Image($image_file, 10, 5, 60, '', 'JPG', '', 'T', false, false, 0, false, false, false);

        $this->SetFont('times', 'B', 25);

        $this->SetY(14);
        $this->Cell(0,15, 'MCU Package', 0, false, 'R', 0, false, 'M', 'M');

        $LineStyle3 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 138, 210));
        $LineStyle1 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 92, 125));
        $LineStyle4 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(34, 190, 169));
        $LineStyle2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(43, 99, 100));

        $line_ypos = 27;
        $this->Line(10, $line_ypos, 50, $line_ypos, $LineStyle1);
        $this->Line(50, $line_ypos, 100, $line_ypos, $LineStyle2);
        $this->Line(100, $line_ypos, 150, $line_ypos, $LineStyle3);
        $this->Line(150, $line_ypos, 200, $line_ypos, $LineStyle4);

//        $this->SetFont('times', 'B', 11);
//        $this->Ln(14);
//        $this->SetX(143);
//        $this->Cell(15, 6, '#Doc. : ', 0, false, 'L');
//        $this->Cell(0, 6, $this->imnum, 0,false, 'L');
    }

    public function HeaderTable($arr_wdesctable,$arr_desctable) {
        $this->SetFont('times', 'B', 12);
        $this->SetFillColor(203, 232, 249);
        $this->SetLineStyle(array('color' => array(255,255,255), 'width' => 0.5));
        $this->Ln(3);

        for($i = 0; $i < count($arr_wdesctable); $i++) {
            $this->Cell($arr_wdesctable[$i], 8, $arr_desctable[$i], 1,false, 'C', 1, '', '', '', '', 'M');
        }
        $this->Ln();
    }

    public function RowMultiCell($arr_wdesctable,$arr_desctable,$arr_talign,$rowHeight,$border,$fontsize) {
        $nb = 0;
        for($inb=0; $inb < count($arr_desctable); $inb++)
            $nb = $this->getNumLines($arr_desctable[$inb],$arr_wdesctable[$inb]);

        $h = $rowHeight * $nb;

        $xpos = $this->GetX();
        $this->SetX($xpos);

        for($i = 0; $i < count($arr_desctable); $i++) {
            $this->SetFont('times', '', $fontsize[$i]);

            $x = $this->GetX();
            $y = $this->GetY();

            $this->MultiCell($arr_wdesctable[$i], $rowHeight, $arr_desctable[$i], $border, $arr_talign[$i], 0, false);
            $this->SetXY($x + $arr_wdesctable[$i],$y);
        }
        $this->Ln($h);
    }

    public function drawLine($linex, $liney, $linepos) {
        $LineStyle = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 92, 125));
        $this->Line($linex, $linepos, $liney, $linepos, $LineStyle);
    }
}

//$imnum = substr($packageheader->imgid, 0, 10);
if($packageheader->packagelanguage == "0") { $packlanguage = "Indonesia"; } else { $packlanguage = "English"; }
if($packageheader->resulttype == "0") {
    $resulttype = "Hardcopy";
} elseif($packageheader->resulttype == "1") {
    $resulttype = "Softcopy";
} else {
    $resulttype = "Hardcopy & Softcopy";
}

$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
//$pdf->setIMNum($imnum);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Medika Plaza');
$pdf->SetTitle($packageheader->companyname);
$pdf->SetSubject('MCU Package');

$pdf->SetAutoPageBreak(true);
$pdf->SetDisplayMode('real', 'default');
$pdf->AddPage();

$pdf->Ln(20);
$pdf->SetFont('times', 'BU', 14);
$pdf->Cell(0, 6, $packageheader->companyname, 0,1, 'L');
$pdf->SetFont('times', '', 12);
//$pdf->Cell(0, 6, $addr1, 0,1, 'L');
//$pdf->Cell(0, 6, $addr2, 0,1, 'L');
//$pdf->Cell(0, 6, $addr3, 0,1, 'L');

$pdf->Ln(3);

$pdf->SetFont('times', '', 12);

$pdf->Cell(35, 6, 'Active Period', 0,0, 'L');
$pdf->Cell(2, 6, ':', 0,0, 'C');
$pdf->Cell(0, 6, date('d M Y', strtotime($packageheader->startperiode)).' - '.date('d M Y', strtotime($packageheader->endperiode)), 0,1, 'L');

$pdf->Cell(35, 6, 'Result in',0,0, 'L');
$pdf->Cell(2, 6, ':', 0,0, 'C');
$pdf->Cell(0, 6, $packlanguage, 0,1, 'L');

$pdf->Cell(35, 6, 'Result type',0,0, 'L');
$pdf->Cell(2, 6, ':', 0,0, 'C');
$pdf->Cell(0, 6, $resulttype, 0,1, 'L');

$pdf->Cell(35, 6, 'Term of Payment',0,0, 'L');
$pdf->Cell(2, 6, ':', 0,0, 'C');
$pdf->Cell(0, 6, $packageheader->packtop.' days', 0,1, 'L');

$pdf->Cell(35, 6, 'Marketing PIC',0,0, 'L');
$pdf->Cell(2, 6, ':', 0,0, 'C');
$pdf->Cell(0, 6, ucwords(strtolower($packageheader->namakaryawan)), 0,1, 'L');

$setWHeaderTbl = array(10,70,88,25);
$setDescHeaderTbl = array('No','Package','Detail','Price');
$pdf->HeaderTable($setWHeaderTbl,$setDescHeaderTbl);

$no = 0;
$no_show = "";
$lastpackage = "";
$packagedescription = "";

foreach ($packagedetail as $rowpack) {
    if($lastpackage != $rowpack->packageid) {
        $no = $no + 1;
        $packagedescription = $rowpack->packagename;
        $packageprice = "Rp. ".number_format($rowpack->totalprice);
    } else {
        $packagedescription = "";
        $packageprice = "";
    }

    if($lastpackage == $rowpack->packageid) {
        $no_show = "";
    } else {
        $no_show = $no.".";
    }

    $arr_wdesctable = array(10,70,88,25);
    $arr_desctable = array($no_show,$packagedescription,$rowpack->keterangan,$packageprice);
    if($lastpackage != $rowpack->packageid) {
        $arr_talign = array('C','L','L','R');
        $border = 0;
        if($no != 1) {
            $pdf->Ln(5);
        }
    } else {
        $arr_talign = array('C','L','L','R');
        $border = 0;
    }
    $rowHeight = 7;
    $arr_fontsize = array(11,11,9,11);

    $pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
    $pdf->RowMultiCell($arr_wdesctable,$arr_desctable,$arr_talign,$rowHeight,$border,$arr_fontsize);

    $lastpackage = $rowpack->packageid;
}

$setLine_x = 11; $setLine_y = 202;
$setLinePos = $pdf->GetY();
$pdf->drawLine($setLine_x, $setLine_y, $setLinePos);

$pdf->Output('contoh1.pdf', 'I');
?>
}