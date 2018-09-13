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
}
