<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empleadores_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

  function insertar_empleador($data){
    if($this->db->insert('sge_empleador', array(
      'nombre_empleador' => $data['nombre_empleador'],
      'apellido_empleador' => $data['apellido_empleador'],
      'cargo_empleador' => $data['cargo_empleador']
    ))){
      return $this->db->insert_id();
    }else{
      return "fracaso";
    }
  }
}
