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

	public function obtener_directivos_sindicato($id_sindicato){
		$this->db->select('d.id_sindicato,d.id_directivo,d.nombre_directivo,d.apellido_directivo,d.tipo_directivo,d.acreditacion_directivo')
						 ->from('sge_directivo d')
		         ->where('d.id_sindicato',$id_sindicato);
		$query = $this->db->get();
		if ($query->num_rows()>0) {
			return $query;
		}else {
			return FALSE;
		}
	}
}
