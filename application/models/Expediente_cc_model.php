<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expediente_cc_model extends CI_Model {

    function __construct(){
		parent::__construct();
    }

    public function insertar_expediente($data) {
      if ($this->db->insert('sct_expedienteci', $data)) {
        return $this->db->insert_id();
      }else {
        return "fracaso";
      }
    }

    public function editar_expediente($data){
      $this->db->where("id_expedienteci", $data["id_expedienteci"]);
      if ($this->db->update('sct_expedienteci', $data)) {
        return "exito";
      }else {
        return "fracaso";
      }
    }

    public function obtener_expediente($id) {
      $this->db->from('sct_expedienteci a')
               ->where("a.id_expedienteci", $id);

      $query=$this->db->get();
      
			if ($query->num_rows() > 0) {
					return  $query;
			}
			else {
					return FALSE;
			}
    }

    public function obtener_expediente_persona($id) {
      $this->db->select('')
               ->from('sct_expedienteci a')
               ->join('sct_personaci b', 'a.id_personaci = b.id_personaci', 'left')
               ->where("a.id_expedienteci", $id);

      $query=$this->db->get();
      
			if ($query->num_rows() > 0) {
					return  $query;
			}
			else {
					return FALSE;
			}
    }

}

?>