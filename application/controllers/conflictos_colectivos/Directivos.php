<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Directivos extends CI_Controller {

	function __construct(){
		parent::__construct();
    $this->load->model( array('sindicatos_model','directivos_model'));
	}

  public function tabla_directivos(){
		$data['directivos'] = $this->directivos_model->obtener_directivos_sindicato($this->input->get('id_sindicato'));
		$data['sindicato'] = $this->input->get('id_sindicato');
    $this->load->view('conflictos_colectivos/sindicatos_ajax/tabla_directivos',$data);
  }

  public function gestionar_directivos(){
		if($this->input->post('band2') == "save"){
			$data = array(
			'id_sindicato' => $this->input->post('id_sindicato'),
		  'nombre_directivo' => $this->input->post('nombre_directivo'),
			'apellido_directivo' => $this->input->post('apellido_directivo'),
			'dui_directivo' => $this->input->post('dui_directivo'),
			'tipo_directivo' => $this->input->post('tipo_directivo'),
			'acreditacion_directivo' => $this->input->post('acreditacion_directivo'),
			'sexo_directivo' => $this->input->post('sexo_directivo')
			);
			$this->directivos_model->insertar_directivo($data);
			echo $data['id_sindicato'];

		}else if($this->input->post('band2') == "edit"){

			$data = array(
        'id_directivo' => $this->input->post('id_directivo'),
				'id_sindicato' => $this->input->post('id_sindicato'),
			  'nombre_directivo' => $this->input->post('nombre_directivo'),
				'apellido_directivo' => $this->input->post('apellido_directivo'),
				'dui_directivo' => $this->input->post('dui_directivo'),
				'tipo_directivo' => $this->input->post('tipo_directivo'),
				'acreditacion_directivo' => $this->input->post('acreditacion_directivo'),
				'sexo_directivo' => $this->input->post('sexo_directivo')
			);
			$this->directivos_model->editar_directivo($data);
			echo $data['id_sindicato'];
		}
	}

	public function obtener_directivo() {

		print json_encode(
			$this->directivos_model->obtener_directivo($this->input->post('id_directivo'))->result()
		);
	}

	public function bajar_directivo() {
			$data = array(
					'id_directivo' => $this->input->post('id'),
					'estado_directivo' => 0
			);
			echo $this->directivos_model->editar_directivo($data);
	}

	public function activar_directivo() {
		$data = array(
				'id_directivo' => $this->input->post('id'),
				'estado_directivo' => 1
		);
			echo $this->directivos_model->editar_directivo($data);
	}

	public function combo_directivos() {
		$directivos = $this->directivos_model->obtener_directivos_sindicato($this->input->post('id_sindicato'));
		$this->load->view('conflictos_colectivos/sindicatos_ajax/combo_directivos',
			array(
				'id' => $this->input->post('id'),
				'id_sindicato' => $this->input->post('id_sindicato'),
				'directivos' => $directivos
			)
		);
	}

	public function modal_directivos(){
		$this->load->view('conflictos_colectivos/sindicatos_ajax/modal_directivo',
			array(
				'id_directivo' => $this->input->post('id_directivo')
			));
	}

	// public function bitacora_delegados() {
	// 	$this->load->view('resolucion_conflictos/solicitudes_ajax/modal_bitacora_delegado',
	// 	array(
	// 		'id' => $this->input->post('id'),
	// 		'num_exp' => $this->input->post('num_exp')
	// 	));
	// }
}
?>
