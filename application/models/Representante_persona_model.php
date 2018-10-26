<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Representante_persona_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	public function editar_representante($data){
		$this->db->where("id_representantepersonaci",$data["id_representantepersonaci"]);
		if ($this->db->update('sct_representantepersonaci', $data)) {
			return $data["id_representantepersonaci"];
		}else {
			return "fracaso";
		}
	}

  public function insertar_representante($data){
    if ($this->db->insert('sct_representantepersonaci', $data)) {
      return $this->db->insert_id();
    }else {
      return "fracaso";
    }
  }

	public function obtener_defensores(){
		$this->db->select("id_representantepersonaci, CONCAT_WS(' ',nombre_representantepersonaci,apellido_representantepersonaci) nombre_completo")
						 ->from('sct_representantepersonaci');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query;
		}else {
			return FALSE;
		}
	}
}
