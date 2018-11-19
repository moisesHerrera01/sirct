<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_individuales extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('reportes_individuales_model');
	}

	public function index(){
		//$data['id_modulo']=$id_modulo;
		$this->load->view('templates/header');
		$this->load->view('reportes/reportes_individuales');
		$this->load->view('templates/footer');
	}

	public function relaciones_individuales(){
		$this->load->view('templates/header');
		$this->load->view('reportes/lista_reportes_individuales/relaciones_individuales');
		$this->load->view('templates/footer');
	}

	public function renuncia_voluntaria(){
		$this->load->view('templates/header');
		$this->load->view('reportes/lista_reportes_individuales/renuncia_voluntaria');
		$this->load->view('templates/footer');
	}

	public function consolidado(){
		$this->load->view('templates/header');
		$this->load->view('reportes/lista_reportes_individuales/consolidado');
		$this->load->view('templates/footer');
	}

	function relaciones_individuales_report(){
		$data = array(
			'anio' => $this->input->post('anio'),
			'tipo' => $this->input->post('tipo'),
			'value' => $this->input->post('value'),
			'value2' => $this->input->post('value2')
		);

		$titles = array(
				'MINISTERIO DE TRABAJO Y PREVISION SOCIAL', 
				'DIRECCIÓN GENERAL DE TRABAJO', 
				'INFORME DE RELACIONES INDIVIDUALES', 
				periodo($data));

		$titles_head = array(
			'N° Exp',
			'Depto',
			'Delegado',
			'Fecha inicio',
			'Fecha fin',
			'Persona Solicitante',
			'M',
			'F',
			'Patronos',
			'Edad',
			'Personas con discapacidad',
			'Persona solicitada',
			'Causas',	
			'Rama económica',
			'Actividad económica',
			'Resolución',
			'Cantidad pagada Total',
			'Observaciones');

		$body = '';
		if($this->input->post('report_type') == "html"){
			$body .= head_table_html($titles, $data, 'html');
			$body .= $this->relaciones_individuales_html($data, $titles_head);
			echo $body;
		}else if($this->input->post('report_type') == "pdf"){
			$this->load->library('mpdf');
			$this->mpdf=new mPDF('c','legal','10','Arial',10,10,35,17,3,9);

		 	$header = head_table_html($titles, $data, 'pdf');

		 	$this->mpdf->SetHTMLHeader($header);
		 	
		 	$body .= $this->relaciones_individuales_html($data, $titles_head);

		 	$pie = piePagina($this->session->userdata('usuario'));
			$this->mpdf->setFooter($pie);

			$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
			$this->mpdf->AddPage('L','','','','',10,10,35,17,5,10);
			$this->mpdf->SetTitle($titles[2]);
			$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
			$this->mpdf->WriteHTML($body);
			$this->mpdf->Output($titles[2].date(" - Ymd_His").'.pdf','I');
		}else if($this->input->post('report_type') == "excel"){
			$this->relaciones_individuales_excel($data, $titles, $titles_head);
		}
	}

	function relaciones_individuales_html($data, $titles_head){
		$cuerpo = "";
		$cuerpo .= table_header($titles_head);

		$registros = $this->reportes_individuales_model->registros_relaciones_individuales($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$cell_row = array(
					$rows->numerocaso_expedienteci,
					$rows->departamento,
					$rows->delegado,
					fecha_ESP($rows->fecha_inicio),
					fecha_ESP($rows->fecha_fin),
					$rows->solicitante,
					$rows->cant_masc,
					$rows->cant_feme,
					'',
					$rows->edad,
					$rows->discapacidadci,
					$rows->nombre_empresa,
					$rows->causa,
					$rows->grupo_catalogociiu,
					$rows->actividad_catalogociiu,
					$rows->resultadoci,
					$rows->monto,
					''
				);
			}
			$cuerpo .= table_row($cell_row);
		}else{
			$cuerpo .= no_rows(count($titles_head));
		}
		$cuerpo .= table_footer();

		return $cuerpo;
	}

	function relaciones_individuales_excel($data, $titulos, $titles_table_head){
		$this->load->library('phpe');
		error_reporting(E_ALL); ini_set('display_errors', TRUE); ini_set('display_startup_errors', TRUE); 
		$estilo = array( 'borders' => array( 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN ) ) );

		if (PHP_SAPI == 'cli') die('Este reporte solo se ejecuta en un navegador web');

		// Create new PHPExcel object
		$this->objPHPExcel = new Phpe();

		// Set document properties
		PhpExcelSetProperties($this->objPHPExcel,"Sistema de Mediación Individual");

		$titulo = $titulos[2].date(" - Ymd_His");

		$f=1;
		$letradesde = 'A';
		$letrahasta = 'R';

		//MODIFICANDO ANCHO DE LAS COLUMNAS 18
		PhpExcelSetColumnWidth($this->objPHPExcel,
			$width = array(5,10,30,15,15,30,5,5,10,5,12,30,10,30,30,10,15,20), 
			$letradesde, $letrahasta);

		//AGREGAMOS LOS TITULOS DEL REPORTE
		$f = PhpExcelSetTitles($this->objPHPExcel,
			$title = $titulos,
		$letradesde, "L", $f);

		/************************ 	  INICIO ENCABEZADOS DE LA TABLAS	********************************/
		$f = PhpExcelAddHeaderTable($this->objPHPExcel, $titles_table_head, $letradesde, $letrahasta, $f, $estilo);
	 	/************************* 	   FIN ENCABEZADOS DE LA TABLA   	**********************************/

	 	/************************** 	   INICIO DE LOS REGISTROS DE LA TABLA   	*******************/
	 	$registros = $this->reportes_individuales_model->registros_relaciones_individuales($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$cell_row = array(
					$rows->numerocaso_expedienteci,
					$rows->departamento,
					$rows->delegado,
					fecha_ESP($rows->fecha_inicio),
					fecha_ESP($rows->fecha_fin),
					$rows->solicitante,
					$rows->cant_masc,
					$rows->cant_feme,
					'',
					$rows->edad,
					$rows->discapacidadci,
					$rows->nombre_empresa,
					$rows->causa,
					$rows->grupo_catalogociiu,
					$rows->actividad_catalogociiu,
					$rows->resultadoci,
					$rows->monto,
					''
				);
				$f = PhpExcelAddRowTable($this->objPHPExcel, $cell_row, $letradesde, $letrahasta, $f, $estilo);
			}
		}else{
			$f = PhpExcelAddNoRows($this->objPHPExcel,$letradesde, $letrahasta, $f, $estilo); //CUANDO NO HAY REGISTROS
		}

		/************************** 	   FIN DE LOS REGISTROS DE LA TABLA   	****************************/
		
		$this->objPHPExcel->getActiveSheet()->getStyle($letradesde.'1:'.$letrahasta.$this->objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

		$f+=3;

	 	$fecha=strftime( "%d-%m-%Y - %H:%M:%S", time() );
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$f,"Fecha y Hora de Creación: ".$fecha); $f++;
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$f,"Usuario: ".$this->session->userdata('usuario'));
		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($titulo);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$titulo.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
    	$writer = new PHPExcel_Writer_Excel5($this->objPHPExcel);
		header('Content-type: application/vnd.ms-excel');
		$writer->save('php://output');
	}


	function renuncia_voluntaria_report(){
		$data = array(
			'anio' => $this->input->post('anio'),
			'tipo' => $this->input->post('tipo'),
			'value' => $this->input->post('value'),
			'value2' => $this->input->post('value2')
		);

		$titles = array(
				'MINISTERIO DE TRABAJO Y PREVISION SOCIAL', 
				'DIRECCIÓN GENERAL DE TRABAJO', 
				'INFORME DE RENUNCIA VOLUNTARIA', 
				periodo($data));

		$titles_head = array( 'N° Exp', 'Depto', 'Delegado', 'Fecha inicio', 'Fecha fin',
			'Persona Solicitante', 'M', 'F', 'Patronos', 'Edad', 'Personas con discapacidad',
			'Persona solicitada', 'Causas',	'Terminación de contrato', 'Actividad económica',
			'Resolución', 'Cantidad pagada Total','Observaciones');

		$body = '';
		if($this->input->post('report_type') == "html"){
			$body .= head_table_html($titles, $data, 'html');
			$body .= $this->renuncia_voluntaria_html($data, $titles_head);
			echo $body;
		}else if($this->input->post('report_type') == "pdf"){
			$this->load->library('mpdf');
			$this->mpdf=new mPDF('c','legal','10','Arial',10,10,35,17,3,9);

		 	$header = head_table_html($titles, $data, 'pdf');

		 	$this->mpdf->SetHTMLHeader($header);
		 	
		 	$body .= $this->renuncia_voluntaria_html($data, $titles_head);

		 	$pie = piePagina($this->session->userdata('usuario'));
			$this->mpdf->setFooter($pie);

			$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
			$this->mpdf->AddPage('L','','','','',10,10,35,17,5,10);
			$this->mpdf->SetTitle($titles[2]);
			$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
			$this->mpdf->WriteHTML($body);
			$this->mpdf->Output($titles[2].date(" - Ymd_His").'.pdf','I');
		}else if($this->input->post('report_type') == "excel"){
			$this->renuncia_voluntaria_excel($data, $titles, $titles_head);
		}
	}

	function renuncia_voluntaria_html($data, $titles_head){
		$cuerpo = "";
		$cuerpo .= table_header($titles_head);

		$registros = $this->reportes_individuales_model->registros_renuncia_voluntaria($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$cell_row = array(
					$rows->numerocaso_expedienteci,
					$rows->departamento,
					$rows->delegado,
					fecha_ESP($rows->fecha_inicio),
					fecha_ESP($rows->fecha_fin),
					$rows->solicitante,
					$rows->cant_masc,
					$rows->cant_feme,
					'',
					$rows->edad,
					$rows->discapacidadci,
					$rows->nombre_empresa,
					$rows->causa,
					'',
					$rows->actividad_catalogociiu,
					$rows->resultadoci,
					$rows->monto,
					''
				);
			}
			$cuerpo .= table_row($cell_row);
		}else{
			$cuerpo .= no_rows(count($titles_head));
		}
		$cuerpo .= table_footer();

		return $cuerpo;
	}

	function renuncia_voluntaria_excel($data, $titulos, $titles_table_head){
		$this->load->library('phpe');
		error_reporting(E_ALL); ini_set('display_errors', TRUE); ini_set('display_startup_errors', TRUE); 
		$estilo = array( 'borders' => array( 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN ) ) );

		if (PHP_SAPI == 'cli') die('Este reporte solo se ejecuta en un navegador web');

		// Create new PHPExcel object
		$this->objPHPExcel = new Phpe();

		// Set document properties
		PhpExcelSetProperties($this->objPHPExcel,"Sistema de Mediación Individual");

		$titulo = $titulos[2].date(" - Ymd_His");

		$f=1;
		$letradesde = 'A';
		$letrahasta = 'R';

		//MODIFICANDO ANCHO DE LAS COLUMNAS 18
		PhpExcelSetColumnWidth($this->objPHPExcel,
			$width = array(5,10,30,15,15,30,5,5,10,5,12,30,10,30,30,10,15,20), 
			$letradesde, $letrahasta);

		//AGREGAMOS LOS TITULOS DEL REPORTE
		$f = PhpExcelSetTitles($this->objPHPExcel,
			$title = $titulos,
		$letradesde, "L", $f);

		/************************ 	  INICIO ENCABEZADOS DE LA TABLAS	********************************/
		$f = PhpExcelAddHeaderTable($this->objPHPExcel, $titles_table_head, $letradesde, $letrahasta, $f, $estilo);
	 	/************************* 	   FIN ENCABEZADOS DE LA TABLA   	**********************************/

	 	/************************** 	   INICIO DE LOS REGISTROS DE LA TABLA   	*******************/
	 	$registros = $this->reportes_individuales_model->registros_renuncia_voluntaria($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$cell_row = array(
					$rows->numerocaso_expedienteci,
					$rows->departamento,
					$rows->delegado,
					fecha_ESP($rows->fecha_inicio),
					fecha_ESP($rows->fecha_fin),
					$rows->solicitante,
					$rows->cant_masc,
					$rows->cant_feme,
					'',
					$rows->edad,
					$rows->discapacidadci,
					$rows->nombre_empresa,
					$rows->causa,
					'',
					$rows->actividad_catalogociiu,
					$rows->resultadoci,
					$rows->monto,
					''
				);
				$f = PhpExcelAddRowTable($this->objPHPExcel, $cell_row, $letradesde, $letrahasta, $f, $estilo);
			}
		}else{
			$f = PhpExcelAddNoRows($this->objPHPExcel,$letradesde, $letrahasta, $f, $estilo); //CUANDO NO HAY REGISTROS
		}

		/************************** 	   FIN DE LOS REGISTROS DE LA TABLA   	****************************/
		
		$this->objPHPExcel->getActiveSheet()->getStyle($letradesde.'1:'.$letrahasta.$this->objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

		$f+=3;

	 	$fecha=strftime( "%d-%m-%Y - %H:%M:%S", time() );
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$f,"Fecha y Hora de Creación: ".$fecha); $f++;
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$f,"Usuario: ".$this->session->userdata('usuario'));
		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($titulo);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$titulo.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
    	$writer = new PHPExcel_Writer_Excel5($this->objPHPExcel);
		header('Content-type: application/vnd.ms-excel');
		$writer->save('php://output');
	}

	function consolidado_report(){
		$data = array(
			'anio' => $this->input->post('anio'),
			'tipo' => $this->input->post('tipo'),
			'value' => $this->input->post('value'),
			'value2' => $this->input->post('value2')
		);

		$titles = array(
				'MINISTERIO DE TRABAJO Y PREVISION SOCIAL', 
				'OFICINA DE ESTADÍSTICA E INFORMACIÓN LABORAL', 
				'CONSOLIDADO DE SOLICITUDES', 
				periodo($data));

		$titles_head = array( 'N° Exp', 'Depto', 'Delegado', 'Fecha inicio', 'Fecha fin',
			'Persona Solicitante', 'M', 'F', 'Patronos', 'Edad', 'Personas con discapacidad',
			'Persona solicitada', 'Causas',	'Terminación de contrato', 'Actividad económica',
			'Resolución', 'Cantidad pagada Total','Observaciones');

		$textadd = "DEPARTEMENTO DE SAN SALVADOR, ".get_fecha();

		$body = '';
		if($this->input->post('report_type') == "html"){
			$body .= head_table_html($titles, $data, 'html').$textadd;
			$body .= $this->consolidado_html($data, $titles_head);
			echo $body;
		}else if($this->input->post('report_type') == "pdf"){
			$this->load->library('mpdf');
			$this->mpdf=new mPDF('c','letter','10','Arial',10,10,35,17,3,9);

		 	$header = head_table_html($titles, $data, 'pdf');

		 	$this->mpdf->SetHTMLHeader($header);
		 	
		 	$body .= $textadd;
		 	$body .= $this->consolidado_html($data, $titles_head);

		 	$pie = piePagina($this->session->userdata('usuario'));
			$this->mpdf->setFooter($pie);

			$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
			$this->mpdf->AddPage('P','letter','','','',10,10,35,17,5,10);
			$this->mpdf->SetTitle($titles[2]);
			$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
			$this->mpdf->WriteHTML($body);
			$this->mpdf->Output($titles[2].date(" - Ymd_His").'.pdf','I');
		}else if($this->input->post('report_type') == "excel"){
			header("Content-type: application/octet-stream");
			header('Content-Type: text/html; charset=utf-8');
			header("Content-Disposition: attachment; filename=consumo_vales.xls");
			header("Pragma: no-cache");
			header("Expires: 0");

			$body .= '<!DOCTYPE HTML>
			<head>
			    <meta http-equiv="Content-Type" content="text/html" charset=utf-8" />
			</head>
			<body>';
			$body .= head_table_html($titles, $data, 'html').$textadd;
			$body .= $this->consolidado_html($data, $titles_head);
			$body .= '</body></html>';

			echo $body;

			//$this->consolidado_excel($data, $titles, $titles_head);
		}
	}

	function consolidado_html($data, $titles_head){
		$cuerpo = "";

		/**************************** PENDIENTES DEL MES ANTERIOR **************************/
		$table_header1 = array('','M','F','TOTAL');
		$cuerpo .= table_header($table_header1);
		$registros = $this->reportes_individuales_model->registros_consolidado_pendientes($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$cell_row = array(
					$rows->texto,
					$rows->cant_masc,
					$rows->cant_feme,
					$rows->cant_total
				);
				$cuerpo .= table_row($cell_row);
			}
		}
		$cuerpo .= table_footer()."<br>";

		/**************************** REGISTRADOS EN EL MES ACTUAL **************************/
		$cuerpo .= '<table border="1" style="width:100%; border-collapse: collapse;"><tr><td style="padding: 10px;">';

		$table_header1 = array('','M','F','TOTAL');
		$cuerpo .= table_header($table_header1);
		$registros = $this->reportes_individuales_model->registros_consolidado_recibidos($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$cell_row = array(
					$rows->texto,
					$rows->cant_masc,
					$rows->cant_feme,
					$rows->cant_total
				);
				$cuerpo .= table_row($cell_row);
			}
		}
		$cuerpo .= table_footer()."<br>";

					/********************** REGISTRADOS EN EL MES ACTUAL POR CAUSA **************************/
		$table_header1 = array('','M','F','TOTAL');
		$cuerpo .= table_header($table_header1);
		$registros = $this->reportes_individuales_model->registros_consolidado_recibidos_por_causa($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$cell_row = array(
					$rows->nombre_motivo,
					$rows->cant_masc,
					$rows->cant_feme,
					$rows->cant_total
				);
				$cuerpo .= table_row($cell_row);
			}
		}else{
			$cuerpo .= no_rows(count($table_header1));
		}
		$cuerpo .= table_footer();

		$cuerpo .= '</td></tr></table><br>';

		/********************** CASOS FINALIZADOS **************************/

		$cuerpo .= '<table border="1" style="width:100%; border-collapse: collapse;"><tr><td style="padding: 10px;">';

		$total = 0;
		$registros = $this->reportes_individuales_model->registros_consolidado_casos_finalizados($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$total+=$rows->cant_masc;
			}
		}else{ $total = 0; }
		$cuerpo .= table_header(array('CASOS FINALIZADOS', $total));
		$cuerpo .= table_footer();


		$table_header1 = array('','M','F','TOTAL');
		$masc = 0; $feme = 0;
		$cuerpo .= table_header($table_header1);
		$registros = $this->reportes_individuales_model->registros_consolidado_casos_finalizados($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$masc+=$rows->cant_masc;
				$feme+=$rows->cant_feme;
				$cell_row = array(
					$rows->resultado,
					$rows->cant_masc,
					$rows->cant_feme,
					$rows->cant_total
				);
				$cuerpo .= table_row($cell_row);
			}

			$cell_row = array(
				'TOTAL',
				$masc,
				$feme,
				($masc+$feme)
			);
			$cuerpo .= table_row($cell_row);
		}else{
			$cuerpo .= no_rows(count($table_header1));
		}
		$cuerpo .= table_footer();

		$cuerpo .= '</td></tr></table><br>';

		/********************************** EXPEDIENTES PENDIENTES ************************************/

		$masc = 0; $feme = 0;
		$registros = $this->reportes_individuales_model->registros_consolidado_expedientes_pendientes($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$masc+=$rows->cant_masc;
				$feme+=$rows->cant_feme;
			}
		}else{ $total = 0; }
		$cuerpo .= table_header(array($rows->texto, 'TOTAL: '.($feme+$masc), 'MUJERES: '.$feme, 'HOMBRES: '.$masc));
		$cuerpo .= table_footer()."<br>";

		/*******************************  ***********************************/

		$cuerpo .= '<table style="width:100%; border: 1px solid black;"><tr>
			<th>PERSONAS TRABAJADORAS</th>
			<th>AUDIENCIAS CELEBRADAS EN EL MES</th>
			<th>MONTOS ACORDADOS</th>
		</tr><tr>';

		$cuerpo .= '<td style="padding: 10px;" align="center">';
			$cuerpo .= '<b>DESPEDIDAS</b>';
			$total = 0;
			$registros = $this->reportes_individuales_model->registros_consolidado_personas_despedidas($data);
			if($registros->num_rows()>0){
				foreach ($registros->result() as $rows) {
					$total+=$rows->cant_masc;
				}
			}else{ $total = 0; }
			$cuerpo .= table_header(array($total));
			$cuerpo .= table_footer();

		$cuerpo .= '</td>';
		$cuerpo .= '<td style="padding: 10px;" align="center">';
			$cuerpo .= '<b><small>(Total de conciliadas, sin consiliar y reinstalo)</small></b>';
			$total = 0;
			$registros = $this->reportes_individuales_model->registros_consolidado_audiencias($data);
			if($registros->num_rows()>0){
				foreach ($registros->result() as $rows) {
					$total+=$rows->cant_masc;
				}
			}else{ $total = 0; }
			$cuerpo .= table_header(array($total));
			$cuerpo .= table_footer();

		$cuerpo .= '</td>';
		$cuerpo .= '<td style="padding: 10px;">';


			$masc = 0; $feme = 0;
			$cuerpo .= table_no_header();
			$registros = $this->reportes_individuales_model->registros_consolidado_pagos($data);
			if($registros->num_rows()>0){
				foreach ($registros->result() as $rows) {
					$cell_row = array(
						"TOTAL",
						$rows->cant_total,
						"$ ".$rows->monto_total
					);
					$cuerpo .= table_row($cell_row);
					$cell_row = array(
						"MUJERES",
						$rows->cant_feme,
						"$ ".$rows->monto_feme
					);
					$cuerpo .= table_row($cell_row);
					$cell_row = array(
						"HOMBRES",
						$rows->cant_masc,
						"$ ".$rows->monto_masc
					);
					$cuerpo .= table_row($cell_row);
				}
			}
			$cuerpo .= table_footer();

		$cuerpo .= '</td>';

		$cuerpo .= '</tr></table><br>';



		return $cuerpo;
	}

	function consolidado_excel($data, $titulos, $titles_table_head){
		$this->load->library('phpe');
		error_reporting(E_ALL); ini_set('display_errors', TRUE); ini_set('display_startup_errors', TRUE); 
		$estilo = array( 'borders' => array( 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN ) ) );

		if (PHP_SAPI == 'cli') die('Este reporte solo se ejecuta en un navegador web');

		// Create new PHPExcel object
		$this->objPHPExcel = new Phpe();

		// Set document properties
		PhpExcelSetProperties($this->objPHPExcel,"Sistema de Mediación Individual");

		$titulo = $titulos[2].date(" - Ymd_His");

		$f=1;
		$letradesde = 'A';
		$letrahasta = 'R';

		//MODIFICANDO ANCHO DE LAS COLUMNAS 18
		PhpExcelSetColumnWidth($this->objPHPExcel,
			$width = array(5,10,30,15,15,30,5,5,10,5,12,30,10,30,30,10,15,20), 
			$letradesde, $letrahasta);

		//AGREGAMOS LOS TITULOS DEL REPORTE
		$f = PhpExcelSetTitles($this->objPHPExcel,
			$title = $titulos,
		$letradesde, "L", $f);

		/************************ 	  INICIO ENCABEZADOS DE LA TABLAS	********************************/
		$f = PhpExcelAddHeaderTable($this->objPHPExcel, $titles_table_head, $letradesde, $letrahasta, $f, $estilo);
	 	/************************* 	   FIN ENCABEZADOS DE LA TABLA   	**********************************/

	 	/************************** 	   INICIO DE LOS REGISTROS DE LA TABLA   	*******************/
	 	$registros = $this->reportes_individuales_model->registros_renuncia_voluntaria($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$cell_row = array(
					$rows->numerocaso_expedienteci,
					$rows->departamento,
					$rows->delegado,
					fecha_ESP($rows->fecha_inicio),
					fecha_ESP($rows->fecha_fin),
					$rows->solicitante,
					$rows->cant_masc,
					$rows->cant_feme,
					'',
					$rows->edad,
					$rows->discapacidadci,
					$rows->nombre_empresa,
					$rows->causa,
					'',
					$rows->actividad_catalogociiu,
					$rows->resultadoci,
					$rows->monto,
					''
				);
				$f = PhpExcelAddRowTable($this->objPHPExcel, $cell_row, $letradesde, $letrahasta, $f, $estilo);
			}
		}else{
			$f = PhpExcelAddNoRows($this->objPHPExcel,$letradesde, $letrahasta, $f, $estilo); //CUANDO NO HAY REGISTROS
		}

		/************************** 	   FIN DE LOS REGISTROS DE LA TABLA   	****************************/
		
		$this->objPHPExcel->getActiveSheet()->getStyle($letradesde.'1:'.$letrahasta.$this->objPHPExcel->getActiveSheet()->getHighestRow())->getAlignment()->setWrapText(true); 

		$f+=3;

	 	$fecha=strftime( "%d-%m-%Y - %H:%M:%S", time() );
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$f,"Fecha y Hora de Creación: ".$fecha); $f++;
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$f,"Usuario: ".$this->session->userdata('usuario'));
		// Rename worksheet
		$this->objPHPExcel->getActiveSheet()->setTitle($titulo);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$titulo.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
    	$writer = new PHPExcel_Writer_Excel5($this->objPHPExcel);
		header('Content-type: application/vnd.ms-excel');
		$writer->save('php://output');
	}


}
?>
