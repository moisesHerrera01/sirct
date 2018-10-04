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
			(SELECT COUNT(*) FROM sct_personaci AS p2 WHERE p2.id_expedienteci = ecc.id_expedienteci  AND p2.discapacidad_personaci = '1') AS discapacidadci,
			ecc.*, p.*, emp.*, est.*, ciiu.*")
			->from('sct_expedienteci AS ecc')
			->join('sct_personaci p ', 'p.id_personaci = ecc.id_personaci')
			->join('sir_empleado emp','emp.id_empleado = ecc.id_personal')
			->join('sge_empresa est', 'ecc.id_empresaci = est.id_empresa')
			->join('sge_catalogociiu ciiu', 'est.id_catalogociiu = ciiu.id_catalogociiu');

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
	 	}else{
	 		$this->db->where('YEAR(ecc.fechacrea_expedienteci)', $data["anio"]);
	 	}
        
        return $query=$this->db->get();
    }


}