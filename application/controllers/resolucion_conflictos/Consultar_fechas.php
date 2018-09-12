<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consultar_fechas extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("expedientes_model", "audiencias_model","pagos_model"));
	}

  public function index(){
    $this->load->view('templates/header');
    $this->load->view('resolucion_conflictos/consultar_fechas');
    $this->load->view('templates/footer');
  }

  public function calendario(){
    $data['fechas'] = $this->audiencias_model->obtener_audiencias_delegado( $this->input->get('id_delegado') );
    $this->load->view('resolucion_conflictos/solicitudes_ajax/calendario',$data);
  }
}
