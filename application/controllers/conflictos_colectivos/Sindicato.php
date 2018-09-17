<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sindicato extends CI_Controller {

	function __construct(){
		parent::__construct();
    $this->load->model( array('sindicatos_model'));
	}

	public function index(){
		$data['municipio'] = $this->sindicatos_model->obtener_municipios();
		$this->load->view('templates/header');
		$this->load->view('conflictos_colectivos/sindicatos',$data);
		$this->load->view('templates/footer');
	}

  public function tabla_sindicatos(){
		$data['sindicatos'] = $this->sindicatos_model->obtener_expedientes_sindicatos($this->input->get('nr'),$this->input->get('tipo'));
    $this->load->view('conflictos_colectivos/sindicatos_ajax/tabla_sindicatos',$data);
  }

  public function gestionar_sindicato(){
		if($this->input->post('band1') == "save"){
			$data = array(
		  'nombre_sindicato' => $this->input->post('nombre_sindicato'),
			'id_municipio' => $this->input->post('municipio'),
			'direccion_sindicato' => $this->input->post('direccion_sindicato'),
			'telefono_sindicato' => $this->input->post('telefono_sindicato'),
			'totalafiliados_sindicato' => $this->input->post('totalafiliados_sindicato')
			);
			echo $this->sindicatos_model->insertar_sindicato($data);

		}else if($this->input->post('band1') == "edit"){

			$data = array(
        'id_sindicato' => $this->input->post('id_sindicato'),
        'nombre_sindicato' => $this->input->post('nombre_sindicato'),
  			'id_municipio' => $this->input->post('municipio'),
  			'direccion_sindicato' => $this->input->post('direccion_sindicato'),
  			'telefono_sindicato' => $this->input->post('telefono_sindicato'),
  			'totalafiliados_sindicato' => $this->input->post('totalafiliados_sindicato')
			);
			echo $this->sindicatos_model->editar_sindicato($data);
		}
	}
}
?>
