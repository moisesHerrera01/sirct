<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Actividad extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('actividad_model');
	}

	public function index(){
		//$data['id_modulo']=$id_modulo;
		$this->load->view('templates/header');
		$this->load->view('configuraciones/actividad');
		$this->load->view('templates/footer');
	}

	public function mostrarActividad($id){
		$nuevo['depende_vyp_actividades']=$id;
		$this->load->view('configuraciones/actividad_ajax/combo_actividad',$nuevo);
	}

	public function tabla_actividad(){
		//$data['id_modulo']=$id_modulo;
		$this->load->view('configuraciones/actividad_ajax/tabla_actividad');
	}

	public function gestionar_actividad(){
		if($this->input->post('band') == "save"){
			$data = array(
			'nombre_vyp_actividades' => mb_strtoupper($this->input->post('nombre_vyp_actividades')),
			'depende_vyp_actividades' => $this->input->post('depende_vyp_actividades'),
			);
      		echo $this->actividad_model->insertar_actividad($data);
		}else if($this->input->post('band') == "edit"){
      		$data = array(
		    'id_vyp_actividades' => $this->input->post('id_vyp_actividades'),
		    'nombre_vyp_actividades' => mb_strtoupper($this->input->post('nombre_vyp_actividades')),
			'depende_vyp_actividades' => $this->input->post('depende_vyp_actividades'),
			);
			echo $this->actividad_model->editar_actividad($data);
		}else if($this->input->post('band') == "delete"){
			$data = array(
			'id_vyp_actividades' => $this->input->post('id_vyp_actividades')
			);
			echo $this->actividad_model->eliminar_actividad($data);
		}
	}
}
?>
