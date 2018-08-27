<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/mpdf/mpdf.php";

class M_pdf extends MPDF
{
    public function __construct() {
            parent::__construct();
        }

}
?>