<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/***********************************
	Creado por: Willian Rivera
************************************/

	function PhpExcelSetProperties(PHPExcel $objPHPExcel, $sistema){
		$objPHPExcel->getProperties()->setCreator($sistema)->setLastModifiedBy($sistema)->setTitle("Office 2007 XLSX Test Document")->setSubject("Office 2007 XLSX Test Document")->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")->setKeywords("office 2007 openxml php");

    }

	function PhpExcelSetColumnWidth(PHPExcel $objPHPExcel, $width, $desde, $hasta){
		$i = 0;
		foreach(range($desde,$hasta) as $columnID) { //Cambia el tamaño de las columnas
			$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setWidth($width[$i]);
			$i++;
		}
    }

    function PhpExcelSetTitles(PHPExcel $objPHPExcel, $titulos, $desde, $hasta, $fila){
    	foreach ($titulos as $title) {
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($desde.$fila, $title);
			$objPHPExcel->getActiveSheet()->getStyle($desde.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells($desde.$fila.':'.$hasta.$fila);	$fila+=1;
    	}
    	return $fila+=2;
    }

    function PhpExcelMergeCell(PHPExcel $objPHPExcel, $desde, $hasta, $fila){
    	$objPHPExcel->setActiveSheetIndex(0)->mergeCells($desde.$fila.':'.$hasta.$fila);
    }

    function PhpExcelAddHeaderTable(PHPExcel $objPHPExcel, $titulos, $desde, $hasta, $fila, $estilo){
    	$i = 0;
    	foreach(range($desde,$hasta) as $columnID) {	//APLICA BORDES A LAS CELDAS
		    $objPHPExcel->getActiveSheet()->getStyle($columnID.$fila)->applyFromArray($estilo);
		    $objPHPExcel->getActiveSheet()->getStyle($columnID.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnID.$fila, $titulos[$i]);
		    $i++;
		}
		$objPHPExcel->setActiveSheetIndex(0)->getStyle($desde.$fila.':'.$hasta.$fila)->getFont()->setBold(true);
		return $fila+=1;
    }

    function PhpExcelAddRowTable(PHPExcel $objPHPExcel, $titulos, $desde, $hasta, $fila, $estilo){
    	$i = 0;
    	foreach(range($desde,$hasta) as $columnID) {	//APLICA BORDES A LAS CELDAS
		    $objPHPExcel->getActiveSheet()->getStyle($columnID.$fila)->applyFromArray($estilo);
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnID.$fila, $titulos[$i]);
		    $i++;
		}
		return $fila+=1;
    }

    function PhpExcelAddFooterTable(PHPExcel $objPHPExcel, $titulos, $desde, $hasta, $fila, $estilo){
    	$i = 0;
    	foreach(range($desde,$hasta) as $columnID) {	//APLICA BORDES A LAS CELDAS
		    $objPHPExcel->getActiveSheet()->getStyle($columnID.$fila)->applyFromArray($estilo);
		    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnID.$fila, $titulos[$i]);
		    $i++;
		}
		$objPHPExcel->setActiveSheetIndex(0)->getStyle($desde.$fila.':'.$hasta.$fila)->getFont()->setBold(true);
		return $fila+=1;
    }

    function PhpExcelAddNoRows(PHPExcel $objPHPExcel, $desde, $hasta, $fila, $estilo){
    	foreach(range($desde,$hasta) as $columnID) {	//APLICA BORDES A LAS CELDAS
		    $objPHPExcel->getActiveSheet()->getStyle($columnID.$fila)->applyFromArray($estilo);
		    $objPHPExcel->getActiveSheet()->getStyle($columnID.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells($desde.$fila.':'.$hasta.$fila);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($desde.$fila, 'NO HAY REGISTROS DISPONIBLES...');
		return $fila+=1;
    }


    function periodo($data){
		$periodo = "";
		if($data["tipo"] == "mensual"){
			$periodo = mb_strtoupper("Correspondiente al MES: ".mes($data["value"])." DE ".$data["anio"]);
		}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);
 			$tminicio = $tmfin-2;
 			$periodo = mb_strtoupper("Correspondiente al TRIMESTRE: ".mes($tminicio)." - ".mes($tmfin)." DE ".$data["anio"]);
	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);
 			$sminicio = $smfin-5;
 			$periodo = mb_strtoupper("Correspondiente al SEMESTRE: ".mes($sminicio)." - ".mes($smfin)." DE ".$data["anio"]);
	 	}else if($data["tipo"] == "periodo"){
	 		$periodo = mb_strtoupper("Correspondiente al PERIODO: ".fecha_ESP($data["value"])." - ".fecha_ESP($data["value2"]));
	 	}else{
	 		$periodo = mb_strtoupper("Correspondiente al AÑO: ".$data["anio"]);
	 	}
	 	return $periodo;
	}


	function head_table_html($titles, $tipo){

		if($tipo == 'pdf'){
			$cabecera_vista = '
		 	<table style="width: 100%;">
		 		<tr style="font-size: 20px; vertical-align: middle; font-family: "Poppins", sans-serif;">
		 			<td width="110px"><img src="'.base_url().'assets/logos_vista/logo_izquierdo.jpg" width="110px"></td>
					<td align="center" style="font-size: 13px; font-weight: bold; line-height: 1.3;">';
		}else{
			$cabecera_vista = '
		 	<table style="width: 100%;">
			 	<tr style="font-size: 20px; vertical-align: center; font-family: "Poppins", sans-serif;">
			 		<td width="130px"><img src="'.base_url().'assets/logos_vista/logo_izquierdo.jpg" width="130px"></td>
					<td align="center" style="font-size: 15px; font-weight: bold; vertical-align: center; line-height: 1.5;">';
		}
		for($i=0; $i < count($titles); $i++){
			if($i < 2){
				$cabecera_vista .= mb_strtoupper($titles[$i])."<br>";
			}elseif($i == 2){
				$cabecera_vista .= '<span style="font-size: 12px; text-decoration: underline;">'.mb_strtoupper($titles[$i])."</span><br>";
			}elseif($i == 3){
				$cabecera_vista .= '<span style="font-size: 12px; font-weight: normal;">'.mb_strtoupper($titles[$i])."</span><br>";
			}
		}
	 	if($tipo == 'pdf'){
			$cabecera_vista .= '</td>
				<td width="130px"><img src="'.base_url().'assets/logos_vista/logo_derecho.jpg"  width="130px"></td>
				 	</tr>
			 	</table><br>';
		}else{
			$cabecera_vista .= '</td>
				<td width="150px"><img src="'.base_url().'assets/logos_vista/logo_derecho.jpg"  width="150px"></td>
				 	</tr>
			 	</table><br>';
		}
		
	 	return $cabecera_vista;
	}

	function table_header($titles){

		$cabecera_vista = '<div class="table table-responsive">
			<table border="1" style="width:100%; border-collapse: collapse;">
				<thead>
					<tr>';
		
		for($i=0; $i < count($titles); $i++){
			$cabecera_vista .= '<th align="center">'.mb_strtoupper($titles[$i])."</th>";
		}
	 	
		$cabecera_vista .= '</tr>
				</thead>
				<tbody>';
	 	return $cabecera_vista;
	}

	function table_row($registros){
		$cabecera_vista = '<tr>';
		for($i=0; $i < count($registros); $i++){
			$cabecera_vista .= '<td align="center">'.$registros[$i].'</td>';
		}
		$cabecera_vista .= '</tr>';

	 	return $cabecera_vista;
	}

	function table_footer(){
		$cabecera_vista = '</tbody>
			</table></div>';	
	 	return $cabecera_vista;
	}

?>