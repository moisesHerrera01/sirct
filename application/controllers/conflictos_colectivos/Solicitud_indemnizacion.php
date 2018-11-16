<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud_indemnizacion extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array('expediente_cc_model', 'Persona_cc_model', 'Representante_cc_model', 'solicitudes_model', 'establecimiento_model'));
	}

	public function index(){
		$data['band_mantto'] = $this->input->post('band_mantto');
		$this->load->view('templates/header');
		$this->load->view('conflictos_colectivos/solicitud_indemnizacion',$data);
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
				'tiposolicitud_expedienteci' => '5',
				'fechacrea_expedienteci' => date("Y-m-d H:i:s"),
        'id_estadosci' => 1,
			);
			echo $this->expediente_cc_model->insertar_expediente($data);

		} else if ($this->input->post('band1') == "edit") {

			$data = $this->expediente_cc_model->obtener_expediente($this->input->post('id_expediente'))->result_array()[0];

			$data['id_empresaci'] = $this->input->post('establecimiento');
			$data['id_personal'] = $this->input->post('id_personal');
			if ($this->expediente_cc_model->editar_expediente($data) != "fracaso") {
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
                //'fechaconflicto_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_conflicto'))),
                'nombre_personaci' => $this->input->post('nombre_persona'),
                'apellido_personaci' => $this->input->post('apellido_persona'),
                //'funciones_personaci' => $this->input->post('cago_persona'),
								'sexo_personaci' => 'N/A',
								'id_municipio' => 0
			);

			$id_persona = $this->Persona_cc_model->insertar_persona_conflicto($data);

			$res = $this->expediente_cc_model->editar_expediente(array(
				'id_expedienteci' => $this->input->post('id_expediente'),
				'id_personaci' => $id_persona,
				'fechaconflicto_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_conflicto'))),
				'causa_expedienteci' => $this->input->post('motivo')
			));

			if ($res == 'exito') {
				echo $id_persona;
			} else {
				echo $res;
			}

		}else if ($this->input->post('band2') == "edit") {

			$data = $this->Persona_cc_model->obtener_persona($this->input->post('id_persona'))->result_array()[0];

			//$data['fechaconflicto_personaci'] = date("Y-m-d",strtotime($this->input->post('fecha_conflicto')));
			$data['nombre_personaci'] = $this->input->post('nombre_persona');
			$data['apellido_personaci'] = $this->input->post('apellido_persona');
			//$data['funciones_personaci'] = $this->input->post('cago_persona');

			$data2['fechaconflicto_personaci'] = date("Y-m-d",strtotime($this->input->post('fecha_conflicto')));
			$data2['causa_expedienteci'] = $this->input->post('motivo');

			$this->expediente_cc_model->editar_expediente(array(
				'id_expedienteci' => $this->input->post('id_expediente'),
				'fechaconflicto_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_conflicto'))),
				'causa_expedienteci' => $this->input->post('motivo')
			));

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
            $this->expediente_cc_model->obtener_expediente_persona($this->input->post('id'))->result()
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
        'fnacimiento_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_nacimiento'))),
        'telefono_personaci' => $this->input->post('telefono'),
				'telefono2_personaci' => $this->input->post('telefono2'),
				'id_municipio' => $this->input->post('municipio_solicitante'),
				'direccion_personaci' => $this->input->post('direccion'),
				'sexo_personaci' => $this->input->post('sexo_solicitante'),
				'discapacidad_personaci' => $this->input->post('discapacidad_solicitante'),
				'estado_persona' => 1,
				'nacionalidad_personaci' => $this->input->post('nacionalidad'),
				'discapacidad_personaci' => $this->input->post('discapacidad_solicitante'),
				'discapacidad' => $this->input->post('discapacidad_desc'),
				'estudios_personaci' => $this->input->post('estudios'),
				'pertenece_lgbt' => $this->input->post('pertenece_lgbt'),
				'id_doc_identidad' => $this->input->post('id_doc_identidad')
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

			echo $this->Persona_cc_model->insertar_persona_conflicto($data);

		} else if ($this->input->post('band4') == "edit") {

			$data = array(
				'id_personaci' => $this->input->post('id_persona'),
				'nombre_personaci' => $this->input->post('nombre_solicitante'),
				'apellido_personaci' => $this->input->post('apellido_solicitante'),
				'dui_personaci' => $this->input->post('dui'),
				'fnacimiento_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_nacimiento'))),
				'telefono_personaci' => $this->input->post('telefono'),
				'telefono2_personaci' => $this->input->post('telefono2'),
				'id_municipio' => $this->input->post('municipio_solicitante'),
				'direccion_personaci' => $this->input->post('direccion'),
				'sexo_personaci' => $this->input->post('sexo_solicitante'),
				'discapacidad_personaci' => $this->input->post('discapacidad_solicitante'),
				'discapacidad' => $this->input->post('discapacidad_desc'),
				'estudios_personaci' => $this->input->post('estudios'),
				'pertenece_lgbt' => $this->input->post('pertenece_lgbt'),
				'id_doc_identidad' => $this->input->post('id_doc_identidad'),
				'nacionalidad_personaci'=> $this->input->post('nacionalidad'),
				'id_partida' => $this->input->post('id_partida')
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
		// 	$data = array(
    //     'id_personaci' => $this->input->post('id_persona'),
		// 		'nombre_representantepersonaci' => $this->input->post('nombre_representacion_solicitante'),
		// 		'apellido_representantepersonaci' => $this->input->post('apellido_representacion_solicitante'),
		// 		'tipo_representantepersonaci' => $this->input->post('tipo_representacion_solicitante')
		// 	);
		//
		// 	$repre = $this->Representante_cc_model->insertar_representante($data);

			$data2 = $this->Persona_cc_model->obtener_persona($this->input->post('id_persona'))->result_array()[0];

			$data2['funciones_personaci'] = $this->input->post('funciones');
			$data2['salario_personaci'] = $this->input->post('salario');
			$data2['formapago_personaci'] = $this->input->post('forma_pago');
			$data2['horarios_personaci'] = $this->input->post('horario');
			$data2['ocupacion'] = $this->input->post('ocupacion');

			$this->Persona_cc_model->editar_persona($data2);

		} else if ($this->input->post('band5') == "edit") {
			echo $this->input->post('id_representante');
			// $data = $this->Representante_cc_model->obtener_representante($this->input->post('id_representante'))->result_array()[0];
			//
			// $data['nombre_representantepersonaci'] = $this->input->post('nombre_representacion_solicitante');
			// $data['apellido_representantepersonaci'] = $this->input->post('apellido_representacion_solicitante');
			// $data['tipo_representantepersonaci'] = $this->input->post('tipo_representacion_solicitante');
			//
			// $this->Representante_cc_model->editar_representante($data);

			$data2 = $this->Persona_cc_model->obtener_persona($this->input->post('id_persona'))->result_array()[0];

			$data2['ocupacion'] = $this->input->post('ocupacion');
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
        $data['expediente'] = $this->expediente_cc_model->obtener_expediente_indemnizacion( $this->input->post('id') );

        $this->load->view('conflictos_colectivos/solicitud_indemnizacion_ajax/vista_expediente', $data);
	}

	public function gestionar_inhabilitar_expediente()
	{
		# code...
	}

	public function modal_representante() {
		echo $this->load->view('conflictos_colectivos/solicitud_indemnizacion_ajax/modal_representante', array(), true);
	}

	public function obtener_respresentante_mayor() {
		echo json_encode($this->establecimiento_model->obtener_respresentante_mayor( $this->input->post('id') )->row_array());
	}

	public function combo_motivo_solicitud() {
		$motivos = $this->expediente_cc_model->obtener_motivos();
		$this->load->view('conflictos_colectivos/solicitud_indemnizacion_ajax/combo_motivos',
			array(
				'id' => $this->input->post('id'),
				'motivos' => $motivos
			)
		);

	}

}
