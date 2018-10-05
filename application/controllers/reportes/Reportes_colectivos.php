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
		}else{
			$this->relaciones_colectivas_excel($data, $titles);
		}
	}

	function relaciones_colectivas_html($data){
		$cuerpo = "";

		$cuerpo .= '<div class="table table-responsive">
			<table border="1" style="width:100%; border-collapse: collapse;">
				<thead>
					<tr>
						<th align="center">N° Exp.</th>
						<th align="center">Depto.</th>
						<th align="center">Delegado</th>
						<th align="center">Fecha inicio</th>
						<th align="center">Fecha fin</th>
						<th align="center">Persona Solicitante</th>
						<th align="center">M</th>
						<th align="center">F</th>
						<th align="center">Patronos</th>
						<th align="center">Edad</th>
						<th align="center">Personas con discapacidad</th>
						<th align="center">Persona solicitada</th>
						<th align="center">Causas</th>	
						<th align="center">Rama económica</th>
						<th align="center">Actividad económica</th>
						<th align="center">Resolución</th>
						<th align="center">Cantidad pagada Hombres</th>
						<th align="center">Cantidad pagada Mujeres</th>
						<th align="center">Cantidad pagada Total</th>
						<th align="center">Observaciones</th>
					</tr>
				</thead>
				<tbody>';

				$registros = $this->reportes_colectivos_model->registros_relaciones_colectivas($data);
				if($registros->num_rows()>0){
					foreach ($registros->result() as $rows) {

						$cuerpo .= '
						<tr>
							<td align="center">'.$rows->numerocaso_expedienteci.'</td>
							<td align="center">'.extraer_departamento($rows->numerocaso_expedienteci).'</td>
							<td align="center">'.implode(" ", array($rows->primer_nombre, $rows->segundo_nombre, $rows->tercer_nombre, $rows->primer_apellido, $rows->segundo_apellido, $rows->apellido_casada)).'</td>
							<td align="center">'.fecha_ESP($rows->fechacrea_expedienteci).'</td>
							<td align="center">'.fecha_ESP($rows->fechacrea_expedienteci).'</td>
							<td align="center">'.$rows->nombre_personaci.' '.$rows->apellido_personaci.'</td>
							<td align="center">'.$rows->cant_masc.'</td>
							<td align="center">'.$rows->cant_feme.'</td>
							<td align="center">'.$rows->monto.'</td>
							<td align="center">'.calcular_edad($rows->fnacimiento_personaci).'</td>
							<td align="center">'.$rows->discapacidadci.'</td>
							<td align="center">'.$rows->nombre_empresa.'</td>
							<td align="center">'.$rows->tiposolicitud_expedienteci.'</td>
							<td align="center">'.$rows->grupo_catalogociiu.'</td>
							<td align="center">'.$rows->actividad_catalogociiu.'</td>
							<td align="center">'.$rows->resultado_expedienteci.'</td>
							<td align="center">$ '.number_format($rows->monto,2,'.',',').'</td>
							<td align="center">$ '.number_format($rows->monto,2,'.',',').'</td>
							<td align="center">$ '.number_format($rows->monto,2,'.',',').'</td>
							<td align="center">'.$rows->monto.'</td>
						</tr>';
					}
				}

				$cuerpo .= '	
				</tbody>
			</table></div>';
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
					$rows->monto,
					calcular_edad($rows->fnacimiento_personaci),
					$rows->discapacidadci,
					$rows->nombre_empresa,
					$rows->tiposolicitud_expedienteci,
					$rows->grupo_catalogociiu,
					$rows->actividad_catalogociiu,
					$rows->resultado_expedienteci,
					"$ ".number_format($rows->monto,2,".",","),
					"$ ".number_format($rows->monto,2,".",","),
					"$ ".number_format($rows->monto,2,".",","),
					$rows->monto
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

}
?>
