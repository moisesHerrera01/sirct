<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud_indemnizacion extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array('Expediente_cc_model', 'Persona_cc_model', 'Representante_cc_model'));
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
				'fechacrea_expedienteci' => date("Y-m-d H:i:s"),
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

	public function modal_solicitantes() {

		$data = $this->db->get('sge_catalogociuo');

		$this->load->view('conflictos_colectivos/solicitud_indemnizacion_ajax/modal_solicitantes',
			array(
				'id' => $this->input->post('id'),
				'ocupacion' => $this->load->view(
					'resolucion_conflictos/solicitudes_ajax/combo_ocupacion',
					array( 'id' => 0, 'ocupacion' => $data ),
					TRUE
				)
			)
		);
	}

	public function gestionar_solicitante() {
		
		if($this->input->post('band4') == "save"){
			$data = array(
                'nombre_personaci' => $this->input->post('nombre_solicitante'),
				'apellido_personaci' => $this->input->post('apellido_solicitante'),
				'id_expedienteci' => $this->input->post('id_expediente'),
				'dui_personaci' => $this->input->post('dui'),
                'funciones_personaci' => $this->input->post('cago_persona'),
                'fnacimiento_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_nacimiento'))),
                'telefono_personaci' => $this->input->post('telefono_personaci'),
				'id_municipio' => $this->input->post('municipio_solicitante'),
				'direccion_personaci' => $this->input->post('direccion'),
				'sexo_personaci' => $this->input->post('sexo_solicitante'),
				'discapacidad_personaci' => $this->input->post('discapacidad_solicitante'),
				'estado_persona' => 1
			);

			echo $this->Persona_cc_model->insertar_persona_conflicto($data);

		} else if ($this->input->post('band4') == "edit") {
			
			$data = $this->Persona_cc_model->obtener_persona($this->input->post('id_persona'))->result_array()[0];

			$data['nombre_personaci'] = $this->input->post('nombre_solicitante');
			$data['apellido_personaci'] = $this->input->post('apellido_solicitante');
			$data['dui_personaci'] = $this->input->post('dui');
			$data['funciones_personaci'] = $this->input->post('cago_persona');
			$data['fnacimiento_personaci'] = date("Y-m-d",strtotime($this->input->post('fecha_nacimiento')));
			$data['telefono_personaci'] = $this->input->post('telefono_personaci');
			$data['id_municipio'] = $this->input->post('municipio_solicitante');
			$data['direccion_personaci'] = $this->input->post('direccion');
			$data['sexo_personaci'] = $this->input->post('sexo_solicitante');
			$data['discapacidad_personaci'] = $this->input->post('discapacidad_solicitante');

			if ($this->Persona_cc_model->editar_persona($data) == "exito") {
				echo $this->input->post('id_persona');
			} else {
				echo "fracaso";
			}

		} else {
			echo "fracaso";
		}

	}

	public function gestionar_representante() {
		
		if($this->input->post('band5') == "save"){
			$data = array(
                'id_personaci' => $this->input->post('id_persona'),
				'nombre_representantepersonaci' => $this->input->post('nombre_representacion_solicitante'),
				'apellido_representantepersonaci' => $this->input->post('apellido_representacion_solicitante'),
				'tipo_representantepersonaci' => $this->input->post('tipo_representacion_solicitante')
			);

			$repre = $this->Representante_cc_model->insertar_representante($data);

			$data2 = $this->Persona_cc_model->obtener_persona($this->input->post('id_persona'))->result_array()[0];

			$data2['tipopeticion_personaci'] = $this->input->post('motivo');
			$data2['id_catalogociuo'] = $this->input->post('ocupacion');
			$data2['funciones_personaci'] = $this->input->post('funciones');
			$data2['salario_personaci'] = $this->input->post('salario');
			$data2['formapago_personaci'] = $this->input->post('forma_pago');
			$data2['horarios_personaci'] = $this->input->post('horario');

			$this->Persona_cc_model->editar_persona($data2);

		} else if ($this->input->post('band5') == "edit") {
			echo $this->input->post('id_representante');
			$data = $this->Representante_cc_model->obtener_representante($this->input->post('id_representante'))->result_array()[0];

			$data['nombre_representantepersonaci'] = $this->input->post('nombre_representacion_solicitante');
			$data['apellido_representantepersonaci'] = $this->input->post('apellido_representacion_solicitante');
			$data['tipo_representantepersonaci'] = $this->input->post('tipo_representacion_solicitante');

			$this->Representante_cc_model->editar_representante($data);

			$data2 = $this->Persona_cc_model->obtener_persona($this->input->post('id_persona'))->result_array()[0];

			$data2['tipopeticion_personaci'] = $this->input->post('motivo');
			$data2['id_catalogociuo'] = $this->input->post('ocupacion');
			$data2['funciones_personaci'] = $this->input->post('funciones');
			$data2['salario_personaci'] = $this->input->post('salario');
			$data2['formapago_personaci'] = $this->input->post('forma_pago');
			$data2['horarios_personaci'] = $this->input->post('horario');

			$this->Persona_cc_model->editar_persona($data2);

		} else {
			echo "fracaso";
		}

	}

	public function ver_expediente() {
        $data['expediente'] = $this->Expediente_cc_model->obtener_expediente_indemnizacion( $this->input->post('id') );

        $this->load->view('conflictos_colectivos/solicitud_indemnizacion_ajax/vista_expediente', $data);
	}
	
	public function gestionar_inhabilitar_expediente()
	{
		# code...
	}

}
