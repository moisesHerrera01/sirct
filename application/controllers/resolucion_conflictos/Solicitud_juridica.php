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

	public function gestionar_solicitado(){
		if($this->input->post('band3') == "save"){
			$data = array(
      		'nombre_personaci' => $this->input->post('nombre_personaci'),
			'apellido_personaci' => $this->input->post('apellido_personaci'),
			'telefono_personaci' => $this->input->post('telefono_personaci'),
			'id_municipio' => $this->input->post('municipio'),
			'direccion_personaci' => $this->input->post('direccion_personaci'),
			'sexo_personaci' => $this->input->post('sexo'),
			'salario_personaci' => $this->input->post('salario_personaci'),
			'horarios_personaci' => $this->input->post('horarios_personaci'),
			'id_catalogociuo' => $this->input->post('id_catalogociuo'),
			'id_empresaci' => $this->input->post('id_empresaci'),
			'discapacidad_personaci' => $this->input->post('discapacidad')
			);
			echo $this->solicitud_juridica_model->insertar_solicitado($data);

		}else if($this->input->post('band3') == "edit"){
			$data = array(
			'id_personaci' => $this->input->post('id_personaci'),
			'nombre_personaci' => $this->input->post('nombre_personaci'),
			'apellido_personaci' => $this->input->post('apellido_personaci'),
			'telefono_personaci' => $this->input->post('telefono_personaci'),
			'id_municipio' => $this->input->post('id_municipio'),
			'direccion_personaci' => $this->input->post('direccion_personaci'),
			'sexo_personaci' => $this->input->post('sexo_personaci'),
			'salario_personaci' => $this->input->post('salario_personaci'),
			'horarios_personaci' => $this->input->post('horarios_personaci'),
			'id_catalogociuo' => $this->input->post('id_catalogociuo'),
			'discapacidad_personaci' => $this->input->post('discapacidad_personaci')
			);
			echo $this->solicitud_juridica_model->editar_solicitado($data);

		}else if($this->input->post('band3') == "delete"){
			$data = array(
			'id_personaci' => $this->input->post('id_personaci'),
			'id_estadosci' => $this->input->post('id_estadosci')
			);
			echo $this->solicitud_juridica_model->eliminar_solicitado($data);
		}
	}


	public function gestionar_expediente(){
		if($this->input->post('band4') == "save"){
			$data = array(
      		'id_empresaci' => $this->input->post('id_empresaci'),
			'id_personal' => $this->input->post('id_personal'),
			'id_personaci' => $this->input->post('id_personaci'),
			'motivo_expedienteci' => $this->input->post('motivo_expedienteci'),
			'descripmotivo_expedienteci' => $this->input->post('descripmotivo_expedienteci')
			);
			echo $this->solicitud_juridica_model->insertar_expediente($data);

		}else if($this->input->post('band4') == "edit"){
			$data = array(
			'id_expedienteci' => $this->input->post('id_expedienteci'),
			'id_empresaci' => $this->input->post('id_empresaci'),
			'id_personal' => $this->input->post('id_personal'),
			'id_personaci' => $this->input->post('id_personaci'),
			'motivo_expedienteci' => $this->input->post('motivo_expedienteci'),
			'descripmotivo_expedienteci' => $this->input->post('descripmotivo_expedienteci')
			);
			echo $this->solicitud_juridica_model->editar_expediente($data);

		}else if($this->input->post('band4') == "delete"){
			$data = array(
			'id_expedienteci' => $this->input->post('id_expedienteci'),
			'id_estadosci' => $this->input->post('id_estadosci')
			);
			echo $this->solicitud_juridica_model->eliminar_expediente($data);
		}
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
