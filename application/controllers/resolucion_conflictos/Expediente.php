<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expediente extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("solicitudes_model"));
		$this->load->model(array("expedientes_model"));
		$this->load->model(array("empleadores_model"));
	}

	public function gestionar_expediente() {
		 $fecha_actual=date("Y-m-d H:i:s");

		if($this->input->post('band2') == "save"){

						$data3 = array(
							'nombre_empleador' => $this->input->post('nombres_jefe'),
							'apellido_empleador' => $this->input->post('apellidos_jefe'),
							'cargo_empleador' => $this->input->post('cargo_jefe')
						);

						//date_default_timezone_set('America/El_Salvador');
						$data2 = array(
                'motivo_expedienteci' => $this->input->post('motivo'),
                'descripmotivo_expedienteci' => $this->input->post('descripcion_motivo'),
								'id_personaci' => $this->input->post('id_persona'),
								'id_personal' => $this->input->post('id_personal'),
								'id_empresaci' => $this->input->post('establecimiento'),
								'id_estadosci' => 1,
								'fechacrea_expedienteci' => $fecha_actual,
								'tiposolicitud_expedienteci' =>"Conciliación",
								'numerocaso_expedienteci' =>10
            );

						$id_empleador=$this->empleadores_model->insertar_empleador($data3);

            if ("fracaso" != $id_empleador) {
								$data = $this->solicitudes_model->obtener_persona($this->input->post('id_persona'))->result_array()[0];
								$data['salario_personaci'] = $this->input->post('salario');
								$data['funciones_personaci'] = $this->input->post('funciones');
								$data['formapago_personaci'] = $this->input->post('forma_pago');
								$data['horarios_personaci'] = $this->input->post('horario');
								$data['fechaconflicto_personaci'] = date("Y-m-d",strtotime($this->input->post('fecha_conflicto')));
								$data['id_catalogociuo'] = $this->input->post('ocupacion');
								$data['id_empleador'] = $id_empleador;
							$this->solicitudes_model->editar_solicitud($data);
							$this->expedientes_model->insertar_expediente($data2);
            } else {
                echo "fracaso";
            }

		} else if($this->input->post('band2') == "edit"){

			$data3 = array(
				'id_empleador' => $this->input->post('id_empleador'),
				'nombre_empleador' => $this->input->post('nombres_jefe'),
				'apellido_empleador' => $this->input->post('apellidos_jefe'),
				'cargo_empleador' => $this->input->post('cargo_jefe')
			);

			$data = $this->solicitudes_model->obtener_persona($this->input->post('id_persona'))->result_array()[0];
			$data['id_personaci'] = $this->input->post('id_persona');
			$data['salario_personaci'] = $this->input->post('salario');
			$data['funciones_personaci'] = $this->input->post('funciones');
			$data['formapago_personaci'] = $this->input->post('forma_pago');
			$data['horarios_personaci'] = $this->input->post('horario');
			$data['fechaconflicto_personaci'] = date("Y-m-d",strtotime($this->input->post('fecha_conflicto')));
			$data['id_catalogociuo'] = $this->input->post('ocupacion');
			$data['id_empleador'] = $this->input->post('id_empleador');

			$data2 = array(
					'id_expedienteci' => $this->input->post('id_expedienteci'),
					'motivo_expedienteci' => $this->input->post('motivo'),
					'descripmotivo_expedienteci' => $this->input->post('descripcion_motivo'),
					'id_personaci' => $this->input->post('id_persona'),
					'id_personal' => $this->input->post('id_personal'),
					'id_empresaci' => $this->input->post('establecimiento'),
					'id_estadosci' => 1,
					'fechacrea_expedienteci' => $fecha_actual,
					'tiposolicitud_expedienteci' =>"Conciliación",
					'numerocaso_expedienteci' =>10
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

		public function combo_delegado() {

			$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_delegado',
				array(
					'id' => $this->input->post('id'),
					'colaborador' => $this->db->get('lista_empleados_estado')
				)
			);

		}

		public function registros_expedientes() {

			print json_encode(
				$this->expedientes_model->obtener_registros_expedientes($this->input->post('id'))->result()
			);

		}
}
?>
