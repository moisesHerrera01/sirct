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
			$body .= $this->relaciones_colectivas_html($data, $titles_head);
			echo $body;
		}else if($this->input->post('report_type') == "pdf"){
			$this->load->library('mpdf');
			$this->mpdf=new mPDF('c','legal','10','Arial',10,10,35,17,3,9);

		 	$header = head_table_html($titles, $data, 'pdf');

		 	$this->mpdf->SetHTMLHeader($header);
		 	
		 	$body .= $this->relaciones_colectivas_html($data, $titles_head);

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

	function relaciones_colectivas_html($data, $titles_head){
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

}
?>
