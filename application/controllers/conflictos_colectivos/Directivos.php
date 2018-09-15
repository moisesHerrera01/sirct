<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Directivos extends CI_Controller {

	function __construct(){
		parent::__construct();
    $this->load->model( array('sindicatos_model','directivos_model'));
	}

  public function tabla_directivos(){
		$data['directivos'] = $this->directivos_model->obtener_directivos_sindicato($this->input->get('id_sindicato'));
    $this->load->view('conflictos_colectivos/sindicatos_ajax/tabla_directivos',$data);
  }

  public function gestionar_directivos(){
		if($this->input->post('band2') == "save"){
			$data = array(
			'id_sindicato' => $this->input->post('id_sindicato'),
		  'nombre_directivo' => $this->input->post('nombre_directivo'),
			'apellido_directivo' => $this->input->post('nombre_directivo'),
			'dui_directivo' => $this->input->post('dui_directivo'),
			'tipo_directivo' => $this->input->post('tipo_directivo'),
			'acreditacion_directivo' => $this->input->post('acreditacion_directivo')
			);
			$this->directivos_model->insertar_directivo($data);
			echo $data['id_sindicato'];

		}else if($this->input->post('band2') == "edit"){

			$data = array(
        'id_directivo' => $this->input->post('id_directivo'),
				'id_sindicato' => $this->input->post('id_sindicato'),
			  'nombre_directivo' => $this->input->post('nombre_directivo'),
				'apellido_directivo' => $this->input->post('nombre_directivo'),
				'dui_directivo' => $this->input->post('dui_directivo'),
				'tipo_directivo' => $this->input->post('tipo_directivo'),
				'acreditacion_directivo' => $this->input->post('acreditacion_directivo')
			);
			$this->directivos_model->editar_directivo($data);
			echo $data['id_sindicato'];
		}
	}
}
?>
