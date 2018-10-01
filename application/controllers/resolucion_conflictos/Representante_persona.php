<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Representante_persona extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("expedientes_model","representante_persona_model"));
	}

	public function gestionar_representantes(){

		if($this->input->post('band6') == "save"){
			$data = array(
			'nombre_representantepersonaci' => $this->input->post('nombre_representante_persona'),
			'apellido_representantepersonaci' => $this->input->post('apellido_representante_persona'),
			'tipo_representantepersonaci' => $this->input->post('tipo_representante_persona'),
			'dui_representantepersonaci' => $this->input->post('dui_representante_persona'),
			'tel_representantepersonaci' => $this->input->post('telefono_representante_persona'),
			'acreditacion_representantepersonaci' => $this->input->post('acreditacion_representante_persona')
			);
			echo $this->representante_persona_model->insertar_representante($data);

		}else if($this->input->post('band6') == "edit"){

			$data = array(
			'id_representantepersonaci' => $this->input->post('id_representante_persona'),
			'nombre_representantepersonaci' => $this->input->post('nombre_representante_persona'),
			'apellido_representantepersonaci' => $this->input->post('apellido_representante_persona'),
			'tipo_representantepersonaci' => $this->input->post('tipo_representante_persona'),
			'dui_representantepersonaci' => $this->input->post('dui_representante_persona'),
			'tel_representantepersonaci' => $this->input->post('telefono_representante_persona'),
			'acreditacion_representantepersonaci' => $this->input->post('acreditacion_representante_persona')
			);
			echo $this->representante_persona_model->editar_representante($data);

		}/*else if($this->input->post('band') == "delete"){
			$data = array(
			'id_personaci' => $this->input->post('id_personaci'),
			'id_estadosci' => $this->input->post('id_estadosci')
			);
			echo $this->solicitudes_model->eliminar_estado($data);
		}*/
	}
}
