<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consultar_fechas extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("expedientes_model", "audiencias_model","pagos_model","login_model"));
	}

  public function index(){
    $this->load->view('templates/header');
    $this->load->view('resolucion_conflictos/consultar_fechas');
    $this->load->view('templates/footer');
  }

  public function calendario(){
		$eventos= array();
		$nombre_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->nombre_rol;

		if ($nombre_rol == 'Delegado(a) CCIT' || $nombre_rol == 'FILTRO CCIT' || $nombre_rol == 'JEFE CCIT') {
			$tipo = 1;
		}else {
			$tipo = 2;
		}
    $data = $this->audiencias_model->obtener_audiencias_delegado( $this->input->get('nr'),FALSE,FALSE,$tipo );
		$data2= $this->pagos_model->obtener_pagos_delegado($this->input->get('nr'),$tipo);

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
				if ($au->tiposolicitud_expedienteci=='Conciliación' || $au->tiposolicitud_expedienteci == 'Indemnización y Prestaciones Laborales') {
				 	$solicitante = strtoupper($au->persona);
				}elseif ($au->tiposolicitud_expedienteci == 'Diferencia Laboral') {
					$solicitante = strtoupper($au->nombre_sindicato);
				}

				 $eventos[] = array(
					 									'id' => $id,
														'title' => $title,
														'start' => $start,
														'end' => $end,
														'inicio' => $inicio,
														'fin' => $fin,
														'tipo'=>$tipo,
														'delegado'=>$delegado,
														'persona' =>$solicitante
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
				$tipo = strtoupper($pago->tiposolicitud_expedienteci);
				$delegado = $pago->nombre_completo;
				if ($pago->tiposolicitud_expedienteci=='Conciliación' || $pago->tiposolicitud_expedienteci == 'Indemnización y Prestaciones Laborales') {
				 $solicitante = strtoupper($pago->persona);
			 }elseif ($pago->tiposolicitud_expedienteci == 'Diferencia Laboral') {
				 $solicitante = strtoupper($pago->nombre_sindicato);
			 }

				$eventos[] = array(
													 'id' => $id,
													 'title' => $title,
													 'start' => $start,
													 'end' => $end,
													 'inicio' => $inicio,
													 'fin' => $fin,
													 'tipo'=>$tipo,
													 'delegado'=>$delegado,
													 'persona' =>$solicitante
												 );
			}
		}
		if ($eventos==NULL) {
			echo('No se encontraron resultados');
		}else {
			$arrayJson = json_encode($eventos);

			print_r($arrayJson);
		}
  }
}
