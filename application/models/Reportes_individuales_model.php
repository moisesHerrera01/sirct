<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_individuales_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function registros_relaciones_individuales($data){
		$this->db->select("
			ecc.numerocaso_expedienteci,
			d.departamento,
			CONCAT_WS(' ', emp.primer_nombre, emp.segundo_nombre, emp.tercer_nombre, emp.primer_apellido, emp.segundo_apellido, emp.apellido_casada) delegado,
			CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE '' END cant_masc,
			CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE '' END cant_feme,
			ecc.fechacrea_expedienteci fecha_inicio,
			COALESCE((SELECT fea.fecha_resultado FROM sct_fechasaudienciasci fea
				JOIN sct_resultadosci r ON r.id_resultadoci=fea.resultado WHERE estado_audiencia=2
				AND fea.id_expedienteci = ecc.id_expedienteci 
				AND fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa WHERE fa.id_expedienteci=fea.id_expedienteci AND fa.estado_audiencia=2)), 'N/A') fecha_fin,
			CONCAT_WS(' ',p.nombre_personaci,p.apellido_personaci) solicitante,
			TIMESTAMPDIFF(YEAR,p.fnacimiento_personaci,CURDATE()) AS edad,
			CASE WHEN p.discapacidad_personaci = 1 THEN 1 ELSE '' END discapacidadci,
			est.nombre_empresa,
			mv.nombre_motivo causa,
			ciiu.grupo_catalogociiu,
			ciiu.actividad_catalogociiu,
			(SELECT SUM(fp.montopago_fechaspagosci) FROM sct_fechaspagosci AS fp WHERE fp.id_expedienteci = ecc.id_expedienteci) AS monto,
			COALESCE((SELECT r.resultadoci FROM sct_fechasaudienciasci fea
				JOIN sct_resultadosci r ON r.id_resultadoci=fea.resultado WHERE estado_audiencia=2
				AND fea.id_expedienteci = ecc.id_expedienteci 
				AND fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa WHERE fa.id_expedienteci=fea.id_expedienteci AND fa.estado_audiencia=2)), 'Pendiente 99') resultadoci")
			->from('sct_expedienteci AS ecc')
			->join('sct_motivo_solicitud mv','mv.id_motivo_solicitud=ecc.causa_expedienteci')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->join('sge_empresa est', 'ecc.id_empresaci = est.id_empresa')
			->join('org_municipio m','m.id_municipio=emp.id_muni_residencia')
			->join('org_departamento d','d.id_departamento=m.id_departamento_pais')
			->join('sge_catalogociiu ciiu', 'est.id_catalogociiu = ciiu.id_catalogociiu')
			->where("ecc.id_personal IN(".$data["id_delegado"].")")
			->where('(ecc.tiposolicitud_expedienteci = 1 OR ecc.tiposolicitud_expedienteci = 3)')
			->group_by('ecc.id_expedienteci');

		if($data["tipo"] == "mensual"){
			$this->db->where('YEAR(ecc.fechacrea_expedienteci)', $data["anio"])
					->where('MONTH(ecc.fechacrea_expedienteci)', $data["value"]);
	 	}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
	 		$this->db->where('YEAR(ecc.fechacrea_expedienteci)', $data["anio"])
					->where("MONTH(ecc.fechacrea_expedienteci) BETWEEN '".$tminicio."' AND '".$tmfin."'");
	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
 			$this->db->where('YEAR(ecc.fechacrea_expedienteci)', $data["anio"])
					->where("MONTH(ecc.fechacrea_expedienteci) BETWEEN '".$sminicio."' AND '".$smfin."'");
	 	}else if($data["tipo"] == "periodo"){
 			$this->db->where("ecc.fechacrea_expedienteci BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
	 	}else{
	 		$this->db->where('YEAR(ecc.fechacrea_expedienteci)', $data["anio"]);
	 	}

	 	//echo $this->db->get_compiled_select();

        return $query=$this->db->get();
    }

    function registros_renuncia_voluntaria($data){

    	$this->db->select("
			ecc.numerocaso_expedienteci,
			d.departamento,
			CONCAT_WS(' ', emp.primer_nombre, emp.segundo_nombre, emp.tercer_nombre, emp.primer_apellido, emp.segundo_apellido, emp.apellido_casada) delegado,
			CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE '' END cant_masc,
			CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE '' END cant_feme,
			ecc.fechacrea_expedienteci fecha_inicio,
			ecc.fechacrea_expedienteci fecha_fin,
			CONCAT_WS(' ',p.nombre_personaci,p.apellido_personaci) solicitante,
			TIMESTAMPDIFF(YEAR,p.fnacimiento_personaci,CURDATE()) AS edad,
			CASE WHEN p.discapacidad_personaci = 1 THEN 1 ELSE '' END discapacidadci,
			est.nombre_empresa,
			mv.nombre_motivo causa,
			ciiu.grupo_catalogociiu,
			ciiu.actividad_catalogociiu,
			(SELECT SUM(fp.montopago_fechaspagosci) FROM sct_fechaspagosci AS fp WHERE fp.id_expedienteci = ecc.id_expedienteci) AS monto,
			COALESCE((SELECT r.resultadoci FROM sct_fechasaudienciasci fea
				JOIN sct_resultadosci r ON r.id_resultadoci=fea.resultado WHERE estado_audiencia=2
				AND fea.id_expedienteci = ecc.id_expedienteci 
				AND fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa WHERE fa.id_expedienteci=fea.id_expedienteci AND fa.estado_audiencia=2)), 'Pendiente 99') resultadoci")
			->from('sct_expedienteci AS ecc')
			->join('sct_motivo_solicitud mv','mv.id_motivo_solicitud=ecc.causa_expedienteci')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->join('sge_empresa est', 'ecc.id_empresaci = est.id_empresa')
			->join('org_municipio m','m.id_municipio=emp.id_muni_residencia')
			->join('org_departamento d','d.id_departamento=m.id_departamento_pais')
			->join('sge_catalogociiu ciiu', 'est.id_catalogociiu = ciiu.id_catalogociiu')
			->where("ecc.id_personal IN(".$data["id_delegado"].")")
			->where('ecc.tiposolicitud_expedienteci = 2')
			->group_by('ecc.id_expedienteci');

		if($data["tipo"] == "mensual"){
			$this->db->where('YEAR(ecc.fechacrea_expedienteci)', $data["anio"])
					->where('MONTH(ecc.fechacrea_expedienteci)', $data["value"]);
	 	}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);	$tminicio = $tmfin-2;
	 		$this->db->where('YEAR(ecc.fechacrea_expedienteci)', $data["anio"])
					->where("MONTH(ecc.fechacrea_expedienteci) BETWEEN '".$tminicio."' AND '".$tmfin."'");
	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);	$sminicio = $smfin-5;
 			$this->db->where('YEAR(ecc.fechacrea_expedienteci)', $data["anio"])
					->where("MONTH(ecc.fechacrea_expedienteci) BETWEEN '".$sminicio."' AND '".$smfin."'");
	 	}else if($data["tipo"] == "periodo"){
 			$this->db->where("ecc.fechacrea_expedienteci BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
	 	}else{
	 		$this->db->where('YEAR(ecc.fechacrea_expedienteci)', $data["anio"]);
	 	}

	 	//echo $this->db->get_compiled_select();

        return $query=$this->db->get();
    }

    function registros_consolidado_pendientes($data){

    	$fecha_actual = strtotime($data["anio"]."-".$data["value"]."-01");
  		$fecha_menor = date("Ym", strtotime("-1 month", $fecha_actual));

  		$this->db->select(" 'DIFERENCIAS INDIVIDUALES DEL MES ANTERIOR' AS texto,
  			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE 0 END),0) cant_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE 0 END),0) cant_feme,
			COALESCE(COUNT(p.sexo_personaci),0) cant_total")
			->from('sct_expedienteci AS ecc')
			->join('sct_motivo_solicitud mv','mv.id_motivo_solicitud=ecc.causa_expedienteci')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->where('ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3')
			->where("ecc.id_expedienteci NOT IN(SELECT ecc.id_expedienteci FROM sct_fechasaudienciasci fea
				JOIN sct_resultadosci r ON r.id_resultadoci=fea.resultado WHERE estado_audiencia=2
				AND fea.id_expedienteci = ecc.id_expedienteci 
				AND fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa WHERE fa.id_expedienteci=fea.id_expedienteci AND (fa.estado_audiencia=2 AND fea.resultado IN(1,2,4,5,6,7,8)) OR DATE_FORMAT(fea.fecha_resultado, '%Y%m') > '".$fecha_menor."' ))")
			->where("ecc.id_personal IN(".$data["id_delegado"].")")
			->where("DATE_FORMAT(ecc.fechacrea_expedienteci, '%Y%m') <= '".$fecha_menor."'");
	 	//echo $this->db->get_compiled_select();

        return $query=$this->db->get();
    }

    function registros_consolidado_recibidos($data){
  		$fecha_actual = explode("-", $data["anio"]."-".$data["value"]."-01");

		$this->db->select("'DIFERENCIAS INDIVIDUALES DEL PRESENTE MES' texto,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE 0 END),0) cant_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE 0 END),0) cant_feme,
			COALESCE(COUNT(p.sexo_personaci),0) cant_total,
			ecc.fechacrea_expedienteci fecha_inicio")
			->from('sct_expedienteci AS ecc')
			->join('sct_motivo_solicitud mv','mv.id_motivo_solicitud=ecc.causa_expedienteci')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->where('ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3')
			->where("ecc.id_personal IN(".$data["id_delegado"].")")
			->where("(YEAR(ecc.fechacrea_expedienteci) = '".$fecha_actual[0]."' AND MONTH(ecc.fechacrea_expedienteci) = '".$fecha_actual[1]."')");

        return $query=$this->db->get();
    }

    function registros_consolidado_recibidos_por_causa($data){
  		$fecha_actual = explode("-", $data["anio"]."-".$data["value"]."-01");

		$this->db->select("mv.nombre_motivo,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE 0 END),0) cant_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE 0 END),0) cant_feme,
			COALESCE(COUNT(p.sexo_personaci),0) cant_total,
			ecc.fechacrea_expedienteci fecha_inicio")
			->from("sct_motivo_solicitud mv LEFT JOIN (SELECT ecc.* FROM sct_expedienteci ecc WHERE (ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3) AND (YEAR(ecc.fechacrea_expedienteci) = '".$fecha_actual[0]."' AND MONTH(ecc.fechacrea_expedienteci) = '".$fecha_actual[1]."') AND ecc.id_personal IN(".$data["id_delegado"].")) ecc ON mv.id_motivo_solicitud=ecc.causa_expedienteci")
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci','LEFT')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal','LEFT')
			->where('mv.id_tipo_solicitud<4')
			->group_by('mv.id_motivo_solicitud')
			->order_by('mv.id_motivo_solicitud');
		
		//$this->db->get_compiled_select();
        return $query=$this->db->get();
    }

  	function registros_consolidado_casos_finalizados($data){
  		$fecha_actual = date("Ym", strtotime($data["anio"]."-".$data["value"]."-01"));

		$this->db->select("res.resultadoci resultado,
							SUM(q.cant_masc) cant_masc,
							SUM(q.cant_feme) cant_feme,
							SUM(q.cant_total) cant_total")
				->from("sct_resultadosci res LEFT JOIN (SELECT 
						CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE '' END cant_masc,
						CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE '' END cant_feme,
						1 cant_total,
						(SELECT r.id_resultadoci FROM sct_fechasaudienciasci fea
							JOIN sct_resultadosci r ON r.id_resultadoci=fea.resultado WHERE estado_audiencia=2
							AND fea.id_expedienteci = ecc.id_expedienteci 
							AND fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa WHERE fa.id_expedienteci=fea.id_expedienteci AND fa.estado_audiencia=2)) id_resultado
						FROM sct_expedienteci ecc 
						JOIN sct_personaci p ON p.id_personaci = ecc.id_personaci
						JOIN sir_empleado emp ON emp.id_empleado = ecc.id_personal
						WHERE ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3
						AND ecc.id_personal IN(".$data["id_delegado"].")
						AND ecc.id_expedienteci IN(SELECT ecc.id_expedienteci FROM sct_fechasaudienciasci fea
							JOIN sct_resultadosci r ON r.id_resultadoci=fea.resultado WHERE estado_audiencia=2
							AND fea.id_expedienteci = ecc.id_expedienteci 
							AND fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa WHERE fa.id_expedienteci=fea.id_expedienteci AND (fa.estado_audiencia=2 AND fea.resultado IN(1,2,4,5,6,7,8)) AND DATE_FORMAT(fea.fecha_resultado, '%Y%m') = '".$fecha_actual."' ))
						) q ON q.id_resultado = res.id_resultadoci WHERE res.id_tipo_solicitud <= 3")
				->group_by("res.id_resultadoci");

        return $query=$this->db->get();
    }

    function registros_consolidado_expedientes_pendientes($data){
  		$fecha_actual = date("Ym", strtotime($data["anio"]."-".$data["value"]."-01"));

  		$this->db->select(" 'EXPEDIENTES PENDIENTES PARA EL PRÓXIMO MES' AS texto,
  			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE 0 END),0) cant_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE 0 END),0) cant_feme,
			COALESCE(COUNT(p.sexo_personaci),0) cant_total")
			->from('sct_expedienteci AS ecc')
			->join('sct_motivo_solicitud mv','mv.id_motivo_solicitud=ecc.causa_expedienteci')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->where('ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3')
			->where("ecc.id_personal IN(".$data["id_delegado"].")")
			->where("ecc.id_expedienteci NOT IN(SELECT ecc.id_expedienteci FROM sct_fechasaudienciasci fea
				JOIN sct_resultadosci r ON r.id_resultadoci=fea.resultado WHERE estado_audiencia=2
				AND fea.id_expedienteci = ecc.id_expedienteci 
				AND fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa WHERE fa.id_expedienteci=fea.id_expedienteci AND (fa.estado_audiencia=2 AND fea.resultado IN(1,2,4,5,6,7,8)) OR DATE_FORMAT(fea.fecha_resultado, '%Y%m') > '".$fecha_actual."' ))")
			->where("DATE_FORMAT(ecc.fechacrea_expedienteci, '%Y%m') <= '".$fecha_actual."'");

        return $query=$this->db->get();
    }

    function registros_consolidado_personas_despedidas($data){
  		$fecha_actual = date("Ym", strtotime($data["anio"]."-".$data["value"]."-01"));

  		$this->db->select(" 'EXPEDIENTES PENDIENTES PARA EL PRÓXIMO MES' AS texto,
  			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE 0 END),0) cant_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE 0 END),0) cant_feme,
			COALESCE(COUNT(p.sexo_personaci),0) cant_total")
			->from('sct_expedienteci AS ecc')
			->join('sct_motivo_solicitud mv','mv.id_motivo_solicitud=ecc.causa_expedienteci')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->where('ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3')
			->where('ecc.motivo_expedienteci = 1')
			->where("ecc.id_personal IN(".$data["id_delegado"].")")
			->where("ecc.id_expedienteci IN(SELECT ecc.id_expedienteci FROM sct_fechasaudienciasci fea
				JOIN sct_resultadosci r ON r.id_resultadoci=fea.resultado WHERE estado_audiencia=2
				AND fea.id_expedienteci = ecc.id_expedienteci 
				AND fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa WHERE fa.id_expedienteci=fea.id_expedienteci AND (fa.estado_audiencia=2 AND fea.resultado IN(1,2,4,5,6,8)) AND DATE_FORMAT(fea.fecha_resultado, '%Y%m') = '".$fecha_actual."' ))");

        return $query=$this->db->get();
    }

    function registros_consolidado_audiencias($data){
  		$fecha_actual = date("Ym", strtotime($data["anio"]."-".$data["value"]."-01"));

  		$this->db->select(" 'EXPEDIENTES PENDIENTES PARA EL PRÓXIMO MES' AS texto,
  			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE 0 END),0) cant_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE 0 END),0) cant_feme,
			COALESCE(COUNT(p.sexo_personaci),0) cant_total")
			->from('sct_expedienteci AS ecc')
			->join('sct_motivo_solicitud mv','mv.id_motivo_solicitud=ecc.causa_expedienteci')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->where('ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3')
			->where("ecc.id_personal IN(".$data["id_delegado"].")")
			->where("ecc.id_expedienteci IN(SELECT ecc.id_expedienteci FROM sct_fechasaudienciasci fea
				JOIN sct_resultadosci r ON r.id_resultadoci=fea.resultado WHERE estado_audiencia=2
				AND fea.id_expedienteci = ecc.id_expedienteci 
				AND fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa WHERE fa.id_expedienteci=fea.id_expedienteci AND (fa.estado_audiencia=2 AND fea.resultado IN(1,2,7)) AND DATE_FORMAT(fea.fecha_resultado, '%Y%m') = '".$fecha_actual."' ))");

        return $query=$this->db->get();
    }

    function registros_consolidado_pagos($data){
    	$fecha_actual = date("Ym", strtotime($data["anio"]."-".$data["value"]."-01"));

		$this->db->select("
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN fp.montopago_fechaspagosci ELSE 0 END),0) monto_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN fp.montopago_fechaspagosci ELSE 0 END),0) monto_feme,
			COALESCE(SUM(fp.montopago_fechaspagosci),0) monto_total,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE 0 END),0) cant_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE 0 END),0) cant_feme,
			COUNT(p.sexo_personaci) cant_total
			")
			->from('sct_expedienteci AS ecc')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->join('sct_fechaspagosci AS fp', 'fp.id_expedienteci = ecc.id_expedienteci')
			->where("ecc.id_personal IN(".$data["id_delegado"].")")
			->where("DATE_FORMAT(fp.fechapago_fechaspagosci, '%Y%m') = '".$fecha_actual."'")
			->where('(ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3)');

		//echo $this->db->get_compiled_select();

        return $query=$this->db->get();
    }

    function registros_consolidado_renuncia_voluntario($data){
    	$fecha_actual = explode("-",$data["anio"]."-".$data["value"]."-01");

		$this->db->select(" (CASE WHEN fea.inasistencia = 1 THEN 'PARTE PATRONAL' WHEN fea.inasistencia = 2 THEN 'PARTE TRABAJADORA' WHEN fea.inasistencia = 3 THEN 'AMBAS PARTES' ELSE 'DESISTIDA' END) AS texto,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE 0 END),0) cant_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE 0 END),0) cant_feme,
			COALESCE(COUNT(p.sexo_personaci),0) cant_total")
			->from('sct_expedienteci AS ecc')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->join('sct_fechasaudienciasci fea','fea.id_expedienteci=ecc.id_expedienteci')
			->where('ecc.tiposolicitud_expedienteci = 2')
			->where("ecc.id_personal IN(".$data["id_delegado"].")")
			->where('fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa 
					 WHERE fa.id_expedienteci=fea.id_expedienteci)')
			->where("(YEAR(fea.fecha_resultado) = '".$fecha_actual[0]."' AND MONTH(fea.fecha_resultado) = '".$fecha_actual[1]."')")
			->where("(fea.resultado IN(4,6,8))")
			->group_by('fea.inasistencia');

        return $query=$this->db->get();
    }



}
