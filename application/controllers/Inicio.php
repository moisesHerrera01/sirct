<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model(array('inicio_model',"login_model"));
	}

	public function index(){
		$id_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->id_rol;

		if ($id_rol == JEFE || $id_rol == FILTRO || $id_rol == DELEGADO) {
			$tipo = 1;
		}else {
			$tipo = 2;
		}

		$data = array(
			'anio' => 2019,//$this->input->post('anio'),
			'tipo' => "",//$this->input->post('tipo'),
			'value' => "mensual",//$this->input->post('value'),
			'value2' => "03",//$this->input->post('value2'),
			'id_delegado' => ""//$this->input->post('id_delegado')
		);

		$data['contadores'] = $this->inicio_model->registros_relaciones_individuales($data);
		$data['tipo_rol'] = $tipo;
		$this->load->view('templates/header');
		$this->load->view('inicio', $data);
		$this->load->view('templates/footer');
	}
}
?>