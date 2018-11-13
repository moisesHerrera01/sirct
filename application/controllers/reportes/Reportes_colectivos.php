<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_colectivos extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('reportes_colectivos_model');
	}

	public function index(){
		//$data['id_modulo']=$id_modulo;
		$this->load->view('templates/header');
		$this->load->view('reportes/reportes_colectivos');
		$this->load->view('templates/footer');
	}

	public function relaciones_colectivas(){
		$this->load->view('templates/header');
		$this->load->view('reportes/lista_reportes_colectivos/relaciones_colectivas');
		$this->load->view('templates/footer');
	}

	public function registro_edades(){
		$this->load->view('templates/header');
		$this->load->view('reportes/lista_reportes_colectivos/registro_edades');
		$this->load->view('templates/footer');
	}

	public function tipo_pago(){
		$this->load->view('templates/header');
		$this->load->view('reportes/lista_reportes_colectivos/tipo_pago');
		$this->load->view('templates/footer');
	}

	function relaciones_colectivas_report(){
		$data = array(
			'anio' => $this->input->post('anio'),
			'tipo' => $this->input->post('tipo'),
			'value' => $this->input->post('value'),
			'value2' => $this->input->post('value2')
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
			$this->mpdf=new mPDF('c','A4','10','Arial',10,10,35,17,3,9);

		 	$header = head_table_html($titles, 'pdf');

		 	$this->mpdf->SetHTMLHeader($header);

		 	$body .= $this->relaciones_colectivas_html($data);

		 	$pie = piePagina($this->session->userdata('usuario_centro'));
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
					"$ ".number_format($rows->monto_masc,2,'.',','),
					"$ ".number_format($rows->monto_feme,2,'.',','),
					"$ ".number_format($rows->monto_masc+$rows->monto_feme,2,'.',','),
					$rows->numerocaso_expedienteci,
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
			'value2' => $this->input->post('value2')
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
			$this->mpdf=new mPDF('c','A4','10','Arial',10,10,35,17,3,9);

		 	$header = head_table_html($titles, 'pdf');

		 	$this->mpdf->SetHTMLHeader($header);

		 	$body .= $this->relaciones_colectivas_html($data);

		 	$pie = piePagina($this->session->userdata('usuario_centro'));
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

}
?>
