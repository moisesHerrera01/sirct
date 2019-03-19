<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagos extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("expedientes_model","pagos_model"));
	}

  public function programar_pagos(){
		var_dump($this->pagos_model->obtener_numero_pagos(112));
    $data['expediente'] = $this->expedientes_model->obtener_expediente( $this->input->post('id') );
		$data['pagos'] = $this->pagos_model->obtener_pagos( $this->input->post('id'),1 );
    $this->load->view('resolucion_conflictos/solicitudes_ajax/programar_pagos', $data);
  }

  public function tabla_pagos(){
    $data['pago'] = $this->pagos_model->obtener_pagos( $this->input->get('id_expedienteci') );
    $this->load->view('resolucion_conflictos/solicitudes_ajax/tabla_pagos',$data);
  }


	public function gestionar_pago(){
		$monto_total = $this->input->post('monto_total');
		$monto = $this->input->post('monto');
		if ($monto_total==0) {
			$restante = 0.0;
		}else {
		  $restante = $monto_total - $monto;
		}

		if($this->input->post('band5') == "save"){
			$data = array(
			'fechapago_fechaspagosci' => date("Y-m-d H:i:s", strtotime($this->input->post('fecha_pago'))),
			'montopago_fechaspagosci' => $this->input->post('monto'),
			'indemnizacion_fechaspagosci' => $restante,
			'id_expedienteci' => $this->input->post('id_expedienteci1'),
			'id_persona' => null
			);
			echo $this->pagos_model->insertar_pago($data);

		}else if($this->input->post('band5') == "edit"){

			$data = array(
			'fechapago_fechaspagosci' =>  date("Y-m-d H:i:s", strtotime($this->input->post('fecha_pago'))),
			'montopago_fechaspagosci' => $this->input->post('monto'),
			'indemnizacion_fechaspagosci' => $this->input->post('monto_total'),
			'id_expedienteci' => $this->input->post('id_expedienteci1'),
			'id_fechaspagosci' => $this->input->post('id_fechaspagosci')
			);
			echo $this->pagos_model->editar_pago($data);

		}else if($this->input->post('band5') == "delete"){
			$data = array('id_fechaspagosci' => $this->input->post('id_fechaspagosci'));
			echo $this->pagos_model->eliminar_pago($data);
		}
	}

	public function programar_pagos_indemnizacion(){
    $data['expediente'] = $this->expedientes_model->obtener_expediente_pagos_indemnizacion( $this->input->post('id') );
    $this->load->view('conflictos_colectivos/solicitud_indemnizacion_ajax/programar_pagos', $data);
  }

  public function tabla_pagos_indemnizacion(){
    $data['pago'] = $this->pagos_model->obtener_pagos_persona( $this->input->get('id_persona') );
    $this->load->view('conflictos_colectivos/solicitud_indemnizacion_ajax/tabla_pagos',$data);
  }

	public function gestionar_pago_indemnizacion() {
		$cuenta = $this->pagos_model->obtener_numero_pagos($this->input->post('id_persona4'));
		if($this->input->post('band6') == "save"){
			$data = array(
			'fechapago_fechaspagosci' => date("Y-m-d H:i:s", strtotime($this->input->post('fecha_pago'))),
			'montopago_fechaspagosci' => $this->input->post('monto'),
			'id_persona' => $this->input->post('id_persona4'),
			'id_expedienteci' => null,
			'numero_pago' => $cuenta->cantidad + 1
			);
			echo $this->pagos_model->insertar_pago($data);

		}else if($this->input->post('band6') == "edit"){

			$data = array(
			'fechapago_fechaspagosci' => date("Y-m-d H:i:s", strtotime($this->input->post('fecha_pago'))),
			'montopago_fechaspagosci' => $this->input->post('monto'),
			'id_persona' => $this->input->post('id_persona4'),
			'id_fechaspagosci' => $this->input->post('id_fechaspagosci')
			);
			echo $this->pagos_model->editar_pago($data);

		}else if($this->input->post('band6') == "delete"){
			$data = array('id_fechaspagosci' => $this->input->post('id_fechaspagosci'));
			echo $this->pagos_model->eliminar_pago($data);
		}
	}

	public function pagos() {
		$this->load->view('resolucion_conflictos/solicitudes_ajax/modal_pagos',
		array(
			'id' => $this->input->post('id')
		));
	}

	public function gestionar_pagos_modal(){
		$this->load->library("CifrasEnLetras");
		$monto_total = number_format($this->input->post('monto_pago'),2);
		if (substr($monto_total,-3)==".00") {
			$pago_total = mb_strtoupper(CifrasEnLetras::convertirCifrasEnLetras(substr($monto_total,0,-3)).' DOLARES DE LOS ESTADOS UNIDOS DE AMERICA');
		}else {
			$pago_total = mb_strtoupper(CifrasEnLetras::convertirEurosEnLetras(number_format($monto_total,2,',','')));
		}
		$i = 1;
		$num_pago = "";
		$cant_pagos = "";
		$hora = "";
		$minuto = "";
		$pagos;

		$data = array(
			'fechapago_fechaspagosci' => date("Y-m-d H:i:s", strtotime($this->input->post('fecha_pago'))),
			'id_expedienteci' => $this->input->post('id_expedienteci')
		 );
		 if ($this->input->post('tipo_conciliacion')==1) {
			 $concat_pagos = "manifiesta el(la) Licenciado(a) ****** que las instrucciones de su Mandante son las de pagarle en este acto al trabajador solicitante la cantidad de  $pago_total , en concepto de indemnización, vacación proporcional y aguinaldo proporcional correspondiente al período del ******* al *******.";
			 $data['montopago_fechaspagosci'] = $this->input->post('monto_pago');
			 $data['indemnizacion_fechaspagosci'] = 0;
		 }else {
			 $concat_pagos = "manifiesta el(la) Licenciado(a) ****** que las instrucciones de su Mandante son las de ofrecerle al trabajador solicitante la cantidad de $pago_total , en concepto de indemnización, vacación proporcional y aguinaldo proporcional correspondiente al período del ******* al *******. Cantidad que de ser aceptada se le pagará por medio de cantidad_pagos cuotas ****** y sucesivas, ";
			 $data['montopago_fechaspagosci'] = $this->input->post('primer_pago');
			 $data['indemnizacion_fechaspagosci'] = $monto_total = $monto_total - $this->input->post('primer_pago');
			 $pagos = number_format($this->input->post('primer_pago'),2);
			 if (substr($pagos,-3)==".00") {
				 $pago = mb_strtoupper(CifrasEnLetras::convertirCifrasEnLetras(substr($pagos,0,-3)).' DOLARES DE LOS ESTADOS UNIDOS DE AMERICA');
			 }else {
				 $pago = mb_strtoupper(CifrasEnLetras::convertirEurosEnLetras(number_format($pagos,2,',','')));
			 }
			 $num_pago = convertir_a_ordinal($i);
			 $dia = dia(date('d', strtotime($this->input->post('fecha_pago'))));
			 $mes = mb_strtoupper(mes(date('m', strtotime($this->input->post('fecha_pago')))));
			 $anio = anio(date('Y', strtotime($this->input->post('fecha_pago'))));
			 $concat_pagos .= "LA $num_pago POR LA CANTIDAD DE $pago el día $dia de $mes de $anio ";
		 }
		 $this->pagos_model->insertar_pago($data);

		 while (!empty($this->input->post('fecha_pago'.$i))) {
			 $pagos = number_format($this->input->post('primer_pago'.$i),2);
			 if (substr($pagos,-3)==".00") {
				 $pago = mb_strtoupper(CifrasEnLetras::convertirCifrasEnLetras(substr($pagos,0,-3)).' DOLARES DE LOS ESTADOS UNIDOS DE AMERICA');
			 }else {
				 $pago = mb_strtoupper(CifrasEnLetras::convertirEurosEnLetras(number_format($pagos,2,',','')));
			 }
			 $dia = dia(date('d', strtotime($this->input->post('fecha_pago'.$i))));
			 $mes = mb_strtoupper(mes(date('m', strtotime($this->input->post('fecha_pago'.$i)))));
			 $anio = anio(date('Y', strtotime($this->input->post('fecha_pago'.$i))));
			 $num_pago = convertir_a_ordinal($i + 1);
			 $concat_pagos .=  ", LA $num_pago POR LA CANTIDAD DE  $pago el día $dia de $mes de $anio ";

			 $data = array(
				 'fechapago_fechaspagosci' => date("Y-m-d H:i:s", strtotime($this->input->post('fecha_pago'.$i))),
				 'montopago_fechaspagosci' => $this->input->post('primer_pago'.$i),
				 'indemnizacion_fechaspagosci' => $monto_total = $monto_total - $this->input->post('primer_pago'.$i),
				 'id_expedienteci' => $this->input->post('id_expedienteci')
				);
				$this->pagos_model->insertar_pago($data);
				$i++;
		 }
		 $hora = hora(date('G', strtotime($this->input->post('fecha_pago'))));
		 $minuto = minuto(INTVAL(date('i', strtotime($this->input->post('fecha_pago')))));
		 $cant_pagos = mb_strtoupper(CifrasEnLetras::convertirCifrasEnLetras($i));
		 $datos = $this->pagos_model->obtener_datos_pago($this->input->post('id_expedienteci'))->row();
		 if ($this->input->post('tipo_conciliacion')==1) {
			 if ($datos->tiposolicitud_empresa==2) {
				 $persona = "a la Sociedad";
			 }else {
				 $persona = "al Sr(a)";
			 }
			 $concat_pagos.="Y por su parte el Señor(a) $datos->solicitante dice que está de acuerdo con dicho monto y por lo tanto recibe a su entera satisfacción el pago. Asimismo hace entrega de la respectiva hoja de terminación de contrato, con la que exonera de toda responsabilidad laboral judicial y extra judicial $persona $datos->solicitado representada legalmente por el(la) Señor(a) $datos->r_legal";
		 }else {
		 	 $concat_pagos.="Los pagos se realizaran a las $hora horas $minuto y en estas oficinas.  Y por su parte el(la) Señor(a) $datos->solicitante dice que está de acuerdo con el monto y la forma de pago. Agrega que se hará presente los días y horas antes señalados. ";
		 }
		 $concat_pagos = str_replace("cantidad_pagos", $cant_pagos, $concat_pagos);
		 echo $concat_pagos;
	}

}
