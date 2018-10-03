<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audiencias extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("expedientes_model","audiencias_model"));
	}

  public function programar_audiencias(){
    $data['expediente'] = $this->expedientes_model->obtener_expediente( $this->input->post('id') );
    $this->load->view('resolucion_conflictos/solicitudes_ajax/programar_audiencias', $data);
  }

	public function reprogramar_audiencia(){

		$audiencia = $this->audiencias_model->obtener_audiencias($this->input->post('id'),2,1)->result_array()[0];
		$data = array(
		'fecha_fechasaudienciasci' => date("Y-m-d",strtotime($this->input->post('fecha'))),
		'hora_fechasaudienciasci' => date("H:i:s",strtotime($this->input->post('hora'))),
		'id_expedienteci' => $this->input->post('id'),
		'numero_fechasaudienciasci' => 2,
		'estado_audiencia' => 1
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
    $data['audiencia'] = $this->audiencias_model->obtener_audiencias( $this->input->get('id_expedienteci') );
    $this->load->view('resolucion_conflictos/solicitudes_ajax/tabla_audiencias',$data);
  }


	public function gestionar_audiencia(){

		if($this->input->post('band4') == "save"){
			$data = array(
			'fecha_fechasaudienciasci' => date("Y-m-d",strtotime($this->input->post('fecha_audiencia'))),
			'hora_fechasaudienciasci' => date("H:i:s",strtotime($this->input->post('hora_audiencia'))),
			'id_expedienteci' => $this->input->post('id_expedienteci1'),
			'numero_fechasaudienciasci' => $this->input->post('numero_audiencia'),
			'estado_audiencia' => 1
			);
			$exp = $this->expedientes_model->obtener_expediente($data['id_expedienteci'])->result_array()[0];
			$resultado = $this->audiencias_model->obtener_audiencias_delegado($exp['nr'],$data['fecha_fechasaudienciasci'],$data['hora_fechasaudienciasci']);
			if ($resultado) {
				echo 'ya_existe';
			}else {
				$numero = $this->audiencias_model->obtener_audiencias($this->input->post('id_expedienteci1'),FALSE,1)->num_rows();
				if ($numero>=2) {
				echo 'reprogramar';
			}else {
				echo $this->audiencias_model->insertar_audiencia($data);
			}
			}


		}else if($this->input->post('band4') == "edit"){

			$data = array(
			'fecha_fechasaudienciasci' => date("Y-m-d",strtotime($this->input->post('fecha_audiencia'))),
			'hora_fechasaudienciasci' => $this->input->post('hora_audiencia'),
			'id_expedienteci' => $this->input->post('id_expedienteci1'),
			'id_fechasaudienciasci' => $this->input->post('id_fechasaudienciasci')
			);
			echo $this->audiencias_model->editar_audiencia($data);

		}else if($this->input->post('band4') == "delete"){
			$data = array(
			'id_fechasaudienciasci' => $this->input->post('id_fechasaudienciasci'),
			);
			echo $this->audiencias_model->eliminar_audiencia($data);
		}
	}

}
