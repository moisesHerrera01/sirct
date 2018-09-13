<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consultar_fechas extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("expedientes_model", "audiencias_model","pagos_model"));
	}

  public function index(){
    $this->load->view('templates/header');
    $this->load->view('resolucion_conflictos/consultar_fechas');
    $this->load->view('templates/footer');
  }

  public function calendario(){
		$eventos= array();

    $data = $this->audiencias_model->obtener_audiencias_delegado( $this->input->get('nr') );
		$data2= $this->pagos_model->obtener_pagos_delegado($this->input->get('nr'));

		if ($data!=FALSE && $data!=NULL) {
			foreach ($data->result() as $au) {
				 $title = $au->numerocaso_expedienteci;
				 $start = $au->fecha_fechasaudienciasci.' '.$au->hora_fechasaudienciasci;
				 $end = $au->fecha_fechasaudienciasci.' '.$au->hora_fechasaudienciasci;
				 $inicio =date("d-M-Y g:i:s A", strtotime($au->fecha_fechasaudienciasci.' '.$au->hora_fechasaudienciasci));
				 $fin =date("d-M-Y g:i:s A", strtotime($au->fecha_fechasaudienciasci.' '.$au->hora_fechasaudienciasci.' + 1 hours'));
				 $id = $au->id_fechasaudienciasci;
				 $tipo = strtoupper($au->tiposolicitud_expedienteci);
				 $delegado = $au->delegado;
				 $persona = strtoupper($au->persona);

				 $eventos[] = array(
					 									'id' => $id,
														'title' => $title,
														'start' => $start,
														'end' => $end,
														'inicio' => $inicio,
														'fin' => $fin,
														'tipo'=>$tipo,
														'delegado'=>$delegado,
														'persona' =>$persona
													);
			}
		}
		if ($data2!=FALSE && $data2!=NULL) {
			foreach ($data2->result() as $pago) {
				$title = $pago->numerocaso_expedienteci;
				$start = $pago->fechapago_fechaspagosci;
				$end = $pago->fechapago_fechaspagosci;
				$inicio =date("d-M-Y g:i:s A", strtotime($pago->fechapago_fechaspagosci));
				$fin =date("d-M-Y g:i:s A", strtotime($pago->fechapago_fechaspagosci.'+ 1 hours'));
				$id = $pago->id_fechaspagosci;
				$tipo = strtoupper($au->tiposolicitud_expedienteci);
				$delegado = $au->delegado;
				$persona = strtoupper($au->persona);

				$eventos[] = array(
													 'id' => $id,
													 'title' => $title,
													 'start' => $start,
													 'end' => $end,
													 'inicio' => $inicio,
													 'fin' => $fin,
													 'tipo'=>$tipo,
													 'delegado'=>$delegado,
													 'persona' =>$persona
												 );
			}
		}
		if ($eventos==NULL) {
			echo('No se encontraron resultados');
		}else {
			$arrayJson = json_encode($eventos, JSON_UNESCAPED_UNICODE);
			print_r($arrayJson);
		}
  }
}
