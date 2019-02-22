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

    public function obtener_estadisticas_ficha($id_expediente) {
      
      $this->db->select(
              "(SELECT COUNT(*)
              FROM sct_personaci a
              WHERE a.sexo_personaci = 'M'
              AND a.id_expedienteci = f.id_expedienteci
            ) AS hombres, (
              SELECT COUNT(*)
              FROM sct_personaci b
              WHERE b.sexo_personaci = 'F'
              AND b.id_expedienteci = f.id_expedienteci
            ) AS mujeres, (
              SELECT COUNT(*)
              FROM ( SELECT TIMESTAMPDIFF( YEAR,c.fnacimiento_personaci,CURDATE() ) AS edad, c.id_expedienteci FROM sct_personaci c ) d
              WHERE d.edad < 18
              AND  d.id_expedienteci = f.id_expedienteci
            ) AS menores, (
              SELECT COUNT(*)
              FROM ( sct_personaci e )
              WHERE e.discapacidad_personaci = 1
              AND e.id_expedienteci = f.id_expedienteci
            ) AS discapacitados"
          )
          ->from('sct_personaci f')
          ->where(' f.id_expedienteci',$id_expediente)
          ->limit(1);
      $query = $this->db->get();
      if ($query->num_rows() > 0) {
      return $query;
      }else {
      return FALSE;
      }

    }

}

?>