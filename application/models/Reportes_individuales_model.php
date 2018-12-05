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
			fecha_fechasaudienciasci fecha_fin,
			CONCAT_WS(' ',p.nombre_personaci,p.apellido_personaci) solicitante,
			TIMESTAMPDIFF(YEAR,p.fnacimiento_personaci,CURDATE()) AS edad,
			CASE WHEN p.discapacidad_personaci = 1 THEN 1 ELSE '' END discapacidadci,
			est.nombre_empresa,
			mv.nombre_motivo causa,
			ciiu.grupo_catalogociiu,
			ciiu.actividad_catalogociiu,
			(SELECT SUM(fp.montopago_fechaspagosci) FROM sct_fechaspagosci AS fp JOIN sct_personaci AS p3 WHERE p3.id_personaci = fp.id_persona AND p3.sexo_personaci = 'M') AS monto,
			res.resultadoci")
			->from('sct_expedienteci AS ecc')
			->join('sct_motivo_solicitud mv','mv.id_motivo_solicitud=ecc.causa_expedienteci')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->join('sge_empresa est', 'ecc.id_empresaci = est.id_empresa')
			->join('org_municipio m','m.id_municipio=emp.id_muni_residencia')
			->join('org_departamento d','d.id_departamento=m.id_departamento_pais')
			->join('sge_catalogociiu ciiu', 'est.id_catalogociiu = ciiu.id_catalogociiu')
			->join('sct_fechasaudienciasci fea','fea.id_expedienteci=ecc.id_expedienteci')
			->join('sct_resultadosci res','res.id_resultadoci=fea.resultado')
			->where('fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa
					 WHERE fa.id_expedienteci=fea.id_expedienteci)')
			->where('fea.estado_audiencia = 2')
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
	 	}else if($data["tipo"] == "semestral"){
 			$this->db->where("ecc.fechacrea_expedienteci BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
	 	}else{
	 		$this->db->where('YEAR(ecc.fechacrea_expedienteci)', $data["anio"]);
	 	}

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
			fecha_fechasaudienciasci fecha_fin,
			CONCAT_WS(' ',p.nombre_personaci,p.apellido_personaci) solicitante,
			TIMESTAMPDIFF(YEAR,p.fnacimiento_personaci,CURDATE()) AS edad,
			CASE WHEN p.discapacidad_personaci = 1 THEN 1 ELSE '' END discapacidadci,
			est.nombre_empresa,
			mv.nombre_motivo causa,
			ciiu.grupo_catalogociiu,
			ciiu.actividad_catalogociiu,
			(SELECT SUM(fp.montopago_fechaspagosci) FROM sct_fechaspagosci AS fp JOIN sct_personaci AS p3 WHERE p3.id_personaci = fp.id_persona AND p3.sexo_personaci = 'M') AS monto,
			res.resultadoci")
			->from('sct_expedienteci AS ecc')
			->join('sct_motivo_solicitud mv','mv.id_motivo_solicitud=ecc.causa_expedienteci')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->join('sge_empresa est', 'ecc.id_empresaci = est.id_empresa')
			->join('org_municipio m','m.id_municipio=emp.id_muni_residencia')
			->join('org_departamento d','d.id_departamento=m.id_departamento_pais')
			->join('sge_catalogociiu ciiu', 'est.id_catalogociiu = ciiu.id_catalogociiu')
			->join('sct_fechasaudienciasci fea','fea.id_expedienteci=ecc.id_expedienteci')
			->join('sct_resultadosci res','res.id_resultadoci=fea.resultado')
			->where('fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa
					 WHERE fa.id_expedienteci=fea.id_expedienteci)')
			->where('fea.estado_audiencia = 2')
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
	 	}else if($data["tipo"] == "semestral"){
 			$this->db->where("ecc.fechacrea_expedienteci BETWEEN '".$data["value"]."' AND '".$data["value2"]."'");
	 	}else{
	 		$this->db->where('YEAR(ecc.fechacrea_expedienteci)', $data["anio"]);
	 	}

        return $query=$this->db->get();
    }

    function registros_consolidado_pendientes($data){

    	$fecha_actual = strtotime($data["anio"]."-".$data["value"]."-01");
  		$fecha_menor = explode("-", date("Y-m-d", strtotime("-1 month", $fecha_actual)));
  		$fecha_actual = explode("-", $fecha_actual);


		$this->db->select(" 'DIFERENCIAS INDIVIDUALES DEL MES ANTERIOR' AS texto,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE 0 END),0) cant_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE 0 END),0) cant_feme,
			COALESCE(COUNT(p.sexo_personaci),0) cant_total,
			ecc.fechacrea_expedienteci fecha_inicio,
			fea.fecha_fechasaudienciasci fecha_fin")
			->from('sct_expedienteci AS ecc')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->join('sct_fechasaudienciasci fea','fea.id_expedienteci=ecc.id_expedienteci')
			->join('sct_resultadosci res','res.id_resultadoci=fea.resultado')
			->where('ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3')
			->where('fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa
					 WHERE fa.id_expedienteci=fea.id_expedienteci)')
			->where("(YEAR(ecc.fechacrea_expedienteci) = '".$fecha_menor[0]."' AND MONTH(ecc.fechacrea_expedienteci) = '".$fecha_menor[1]."')")
			->where("(fea.estado_audiencia = 1 OR fea.resultado IN(1,4,5,6,7,8))");

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
			->from('sct_expedienteci AS ecc')
			->join('sct_motivo_solicitud mv','mv.id_motivo_solicitud=ecc.causa_expedienteci')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->where('ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3')
			->where("(YEAR(ecc.fechacrea_expedienteci) = '".$fecha_actual[0]."' AND MONTH(ecc.fechacrea_expedienteci) = '".$fecha_actual[1]."')")
			->group_by('ecc.causa_expedienteci')
			->order_by('mv.nombre_motivo');

        return $query=$this->db->get();
    }

  	function registros_consolidado_casos_finalizados($data){
  		$fecha_actual = explode("-", $data["anio"]."-".$data["value"]."-01");

		$this->db->select(" res.resultadoci AS resultado,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE 0 END),0) cant_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE 0 END),0) cant_feme,
			COALESCE(COUNT(p.sexo_personaci),0) cant_total,
			ecc.fechacrea_expedienteci fecha_inicio,
			fea.id_fechasaudienciasci fecha_fin")
			->from('sct_expedienteci AS ecc')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->join('sct_fechasaudienciasci fea','fea.id_expedienteci=ecc.id_expedienteci')
			->join('sct_resultadosci res','res.id_resultadoci=fea.resultado')
			->where('ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3')
			->where('fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa
					 WHERE fa.id_expedienteci=fea.id_expedienteci)')
			->where("(YEAR(fea.fecha_resultado) = '".$fecha_actual[0]."' AND MONTH(fea.fecha_resultado) = '".$fecha_actual[1]."')")
			->where("fea.estado_audiencia = 2")
			->group_by('fea.resultado');

        return $query=$this->db->get();
    }

    function registros_consolidado_expedientes_pendientes($data){
  		$fecha_actual = explode("-",$data["anio"]."-".$data["value"]."-01");

		$this->db->select(" 'EXPEDIENTES PENDIENTES PARA EL PRÓXIMO MES' AS texto,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE 0 END),0) cant_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE 0 END),0) cant_feme,
			COALESCE(COUNT(p.sexo_personaci),0) cant_total,
			ecc.fechacrea_expedienteci fecha_inicio,
			fea.fecha_fechasaudienciasci fecha_fin")
			->from('sct_expedienteci AS ecc')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->join('sct_fechasaudienciasci fea','fea.id_expedienteci=ecc.id_expedienteci','LEFT')
			->join('sct_resultadosci res','res.id_resultadoci=fea.resultado','LEFT')
			->where('ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3')
			->where('(fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa
					 WHERE fa.id_expedienteci=fea.id_expedienteci) OR fea.id_expedienteci IS NULL)')
			->where("(YEAR(ecc.fechacrea_expedienteci) = '".$fecha_actual[0]."' AND MONTH(ecc.fechacrea_expedienteci) = '".$fecha_actual[1]."')")
			->where("(fea.estado_audiencia = 1 OR `fea`.`estado_audiencia` IS NULL OR fea.resultado IN(2,3))");

        return $query=$this->db->get();
    }

    function registros_consolidado_personas_despedidas($data){
  		$fecha_actual = explode("-",$data["anio"]."-".$data["value"]."-01");

		$this->db->select(" 'EXPEDIENTES PENDIENTES PARA EL PRÓXIMO MES' AS texto,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE 0 END),0) cant_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE 0 END),0) cant_feme,
			COALESCE(COUNT(p.sexo_personaci),0) cant_total,
			ecc.fechacrea_expedienteci fecha_inicio,
			fea.fecha_fechasaudienciasci fecha_fin")
			->from('sct_expedienteci AS ecc')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->join('sct_fechasaudienciasci fea','fea.id_expedienteci=ecc.id_expedienteci','LEFT')
			->join('sct_resultadosci res','res.id_resultadoci=fea.resultado','LEFT')
			->where('ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3')
			->where('(fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa
					 WHERE fa.id_expedienteci=fea.id_expedienteci) OR fea.id_expedienteci IS NULL)')
			->where("(YEAR(ecc.fechacrea_expedienteci) = '".$fecha_actual[0]."' AND MONTH(ecc.fechacrea_expedienteci) = '".$fecha_actual[1]."')")
			->where('ecc.motivo_expedienteci = 1');

        return $query=$this->db->get();
    }

    function registros_consolidado_audiencias($data){
  		$fecha_actual = explode("-",$data["anio"]."-".$data["value"]."-01");

		$this->db->select(" 'EXPEDIENTES PENDIENTES PARA EL PRÓXIMO MES' AS texto,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE 0 END),0) cant_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE 0 END),0) cant_feme,
			COALESCE(COUNT(p.sexo_personaci),0) cant_total,
			ecc.fechacrea_expedienteci fecha_inicio,
			fea.fecha_fechasaudienciasci fecha_fin")
			->from('sct_expedienteci AS ecc')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->join('sct_fechasaudienciasci fea','fea.id_expedienteci=ecc.id_expedienteci','LEFT')
			->join('sct_resultadosci res','res.id_resultadoci=fea.resultado','LEFT')
			->where('ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3')
			->where('fea.resultado IN (1,2,7)')
			->where("(YEAR(fea.fecha_resultado) = '".$fecha_actual[0]."' AND MONTH(fea.fecha_resultado) = '".$fecha_actual[1]."')");

        return $query=$this->db->get();
    }

    function registros_consolidado_pagos($data){
		$this->db->select("
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN fp.montopago_fechaspagosci ELSE 0 END),0) monto_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN fp.montopago_fechaspagosci ELSE 0 END),0) monto_feme,
			COALESCE(SUM(fp.montopago_fechaspagosci),0) monto_total,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN 1 ELSE 0 END),0) cant_masc,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN 1 ELSE 0 END),0) cant_feme,
			COUNT(p.sexo_personaci) cant_total,
			")
			->from('sct_expedienteci AS ecc')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->join('sct_fechasaudienciasci fea','fea.id_expedienteci=ecc.id_expedienteci')
			->join('sct_fechaspagosci AS fp', 'fp.id_persona = p.id_personaci')
			->where('fea.id_fechasaudienciasci = (SELECT MIN(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa
					 WHERE fa.id_expedienteci=fea.id_expedienteci)')
			->where('fea.estado_audiencia = 2')
			->where('(ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3)');

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
			->where('fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa 
					 WHERE fa.id_expedienteci=fea.id_expedienteci)')
			->where("(YEAR(fea.fecha_resultado) = '".$fecha_actual[0]."' AND MONTH(fea.fecha_resultado) = '".$fecha_actual[1]."')")
			->where("(fea.resultado IN(4,6,8))")
			->group_by('fea.inasistencia');

        return $query=$this->db->get();
    }



}
