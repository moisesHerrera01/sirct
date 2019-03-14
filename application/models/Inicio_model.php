<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function registros_relaciones_individuales($data){
		$anios16 = (intval(date("Y"))-16).date("-m-d");
		$anios30 = (intval(date("Y"))-30).date("-m-d");
		$anios50 = (intval(date("Y"))-50).date("-m-d");

		$this->db->select("
			ecc.numerocaso_expedienteci,
			d.departamento,

			CONCAT_WS(' ', emp.primer_nombre, emp.segundo_nombre, emp.tercer_nombre, emp.primer_apellido, emp.segundo_apellido, emp.apellido_casada) delegado,

			COALESCE(SUM(CASE WHEN ecc.id_empleador !=0 AND ecc.id_empleador IS NOT NULL THEN 1 ELSE 0 END),0) cant_empleador,

			COALESCE(SUM(CASE WHEN p.fnacimiento_personaci BETWEEN '".$anios30."' AND '".$anios16."' AND p.sexo_personaci = 'M' THEN 1 END),0) aniosm16,
			COALESCE(SUM(CASE WHEN p.fnacimiento_personaci BETWEEN '".$anios50."' AND '".$anios30."' AND p.sexo_personaci = 'M' THEN 1 END),0) aniosm30,
			COALESCE(SUM(CASE WHEN p.fnacimiento_personaci < '".$anios50."' AND p.sexo_personaci = 'M' THEN 1 END),0) aniosm50,
			COALESCE(SUM(CASE WHEN p.fnacimiento_personaci BETWEEN '".$anios30."' AND '".$anios16."' AND p.sexo_personaci = 'F' THEN 1 END),0) aniosf16,
			COALESCE(SUM(CASE WHEN p.fnacimiento_personaci BETWEEN '".$anios50."' AND '".$anios30."' AND p.sexo_personaci = 'F' THEN 1 END),0) aniosf30,
			COALESCE(SUM(CASE WHEN p.fnacimiento_personaci < '".$anios50."' AND p.sexo_personaci = 'F' THEN 1 END),0) aniosf50,

			COALESCE(SUM(CASE WHEN p.fnacimiento_personaci BETWEEN '".$anios30."' AND '".$anios16."' THEN 1 END),0) anios16,
			COALESCE(SUM(CASE WHEN p.fnacimiento_personaci BETWEEN '".$anios50."' AND '".$anios30."' THEN 1 END),0) anios30,
			COALESCE(SUM(CASE WHEN p.fnacimiento_personaci < '".$anios50."' THEN 1 END),0) anios50,

			COALESCE(SUM(CASE WHEN p.discapacidad_personaci = 1 AND p.sexo_personaci = 'M' THEN 1 END),0) discapacitadom,
			COALESCE(SUM(CASE WHEN p.discapacidad_personaci <> 1 AND p.sexo_personaci = 'M' THEN 1 END),0) nodiscapacitadom,
			COALESCE(SUM(CASE WHEN p.discapacidad_personaci = 1 AND p.sexo_personaci = 'F' THEN 1 END),0) discapacitadof,
			COALESCE(SUM(CASE WHEN p.discapacidad_personaci <> 1 AND p.sexo_personaci = 'F' THEN 1 END),0) nodiscapacitadof,
			COALESCE(SUM(CASE WHEN p.discapacidad_personaci = 1 THEN 1 END),0) discapacitado,

			COALESCE(SUM(CASE WHEN p.pertenece_lgbt = 1 AND p.sexo_personaci = 'M' THEN 1 END),0) lgbtim,
			COALESCE(SUM(CASE WHEN p.pertenece_lgbt <> 1 AND p.sexo_personaci = 'M' THEN 1 END),0) nolgbtim,
			COALESCE(SUM(CASE WHEN p.pertenece_lgbt = 1 AND p.sexo_personaci = 'F' THEN 1 END),0) lgbtif,
			COALESCE(SUM(CASE WHEN p.pertenece_lgbt <> 1 AND p.sexo_personaci = 'F' THEN 1 END),0) nolgbtif,
			COALESCE(SUM(CASE WHEN p.pertenece_lgbt = 1 THEN 1 END),0) lgbti,

			COALESCE(SUM(CASE WHEN ecc.embarazada = 1 AND p.sexo_personaci = 'F' THEN 1 END),0) embarazada,
			COALESCE(SUM(CASE WHEN (ecc.embarazada = 0 OR ecc.embarazada IS NULL) AND p.sexo_personaci = 'F' THEN 1 END),0) noembarazada,

			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'M' THEN 1 END),0) totalm,
			COALESCE(SUM(CASE WHEN p.sexo_personaci = 'F' THEN 1 END),0) totalf,
			COALESCE(COUNT(ecc.id_expedienteci),0) total,

			ecc.fechacrea_expedienteci fecha_inicio,

			COALESCE((SELECT fea.fecha_resultado FROM sct_fechasaudienciasci fea
				JOIN sct_resultadosci r ON r.id_resultadoci=fea.resultado WHERE estado_audiencia=2
				AND fea.id_expedienteci = ecc.id_expedienteci
				AND fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa WHERE fa.id_expedienteci=fea.id_expedienteci AND fa.estado_audiencia=2)), 'N/A') fecha_fin,

			CONCAT_WS(' ',p.nombre_personaci,p.apellido_personaci) solicitante,

			(SELECT SUM(fp.montopago_fechaspagosci) FROM sct_fechaspagosci AS fp WHERE fp.id_expedienteci = ecc.id_expedienteci) AS monto,

			COALESCE((SELECT r.resultadoci FROM sct_fechasaudienciasci fea
				JOIN sct_resultadosci r ON r.id_resultadoci=fea.resultado WHERE estado_audiencia=2
				AND fea.id_expedienteci = ecc.id_expedienteci
				AND fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci) FROM sct_fechasaudienciasci fa WHERE fa.id_expedienteci=fea.id_expedienteci AND fa.estado_audiencia=2)), 'Pendiente') resultadoci")

			->from('sct_expedienteci AS ecc')
			->join('sct_motivo_solicitud mv','mv.id_motivo_solicitud=ecc.causa_expedienteci')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sge_empresa est', 'ecc.id_empresaci = est.id_empresa')
			->join('sge_catalogociiu ciiu', 'est.id_catalogociiu = ciiu.id_catalogociiu')
			->join('sct_delegado_exp de','de.id_expedienteci=ecc.id_expedienteci')
			->join('sir_empleado emp','emp.id_empleado = de.id_personal')
			->join('org_municipio m','m.id_municipio=emp.id_muni_residencia')
			->join('org_departamento d','d.id_departamento=m.id_departamento_pais')
			->where("de.id_delegado_exp = (SELECT MAX(de2.id_delegado_exp)
					 FROM sct_delegado_exp de2
					 WHERE de2.id_expedienteci=de.id_expedienteci
					 AND de2.id_personal <> 0 )")
			//->where("de.id_personal IN(".$data["id_delegado"].")")
			->where('(ecc.tiposolicitud_expedienteci BETWEEN 1 AND 3)')
			->group_by('ecc.tiposolicitud_expedienteci')
			->order_by('ecc.tiposolicitud_expedienteci', 'ASC');

		/*if($data["tipo"] == "mensual"){
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
	 	}*/

    return $query=$this->db->get();
    }

}
