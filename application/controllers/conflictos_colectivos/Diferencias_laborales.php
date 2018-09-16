<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diferencias_laborales extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array('expediente_cc_model','sindicatos_model'));
	}

	public function gestionar_expediente() {
		 $fecha_actual=date("Y-m-d H:i:s");

		if($this->input->post('band4') == "save"){
						$data2 = array(
                'motivo_expedienteci' => $this->input->post('motivo'),
                'descripmotivo_expedienteci' => $this->input->post('descripcion_motivo'),
								'id_personal' => $this->input->post('id_personal'),
								'id_empresaci' => $this->input->post('establecimiento'),
								'id_estadosci' => 1,
								'fechacrea_expedienteci' => $fecha_actual,
								'tiposolicitud_expedienteci' =>"Diferencia Laboral",
            );

						$id_expedienteci=$this->expediente_cc_model->insertar_expediente($data2);

            if ("fracaso" != $id_expedienteci) {
								$data = array(
									'id_expedientecc' =>  $id_expedienteci,
									'id_sindicato' => $this->input->post('id_sindicato')
								);
							$this->sindicatos_model->editar_sindicato($data);
            } else {
                echo "fracaso";
            }

		} else if($this->input->post('band4') == "edit"){

			$data3 = array(
				'id_empleador' => $this->input->post('id_emplea'),
				'nombre_empleador' => $this->input->post('nombres_jefe'),
				'apellido_empleador' => $this->input->post('apellidos_jefe'),
				'cargo_empleador' => $this->input->post('cargo_jefe')
			);

			$data = $this->solicitudes_model->obtener_persona($this->input->post('id_personaci'))->result_array()[0];
			$data['id_personaci'] = $this->input->post('id_personaci');
			$data['salario_personaci'] = $this->input->post('salario');
			$data['funciones_personaci'] = $this->input->post('funciones');
			$data['formapago_personaci'] = $this->input->post('forma_pago');
			$data['horarios_personaci'] = $this->input->post('horario');
			$data['fechaconflicto_personaci'] = date("Y-m-d",strtotime($this->input->post('fecha_conflicto')));
			$data['id_catalogociuo'] = $this->input->post('ocupacion');

			$data2 = array(
					'id_expedienteci' => $this->input->post('id_expedienteci'),
					'motivo_expedienteci' => $this->input->post('motivo'),
					'descripmotivo_expedienteci' => $this->input->post('descripcion_motivo'),
					'id_personaci' => $this->input->post('id_personaci'),
					'id_personal' => $this->input->post('id_personal'),
					'id_empresaci' => $this->input->post('establecimiento'),
					'fechacrea_expedienteci' =>  date("Y-m-d H:i:s", strtotime($this->input->post('fecha_creacion_exp'))),
					'tiposolicitud_expedienteci' =>"Conciliación",
			);

			if ("fracaso" != $this->empleadores_model->editar_empleador($data3)) {
				 $this->solicitudes_model->editar_solicitud($data);
				 $this->expedientes_model->editar_expediente($data2);
			} else {
				echo "fracaso";
			}

		}/*else if($this->input->post('band') == "delete"){
			$data = array(
				'id_expedientert' => $this->input->post('id_expedientert')
			);
			echo $this->reglamento_model->eliminar_documento($data);

		}*/

    }

		public function registros_expedientes() {

			print json_encode(
				$this->expediente_cc_model->expedientes_diferencia_laboral($this->input->post('id'))->result()
			);
		}

/*
			public function ver_expediente() {
				$data['empresa'] = $this->expedientes_model->obtener_municipio($this->input->post('id_emp'));
				$data['expediente'] = $this->expedientes_model->obtener_registros_expedientes( $this->input->post('id') );

				$this->load->view('resolucion_conflictos/solicitudes_ajax/vista_expediente', $data);
			}

			public function gestionar_inhabilitar_expediente() {

				//$data = $this->expedientes_model->obtener_expediente($this->input->post('id_exp'))->result_array()[0];
				//	$data['id_estadosci'] = ;
				//$data['inhabilitado_expedienteci'] = $this->input->post('mov_inhabilitar');
				$data  = array(
					'id_expedienteci'=>$this->input->post('id_exp'),
					'id_estadosci' => 4,
					'inhabilitado_expedienteci' => $this->input->post('mov_inhabilitar')
				);
				if ("fracaso" == $this->expedientes_model->editar_expediente($data)) {
					echo "fracaso";
				} else {
					echo "exito";
				}
			}

			public function gestionar_habilitar_expediente() {

				/*$data = $this->expedientes_model->obtener_expediente($this->input->post('id_exp'))->result_array()[0];
				$data['id_estadosci'] = 1;
				$data['inhabilitado_expedienteci'] = null;

				$data  = array(
					'id_expedienteci'=>$this->input->post('id_exp'),
					'id_estadosci' => 1,
					'inhabilitado_expedienteci' => null
				);
				if ("fracaso" == $this->expedientes_model->editar_expediente($data)) {
					echo "fracaso";
				} else {
					echo "exito";
				}
			}

			public function cambiar_delegado() {
				$data = array(
					'id_expedienteci' => $this->input->post('id_expedienteci'),
					'id_personal' => $this->input->post('id_personal'),
				);
				echo $this->expedientes_model->cambiar_delegado($data);
			}

			public function cambiar_estado() {
				$data = array(
					'id_expedienteci' => $this->input->post('id_expedienteci'),
					'id_estadosci' => $this->input->post('id_estadosci')
				);
				echo $this->expedientes_model->editar_expediente($data);
			}

			public function resolucion_expediente() {
				$this->load->view('resolucion_conflictos/solicitudes_ajax/resolucion_expediente', array('id' => $this->input->post('id') ));
			}

			public function gestionar_resolucion_expediente() {
				//$data = $this->expedientes_model->obtener_expediente($this->input->post('id_expedienteci'))->result_array()[0];
				$data['id_expedienteci'] = $this->input->post('id_expedienteci');
				$data['resultado_expedienteci'] = $this->input->post('resolucion');
				$data['tipocociliacion_expedienteci'] = $this->input->post('tipo_conciliacion');;

				if ("fracaso" == $this->expedientes_model->editar_expediente($data)) {
					echo "fracaso";
				} else {
					echo "exito";
				}

			}*/

			public function combo_municipio() {

			$this->load->view('conflictos_colectivos/sindicatos_ajax/combo_municipio',
				array(
					'id' => $this->input->post('id'),
					'municipio' => $this->db->get('org_municipio')
				)
			);

		}
}
?>
