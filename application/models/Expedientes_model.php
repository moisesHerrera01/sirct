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

	public function obtener_registros_expedientes($id) {

			$this->db->select('')
						 ->from('sct_expedienteci e')
						 ->join('sct_personaci p ', ' p.id_personaci = e.id_personaci')
						 ->join('sge_empresa em','em.id_empresa = e.id_empresaci')
						 ->join('sge_representante r ', ' r.id_empresa = e.id_empresaci')
						 ->join('sge_empleador emp','emp.id_empleador=p.id_empleador')
						 ->where('p.id_personaci', $id);
			$query=$this->db->get();
			if ($query->num_rows() > 0) {
					return  $query;
			}
			else {
					return FALSE;
			}

	}

	public function editar_expediente($data){
		$this->db->where("id_expedienteci",$data["id_expedienteci"]);
		if ($this->db->update('sct_expedienteci', $data)) {
			return "exito";
		}else {
			return "fracaso";
		}
	}
}
