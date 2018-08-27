<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    // Incluimos el archivo fpdf
    require_once APPPATH."/third_party/jpgraph/src/jpgraph.php";
    require_once APPPATH."/third_party/jpgraph/src/jpgraph_bar.php";

class J_pgraph extends GRAPH
{
    public function __construct() {
            parent::__construct();
        }

}

?>