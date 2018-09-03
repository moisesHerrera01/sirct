<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud_juridica extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('solicitud_juridica_model');
		$this->load->library('FPDF/fpdf');
	}

  public function index(){
    $this->load->view('templates/header');
    $this->load->view('resolucion_conflictos/solicitud_juridica');
    $this->load->view('templates/footer');
  }

  public function tabla_solicitud_juridica(){
    $this->load->view('resolucion_conflictos/solicitud_juridica_ajax/tabla_solicitud_juridica');
  }

  	public function tabla_representantes(){
		$this->load->view('resolucion_conflictos/solicitud_juridica_ajax/tabla_representantes');
	}

  public function combo_establecimiento() {
		
		$this->load->view('resolucion_conflictos/solicitud_juridica_ajax/combo_establecimiento', 
			array(
				'id' => $this->input->post('id'),
				'establecimiento' => $this->db->get('sge_empresa')
			)
		);

	}

	public function gestionar_representante(){
		if($this->input->post('band2') == "save"){
			$data = array(
			'id_empresa' => $this->input->post('id_empresa'),
			'nombres_representante' => mb_strtoupper($this->input->post('nombres_representante')),
			'dui_representante' => ($this->input->post('dui_representante')),
			'acreditacion_representante' => ($this->input->post('acreditacion_representante')),
			'tipo_representante' => $this->input->post('tipo_representante')
			);
      		echo $this->solicitud_juridica_model->insertar_representante($data);
		}else if($this->input->post('band2') == "edit"){
      		$data = array(
		    'id_representante' => $this->input->post('id_representante'),
		    'id_empresa' => $this->input->post('id_empresa'),
			'nombres_representante' => mb_strtoupper($this->input->post('nombres_representante')),
			'dui_representante' => ($this->input->post('dui_representante')),
			'acreditacion_representante' => ($this->input->post('acreditacion_representante')),
			'tipo_representante' => $this->input->post('tipo_representante')
			);
			echo $this->solicitud_juridica_model->editar_representante($data);
		}else if($this->input->post('band2') == "delete"){
			$data = array(
			'id_representante' => $this->input->post('id_representante'),
			'estado_representante' => $this->input->post('estado_representante')
			);
			echo $this->solicitud_juridica_model->eliminar_representante($data);
		}
	}

	public function gestionar_establecimiento(){
		if($this->input->post('band') == "save"){
			$data = array(
			'nombre_empresa' => mb_strtoupper($this->input->post('nombre_empresa')),
			'telefono_empresa' => mb_strtoupper($this->input->post('telefono_empresa')),
			'id_catalogociiu' => $this->input->post('id_catalogociiu'),
			'id_municipio' => $this->input->post('id_municipio'),
			'direccion_empresa' => $this->input->post('direccion_empresa')
			);
      		echo $this->solicitud_juridica_model->insertar_establecimiento($data);
		}else if($this->input->post('band') == "edit"){
      		$data = array(
		    'nombre_empresa' => mb_strtoupper($this->input->post('nombre_empresa')),
			'telefono_empresa' => mb_strtoupper($this->input->post('telefono_empresa')),
			'id_catalogociiu' => $this->input->post('id_catalogociiu'),
			'id_municipio' => $this->input->post('id_municipio'),
			'direccion_empresa' => $this->input->post('direccion_empresa')
			);
			echo $this->solicitud_juridica_model->editar_establecimiento($data);
		}else if($this->input->post('band') == "delete"){
			$data = array(
			'id_empresa' => $this->input->post('id_empresa'),
			'estado_empresa' => $this->input->post('estado_empresa')
			);
			echo $this->solicitud_juridica_model->eliminar_establecimiento($data);
		}
	}

	public function gestionar_solicitud_juridica(){


		if($this->input->post('band') == "save"){
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

		}else if($this->input->post('band') == "edit"){

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
			echo $this->solicitudes_model->editar_estado($data);

		}else if($this->input->post('band') == "delete"){
			$data = array(
			'id_personaci' => $this->input->post('id_personaci'),
			'id_estadosci' => $this->input->post('id_estadosci')
			);
			echo $this->solicitudes_model->eliminar_estado($data);
		}
	}

	/*public function combo_establecimiento() {
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
	}*/

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
