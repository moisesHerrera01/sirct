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
    $data = $this->audiencias_model->obtener_audiencias_delegado( $this->input->get('id_delegado') );
		foreach ($data->result() as $au) {
			 $title = $au->numerocaso_expedienteci;
			 $start = $au->fecha_fechasaudienciasci.' '.$au->hora_fechasaudienciasci;
			 $end = $au->fecha_fechasaudienciasci.' '.$au->hora_fechasaudienciasci;
			 $id = $au->id_fechasaudienciasci;

			 $eventos[] = array('id' => $id, 'title' => $title, 'start' => $start, 'end' => $end);
		}
		$arrayJson = json_encode($eventos, JSON_UNESCAPED_UNICODE);
		print_r($arrayJson);
  }
}
