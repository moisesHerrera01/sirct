<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sindicatos_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}
  public function editar_sindicato($data){
    $this->db->where("id_sindicato",$data["id_sindicato"]);
    if ($this->db->update('sge_sindicato', $data)) {
      return $data["id_sindicato"];
    }else {
      return "fracaso";
    }
  }

  public function insertar_sindicato($data){
    if ($this->db->insert('sge_sindicato', $data)) {
			return $this->db->insert_id();
    }else {
      return "fracaso";
    }
  }

	public function obtener_expedientes_sindicatos($nr,$estado){
		$this->db->select(
											's.id_sindicato,
											 ex.id_expedienteci,
											 em.id_empresa,
											 ex.id_personal,
											 ex.id_estadosci,
											 ex.numerocaso_expedienteci,
											 s.nombre_sindicato,
											 em.nombre_empresa,
											 CONCAT_WS(" ",e.primer_nombre,e.segundo_nombre,e.primer_apellido,e.segundo_apellido,e.apellido_casada) delegado,
											 es.nombre_estadosci,
											 (select count(*) from sct_fechasaudienciasci f where f.id_expedienteci=ex.id_expedienteci) cuenta,
											 (SELECT r.resultadoci
												FROM sct_fechasaudienciasci fea
												JOIN sct_resultadosci r ON r.id_resultadoci=fea.resultado
												WHERE estado_audiencia=2
												AND fea.id_expedienteci = ex.id_expedienteci
												AND fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci)
																												 FROM sct_fechasaudienciasci fa
																												 WHERE fa.id_expedienteci=fea.id_expedienteci
																												 AND fa.estado_audiencia=2)) AS resultado_expedienteci'
										 )
						 ->from('sir_empleado e')
						 ->join('sct_expedienteci ex','ex.id_personal=e.id_empleado')
						 ->join('sge_sindicato s','s.id_expedientecc=ex.id_expedienteci')
						 ->join('sge_empresa em','em.id_empresa=ex.id_empresaci')
						 ->join('sct_estadosci es','es.id_estadosci=ex.id_estadosci');
		if ($nr) {
			$this->db->where('e.nr',$nr);
		}if ($estado) {
			$this->db->where('ex.id_estadosci',$estado);
		}
		$query = $this->db->get();
		if ($query->num_rows()>0) {
			return $query;
		}else {
			return FALSE;
		}
	}

	public function obtener_municipios(){
		$this->db->select('m.id_municipio,m.municipio')
						 ->from('org_municipio m');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query;
		}else {
			return FALSE;
		}
	}

	public function obtener_tipo_directivos(){
		$this->db->select('id_tipo_directivo,tipo_directivo,estado_tipo_directivo')
						 ->from('sge_tipo_directivo');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query;
		}else {
			return FALSE;
		}
	}

	public function obtener_resultados(){
		$this->db->select('*')
						 ->from('sct_resultadosci')
						 ->where('id_tipo_solicitud',4);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query;
		}else {
			return FALSE;
		}
	}
}
