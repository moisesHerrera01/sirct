<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delegados_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

  public function insertar_delegado_exp($data){
    if ($this->db->insert('sct_delegado_exp', $data)) {
      return $this->db->insert_id();
    }else {
      return "fracaso";
    }
  }


}
