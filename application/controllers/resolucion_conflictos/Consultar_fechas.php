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
		$id_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->id_rol;
		if ($id_rol == DELEGADO || $id_rol == FILTRO || $id_rol == JEFE) {
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
				 $tipo = strtoupper($au->tipo);
				 $delegado = $au->nombre_delegado_actual;
				if ($au->tiposolicitud_expedienteci=='1' || $au->tiposolicitud_expedienteci == '5' || $au->tiposolicitud_expedienteci == '2') {
				 	$solicitante = strtoupper($au->persona);
				}elseif ($au->tiposolicitud_expedienteci == '4') {
					$solicitante = strtoupper($au->nombre_sindicato);
				}elseif ($au->tiposolicitud_expedienteci == '3') {
					$solicitante = strtoupper($au->nombre_empresa);
				}

				$ColorClass = "bg-success bg-opacity";
				if($au->fecha_fechasaudienciasci >= date("Y-m-d")){ $ColorClass = "bg-success"; }

				 $eventos[] = array(
					 									'id' => $id,
														'title' => $title,
														'start' => $start,
														'end' => $end,
														'inicio' => $inicio,
														'fin' => $fin,
														'tipo'=>$tipo,
														'delegado'=>$delegado,
														'persona' =>$solicitante,
														'className' => $ColorClass
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
				$tipo = strtoupper($pago->tipo);
				$delegado = $pago->nombre_completo;
				if ($pago->tiposolicitud_expedienteci=='1' || $pago->tiposolicitud_expedienteci == '5') {
				 $solicitante = strtoupper($pago->persona);
			 }elseif ($pago->tiposolicitud_expedienteci == '4') {
				 $solicitante = strtoupper($pago->nombre_sindicato);
			 }

			 	$ColorClass = "bg-success2 bg-opacity";
				if( date("Y-m-d", strtotime($pago->fechapago_fechaspagosci)) >= date("Y-m-d")){ $ColorClass = "bg-success2"; }

				$eventos[] = array(
													 'id' => $id,
													 'title' => $title,
													 'start' => $start,
													 'end' => $end,
													 'inicio' => $inicio,
													 'fin' => $fin,
													 'tipo'=>$tipo,
													 'delegado'=>$delegado,
													 'persona' =>$solicitante,
													 'className' => $ColorClass
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

  	public function imprimir_citas_del_dia_pdf(){
  		$data = array(
			'fecha' => $this->input->post('fecha'),
			'id_delegado' => $this->input->post('id_delegado')
		);

		$titles = array(
				'MINISTERIO DE TRABAJO Y PREVISION SOCIAL',
				'DIRECCIÓN GENERAL DE TRABAJO',
				'LISTADO DE CITAS DEL '.fecha_ESP($data["fecha"])
			);

		$titles_head = array(
			'N° Exp',
			'Delegado',
			'Solicitante',
			'Estado',
			'Hora'
		);

		$body = '';
		if($this->input->post('report_type') == "html"){
			$body .= head_table_html($titles, $data, 'html');
			$body .= $this->registros_citas_fecha($data, $titles_head);
			echo $body;
		}else if($this->input->post('report_type') == "pdf"){
			$this->load->library('mpdf');

			//$this->mpdf=new mPDF('c','letter','10','Arial',10,10,35,17,3,9);
			$this->mpdf = new \Mpdf\Mpdf();

		 	$header = head_table_html($titles, $data, 'pdf');

		 	$this->mpdf->SetHTMLHeader($header);

		 	$body .= $this->registros_citas_fecha($data, $titles_head);

		 	$pie = piePagina($this->session->userdata('usuario'));
			$this->mpdf->setFooter($pie);

			$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
			$this->mpdf->AddPage('L','','','','',10,10,35,17,5,10);
			$this->mpdf->SetTitle($titles[2]);
			$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
			$this->mpdf->WriteHTML($body);
			$this->mpdf->Output($titles[2].date(" - Ymd_His").'.pdf','I');
		}
  	}

  	function registros_citas_fecha($data, $titles_head){
  		$id_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->id_rol;
		if ($id_rol == DELEGADO || $id_rol == FILTRO || $id_rol == JEFE) {
			$tipo = 1;
		}else {
			$tipo = 2;
		}
    	$citas = $this->audiencias_model->obtener_audiencias_delegado( $data["id_delegado"],$data["fecha"],FALSE,$tipo);
		$pagos = $this->pagos_model->obtener_pagos_delegado($data["id_delegado"],$tipo,$data["fecha"]);

		$cuerpo = "<h5 aligh='center'>CITAS DE AUDIENCIAS</h5>";
		$cuerpo .= table_header($titles_head);
		if($citas){
			foreach ($citas->result() as $rows) {
				$solicitante = "";
				if ($rows->tiposolicitud_expedienteci=='1' || $rows->tiposolicitud_expedienteci == '2' || $rows->tiposolicitud_expedienteci == '5') {
					$solicitante = strtoupper($rows->persona);
				}elseif ($rows->tiposolicitud_expedienteci == '4') {
					$solicitante = strtoupper($rows->nombre_sindicato);
				}elseif ($rows->tiposolicitud_expedienteci == '3') {
					$solicitante = strtoupper($rows->nombre_empresa);
				}
				$cell_row = array(
					$rows->numerocaso_expedienteci,
					$rows->delegado,
					$solicitante,
					'resultado',
					date("h:i A",strtotime($rows->hora_fechasaudienciasci))
				);
				$cuerpo .= table_row($cell_row);
			}
		}else{
			$cuerpo .= no_rows(count($titles_head));
		}
		$cuerpo .= table_footer();

		$titles_head = array('N° Exp', 'Delegado', 'Solicitante', 'Monto', 'Hora');

		$cuerpo .= "<br><h5 aligh='center'>CITAS DE PAGOS</h5>";
		$cuerpo .= table_header($titles_head);
		if($pagos){
			foreach ($pagos->result() as $rows) {
				$cell_row = array(
					$rows->numerocaso_expedienteci,
					$rows->nombre_completo,
					$rows->persona,
					"$".$rows->montopago_fechaspagosci,
					date("h:i A",strtotime($rows->fechapago_fechaspagosci))
				);
				$cuerpo .= table_row($cell_row);
			}
		}else{
			$cuerpo .= no_rows(count($titles_head));
		}
		$cuerpo .= table_footer();

		return $cuerpo;
	}

}
