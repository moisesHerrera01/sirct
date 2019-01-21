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
}
