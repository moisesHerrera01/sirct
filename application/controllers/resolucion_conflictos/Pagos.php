<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagos extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("expedientes_model","pagos_model"));
	}

  public function programar_pagos(){
    $data['expediente'] = $this->expedientes_model->obtener_expediente( $this->input->post('id') );
    $this->load->view('resolucion_conflictos/solicitudes_ajax/programar_pagos', $data);
  }

  public function tabla_pagos(){
    $data['pago'] = $this->pagos_model->obtener_pagos( $this->input->get('id_expedienteci') );
    $this->load->view('resolucion_conflictos/solicitudes_ajax/tabla_pagos',$data);
  }


	public function gestionar_pago(){
		if($this->input->post('band5') == "save"){
			$data = array(
			'fechapago_fechaspagosci' => date("Y-m-d H:i:s", strtotime($this->input->post('fecha_pago'))),
			'montopago_fechaspagosci' => $this->input->post('monto'),
			'id_expedienteci' => $this->input->post('id_expedienteci1')
			);
			echo $this->pagos_model->insertar_pago($data);

		}else if($this->input->post('band5') == "edit"){

			$data = array(
			'fechapago_fechaspagosci' =>  date("Y-m-d H:i:s", strtotime($this->input->post('fecha_pago'))),
			'montopago_fechaspagosci' => $this->input->post('monto'),
			'id_expedienteci' => $this->input->post('id_expedienteci1'),
			'id_fechaspagosci' => $this->input->post('id_fechaspagosci')
			);
			echo $this->pagos_model->editar_pago($data);

		}else if($this->input->post('band5') == "delete"){
			$data = array('id_fechaspagosci' => $this->input->post('id_fechaspagosci'));
			echo $this->pagos_model->eliminar_pago($data);
		}
	}

}