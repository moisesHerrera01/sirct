<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audiencias extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("expedientes_model","audiencias_model","pagos_model","login_model"));
	}

  public function programar_audiencias(){
    $data['expediente'] = $this->expedientes_model->obtener_expediente( $this->input->post('id') );
    $this->load->view('resolucion_conflictos/solicitudes_ajax/programar_audiencias', $data);
  }

	public function reprogramar_audiencia(){
		$exp = $this->expedientes_model->obtener_expediente($this->input->post('id'))->result_array()[0];
		if ($exp['tiposolicitud_expedienteci'] == '2') {
			$audiencia = $this->audiencias_model->obtener_audiencias($this->input->post('id'),1,1)->result_array()[0];
		}elseif ($exp['tiposolicitud_expedienteci'] == '1') {
			$audiencia = $this->audiencias_model->obtener_audiencias($this->input->post('id'),2,1)->result_array()[0];
		}

		$data = array(
		'fecha_fechasaudienciasci' => date("Y-m-d",strtotime($this->input->post('fecha'))),
		'hora_fechasaudienciasci' => date("H:i:s",strtotime($this->input->post('hora'))),
		'id_expedienteci' => $this->input->post('id'),
		'numero_fechasaudienciasci' => 2,
		'estado_audiencia' => 1,
		'id_defensorlegal' => $this->input->post('defensor'),
		'id_representaci' => $this->input->post('representante_empresa'),
		'id_delegado' => $this->input->post('delegado')
		);

		$data2 = array(
			'id_fechasaudienciasci' => $audiencia['id_fechasaudienciasci'],
			'estado_audiencia' => 0,
			'motivo_reprogramacion' =>$this->input->post('motivo')
		);
		$this->audiencias_model->editar_audiencia($data2);
		echo $this->audiencias_model->insertar_audiencia($data);
	}

  public function tabla_audiencias(){
		$id_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->id_rol;
		if ($id_rol == DELEGADO || $id_rol == FILTRO || $id_rol == JEFE) {
			$tipo = 1;
		}else {
			$tipo = 2;
		}
    $data['audiencia'] = $this->audiencias_model->obtener_audiencias( $this->input->get('id_expedienteci') );
		$data['tipo'] = $tipo;
    $this->load->view('resolucion_conflictos/solicitudes_ajax/tabla_audiencias',$data);
  }


	public function gestionar_audiencia(){
		if($this->input->post('band4') == "save"){
			$data = array(
			'fecha_fechasaudienciasci' => date("Y-m-d",strtotime($this->input->post('fecha_audiencia'))),
			'hora_fechasaudienciasci' => date("H:i:s",strtotime($this->input->post('hora_audiencia'))),
			'id_expedienteci' => $this->input->post('id_expedienteci1'),
			'numero_fechasaudienciasci' => $this->input->post('numero_audiencia'),
			'estado_audiencia' => 1,
			'id_defensorlegal' => $this->input->post('defensor'),
			'id_representaci' => $this->input->post('representante_empresa'),
			'id_delegado' => $this->input->post('delegado')
			);
			$exp = $this->expedientes_model->obtener_expediente($data['id_expedienteci'])->result_array()[0];
			$resultado = $this->audiencias_model->obtener_audiencias_delegado($exp['nr'],$data['fecha_fechasaudienciasci'],$data['hora_fechasaudienciasci']);
			if ($resultado) {
				echo 'ya_existe';
			}else {
				$numero = $this->audiencias_model->obtener_audiencias($this->input->post('id_expedienteci1'),FALSE,1);
				if ($numero) {
					$numero = $this->audiencias_model->obtener_audiencias($this->input->post('id_expedienteci1'),FALSE,1)->num_rows();
				}else {
					$numero = 0;
				}
				if ($exp['tiposolicitud_expedienteci'] == '2') {
						if ($numero>=1) {
						echo 'reprogramar';
						}else {
						echo $this->audiencias_model->insertar_audiencia($data);
					}
				}elseif($exp['tiposolicitud_expedienteci'] == '1' || $exp['tiposolicitud_expedienteci'] == '3' || $exp['tiposolicitud_expedienteci'] == '4') {
						if ($numero>=2) {
						echo 'reprogramar';
						}else {
						echo $this->audiencias_model->insertar_audiencia($data);
					}
				}
			}

		}else if($this->input->post('band4') == "edit"){

			$data = array(
			'fecha_fechasaudienciasci' => date("Y-m-d",strtotime($this->input->post('fecha_audiencia'))),
			'hora_fechasaudienciasci' => $this->input->post('hora_audiencia'),
			'id_expedienteci' => $this->input->post('id_expedienteci1'),
			'id_fechasaudienciasci' => $this->input->post('id_fechasaudienciasci'),
			'id_defensorlegal' => $this->input->post('defensor'),
			'id_representaci' => $this->input->post('representante_empresa'),
			'id_delegado' => $this->input->post('delegado')
			);
			echo $this->audiencias_model->editar_audiencia($data);

		}else if($this->input->post('band4') == "delete"){
			$data = array(
			'id_fechasaudienciasci' => $this->input->post('id_fechasaudienciasci'),
			);
			echo $this->audiencias_model->eliminar_audiencia($data);
		}
	}

	public function combo_procuradores() {
		$data = $this->audiencias_model->obtener_procuradores();
		$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_procuradores',
			array(
				'id' => $this->input->post('id'),
				'procuradores' => $data
			)
		);
	}

	public function gestionar_resolucion_audiencia() {
		$data['id_fechasaudienciasci'] = $this->input->post('id_fechasaudienciasci');
		$data['tipo_pago'] = $this->input->post('tipo_conciliacion');
		$data['id_expedienteci'] = $this->input->post('id_expedienteci');
		$data['resultado'] = $this->input->post('resolucion');
		$data['fecha_resultado'] = date("Y-m-d",strtotime($this->input->post('fecha_resultado')));
		$data['detalle_resultado'] = $this->input->post('detalle_resultado');
		$data['inasistencia'] = $this->input->post('inasistencia');
		$data['numero_folios'] = $this->input->post('numero_folios');
		$data['asistieron'] = $this->input->post('asistieron');
		$data['estado_audiencia'] = '2';
		$resultado = $this->audiencias_model->editar_audiencia($data);
		echo $resultado;
		if ($resultado == "exito") {
			$this->expedientes_model->editar_expediente(array(
			'id_expedienteci' => $this->input->post('id_expedienteci'),
			'id_estadosci' => "2"
		));
		}
		$data2 = array(
						'id_expedienteci' => $data['id_expedienteci'],
						'fechapago_fechaspagosci' =>  date("Y-m-d H:i:s", strtotime($this->input->post('fecha_pago')))
					);

			if ($this->input->post('resolucion')=="1") {
				if ($this->input->post('tipo_conciliacion') == "1") {
					$data2['montopago_fechaspagosci'] = $this->input->post('monto_pago');
				}else {
					$data2['indemnizacion_fechaspagosci'] = ($this->input->post('monto_pago')-$this->input->post('primer_pago'));
					$data2['montopago_fechaspagosci'] = $this->input->post('primer_pago');
				}
				$this->pagos_model->insertar_pago($data2);
			}
		}

		public function resolucion_audiencia() {
			$this->load->view('resolucion_conflictos/solicitudes_ajax/resolucion_expediente',
			array(
				'id' => $this->input->post('id'),
				'id_audiencia' => $this->input->post('id_audiencia')
			));
		}
}
