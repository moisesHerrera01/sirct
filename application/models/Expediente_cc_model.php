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

    public function expedientes_diferencia_laboral($id_expedienteci){
      $this->db->select(
                        'e.id_expedienteci,
                         e.numerocaso_expedienteci,
                         e.id_empresaci,
                         e.id_estadosci,
                         e.id_personal,
                         e.motivo_expedienteci,
                         e.descripmotivo_expedienteci,
                         e.tiposolicitud_expedienteci,
                         e.fechacrea_expedienteci,
                         s.id_sindicato,
                         s.id_municipio,
                         s.nombre_sindicato,
                         s.direccion_sindicato,
                         s.telefono_sindicato,
                         s.totalafiliados_sindicato'
                       )
               ->from('sct_expedienteci e')
               ->join('sge_sindicato s','s.id_expedientecc=e.id_expedienteci')
               ->where('e.id_expedienteci',$id_expedienteci);
      $query = $this->db->get();
      if ($query->num_rows() > 0) {
        return $query;
      }else {
        return FALSE;
      }
    }
}

?>
