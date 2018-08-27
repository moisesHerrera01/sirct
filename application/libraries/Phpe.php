<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."third_party/phpexcel/Classes/phpexcel.php";

class Phpe extends PHPEXCEL
{
    public function __construct() {
            parent::__construct();
        }

}
?>