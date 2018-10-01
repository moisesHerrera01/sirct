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
			'conocido_por' => $this->input->post('conocido_por'),
			'dui_personaci' => $this->input->post('dui'),
			'telefono_personaci' => $this->input->post('telefono'),
			'telefono2_personaci' => $this->input->post('telefono2'),
			'id_municipio' => $this->input->post('municipio'),
			'direccion_personaci' => $this->input->post('direccion'),
			'fnacimiento_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_nacimiento'))),
			'sexo_personaci' => $this->input->post('sexo'),
			'estudios_personaci' => $this->input->post('estudios'),
			'nacionalidad_personaci' => $this->input->post('nacionalidad'),
			'discapacidad_personaci' => $this->input->post('discapacidad'),
			'posee_representante' => $this->input->post('posee_representante'),
			'pertenece_lgbt' => $this->input->post('pertenece_lgbt'),
			'id_doc_identidad' => $this->input->post('id_doc_identidad'),
			'discapacidad' => $this->input->post('discapacidad_desc'),
			);

			$data2  = array(
				'numero_partida' =>$this->input->post('numero_partida'),
				'folio_partida' =>$this->input->post('folio_partida'),
				'libro_partida' =>$this->input->post('libro_partida'),
				'asiento_partida' =>$this->input->post('asiento_partida'),
				'anio_partida' =>$this->input->post('numero_partida')
			 );

			$id_partida = $this->solicitudes_model->insertar_partida($data2);
			$data['id_partida'] = $id_partida;

			echo $this->solicitudes_model->insertar_solicitud($data);

		}else if($this->input->post('band1') == "edit"){

			$data = array(
			'id_personaci' => $this->input->post('id_personaci'),
			'nombre_personaci' => $this->input->post('nombres'),
			'apellido_personaci' => $this->input->post('apellidos'),
			'conocido_por' => $this->input->post('conocido_por'),
			'id_doc_identidad' => $this->input->post('id_doc_identidad'),
			'dui_personaci' => $this->input->post('dui'),
			'telefono_personaci' => $this->input->post('telefono'),
			'telefono2_personaci' => $this->input->post('telefono2'),
			'id_municipio' => $this->input->post('municipio'),
			'direccion_personaci' => $this->input->post('direccion'),
			'fnacimiento_personaci' => $this->input->post('fecha_nacimiento'),
			'sexo_personaci' => $this->input->post('sexo'),
			'estudios_personaci' => $this->input->post('estudios'),
			'nacionalidad_personaci' => $this->input->post('nacionalidad'),
			'discapacidad_personaci' => $this->input->post('discapacidad'),
			'posee_representante' => $this->input->post('posee_representante'),
			'pertenece_lgbt' => $this->input->post('pertenece_lgbt'),
			'discapacidad' => $this->input->post('discapacidad_desc'),
			'id_partida' =>$this->input->post('id_partida')
			);

			$data2  = array(
				'id_partida' =>$this->input->post('id_partida'),
				'numero_partida' =>$this->input->post('numero_partida'),
				'folio_partida' =>$this->input->post('folio_partida'),
				'libro_partida' =>$this->input->post('libro_partida'),
				'asiento_partida' =>$this->input->post('asiento_partida'),
				'anio_partida' =>$this->input->post('numero_partida')
			 );

			$this->solicitudes_model->editar_partida($data2);
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

	public function combo_nacionalidades() {
		$data = $this->solicitudes_model->obtener_nacionalidades();
		$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_nacionalidades',
			array(
				'id' => $this->input->post('id'),
				'nacionalidad' => $data
			)
		);
	}

	public function combo_tipo_doc() {
		$data = $this->solicitudes_model->obtener_tipo_documentos();
		$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_tipo_doc',
			array(
				'id' => $this->input->post('id'),
				'doc_identidad' => $data
			)
		);
	}

	public function combo_discapacidad() {
		$data = $this->solicitudes_model->obtener_discapacidades();
		$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_discapacidad',
			array(
				'id' => $this->input->post('id'),
				'discapacidad' => $data
			)
		);
	}

	public function combo_tipo_representante() {
		$data = $this->solicitudes_model->obtener_tipos_representante();
		$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_tipo_representante',
			array(
				'id' => $this->input->post('id'),
				'tipo_representante' => $data
			)
		);
	}

	/*public function combo_ocupacion() {
		$data = $this->db->get('sge_catalogociuo');
		$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_ocupacion',
			array(
				'id' => $this->input->post('id'),
				'ocupacion' => $data
			)
		);
	}*/
}
