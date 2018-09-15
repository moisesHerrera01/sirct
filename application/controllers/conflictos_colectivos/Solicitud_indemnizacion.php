<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud_indemnizacion extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array('Expediente_cc_model', 'Persona_cc_model'));
	}

	public function index(){
		$this->load->view('templates/header');
		$this->load->view('conflictos_colectivos/solicitud_indemnizacion');
		$this->load->view('templates/footer');
	}

	public function tabla_solicitudes(){
		$this->load->view('conflictos_colectivos/solicitud_indemnizacion_ajax/tabla_solicitudes');
	}

	public function gestionar_solicitud() {
		
		if($this->input->post('band1') == "save"){
			$data = array(
                'numerocaso_expedienteci' => 'N/A',
                'id_empresaci' => $this->input->post('establecimiento'),
                'id_personal' => $this->input->post('id_personal'),
                'tiposolicitud_expedienteci' => 'Indemnización y Prestaciones Laborales',
                'id_estadosci' => 1
			);
			echo $this->Expediente_cc_model->insertar_expediente($data);

		} else if ($this->input->post('band1') == "edit") {
			
			$data = $this->Expediente_cc_model->obtener_expediente($this->input->post('id_expediente'))->result_array()[0];

			$data['id_empresaci'] = $this->input->post('establecimiento');
			$data['id_personal'] = $this->input->post('id_personal');

			if ($this->Expediente_cc_model->editar_expediente($data) == "exito") {
				echo $this->input->post('id_expediente');
			} else {
				echo "fracaso";
			}

		} else {
			echo "fracaso";
		}

	}

	public function gestionar_solicitud_persona() {
		
		if($this->input->post('band2') == "save"){
			$data = array(
                'fechaconflicto_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_conflicto'))),
                'nombre_personaci' => $this->input->post('nombre_persona'),
                'apellido_personaci' => $this->input->post('apellido_persona'),
                'funciones_personaci' => $this->input->post('cago_persona'),
				'sexo_personaci' => 'N/A',
				'id_municipio' => 0
			);

			$id_persona = $this->Persona_cc_model->insertar_persona_conflicto($data);

			$res = $this->Expediente_cc_model->editar_expediente(array(
				'id_expedienteci' => $this->input->post('id_expediente'), 
				'id_personaci' => $id_persona, 
			));

			if ($res == 'exito') {
				echo $id_persona;
			} else {
				echo $res;
			}

		}else if ($this->input->post('band2') == "edit") {
			
			$data = $this->Persona_cc_model->obtener_persona($this->input->post('id_persona'))->result_array()[0];

			$data['fechaconflicto_personaci'] = date("Y-m-d",strtotime($this->input->post('fecha_conflicto')));
			$data['nombre_personaci'] = $this->input->post('nombre_persona');
			$data['apellido_personaci'] = $this->input->post('apellido_persona');
			$data['funciones_personaci'] = $this->input->post('cago_persona');

			if ($this->Persona_cc_model->editar_persona($data) == "exito") {
				echo $this->input->post('id_persona');
			} else {
				echo "fracaso";
			}

		} else {
			echo "fracaso";
		}

	}

	public function obtener_expediente_json() {
		print json_encode(
            $this->Expediente_cc_model->obtener_expediente_persona($this->input->post('id'))->result()
        );
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
                'discapacidad_personaci' => $this->input->post('discapacidad'),
                'posee_representante' => $this->input->post('posee_representante')
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
			'discapacidad_personaci' => $this->input->post('discapacidad'),
			'id_empleador' => $this->input->post('id_empleador'),
			'posee_representante' => $this->input->post('posee_representante')
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
