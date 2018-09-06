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


	public function gestionar_audiencia(){


		if($this->input->post('band3') == "save"){
			$data = array(
		  'fecha_fechasaudienciasci' => $this->input->post('fecha_audiencia'),
			'hora_fechasaudienciasci' => $this->input->post('hora_audiencia'),
			'id_expedienteci' => $this->input->post('id_expedienteci')
			);
			echo $this->audiencias_model->insertar_audiencia($data);

		}else if($this->input->post('band3') == "edit"){

			$data = array(
			'fecha_fechasaudienciasci' => $this->input->post('fecha_audiencia'),
			'hora_fechasaudienciasci' => $this->input->post('hora_audiencia'),
			'id_expedienteci' => $this->input->post('id_expedienteci'),
			'id_fechasaudienciasci' => $this->input->post('id_fechasaudienciasci')
			);
			echo $this->audiencias_model->editar_audiencia($data);

		}/*else if($this->input->post('band') == "delete"){
			$data = array(
			'id_personaci' => $this->input->post('id_personaci'),
			'id_estadosci' => $this->input->post('id_estadosci')
			);
			echo $this->solicitudes_model->eliminar_estado($data);
		}*/
	}

}
