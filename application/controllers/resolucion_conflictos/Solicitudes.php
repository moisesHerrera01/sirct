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


		if($this->input->post('band1') == "save"){
			$data = array(
      'nombre_personaci' => $this->input->post('nombres'),
			'apellido_personaci' => $this->input->post('apellidos'),
			'dui_personaci' => $this->input->post('dui'),
			'telefono_personaci' => $this->input->post('telefono'),
			'id_municipio' => $this->input->post('municipio'),
			'direccion_personaci' => $this->input->post('direccion'),
			'fnacimiento_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_nacimiento'))),
			'sexo_personaci' => $this->input->post('sexo'),
			'estudios_personaci' => $this->input->post('estudios'),
			'nacionalidad_personaci' => $this->input->post('nacionalidad'),
			'discapacidad_personaci' => $this->input->post('discapacidad')
			);
			echo $this->solicitudes_model->insertar_solicitud($data);

		}else if($this->input->post('band1') == "edit"){

			$data = array(
			'id_personaci' => $this->input->post('id_personaci'),
			'nombre_personaci' => $this->input->post('nombres'),
			'apellido_personaci' => $this->input->post('apellidos'),
			'dui_personaci' => $this->input->post('dui'),
			'telefono_personaci' => $this->input->post('telefono'),
			'id_municipio' => $this->input->post('municipio'),
			'direccion_personaci' => $this->input->post('direccion'),
			'fnacimiento_personaci' => $this->input->post('fecha_nacimiento'),
			'sexo_personaci' => $this->input->post('sexo'),
			'estudios_personaci' => $this->input->post('estudios'),
			'nacionalidad_personaci' => $this->input->post('nacionalidad'),
			'discapacidad_personaci' => $this->input->post('discapacidad')
			);
			echo $this->solicitudes_model->editar_solicitud($data);

		}/*else if($this->input->post('band') == "delete"){
			$data = array(
			'id_personaci' => $this->input->post('id_personaci'),
			'id_estadosci' => $this->input->post('id_estadosci')
			);
			echo $this->solicitudes_model->eliminar_estado($data);
		}*/
	}

	public function combo_establecimiento() {

		$this->db->select("*");
		$this->db->group_by('e.nombre_empresa');
		$query = $this->db->get('sge_empresa e');

		$data = $query->result();

		$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_establecimiento',
			array(
				'id' => $this->input->post('id'),
				'establecimiento' => $query
			)
		);
	}

	public function combo_ocupacion() {
		$data = $this->db->get('sge_catalogociuo');
		$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_ocupacion',
			array(
				'id' => $this->input->post('id'),
				'ocupacion' => $data
			)
		);
	}
}
