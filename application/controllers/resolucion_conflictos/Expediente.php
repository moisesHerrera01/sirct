<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expediente extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("solicitudes_model","representante_persona_model","expedientes_model","empleadores_model",
		"pagos_model","login_model","delegados_model"));
	}

	public function gestionar_expediente() {
		 $fecha_actual=date("Y-m-d H:i:s");

		if($this->input->post('band2') == "save"){

						$data3 = array(
							'nombre_empleador' => $this->input->post('nombres_jefe'),
							'apellido_empleador' => $this->input->post('apellidos_jefe'),
							'cargo_empleador' => $this->input->post('cargo_jefe')
						);

						$data2 = array(
                'motivo_expedienteci' => $this->input->post('motivo'),
                'descripmotivo_expedienteci' => $this->input->post('descripcion_motivo'),
								'id_personaci' => $this->input->post('id_personaci'),
								'causa_expedienteci' => $this->input->post('causa_expedienteci'),
								'id_personal' => $this->input->post('id_personal'),
								'id_empresaci' => $this->input->post('id_empresaci'),
								'id_estadosci' => 1,
								'fechacrea_expedienteci' => $fecha_actual,
								'tiposolicitud_expedienteci' =>"1",
								'id_representanteci' => $this->input->post('id_representanteci'),
								'numerocaso_expedienteci' =>10,
								'salario_personaci' => $this->input->post('salario'),
								'funciones_personaci' => $this->input->post('funciones'),
								'formapago_personaci' => $this->input->post('forma_pago'),
								'horarios_personaci' => $this->input->post('horario'),
								'fechaconflicto_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_conflicto'))),
								'ocupacion' => $this->input->post('ocupacion'),
								'embarazada' => $this->input->post('embarazada'),
								'tipo_representante_menor' => $this->input->post('acompaniante'),
								'nombre_representante_menor' => $this->input->post('nombre_acompaniante')
            );
						if ($this->input->post('nombres_jefe')!='') {
							$id_empleador = $this->empleadores_model->insertar_empleador($data3);
							if ("fracaso" != $id_empleador) {
									$data2['id_empleador'] = $id_empleador;
							}
						}

						$data = array(
							'id_representantepersonaci'=>$this->input->post('id_representante_persona')
						 );
						echo $id_expedienteci = $this->expedientes_model->insertar_expediente($data2);
						$data['id_expedienteci'] = $id_expedienteci;
						$this->representante_persona_model->editar_representante($data);
						$delegado = array(
							'id_expedienteci' => $id_expedienteci,
							'id_personal' => $data2['id_personal'],
							'fecha_cambio_delegado' => date('Y-m-d')
						);
						$this->delegados_model->insertar_delegado_exp($delegado);

		} else if($this->input->post('band2') == "edit"){

			$data3 = array(
				'id_empleador' => $this->input->post('id_emplea'),
				'nombre_empleador' => $this->input->post('nombres_jefe'),
				'apellido_empleador' => $this->input->post('apellidos_jefe'),
				'cargo_empleador' => $this->input->post('cargo_jefe')
			);

			$data2 = array(
					'id_expedienteci' => $this->input->post('id_expedienteci'),
					'motivo_expedienteci' => $this->input->post('motivo'),
					'descripmotivo_expedienteci' => $this->input->post('descripcion_motivo'),
					'id_personaci' => $this->input->post('id_personaci'),
					'id_personal' => $this->input->post('id_personal'),
					'causa_expedienteci' => $this->input->post('causa_expedienteci'),
					'id_empresaci' => $this->input->post('id_empresaci'),
					'id_representanteci' => $this->input->post('id_representanteci'),
					'fechacrea_expedienteci' =>  date("Y-m-d H:i:s", strtotime($this->input->post('fecha_creacion_exp'))),
					'tiposolicitud_expedienteci' =>"1",
					'salario_personaci' => $this->input->post('salario'),
					'funciones_personaci' => $this->input->post('funciones'),
					'formapago_personaci' => $this->input->post('forma_pago'),
					'horarios_personaci' => $this->input->post('horario'),
					'fechaconflicto_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_conflicto'))),
					'ocupacion' => $this->input->post('ocupacion'),
					'id_empleador' => $this->input->post('id_emplea'),
					'embarazada' => $this->input->post('embarazada'),
					'tipo_representante_menor' => $this->input->post('acompaniante'),
					'nombre_representante_menor' => $this->input->post('nombre_acompaniante')
			);

			$data = array(
				'id_expedienteci' => $data2['id_expedienteci'],
				'id_representantepersonaci'=>$this->input->post('id_representante_persona')
			 );
			$this->empleadores_model->editar_empleador($data3);
			$this->representante_persona_model->editar_representante($data);
			echo $this->expedientes_model->editar_expediente($data2);

			/*if ("fracaso" != $this->empleadores_model->editar_empleador($data3)) {
				 $this->expedientes_model->editar_expediente($data2);
			} else {
				echo "fracaso";
			}*/

		}/*else if($this->input->post('band') == "delete"){
			$data = array(
				'id_expedientert' => $this->input->post('id_expedientert')
			);
			echo $this->reglamento_model->eliminar_documento($data);

		}*/

    }

		public function combo_delegado() {
			$id_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->id_rol;
      if ($id_rol == DELEGADO || $id_rol == FILTRO || $id_rol == JEFE) {
        $tipo = 1;
      }else {
        $tipo = 2;
      }
			$abreviatura = $this->expedientes_model->obtener_abreviatura_depto($this->session->userdata('nr'));
			$delegados = $this->expedientes_model->obtener_delegados_rol($tipo,$abreviatura->pre);
			$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_delegado',
				array(
					'id' => $this->input->post('id'),
					'colaborador' => $delegados
				)
			);

		}

		public function combo_cambiar_delegado() {
			$id_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->id_rol;
			if ($id_rol == DELEGADO || $id_rol == FILTRO || $id_rol == JEFE) {
				$tipo = 1;
			}else {
				$tipo = 2;
			}
			$abreviatura = $this->expedientes_model->obtener_abreviatura_depto($this->session->userdata('nr'));
			$delegados = $this->expedientes_model->obtener_delegados_rol($tipo,$abreviatura->pre);
			$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_cambiar_delegado',
				array(
					'id' => $this->input->post('id'),
					'colaborador' => $delegados
				)
			);

		}

		public function combo_delega2() {
			$id_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->id_rol;
			if ($id_rol == DELEGADO || $id_rol == FILTRO || $id_rol == JEFE) {
				$tipo = 1;
			}else {
				$tipo = 2;
			}
			$abreviatura = $this->expedientes_model->obtener_abreviatura_depto($this->session->userdata('nr'));
			$delegados = $this->expedientes_model->obtener_delegados_rol($tipo,$abreviatura->pre);
			$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_delega2',
				array(
					'id' => $this->input->post('id'),
					'colaborador' => $delegados
				)
			);

		}

		public function combo_delegado_tabla() {
			$id_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->id_rol;
      if ($id_rol == DELEGADO || $id_rol == FILTRO || $id_rol == JEFE) {
        $tipo = 1;
      }else {
        $tipo = 2;
      }
			$abreviatura = $this->expedientes_model->obtener_abreviatura_depto($this->session->userdata('nr'));
			$delegados = $this->expedientes_model->obtener_delegados_rol($tipo,$abreviatura->pre);
			$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_delegado_tabla',
				array(
					'id' => $this->input->post('id'),
					'colaborador' => $delegados
				)
			);

		}

		public function combo_estados_civiles() {
			$estados = $this->expedientes_model->obtener_estados_civiles();
			$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_estados_civiles',
				array(
					'id' => $this->input->post('id'),
					'estados' => $estados
				)
			);

		}

		public function combo_resultados() {
			$resultados = $this->expedientes_model->obtener_resultados();
			$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_resultados',
				array(
					'id' => $this->input->post('id'),
					'resultados' => $resultados
				)
			);

		}

		public function combo_profesiones() {
			$profesiones = $this->expedientes_model->obtener_profesiones();
			$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_profesiones',
				array(
					'id' => $this->input->post('id'),
					'profesiones' => $profesiones
				)
			);

		}

		public function registros_expedientes() {

			print json_encode(
				$this->expedientes_model->obtener_registros_expedientes($this->input->post('id'))->result()
			);
		}


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
				*/
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
					'fecha_cambio_delegado' => date("Y-m-d")
				);
				echo $this->delegados_model->insertar_delegado_exp($data);
			}

			public function cambiar_estado() {
				$data = array(
					'id_expedienteci' => $this->input->post('id_expedienteci'),
					'id_estadosci' => $this->input->post('id_estadosci')
				);
				echo $this->expedientes_model->editar_expediente($data);
			}

			// public function resolucion_expediente() {
			// 	$this->load->view('resolucion_conflictos/solicitudes_ajax/resolucion_expediente', array('id' => $this->input->post('id') ));
			// }

	public function gestionar_resolucion_expediente() {
		$data['tipocociliacion_expedienteci'] = $this->input->post('tipo_conciliacion');
		$data['id_expedienteci'] = $this->input->post('id_expedienteci');
		$data['resultado_expedienteci'] = $this->input->post('resolucion');
		$data['fecha_resultado'] = date("Y-m-d",strtotime($this->input->post('fecha_resultado')));
		$data['detalle_resultado'] = $this->input->post('detalle_resultado');
		$data['inasistencia'] = $this->input->post('inasistencia');

		$id_expedienteci = $this->expedientes_model->editar_expediente($data);
		$data2 = array(
						'id_expedienteci' => $id_expedienteci,
						'fechapago_fechaspagosci' =>  date("Y-m-d H:i:s", strtotime($this->input->post('fecha_pago')))
					);

			if ($this->input->post('resolucion')=="Conciliado") {
				if ($this->input->post('tipo_conciliacion') == "Pago en el momento") {
					$data2['montopago_fechaspagosci'] = $this->input->post('monto_pago');
				}else {
					$data2['indemnizacion_fechaspagosci'] = ($this->input->post('monto_pago')-$this->input->post('primer_pago'));
					$data2['montopago_fechaspagosci'] = $this->input->post('primer_pago');
				}
				$this->pagos_model->insertar_pago($data2);
			}
		}
}
?>
