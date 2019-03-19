<?php
require_once APPPATH."third_party/tcpdf/tcpdf.php";

class MYPDF extends TCPDF {
    private $arr_desctable;

    public function Header() {
        $image_file = K_PATH_IMAGES.'img/mp2016.jpg';
        $this->Image($image_file, 10, 5, 60, '', 'JPG', '', 'T', false, false, 0, false, false, false);

        $this->SetFont('times', 'B', 25);

        $this->SetY(14);
        $this->Cell(335,15, 'INVOICE RECEIPT', 0, false, 'R', 0, false, 'M', 'M');

        $LineStyle3 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 138, 210));
        $LineStyle1 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 92, 125));
        $LineStyle4 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(34, 190, 169));
        $LineStyle2 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(43, 99, 100));

        $line_ypos = 27;
        $this->Line(10, $line_ypos, 50, $line_ypos, $LineStyle1);
        $this->Line(50, $line_ypos, 100, $line_ypos, $LineStyle2);
        $this->Line(100, $line_ypos, 150, $line_ypos, $LineStyle3);
        $this->Line(150, $line_ypos, 345, $line_ypos, $LineStyle4);

        $this->SetMargins(10, 30, 0, true);
    }

    public function drawLine($linex, $liney, $linepos) {
        $LineStyle = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 92, 125));
        $this->Line($linex, $linepos, $liney, $linepos, $LineStyle);
    }
}

$CellHeight1 = 8;
$CellHeight2 = 6;

$pdf = new MYPDF('L', 'mm', 'INVOICE_AR', true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Medika Plaza');
$pdf->SetTitle($invheader['header1']->cardname);
$pdf->SetSubject('MCU Package');

$pdf->SetAutoPageBreak(true, 20);
$pdf->SetDisplayMode('real', 'default');

$pdf->AddPage();

$pdf->SetFont('times', 'B', 14);
$pdf->Cell(35, $CellHeight1, 'Company', 0, 0, 'L');
$pdf->Cell(5, $CellHeight1, ':', 0, 0, 'C');
$pdf->SetFont('times', '', 14);
$pdf->Cell(48, $CellHeight1, $invheader['header1']->cardname, 0, 1, 'L');

$pdf->SetFont('times', 'B', 14);
$pdf->Cell(35, $CellHeight1, 'Attention', 0, 0, 'L');
$pdf->Cell(5, $CellHeight1, ':', 0, 0, 'C');
$pdf->SetFont('times', '', 14);
$pdf->Cell(48, $CellHeight1, $invheader['header1']->attentionto, 0, 0, 'L');

$pdf->Ln(12);
$pdf->SetFont('times', 'B', 14);
$pdf->Cell(75, $CellHeight1, 'INVOICE NO.', 1, 0, 'C');
$pdf->Cell(75, $CellHeight1, 'INVOICE DATE', 1, 0, 'C');
$pdf->Cell(75, $CellHeight1, 'DUE DATE', 1, 0, 'C');
$pdf->Cell(83, $CellHeight1, 'AMOUNT', 1, 0, 'C');
$pdf->Cell(25, $CellHeight1, 'CURR.', 1, 1, 'C');

$pdf->SetFont('times', '', 14);
$totalamount = 0;
foreach ($invheader['header2'] as $rowinv) {
    $pdf->Cell(75, $CellHeight1, $rowinv->docnum, 'B', 0, 'C');
    $pdf->Cell(75, $CellHeight1, date('d M Y', strtotime($rowinv->docdate)), 'B', 0, 'C');
    $pdf->Cell(75, $CellHeight1, date('d M Y', strtotime($rowinv->docduedate)), 'B', 0, 'C');
    $pdf->Cell(83, $CellHeight1, number_format($rowinv->amount), 'B', 0, 'R');
    $pdf->Cell(25, $CellHeight1, $rowinv->currtype, 'B', 1, 'C');

    $totalamount += $rowinv->amount;
}

$complex_cell_border = array(
    'T' => array('width' => 0.5, 'color' => array(0,0,0)),
    'B' => array('width' => 0.5, 'color' => array(0,0,0))
);

$pdf->SetFont('times', 'B', 14);
$pdf->Cell(225, $CellHeight1, 'TOTAL AMOUNT', $complex_cell_border, 0, 'C');
$pdf->Cell(83, $CellHeight1, number_format($totalamount), $complex_cell_border, 0, 'R');
$pdf->Cell(25, $CellHeight1, $rowinv->currtype, $complex_cell_border, 1, 'C');

$pdf->SetFont('times', 'B', 14);
$pdf->Cell(75, $CellHeight1, 'Details Description :', 0, 1, 'L');

$pdf->SetFont('times', '', 12);
foreach ($invdetail as $rowinv) {
    $pdf->SetX(15);
    $pdf->Cell(75, $CellHeight2, '- '.$rowinv['invdesc'], 0, 1, 'L');
}

$pdf->Ln(7);
$pdf->SetFont('times', 'B', 13);
$pdf->Cell(70, $CellHeight1, 'Penerima,', 0, 0, 'L');
$pdf->Ln(20);
$pdf->Cell(70, $CellHeight1, '(________________________________)', 0, 1, 'L');
$pdf->Cell(40, $CellHeight1, 'Nama jelas', 0, 0, 'L'); $pdf->Cell(5, $CellHeight1, ':', 0, 1, 'L');
$pdf->Cell(40, $CellHeight1, 'Tanggal diterima', 0, 0, 'L'); $pdf->Cell(5, $CellHeight1, ':', 0, 1, 'L');

$pdf->Output('invreceipt.pdf', 'I');
?>