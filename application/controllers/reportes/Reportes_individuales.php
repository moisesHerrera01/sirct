<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_individuales extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('actividad_model');
	}

	public function index(){
		//$data['id_modulo']=$id_modulo;
		$this->load->view('templates/header');
		$this->load->view('reportes/reportes_individuales');
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
			'fecha_fin' => $this->input->post('value2')
		);

		$titles = array(
				'MINISTERIO DE TRABAJO Y PREVISION SOCIAL', 
				'DIRECCIÓN GENERAL DE TRABAJO', 
				'INFORME DE RELACIONES COLECTIVAS');

		if($this->input->post('report_type') == "html"){
			$header = head_table_html($titles, $data, 'html');
			echo $header;
		}else{
			$this->load->library('mpdf');
			$this->mpdf=new mPDF('c','A4','10','Arial',10,10,35,17,3,9);

		 	$header = head_table_html($titles, $data, 'pdf');

		 	$this->mpdf->SetHTMLHeader($header);
		 	
		 	$cuerpo = 'hola';//$this->cuerpo($data);

		 	$pie = piePagina($this->session->userdata('usuario_centro'));
			$this->mpdf->setFooter($pie);

			$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
			$this->mpdf->AddPage('P','','','','',10,10,35,17,5,10);
			$this->mpdf->SetTitle('Asistencia a personas usuarias');
			$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
			$this->mpdf->WriteHTML($cuerpo);
			$this->mpdf->Output('Informe de gestion - '.$sufijo.'.pdf','I');
		}
	}

	function pdf(){

		$this->load->library('mpdf');
		$this->mpdf=new mPDF('c','A4','10','Arial',10,10,35,17,3,9);

	 	$cabecera_vista = '
	 	<table style="width: 100%;">
		 	<tr style="font-size: 20px; vertical-align: middle; font-family: "Poppins", sans-serif;">
		 		<td width="130px"><img src="'.base_url().'assets/logos_vista/logo_izquierdo.jpg" width="130px"></td>
				<td align="center" style="font-size: 13px; font-weight: bold; line-height: 1.3;">
					MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br>
					UNIDAD FINANCIERA INSTITUCIONAL <br>
					<span style="font-size: 12px; text-decoration: underline;">INFORME DE INGRESO CONSOLIDADO POR CENTROS DE RECREACIÓN</span>
				</td>
				<td width="130px"><img src="'.base_url().'assets/logos_vista/logo_derecho.jpg" width="130px"></td>
		 	</tr>
	 	</table><br>';

	 	$this->mpdf->SetHTMLHeader($cabecera_vista);
	 	
	 	$cuerpo = 'hola';//$this->cuerpo($data);

	 	$pie = piePagina($this->session->userdata('usuario_centro'));
		$this->mpdf->setFooter($pie);

		$stylesheet = file_get_contents(base_url().'assets/css/bootstrap.min.css');
		$this->mpdf->AddPage('P','','','','',10,10,35,17,5,10);
		$this->mpdf->SetTitle('Asistencia a personas usuarias');
		$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/
		$this->mpdf->WriteHTML($cuerpo);
		$this->mpdf->Output('Informe de gestion - '.$sufijo.'.pdf','I');
	}

	function cuerpo($data){
		$cuerpo = "";

		$centro = $this->reportes_model->obtener_centros($data);
		$labels = array();
		$cuerpo .= '
			<table border="1" style="width:100%; border-collapse: collapse;">
				<thead>
					<tr>
						<th align="center">Fecha</th>';
						if($centro->num_rows()>0){
							foreach ($centro->result() as $filas) {
								$cuerpo .= '<th align="center">'.$filas->nickname.'</th>';
								array_push($labels, $filas->nickname);
							}
						}

		$cuerpo .= '<th align="center">Total</th>	
					</tr>
				</thead>
				<tbody>';

				$total1 = 0;
				$total2 = 0;
				$total3 = 0;
				$total4 = 0;

				$totalcentros = 0;

				$ingresos_centro = $this->reportes_model->obtener_ingresos_diarios();
				if($ingresos_centro->num_rows()>0){
					foreach ($ingresos_centro->result() as $filahi) {
						$totalcentros = 0;
						$total1 += $filahi->column1;
						$total2 += $filahi->column2;
						$total3 += $filahi->column3;
						$total4 += $filahi->column4;

						$totalcentros += floatval($filahi->column1)+floatval($filahi->column2)+floatval($filahi->column3)+floatval($filahi->column4);					

						$cuerpo .= '
						<tr>
							<td align="center" style="width:180px">'.date("d/m/Y",strtotime($filahi->fecha)).'</td>
							<td align="center" style="width:180px">$ '.number_format($filahi->column1,2,".",",").'</td>
							<td align="center" style="width:180px">$ '.number_format($filahi->column2,2,".",",").'</td>
							<td align="center" style="width:180px">$ '.number_format($filahi->column3,2,".",",").'</td>
							<td align="center" style="width:180px">$ '.number_format($filahi->column4,2,".",",").'</td>
							<td align="center" style="width:180px">$ '.number_format($totalcentros,2,".",",").'</td>
						</tr>';
					}
				}

				$cuerpo .= '
					<tr>
						<th align="center" style="width:180px">Total por centro</th>
						<th align="center" style="width:180px">$ '.number_format($total1,2,".",",").'</th>
						<th align="center" style="width:180px">$ '.number_format($total2,2,".",",").'</th>
						<th align="center" style="width:180px">$ '.number_format($total3,2,".",",").'</th>
						<th align="center" style="width:180px">$ '.number_format($total4,2,".",",").'</th>
						<th align="center" style="width:180px">$ '.number_format(($total1+$total2+$total3+$total4),2,".",",").'</th>
					</tr>';

				$cuerpo .= '	
				</tbody>
			</table>';
		return $cuerpo;
	}

	function excel(){
		//echo "VISTA NO DISPONIBLE ACTUALMENTE (AUN EN DESARROLLO)";
		$data = array(
			'anio' => $this->input->get('anio'),
			'tipo' => $this->input->get('tipo'),
			'value' => $this->input->get('value'),
			'id_centro' => ''
		);

		$this->load->library('phpe');
		error_reporting(E_ALL); ini_set('display_errors', TRUE); ini_set('display_startup_errors', TRUE); date_default_timezone_set('America/Mexico_City');
		$estilo = array( 'borders' => array( 'outline' => array( 'style' => PHPExcel_Style_Border::BORDER_THIN ) ) );

		if (PHP_SAPI == 'cli') die('Este reporte solo se ejecuta en un navegador web');

		// Create new PHPExcel object
		$this->objPHPExcel = new Phpe();

		// Set document properties
		PhpExcelSetProperties($this->objPHPExcel,"Sistema de centros recreativos");

		$titulo = 'INFORME DE INGRESO CONSOLIDADO POR CENTRO RECREATIVO';

		$f=1;
		$letradesde = 'A';
		$letrahasta = 'F';

		//MODIFICANDO ANCHO DE LAS COLUMNAS
		PhpExcelSetColumnWidth($this->objPHPExcel,
			$width = array(20,40,40,40,40,20), 
			$letradesde, $letrahasta);

		//AGREGAMOS LOS TITULOS DEL REPORTE
		$f = PhpExcelSetTitles($this->objPHPExcel,
			$title = array("MINISTERIO DE TRABAJO Y PREVISION SOCIAL", "UNIDAD FINANCIERA INSTITUCIONAL", $titulo),
		$letradesde, $letrahasta, $f);
		

		/*********************************** 	  INICIO ENCABEZADOS DE LA TABLAS	****************************************/

		$centro = $this->reportes_model->obtener_centros($data);
		$tableTitles = array('Fecha');
		if($centro->num_rows()>0){
			foreach ($centro->result() as $filas) {
				array_push($tableTitles, $filas->nickname);
			}
		}
		array_push($tableTitles, 'Total');
		$f = PhpExcelAddHeaderTable($this->objPHPExcel, $tableTitles, $letradesde, $letrahasta, $f, $estilo);

	 	/*********************************** 	   FIN ENCABEZADOS DE LA TABLA   	****************************************/


	 	/*********************************** 	   INICIO DE LOS REGISTROS DE LA TABLA   	****************************************/
	 	$total = 0;
		$total_centro = 0;
		$total_ufi = 0;

		$ingresos_centro = $this->reportes_model->obtener_ingresos_diarios_UFI($data);
		if($ingresos_centro->num_rows()>0){
			foreach ($ingresos_centro->result() as $filahi) {
				$total_centro += $filahi->centro;
				$total_ufi += $filahi->ufi;
				$total += $total_centro+$total_ufi;

				$f = PhpExcelAddRowTable($this->objPHPExcel,
					$cellsRow = array(
						date("d/m/Y",strtotime($filahi->fecha)),
						"$ ".number_format($filahi->centro,2,".",","),
						"$ ".number_format($filahi->ufi,2,".",","),
						"$ ".number_format($filahi->total,2,".",",")
					),
					$letradesde, $letrahasta, $f, $estilo);
			}

			$f = PhpExcelAddFooterTable($this->objPHPExcel,
					$cellsRow = array(
						"TOTAL",
						"$ ".number_format($total_centro,2,".",","),
						"$ ".number_format($total_ufi,2,".",","),
						"$ ".number_format($total,2,".",",")
					),
					$letradesde, $letrahasta, $f, $estilo);

		}else{
			$f = PhpExcelAddNoRows($this->objPHPExcel,$letradesde, $letrahasta, $f, $estilo); //CUANDO NO HAY REGISTROS
		}

		$total1 = 0;
		$total2 = 0;
		$total3 = 0;
		$total4 = 0;

		$totalcentros = 0;

		$ingresos_centro = $this->reportes_model->obtener_ingresos_diarios();
		if($ingresos_centro->num_rows()>0){
			foreach ($ingresos_centro->result() as $filahi) {
				$totalcentros = 0;
				$total1 += $filahi->column1;
				$total2 += $filahi->column2;
				$total3 += $filahi->column3;
				$total4 += $filahi->column4;

				$totalcentros += floatval($filahi->column1)+floatval($filahi->column2)+floatval($filahi->column3)+floatval($filahi->column4);

				$f = PhpExcelAddRowTable($this->objPHPExcel,
					$cellsRow = array(
						date("d/m/Y",strtotime($filahi->fecha)),
						"$ ".number_format($filahi->column1,2,".",","),
						"$ ".number_format($filahi->column2,2,".",","),
						"$ ".number_format($filahi->column3,2,".",","),
						"$ ".number_format($filahi->column4,2,".",","),
						"$ ".number_format($totalcentros,2,".",",")
					),
					$letradesde, $letrahasta, $f, $estilo);
			}
			$f = PhpExcelAddFooterTable($this->objPHPExcel,
					$cellsRow = array(
						"TOTAL POR CENTRO",
						"$ ".number_format($total1,2,".",","),
						"$ ".number_format($total2,2,".",","),
						"$ ".number_format($total3,2,".",","),
						"$ ".number_format($total4,2,".",","),
						"$ ".number_format(($total1+$total2+$total3+$total4),2,".",",")
					),
					$letradesde, $letrahasta, $f, $estilo);
		}else{
			$f = PhpExcelAddNoRows($this->objPHPExcel,$letradesde, $letrahasta, $f, $estilo); //CUANDO NO HAY REGISTROS
		}

		/*********************************** 	   FIN DE LOS REGISTROS DE LA TABLA   	****************************************/
		

		$f+=3;

	 	$fecha=strftime( "%d-%m-%Y - %H:%M:%S", time() );
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$f,"Fecha y Hora de Creación: ".$fecha); $f++;
		$this->objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$f,"Usuario: ".$this->session->userdata('usuario_centro'));
		
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



	public function mostrarActividad($id){
		$nuevo['depende_vyp_actividades']=$id;
		$this->load->view('configuraciones/actividad_ajax/combo_actividad',$nuevo);
	}

	public function tabla_actividad(){
		//$data['id_modulo']=$id_modulo;
		$this->load->view('configuraciones/actividad_ajax/tabla_actividad');
	}

	public function gestionar_actividad(){
		if($this->input->post('band') == "save"){
			$data = array(
			'nombre_vyp_actividades' => mb_strtoupper($this->input->post('nombre_vyp_actividades')),
			'depende_vyp_actividades' => $this->input->post('depende_vyp_actividades'),
			);
      		echo $this->actividad_model->insertar_actividad($data);
		}else if($this->input->post('band') == "edit"){
      		$data = array(
		    'id_vyp_actividades' => $this->input->post('id_vyp_actividades'),
		    'nombre_vyp_actividades' => mb_strtoupper($this->input->post('nombre_vyp_actividades')),
			'depende_vyp_actividades' => $this->input->post('depende_vyp_actividades'),
			);
			echo $this->actividad_model->editar_actividad($data);
		}else if($this->input->post('band') == "delete"){
			$data = array(
			'id_vyp_actividades' => $this->input->post('id_vyp_actividades')
			);
			echo $this->actividad_model->eliminar_actividad($data);
		}
	}
}
?>
