<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expedientes_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

  function insertar_expediente($data){
    if($this->db->insert('sct_expedienteci', array(
      'motivo_expedienteci' => $data['motivo_expedienteci'],
      'descripmotivo_expedienteci' => $data['descripmotivo_expedienteci'],
      'id_personaci' => $data['id_personaci'],
      'id_personal' => $data['id_personal'],
      'id_empresaci' => $data['id_empresaci'],
			'id_estadosci' => $data['id_estadosci'],
			'fechacrea_expedienteci' => $data['fechacrea_expedienteci'],
			'tiposolicitud_expedienteci' => $data['tiposolicitud_expedienteci'],
			'numerocaso_expedienteci' => $data['numerocaso_expedienteci']

    ))){
      return "exito,".$this->db->insert_id();
    }else{
      return "fracaso";
    }
  }
}
