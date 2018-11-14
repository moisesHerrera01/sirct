<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_colectivos_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function registros_relaciones_colectivas($data){
		$this->db->select("
			(SELECT COUNT(*) FROM sct_personaci AS p2 WHERE p2.id_expedienteci = ecc.id_expedienteci AND p2.sexo_personaci = 'M') AS cant_masc,
			(SELECT COUNT(*) FROM sct_personaci AS p2 WHERE p2.id_expedienteci = ecc.id_expedienteci  AND p2.sexo_personaci = 'F') AS cant_feme,
			(SELECT SUM(fp.montopago_fechaspagosci) FROM sct_fechaspagosci AS fp JOIN sct_personaci AS p3 WHERE p3.id_personaci = fp.id_persona AND p3.sexo_personaci = 'M') AS monto_masc,
			(SELECT SUM(fp.montopago_fechaspagosci) FROM sct_fechaspagosci AS fp JOIN sct_personaci AS p3 WHERE p3.id_personaci = fp.id_persona AND p3.sexo_personaci = 'F') AS monto_feme,
			(SELECT COUNT(*) FROM sct_personaci AS p2 WHERE p2.id_expedienteci = ecc.id_expedienteci  AND p2.discapacidad_personaci = '1') AS discapacidadci,
			ecc.*, p.*, emp.*, est.*, ciiu.*")
			->from('sct_expedienteci AS ecc')
			->join('sct_personaci p ', 'p.id_expedienteci = ecc.id_expedienteci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->join('sge_empresa est', 'ecc.id_empresaci = est.id_empresa')
			->join('sge_catalogociiu ciiu', 'est.id_catalogociiu = ciiu.id_catalogociiu')
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

    function registros_edades($data){
    	$anios16 = (intval(date("Y"))-16).date("-m-d");
		$anios30 = (intval(date("Y"))-30).date("-m-d");
		$anios50 = (intval(date("Y"))-50).date("-m-d");

		$this->db->select("
			(SELECT COUNT(*) FROM sct_personaci AS p2 WHERE p2.id_expedienteci = ecc.id_expedienteci AND p2.fnacimiento_personaci BETWEEN '".$anios30."' AND '".$anios16."' AND p2.sexo_personaci = 'M') AS aniosm16,
			(SELECT COUNT(*) FROM sct_personaci AS p2 WHERE p2.id_expedienteci = ecc.id_expedienteci AND p2.fnacimiento_personaci BETWEEN '".$anios50."' AND '".$anios30."' AND p2.sexo_personaci = 'M') AS aniosm30,
			(SELECT COUNT(*) FROM sct_personaci AS p2 WHERE p2.id_expedienteci = ecc.id_expedienteci AND p2.fnacimiento_personaci < '".$anios50."' AND p2.sexo_personaci = 'M') AS aniosm50,
			(SELECT COUNT(*) FROM sct_personaci AS p2 WHERE p2.id_expedienteci = ecc.id_expedienteci AND p2.fnacimiento_personaci BETWEEN '".$anios30."' AND '".$anios16."' AND p2.sexo_personaci = 'F') AS aniosf16,
			(SELECT COUNT(*) FROM sct_personaci AS p2 WHERE p2.id_expedienteci = ecc.id_expedienteci AND p2.fnacimiento_personaci BETWEEN '".$anios50."' AND '".$anios30."' AND p2.sexo_personaci = 'F') AS aniosf30,
			(SELECT COUNT(*) FROM sct_personaci AS p2 WHERE p2.id_expedienteci = ecc.id_expedienteci AND p2.fnacimiento_personaci < '".$anios50."' AND p2.sexo_personaci = 'F') AS aniosf50,
			ecc.*, p.*, emp.*, est.*, ciiu.*")
			->from('sct_expedienteci ecc')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->join('sge_empresa est', 'ecc.id_empresaci = est.id_empresa')
			->join('sge_catalogociiu ciiu', 'est.id_catalogociiu = ciiu.id_catalogociiu')
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

		public function reporte_tipo_pago($data){
			$this->db->select('
												 CONCAT_WS(" ",emp.primer_nombre,emp.segundo_nombre,emp.tercer_nombre,emp.primer_apellido,emp.segundo_apellido,emp.apellido_casada) delegado,
												 d.departamento,
												 ecc.numerocaso_expedienteci,
												 ecc.fechaconflicto_personaci,
												 ecc.fechacrea_expedienteci,
												 CONCAT_WS(" ",p.nombre_personaci,p.apellido_personaci) solicitante,
												 (SELECT COUNT(pe.id_personaci) FROM sct_personaci pe WHERE pe.id_expedienteci=ecc.id_expedienteci AND pe.sexo_personaci="M") masculino,
												 (SELECT COUNT(pe.id_personaci) FROM sct_personaci pe WHERE pe.id_expedienteci=ecc.id_expedienteci AND pe.sexo_personaci="F") femenino,
												 ciiu.actividad_catalogociiu actividad_economica,
												 mt.nombre_motivo causa,
												 a.resultadoci resultado,
												 a.fecha_fechasaudienciasci fecha_fin,
												 ciiu.actividad_catalogociiu actividad_economica,
												 ep.nombre_empresa solicitado
											  ')
						   ->from('sct_expedienteci ecc')
							 ->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
							 ->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
							 ->join('sge_empresa est', 'ecc.id_empresaci = est.id_empresa')
							 ->join('sge_catalogociiu ciiu', 'est.id_catalogociiu = ciiu.id_catalogociiu')
							 ->join('org_municipio m','m.id_municipio=emp.id_muni_residencia')
							 ->join('org_departamento d','d.id_departamento=m.id_departamento_pais')
							 ->join('sct_motivo_solicitud mt','mt.id_motivo_solicitud=motivo_expedienteci')
							 ->join('sge_empresa ep','ep.id_empresa=ecc.id_empresaci')
							 ->join('(SELECT fea.tipo_pago,fea.id_expedienteci,r.resultadoci, fea.fecha_fechasaudienciasci,fea.id_fechasaudienciasci
										 	 FROM sct_fechasaudienciasci fea
											 JOIN sct_resultadosci r ON r.id_resultadoci=fea.resultado
											 WHERE estado_audiencia=2
											 AND fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci)
											 																	FROM sct_fechasaudienciasci fa
																												WHERE fa.id_expedienteci=fea.id_expedienteci
																												AND fa.estado_audiencia=2)) a ',
										 'a.id_expedienteci=ecc.id_expedienteci')
							 ->group_by('ecc.id_expedienteci');
							 if ($data['tipo_pago']!='') {
							 	$this->db->where('a.tipo_pago',$data['tipo_pago']);
							 }
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



}
