<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Representante_persona_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	public function editar_representante($data){
		$this->db->where("id_representantepersonaci",$data["id_representantepersonaci"]);
		if ($this->db->update('sct_representantepersonaci', $data)) {
			return "exito";
		}else {
			return "fracaso";
		}
	}

  public function insertar_representante($data){
    if ($this->db->insert('sct_representantepersonaci', $data)) {
      $this->db->insert_id();
    }else {
      return "fracaso";
    }
  }
}