<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_colectivos extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array('reportes_colectivos_model', 'login_model', 'expedientes_model'));
	}

	public function index(){
		//$data['id_modulo']=$id_modulo;
		$this->load->view('templates/header');
		$this->load->view('reportes/reportes_colectivos');
		$this->load->view('templates/footer');
	}

	public function relaciones_colectivas(){
		$id_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->id_rol;
	    if ($id_rol == DELEGADO || $id_rol == FILTRO || $id_rol == JEFE) { $tipo = 1;
	    }else { $tipo = 2; }
		$abreviatura = $this->expedientes_model->obtener_abreviatura_depto($this->session->userdata('nr'));
		$delegados = $this->expedientes_model->obtener_delegados_rol($tipo,$abreviatura->pre);
		$this->load->view('templates/header');
		$this->load->view('reportes/lista_reportes_colectivos/relaciones_colectivas',
			array(
				'id' => $this->input->post('id'),
				'colaborador' => $delegados
			));
		$this->load->view('templates/footer');
	}

	public function registro_edades(){
		$id_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->id_rol;
	    if ($id_rol == DELEGADO || $id_rol == FILTRO || $id_rol == JEFE) { $tipo = 1;
	    }else { $tipo = 2; }
		$abreviatura = $this->expedientes_model->obtener_abreviatura_depto($this->session->userdata('nr'));
		$delegados = $this->expedientes_model->obtener_delegados_rol($tipo,$abreviatura->pre);
		$this->load->view('templates/header');
		$this->load->view('reportes/lista_reportes_colectivos/registro_edades',
			array(
				'id' => $this->input->post('id'),
				'colaborador' => $delegados
			));
		$this->load->view('templates/footer');
	}

	public function tipo_pago(){
		$id_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->id_rol;
	    if ($id_rol == DELEGADO || $id_rol == FILTRO || $id_rol == JEFE) { $tipo = 1;
	    }else { $tipo = 2; }
		$abreviatura = $this->expedientes_model->obtener_abreviatura_depto($this->session->userdata('nr'));
		$delegados = $this->expedientes_model->obtener_delegados_rol($tipo,$abreviatura->pre);
		$this->load->view('templates/header');
		$this->load->view('reportes/lista_reportes_colectivos/tipo_pago',
			array(
				'id' => $this->input->post('id'),
				'colaborador' => $delegados
			));
		$this->load->view('templates/footer');
	}

	public function consolidado(){
		$id_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->id_rol;
	    if ($id_rol == DELEGADO || $id_rol == FILTRO || $id_rol == JEFE) { $tipo = 1;
	    }else { $tipo = 2; }
		$abreviatura = $this->expedientes_model->obtener_abreviatura_depto($this->session->userdata('nr'));
		$delegados = $this->expedientes_model->obtener_delegados_rol($tipo,$abreviatura->pre);
		$this->load->view('templates/header');
		$this->load->view('reportes/lista_reportes_colectivos/consolidado',
			array(
				'id' => $this->input->post('id'),
				'colaborador' => $delegados
			));
		$this->load->view('templates/footer');
	}

	function relaciones_colectivas_report(){
		$data = array(
			'anio' => $this->input->post('anio'),
			'tipo' => $this->input->post('tipo'),
			'value' => $this->input->post('value'),
			'value2' => $this->input->post('value2'),
			'id_delegado' => $this->input->post('id_delegado')
		);

		$titles = array(
				'MINISTERIO DE TRABAJO Y PREVISION SOCIAL',
				'DIRECCIÓN GENERAL DE TRABAJO',
				'INFORME DE RELACIONES COLECTIVAS',
				periodo($data));

		$body = '';
		if($this->input->post('report_type') == "html"){
			$body .= head_table_html($titles, 'html');
			$body .= $this->relaciones_colectivas_html($data);
			echo $body;
		}else if($this->input->post('report_type') == "pdf"){
			$this->load->library('mpdf');
			$this->mpdf=new mPDF('c','letter','10','Arial',10,10,35,17,3,9);

		 	$header = head_table_html($titles, 'pdf');

		 	$this->mpdf->SetHTMLHeader($header);

		 	$body .= $this->relaciones_colectivas_html($data);

		 	$pie = piePagina($this->session->userdata('usuario'));
			$this->mpdf->setFooter($pie);

			$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
			$this->mpdf->AddPage('L','','','','',10,10,35,17,5,10);
			$this->mpdf->SetTitle('Asistencia a personas usuarias');
			$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
			$this->mpdf->WriteHTML($body);
			$this->mpdf->Output('Informe de gestion - '.$sufijo.'.pdf','I');
		}else if($this->input->post('report_type') == "excel"){
			$this->relaciones_colectivas_excel($data, $titles);
		}
	}

	function relaciones_colectivas_html($data){
		$cuerpo = "";

		$titles_head = array(
			'N° Exp.',
			'Depto.',
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
			'Cantidad pagada Hombres',
			'Cantidad pagada Mujeres',
			'Cantidad pagada Total',
			'Observaciones'
		);

		$cuerpo .= table_header($titles_head);
		$registros = $this->reportes_colectivos_model->registros_relaciones_colectivas($data);
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
					'',
					$rows->discapacidadci,
					$rows->nombre_empresa,
					$rows->causa,
					$rows->grupo_catalogociiu,
					$rows->actividad_catalogociiu,
					$rows->resultadoci,
					"$ ".number_format($rows->monto_masc,2,'.',','),
					"$ ".number_format($rows->monto_feme,2,'.',','),
					"$ ".number_format($rows->monto_masc+$rows->monto_feme,2,'.',','),
					'',
				);

				$cuerpo .= table_row($cell_row);
			}
		}else{
			$cuerpo .= no_rows(count($titles_head));
		}
		$cuerpo .= table_footer();

		return $cuerpo;
	}

	function relaciones_colectivas_excel($data, $titulos){

		$this->load->library('phpe');
		error_reporting(E_ALL); ini_set('display_errors', TRUE); ini_set('display_startup_errors', TRUE);
		$estilo = array( 'borders' => array( 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN ) ) );

		if (PHP_SAPI == 'cli') die('Este reporte solo se ejecuta en un navegador web');

		// Create new PHPExcel object
		$this->objPHPExcel = new Phpe();

		// Set document properties
		PhpExcelSetProperties($this->objPHPExcel,"Sistema de conciliación de conflictos de trabajo");

		$titulo = $titulos[2].date(" - Ymd_His");

		$f=1;
		$letradesde = 'A';
		$letrahasta = 'T';

		//MODIFICANDO ANCHO DE LAS COLUMNAS
		PhpExcelSetColumnWidth($this->objPHPExcel,
			$width = array(10,20,40,15,15,40,5,5,10,5,12,40,20,60,60,20,20,20,20,20),
			$letradesde, $letrahasta);

		//AGREGAMOS LOS TITULOS DEL REPORTE
		$f = PhpExcelSetTitles($this->objPHPExcel,
			$title = $titulos,
		$letradesde, $letrahasta, $f);


		/*********************************** 	  INICIO ENCABEZADOS DE LA TABLAS	****************************************/
		$tableTitles = array('N° Exp.','Depto.','Delegado','Fecha inicio','Fecha fin','Persona Solicitante','M','F','Patronos','Edad','Personas con discapacidad','Persona solicitada','Causas','Rama económica','Actividad económica','Resolución','Cantidad pagada Hombres','Cantidad pagada Mujeres','Cantidad pagada Total','Observaciones');
		$f = PhpExcelAddHeaderTable($this->objPHPExcel, $tableTitles, $letradesde, $letrahasta, $f, $estilo);

	 	/*********************************** 	   FIN ENCABEZADOS DE LA TABLA   	****************************************/


	 	/********************************* 	   INICIO DE LOS REGISTROS DE LA TABLA   	***********************************/
	 	$registros = $this->reportes_colectivos_model->registros_relaciones_colectivas($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$f = PhpExcelAddRowTable($this->objPHPExcel,
				$cellsRow = array(
					$rows->numerocaso_expedienteci,
					extraer_departamento($rows->numerocaso_expedienteci),
					implode(" ", array($rows->primer_nombre, $rows->segundo_nombre, $rows->tercer_nombre, $rows->primer_apellido, $rows->segundo_apellido, $rows->apellido_casada)),
					fecha_ESP($rows->fechacrea_expedienteci),
					fecha_ESP($rows->fechacrea_expedienteci),
					$rows->nombre_personaci.' '.$rows->apellido_personaci,
					$rows->cant_masc,
					$rows->cant_feme,
					$rows->numerocaso_expedienteci,
					calcular_edad($rows->fnacimiento_personaci),
					$rows->discapacidadci,
					$rows->nombre_empresa,
					$rows->motivo_expedienteci,
					$rows->grupo_catalogociiu,
					$rows->actividad_catalogociiu,
					$rows->resultado_expedienteci,
					"$ ".number_format($rows->monto,2,".",","),
					"$ ".number_format($rows->monto,2,".",","),
					"$ ".number_format($rows->monto,2,".",","),
					$rows->numerocaso_expedienteci
				),
				$letradesde, $letrahasta, $f, $estilo);
			}
		}else{
			$f = PhpExcelAddNoRows($this->objPHPExcel,$letradesde, $letrahasta, $f, $estilo); //CUANDO NO HAY REGISTROS
		}

		/******************************** 	   FIN DE LOS REGISTROS DE LA TABLA   	***********************************/

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

	function registro_edades_report(){
		$data = array(
			'anio' => $this->input->post('anio'),
			'tipo' => $this->input->post('tipo'),
			'value' => $this->input->post('value'),
			'value2' => $this->input->post('value2'),
			'id_delegado' => $this->input->post('id_delegado')
		);

		$titles = array(
				'MINISTERIO DE TRABAJO Y PREVISION SOCIAL',
				'DIRECCIÓN GENERAL DE TRABAJO',
				'INFORME DE REGISTRO DE EDADES',
				periodo($data));

		$body = '';
		if($this->input->post('report_type') == "html"){
			$body .= head_table_html($titles, 'html');
			$body .= $this->registro_edades_html($data);
			echo $body;
		}else if($this->input->post('report_type') == "pdf"){
			$this->load->library('mpdf');
			$this->mpdf=new mPDF('c','letter','10','Arial',10,10,35,17,3,9);

		 	$header = head_table_html($titles, 'pdf');

		 	$this->mpdf->SetHTMLHeader($header);

		 	$body .= $this->relaciones_colectivas_html($data);

		 	$pie = piePagina($this->session->userdata('usuario'));
			$this->mpdf->setFooter($pie);

			$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
			$this->mpdf->AddPage('L','','','','',10,10,35,17,5,10);
			$this->mpdf->SetTitle('Asistencia a personas usuarias');
			$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
			$this->mpdf->WriteHTML($body);
			$this->mpdf->Output('Informe de gestion - '.$sufijo.'.pdf','I');
		}else if($this->input->post('report_type') == "excel"){
			$this->registro_edades_excel($data, $titles);
		}
	}

	function registro_edades_html($data){
		$cuerpo = "";

		$titles_head = array(
			'N° Exp.',
			'Fecha',
			'Resolución',
			'Delegado',
			'Hombres 16-29',
			'Hombres 30-50',
			'Hombres 50 o más',
			'Mujeres 16-29',
			'Mujeres 30-50',
			'Mujeres 50 o más'
		);

		$cuerpo .= table_header($titles_head);

		$registros = $this->reportes_colectivos_model->registros_edades($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {

				$cell_row = array(
					$rows->numerocaso_expedienteci,
					fecha_ESP($rows->fecha_inicio),
					$rows->resultadoci,
					$rows->delegado,
					$rows->aniosm16,
					$rows->aniosm30,
					$rows->aniosm50,
					$rows->aniosf16,
					$rows->aniosf30,
					$rows->aniosf50
				);

				$cuerpo .= table_row($cell_row);
			}
		}else{
			$cuerpo .= no_rows(count($titles_head));
		}

		$cuerpo .= table_footer();
		return $cuerpo;
	}

	function registro_edades_excel($data, $titulos){

		$this->load->library('phpe');
		error_reporting(E_ALL); ini_set('display_errors', TRUE); ini_set('display_startup_errors', TRUE);
		$estilo = array( 'borders' => array( 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN ) ) );

		if (PHP_SAPI == 'cli') die('Este reporte solo se ejecuta en un navegador web');

		// Create new PHPExcel object
		$this->objPHPExcel = new Phpe();

		// Set document properties
		PhpExcelSetProperties($this->objPHPExcel,"Sistema de conciliación de conflictos de trabajo");

		$titulo = $titulos[2].date(" - Ymd_His");

		$f=1;
		$letradesde = 'A';
		$letrahasta = 'J';

		//MODIFICANDO ANCHO DE LAS COLUMNAS
		PhpExcelSetColumnWidth($this->objPHPExcel,
			$width = array(10,12,25,40,10,10,10,9,9,9),
			$letradesde, $letrahasta);

		//AGREGAMOS LOS TITULOS DEL REPORTE
		$f = PhpExcelSetTitles($this->objPHPExcel,
			$title = $titulos,
		$letradesde, $letrahasta, $f);


		/*********************************** 	  INICIO ENCABEZADOS DE LA TABLAS	****************************************/
		$titles_head = array(
			'N° Exp.',
			'Fecha',
			'Resolución',
			'Delegado',
			'Hombres 16-29',
			'Hombres 30-50',
			'Hombres 50 o más',
			'Mujeres 16-29',
			'Mujeres 30-50',
			'Mujeres 50 o más'
		);
		$f = PhpExcelAddHeaderTable($this->objPHPExcel, $titles_head, $letradesde, $letrahasta, $f, $estilo);

	 	/*********************************** 	   FIN ENCABEZADOS DE LA TABLA   	****************************************/


	 	/********************************* 	   INICIO DE LOS REGISTROS DE LA TABLA   	***********************************/
	 	$registros = $this->reportes_colectivos_model->registros_edades($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {

				$cell_row = array(
					$rows->numerocaso_expedienteci,
					fecha_ESP($rows->fechacrea_expedienteci),
					$rows->resultado_expedienteci,
					implode(" ", array($rows->primer_nombre, $rows->segundo_nombre, $rows->tercer_nombre, $rows->primer_apellido, $rows->segundo_apellido, $rows->apellido_casada)),
					$rows->aniosm16,
					$rows->aniosm30,
					$rows->aniosm50,
					$rows->aniosf16,
					$rows->aniosf30,
					$rows->aniosf50
				);

				$f = PhpExcelAddRowTable($this->objPHPExcel, $cell_row, $letradesde, $letrahasta, $f, $estilo);
			}
		}

		/******************************** 	   FIN DE LOS REGISTROS DE LA TABLA   	***********************************/

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

	/*Inicio reporte por tipo de pago*/
	function tipo_pago_report(){
		$data = array(
			'anio' => $this->input->post('anio'),
			'tipo' => $this->input->post('tipo'),
			'value' => $this->input->post('value'),
			'value2' => $this->input->post('value2'),
			'tipo_pago' => $this->input->post('tipo_pago'),
			'id_delegado' => $this->input->post('id_delegado')
		);

		$titles = array(
				'MINISTERIO DE TRABAJO Y PREVISION SOCIAL',
				'DIRECCIÓN GENERAL DE TRABAJO',
				'INFORME POR TIPO DE PAGO',
				periodo($data));

		$body = '';
		if($this->input->post('report_type') == "html"){
			$body .= head_table_html($titles, 'html');
			$body .= $this->tipo_pago_html($data);
			echo $body;
		}else if($this->input->post('report_type') == "pdf"){
			$this->load->library('mpdf');
			$this->mpdf=new mPDF('c','letter','10','Arial',10,10,35,17,3,9);

			$header = head_table_html($titles, 'pdf');

			$this->mpdf->SetHTMLHeader($header);

			$body .= $this->tipo_pago_html($data);

			$pie = piePagina($this->session->userdata('usuario'));
			$this->mpdf->setFooter($pie);

			$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
			$this->mpdf->AddPage('L','','','','',10,10,35,17,5,10);
			$this->mpdf->SetTitle('Reporte por tipo pago');
			$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
			$this->mpdf->WriteHTML($body);
			$this->mpdf->Output('Informe de gestion - '.$sufijo.'.pdf','I');
		}else if($this->input->post('report_type') == "excel"){
			$this->tipo_pago_excel($data, $titles);
		}
	}

	function tipo_pago_html($data){
		$cuerpo = "";

		$titles_head = array(
			'N° Exp.',
			'Departamento',
			'Delegado',
			'Fecha inicio',
			'Fecha terminación',
			'Solicitante',
			'Masculino',
			'Femenino',
			'Patronos',
			'Solicitado',
			'Causa',
			'Actividad económica',
			'Resolución',
			'Observaciones'
		);

		$cuerpo .= table_header($titles_head);

		$registros = $this->reportes_colectivos_model->reporte_tipo_pago($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {

				$cell_row = array(
					$rows->numerocaso_expedienteci,
					$rows->departamento,
					$rows->delegado,
					fecha_ESP($rows->fechacrea_expedienteci),
					fecha_ESP($rows->fecha_fin),
					$rows->solicitante,
					$rows->masculino,
					$rows->femenino,
					"0",
					$rows->solicitado,
					$rows->causa,
					$rows->actividad_economica,
					$rows->resultado,
					""
				);

				$cuerpo .= table_row($cell_row);
			}
		}else{
			$cuerpo .= no_rows(count($titles_head));
		}

		$cuerpo .= table_footer();
		return $cuerpo;
	}

	function tipo_pago_excel($data, $titulos){

		$this->load->library('phpe');
		error_reporting(E_ALL); ini_set('display_errors', TRUE); ini_set('display_startup_errors', TRUE);
		$estilo = array( 'borders' => array( 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN ) ) );

		if (PHP_SAPI == 'cli') die('Este reporte solo se ejecuta en un navegador web');

		// Create new PHPExcel object
		$this->objPHPExcel = new Phpe();

		// Set document properties
		PhpExcelSetProperties($this->objPHPExcel,"Sistema de conciliación de conflictos de trabajo");

		$titulo = $titulos[2].date(" - Ymd_His");

		$f=1;
		$letradesde = 'A';
		$letrahasta = 'N';

		//MODIFICANDO ANCHO DE LAS COLUMNAS
		PhpExcelSetColumnWidth($this->objPHPExcel,
			$width = array(10,15,30,10,15,30,10,10,10,30,30,30,15,15),
			$letradesde, $letrahasta);

		//AGREGAMOS LOS TITULOS DEL REPORTE
		$f = PhpExcelSetTitles($this->objPHPExcel,
			$title = $titulos,
		$letradesde, $letrahasta, $f);


		/*********************************** 	  INICIO ENCABEZADOS DE LA TABLAS	****************************************/
		$titles_head = array(
			'N° Exp.',
			'Departamento',
			'Delegado',
			'Fecha inicio',
			'Fecha terminación',
			'Solicitante',
			'Masculino',
			'Femenino',
			'Patronos',
			'Solicitado',
			'Causa',
			'Actividad económica',
			'Resolución',
			'Observaciones'
		);
		$f = PhpExcelAddHeaderTable($this->objPHPExcel, $titles_head, $letradesde, $letrahasta, $f, $estilo);

		/*********************************** 	   FIN ENCABEZADOS DE LA TABLA   	****************************************/


		/********************************* 	   INICIO DE LOS REGISTROS DE LA TABLA   	***********************************/
		$registros = $this->reportes_colectivos_model->reporte_tipo_pago($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {

				$cell_row = array(
					$rows->numerocaso_expedienteci,
					$rows->departamento,
					$rows->delegado,
					fecha_ESP($rows->fechacrea_expedienteci),
					fecha_ESP($rows->fecha_fin),
					$rows->solicitante,
					$rows->masculino,
					$rows->femenino,
					"0",
					$rows->solicitado,
					$rows->causa,
					$rows->actividad_economica,
					$rows->resultado,
					""
				);

				$f = PhpExcelAddRowTable($this->objPHPExcel, $cell_row, $letradesde, $letrahasta, $f, $estilo);
			}
		}

		/******************************** 	   FIN DE LOS REGISTROS DE LA TABLA   	***********************************/

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
	/*Fin de reporte por tipo de pago*/

	/*Inicio reporte consolidado relaciones colectivas*/
	function consolidado_report(){
		$data = array(
			'anio' => $this->input->post('anio'),
			'tipo' => $this->input->post('tipo'),
			'value' => $this->input->post('value'),
			'value2' => $this->input->post('value2'),
			'id_delegado' => $this->input->post('id_delegado')
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
			header("Content-Disposition: attachment; filename=consolidado.xls");
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
		$pendientes_mes_anterior=0;
		$registros = $this->reportes_colectivos_model->registros_consolidado_pendientes($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$table_header1 = array('DIFERENCIAS COLECTIVAS PENDIENTES DEL MES ANTERIOR',$rows->cant_total);
				$pendientes_mes_anterior=$rows->cant_total;
				$cuerpo .= table_header($table_header1);
			}
		}
		$cuerpo .= table_footer()."<br>";

		/**************************** REGISTRADOS EN EL MES ACTUAL **************************/
		$cuerpo .= '<table border="1" style="width:100%; border-collapse: collapse;"><tr><td style="padding: 10px;">';

		$total_recibidas=0;
		$registros = $this->reportes_colectivos_model->registros_consolidado_recibidos($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$table_header1 = array('DIFERENCIAS COLECTIVAS RECIBIDAS EN EL PRESENTE MES',$rows->cant_total);
				$total_recibidas=$rows->cant_total;
				$cuerpo .= table_header($table_header1);
			}
		}
		$cuerpo .= table_footer()."<br>";

		/********************** REGISTRADOS EN EL MES ACTUAL POR CAUSA **************************/
		$table_header1 = array('','');
		$cuerpo .= table_header($table_header1);
		$registros = $this->reportes_colectivos_model->registros_consolidado_recibidos_por_causa($data);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$cell_row = array(
					$rows->nombre_motivo,
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

		$total_finalizado = 0;
		$registros = $this->reportes_colectivos_model->registros_consolidado_casos_finalizados($data);
		foreach ($registros->result() as $r) {
			$total_finalizado+=$r->cant_total;
		}

		$cuerpo .= table_header(array('CASOS FINALIZADOS', $total_finalizado));
		$cuerpo .= table_footer();

		$table_header1 = array('','');
		$masc = 0; $feme = 0;
		$cuerpo .= table_header($table_header1);
		if($registros->num_rows()>0){
			foreach ($registros->result() as $rows) {
				$cell_row = array(
					$rows->resultado,
					$rows->cant_total
				);
				$cuerpo .= table_row($cell_row);
			}

			$cell_row = array(
				'TOTAL','0'
			);
			$cuerpo .= table_row($cell_row);
		}else{
			$cuerpo .= no_rows(count($table_header1));
		}
		$cuerpo .= table_footer();

		$cuerpo .= '</td></tr></table><br>';

		/********************************** EXPEDIENTES PENDIENTES ************************************/
		$total_pendientes=$total_recibidas+$pendientes_mes_anterior-$total_finalizado;

		$cuerpo .= table_header(array('EXPEDIENTES PENDIENTES PARA EL PRÓXIMO MES', 'TOTAL: '.($total_pendientes)));
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
			$registros = $this->reportes_colectivos_model->registros_consolidado_personas_despedidas($data);
			if($registros->num_rows()>0){
				foreach ($registros->result() as $rows) {
					$total+=$rows->cant_total;
				}
			}else{ $total = 0; }
			$cuerpo .= table_header(array($total));
			$cuerpo .= table_footer();

		$cuerpo .= '</td>';
		$cuerpo .= '<td style="padding: 10px;" align="center">';
			$cuerpo .= '<b><small>(Total de conciliadas, sin conciliar y reinstalo)</small></b>';
			$total = 0;
			$registros = $this->reportes_colectivos_model->registros_consolidado_audiencias($data);
			if($registros->num_rows()>0){
				foreach ($registros->result() as $rows) {
					$total=$rows->cant_total;
				}
			}else{ $total = 0; }
			$cuerpo .= table_header(array($total));
			$cuerpo .= table_footer();

		$cuerpo .= '</td>';
		$cuerpo .= '<td style="padding: 10px;">';


			$masc = 0; $feme = 0;
			$cuerpo .= table_no_header();
			$registros = $this->reportes_colectivos_model->registros_consolidado_pagos($data);
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

		/*Otras mediaciones*/
		$otras_mediaciones = 0;
		$registros = $this->reportes_colectivos_model->registros_otras_mediaciones($data);
		if ($registros->num_rows() >0 ) {
			foreach ($registros->result() as $r) {
				$otras_mediaciones = $r->cant_total;
			}
		}

		$cuerpo .= table_header(array('OTRAS MEDIACIONES', 'TOTAL: '.($otras_mediaciones)));
		$cuerpo .= table_footer()."<br>";
		/*Pago diferido*/
		$pago_diferido = 0;
		$registros = $this->reportes_colectivos_model->registros_pago_diferido($data);
		if ($registros->num_rows() >0 ) {
			foreach ($registros->result() as $r) {
				$pago_diferido = $r->cant_total;
			}
		}

		$cuerpo .= table_header(array('PAGO DIFERIDO', 'TOTAL: '.($pago_diferido)));
		$cuerpo .= table_footer()."<br>";
		/*Asesorías*/

		$cuerpo .= table_header(array('ASESORÍAS', 'TOTAL: 0','MUJERES: 0','HOMBRES: 0'));
		$cuerpo .= table_footer()."<br>";

		return $cuerpo;
	}
}
?>
