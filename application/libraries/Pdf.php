<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//require_once dirname(__FILE__) . '/third_party/tcpdf/tcpdf.php';
require_once APPPATH."third_party/tcpdf/tcpdf.php";

class Pdf extends TCPDF {
    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = TRUE, $encoding = 'UTF-8', $diskcache = FALSE, $pdfa = FALSE) {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
    }
}

?>