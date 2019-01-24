<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Directivos_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}
  public function editar_directivo($data){
    $this->db->where("id_directivo",$data["id_directivo"]);
    if ($this->db->update('sge_directivo', $data)) {
      return "exito";
    }else {
      return "fracaso";
    }
  }

  public function insertar_directivo($data){
    if ($this->db->insert('sge_directivo', $data)) {
      return $this->db->insert_id();
    }else {
      return "fracaso";
    }
  }

	public function obtener_directivo($id_directivo){
		$query = $this->db->get_where('sge_directivo', array('id_directivo' => $id_directivo));
		if ($query->num_rows()>0) {
			return $query;
		}else {
			return FALSE;
		}
	}

	public function obtener_directivos_sindicato($id_sindicato, $estado_directivo=FALSE){
		$this->db->select(
											'd.dui_directivo,
											 d.id_sindicato,
											 d.id_directivo,
											 d.nombre_directivo,
											 d.apellido_directivo,
											 d.tipo_directivo,
											 d.acreditacion_directivo,
											 d.estado_directivo,
											 d.sexo_directivo,
											 td.tipo_directivo tipo'
										 )
						 ->from('sge_directivo d')
						 ->join('sge_tipo_directivo td','td.id_tipo_directivo=d.tipo_directivo')
		         ->where('d.id_sindicato',$id_sindicato);
		if ($estado_directivo) {
			$this->db->where('d.estado_directivo',1);
		}
		$query = $this->db->get();
		if ($query->num_rows()>0) {
			return $query;
		}else {
			return FALSE;
		}
	}

	public function insertar_directivo_audiencia($data){
    if ($this->db->insert('sct_directivos_audiencia', $data)) {
      return $this->db->insert_id();
    }else {
      return "fracaso";
    }
  }

	public function obtener_directivos_asistencia($id_expedienteci){
		$this->db->select('CONCAT_WS(" ",d.nombre_directivo,d.apellido_directivo) nombre_directivo,
		 									d.acreditacion_directivo,
											d.dui_directivo,
											td.tipo_directivo,
											da.id_audiencia,
											m.municipio,
											s.nombre_sindicato,
											de.departamento,
											d.fnacimiento_directivo,
											d.ocupacion_directivo,
											s.abreviatura_sindicato'
											)
						 ->from('sct_directivos_audiencia da')
						 ->join('sge_directivo d','d.id_directivo=da.id_directivo')
						 ->join('sge_sindicato s','s.id_sindicato=d.id_sindicato')
						 ->join('sge_tipo_directivo td','td.id_tipo_directivo=d.tipo_directivo')
						 ->join('org_municipio m','m.id_municipio=d.municipio_directivo')
						 ->join('org_departamento de','de.id_departamento=m.id_departamento_pais')
						 ->where('s.id_expedientecc',$id_expedienteci);
		$query = $this->db->get();
		return $query;
	}
}
