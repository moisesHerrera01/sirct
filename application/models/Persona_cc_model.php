<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persona_cc_model extends CI_Model {

    function __construct(){
		parent::__construct();
    }

    public function insertar_persona_conflicto($data) {
      if ($this->db->insert('sct_personaci', $data)) {
        return $this->db->insert_id();
      }else {
        return "fracaso";
      }
    }

    public function editar_persona($data){
      $this->db->where("id_personaci", $data["id_personaci"]);
      if ($this->db->update('sct_personaci', $data)) {
        return "exito";
      }else {
        return "fracaso";
      }
    }

    public function obtener_persona($id) {
      $this->db->from('sct_personaci')
               ->where("id_personaci", $id);

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