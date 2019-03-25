<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model(array('inicio_model',"login_model", "expedientes_model"));
	}

	public function index(){
		$id_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->id_rol;

		if ($id_rol == JEFE || $id_rol == FILTRO || $id_rol == DELEGADO) {
			$tipo = 1;
		}else {
			$tipo = 2;
		}
		$abreviatura = $this->expedientes_model->obtener_abreviatura_depto($this->session->userdata('nr'));
		$delegados = $this->expedientes_model->obtener_delegados_rol($tipo,$abreviatura->pre);

		$this->load->view('templates/header');
		$this->load->view('inicio',
			array(
				'id' => $this->input->post('id'),
				'colaborador' => $delegados,
				'tipo_rol' => $tipo
			));
		$this->load->view('templates/footer');
	}

	public function indicadores(){

		$data = array(
			'anio' => $this->input->post('anio'),
			'tipo' => "",//$this->input->post('tipo'),
			'mes' => $this->input->post('mes'),
			'value2' => "03",//$this->input->post('value2'),
			'id_delegado' => ""//$this->input->post('id_delegado')
		);

		$data['contadores'] = $this->inicio_model->registros_relaciones_individuales($data);
		$this->load->view('configuraciones/indicadores', $data);

	}
}
?>