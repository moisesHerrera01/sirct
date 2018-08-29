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

	public function gestionar_solicitudes(){

		if($this->input->post('band') == "save"){

			$data = array(
      'nombre_personaci' => $this->input->post('nombres'),
			'apellido_personaci' => $this->input->post('apellidos'),
			'dui' => $this->input->post('dui'),
			'telefono_personaci' => $this->input->post('telefono'),
			'id_municipio' => $this->input->post('municipio'),
			'direccion_personaci' => $this->input->post('direccion'),
			'fnacimiento_personaci' => $this->input->post('fecha_nacimiento'),
			'sexo_personaci' => $this->input->post('sexo'),
			'estudios_personaci' => $this->input->post('estudios'),
			'nacionalidad_personaci' => $this->input->post('nacionalidad'),
			'discapacidad_personaci' => $this->input->post('discapacidad')
			);
			echo $this->solicitudes_model->insertar_estado($data);

		}else if($this->input->post('band') == "edit"){

			$data = array(
			'id_personaci' => $this->input->post('id_personaci'),
			'nombre_personaci' => $this->input->post('nombres'),
			'apellido_personaci' => $this->input->post('apellidos'),
			'dui' => $this->input->post('dui'),
			'telefono_personaci' => $this->input->post('telefono'),
			'id_municipio' => $this->input->post('municipio'),
			'direccion_personaci' => $this->input->post('direccion'),
			'fnacimiento_personaci' => $this->input->post('fecha_nacimiento'),
			'sexo_personaci' => $this->input->post('sexo'),
			'estudios_personaci' => $this->input->post('estudios'),
			'nacionalidad_personaci' => $this->input->post('nacionalidad'),
			'discapacidad_personaci' => $this->input->post('discapacidad')
			);
			echo $this->solicitudes_model->editar_estado($data);

		}else if($this->input->post('band') == "delete"){
			$data = array(
			'id_personaci' => $this->input->post('id_personaci'),
			'id_estadosci' => $this->input->post('id_estadosci')
			);
			echo $this->solicitudes_model->eliminar_estado($data);
		}
	}
}
