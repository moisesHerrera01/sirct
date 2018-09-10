<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Representante_persona extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("expedientes_model","pagos_model"));
	}

  public function gestionar_representantes(){
    
  }
}
