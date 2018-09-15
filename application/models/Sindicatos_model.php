<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sindicatos_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}
  public function editar_sindicato($data){
    $this->db->where("id_sindicato",$data["id_sindicato"]);
    if ($this->db->update('sct_sindicato', $data)) {
      return "exito";
    }else {
      return "fracaso";
    }
  }

  public function insertar_sindicato($data){
    if ($this->db->insert('sct_sindicato', $data)) {
      $this->db->insert_id();
    }else {
      return "fracaso";
    }
  }

	public function obtener_expedientes_sindicatos($nr,$estado){
		$this->db->select('ex.id_expedienteci,em.id_empresa,ex.id_personal,ex.id_estadosci,ex.numerocaso_expedienteci,s.nombre_sindicato,em.nombre_empresa,
											 CONCAT_WS(" ",e.primer_nombre,e.segundo_nombre,e.primer_apellido,e.segundo_apellido,e.apellido_casada) delegado,
											 es.nombre_estadosci,ex.resultado_expedienteci')
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
}