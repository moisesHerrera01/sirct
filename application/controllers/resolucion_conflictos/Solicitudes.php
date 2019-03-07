<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitudes extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array('solicitudes_model','expedientes_model','expedientes_model','solicitud_juridica_model'));
		$this->load->library('FPDF/fpdf');
	}

	public function index(){
		$data['tipo_solicitud'] = $this->input->post('tipo_solicitud');
		$data['id_expedienteci'] = $this->input->post('id_personaci');
		$data['band_mantto'] = $this->input->post('band_mantto');
		$this->load->view('templates/header');
		$this->load->view('resolucion_conflictos/solicitudes',$data);
		$this->load->view('templates/footer');
	}

	public function tabla_solicitudes(){
		$data['abreviatura'] = $this->expedientes_model->obtener_abreviatura_depto($this->session->userdata('nr'));
		$this->load->view('resolucion_conflictos/solicitudes_ajax/tabla_solicitudes',$data);
	}

	public function tabla_representantes(){
	$this->load->view('resolucion_conflictos/solicitudes_ajax/tabla_representantes');
}

	public function gestionar_solicitudes(){


		if($this->input->post('band1') == "save"){
			$data = array(
			'email' => $this->input->post('email'),
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
			'posee_representante' => 0,
			'pertenece_lgbt' => $this->input->post('pertenece_lgbt'),
			'id_doc_identidad' => $this->input->post('id_doc_identidad'),
			'discapacidad' => $this->input->post('discapacidad_desc'),
			'id_usuario' => $this->session->userdata('id_usuario'),
			'fecha_modifica' => date('Y-m-d')
			);

			$data2  = array(
				'numero_partida' =>$this->input->post('numero_partida'),
				'id_municipio_partida' =>$this->input->post('municipio_partida'),
				'libro_partida' =>$this->input->post('libro_partida'),
				'id_municipio_menor' =>$this->input->post('municipio_menor'),
				'fecha_partida' => date("Y-m-d",strtotime($this->input->post('fecha_partida')))
			 );
			 $id_partida = 0;
			if ($this->input->post('numero_partida')!='') {
				$id_partida = $this->solicitudes_model->insertar_partida($data2);
				$data['id_partida'] = $id_partida;
			}

			echo json_encode(array('id_personaci' => $this->solicitudes_model->insertar_solicitud($data),'id_partida'=>$id_partida ));

		}else if($this->input->post('band1') == "edit"){

			$data = array(
			'email' => $this->input->post('email'),	
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
			'fnacimiento_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_nacimiento'))),
			'sexo_personaci' => $this->input->post('sexo'),
			'estudios_personaci' => $this->input->post('estudios'),
			'nacionalidad_personaci' => $this->input->post('nacionalidad'),
			'discapacidad_personaci' => $this->input->post('discapacidad'),
			'posee_representante' => 0,
			'pertenece_lgbt' => $this->input->post('pertenece_lgbt'),
			'discapacidad' => $this->input->post('discapacidad_desc'),
			// 'id_partida' =>$this->input->post('id_partida'),
			'id_usuario' => $this->session->userdata('id_usuario'),
			'fecha_modifica' => date('Y-m-d')
			);

			$data2  = array(
				'id_partida' =>$this->input->post('id_partida'),
				'numero_partida' =>$this->input->post('numero_partida'),
				'id_municipio_partida' =>$this->input->post('municipio_partida'),
				'libro_partida' =>$this->input->post('libro_partida'),
				'id_municipio_menor' =>$this->input->post('municipio_menor'),
				'fecha_partida' => date("Y-m-d",strtotime($this->input->post('fecha_partida'))),
				'fnacimiento_menor' => date("Y-m-d",strtotime($this->input->post('fnacimiento_menor'))),
			 );

			$this->solicitudes_model->editar_partida($data2);
			echo json_encode(array('id_personaci' => $this->solicitudes_model->editar_solicitud($data),'id_partida'=>$data2['id_partida'] ));

		}
	}

	public function gestionar_representante(){
		if($this->input->post('band4') == "save"){
			$data = array(
			'id_empresa' => $this->input->post('id_empresa'),
			'nombres_representante' => mb_strtoupper($this->input->post('nombres_representante')),
			'dui_representante' => ($this->input->post('dui_representante')),
			'acreditacion_representante' => ($this->input->post('acreditacion_representante')),
			'tipo_representante' => $this->input->post('tipo_representante'),
			'id_municipio' => $this->input->post('municipio_representante'),
			'id_estado_civil' => $this->input->post('estado_civil'),
			'id_titulo_academico' => $this->input->post('profesion'),
			'f_nacimiento_representante' => date("Y-m-d",strtotime($this->input->post('f_nacimiento_representante'))),
			'sexo_representante' => $this->input->post('sexo'),
			'estado_representante' => 1,
			'id_doc_identidad' => $this->input->post('rep_tipo_doc')
			);
      		echo $this->solicitud_juridica_model->insertar_representante($data);
		}else if($this->input->post('band4') == "edit"){
      		$data = array(
	    'id_representante' => $this->input->post('id_representante'),
	    'id_empresa' => $this->input->post('id_empresa'),
			'nombres_representante' => mb_strtoupper($this->input->post('nombres_representante')),
			'dui_representante' => ($this->input->post('dui_representante')),
			'acreditacion_representante' => ($this->input->post('acreditacion_representante')),
			'tipo_representante' => $this->input->post('tipo_representante'),
			'id_municipio' => $this->input->post('municipio_representante'),
			'id_estado_civil' => $this->input->post('estado_civil'),
			'id_titulo_academico' => $this->input->post('profesion'),
			'f_nacimiento_representante' => date("Y-m-d",strtotime($this->input->post('f_nacimiento_representante'))),
			'sexo_representante' => $this->input->post('sexo'),
			'id_doc_identidad' => $this->input->post('rep_tipo_doc')
			);
			echo $this->solicitud_juridica_model->editar_representante($data);
		}else if($this->input->post('band4') == "delete"){
			$data = array(
			'id_representante' => $this->input->post('id_representante'),
			'estado_representante' => $this->input->post('estado_representante')
			);
			echo $this->solicitud_juridica_model->eliminar_representante($data);
		}
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
}
