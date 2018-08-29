<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitudes extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('solicitudes_model');
		$this->load->library('FPDF/fpdf');
	}

  public function index(){
    $this->load->view('templates/header');
    $this->load->view('resolucion_conflictos/solicitudes');
    $this->load->view('templates/footer');
  }

  public function tabla_solicitudes(){
    $this->load->view('resolucion_conflictos/solicitudes_ajax/tabla_solicitudes');
  }
}
