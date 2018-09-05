<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audiencias extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("expedientes_model","audiencias_model"));
	}

  public function programar_audiencias(){
    $data['expediente'] = $this->expedientes_model->obtener_registros_expedientes( $this->input->post('id') );
    $this->load->view('resolucion_conflictos/solicitudes_ajax/programar_audiencias', $data);
  }

  public function tabla_audiencias(){
    $data['audiencia'] = $this->audiencias_model->obtener_audiencias( $this->input->get('id_expedienteci') );
    $this->load->view('resolucion_conflictos/solicitudes_ajax/tabla_audiencias',$data);
  }

	public function gestionar_audiencias(){
		
	}

}
