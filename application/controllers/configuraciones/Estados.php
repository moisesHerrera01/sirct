<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estados extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('estados_model');
	}

	public function index(){
		$this->load->view('templates/header');
		$this->load->view('configuraciones/estados');
		$this->load->view('templates/footer');
	}

	public function tabla_estados(){
		$this->load->view('configuraciones/estados_ajax/tabla_estados');
	}

	public function gestionar_estados(){

		if($this->input->post('band') == "save"){

			$data = array(
      'nombre_estadosci' => $this->input->post('nombre'),
			'descripcion_estadosci' => $this->input->post('descripcion'),
			'estado' => $this->input->post('estado')
			);
			echo $this->estados_model->insertar_estado($data);

		}else if($this->input->post('band') == "edit"){

			$data = array(
			'id_estadosci' => $this->input->post('idestado'),
      'nombre_estadosci' => $this->input->post('nombre'),
			'descripcion_estadosci' => $this->input->post('descripcion'),
			'estado' => $this->input->post('estado')
			);
			echo $this->estados_model->editar_estado($data);

		}else if($this->input->post('band') == "delete"){
			$data = array(
			'id_estadosci' => $this->input->post('idestado'),
			'estado' => $this->input->post('estado')
			);
			echo $this->estados_model->eliminar_estado($data);

		}
	}
}
?>
