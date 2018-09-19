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
                         s.totalafiliados_sindicato,
                         m.municipio,
                         CONCAT_WS(" ",em.primer_nombre,em.segundo_nombre,em.primer_apellido,em.segundo_apellido,em.apellido_casada) delegado'
                       )
               ->from('sct_expedienteci e')
               ->join('sge_sindicato s','s.id_expedientecc=e.id_expedienteci')
               ->join('org_municipio m','m.id_municipio=s.id_municipio')
               ->join('sir_empleado em','em.id_empleado=e.id_personal')
               ->where('e.id_expedienteci',$id_expedienteci);
      $query = $this->db->get();
      if ($query->num_rows() > 0) {
        return $query;
      }else {
        return FALSE;
      }
    }

    public function obtener_expediente_indemnizacion($id) {
      $this->db->select('
                  a.id_expedienteci,
                  a.numerocaso_expedienteci,
                  a.fechacrea_expedienteci,
                  b.fechaconflicto_personaci,
                  b.nombre_personaci,
                  b.apellido_personaci,
                  b.funciones_personaci,
                  c.nombre_empresa,
                  c.numinscripcion_empresa,
                  c.direccion_empresa,
                  c.telefono_empresa,
                  c.direccion_empresa, 
                  d.municipio,
                  e.actividad_catalogociiu,
                  e.grupo_catalogociiu,
                  f.nombres_representante,
                  CONCAT_WS(" ",g.primer_nombre,g.segundo_nombre,g.primer_apellido,g.segundo_apellido,g.apellido_casada) delegado,
                  CONCAT_WS(" ", h.nombre_personaci, h.apellido_personaci) nombre_solicitante,
                  h.telefono_personaci telefono_solicitante,
                  h.direccion_personaci direccion_solicitante,
                  h.salario_personaci salario_solicitante,
                  h.formapago_personaci formapago_solicitante,
                  h.funciones_personaci funciones_solicitante,
                  h.horarios_personaci horarios_solicitante
              ')
               ->from('sct_expedienteci a')
               ->join('sct_personaci b', 'a.id_personaci = b.id_personaci', 'left')
               ->join('sge_empresa c', 'a.id_empresaci = c.id_empresa')
               ->join('org_municipio d', 'c.id_municipio = d.id_municipio')
               ->join('sge_catalogociiu e', 'c.id_catalogociiu = e.id_catalogociiu', 'left')
               ->join('sge_representante f', 'c.id_empresa = f.id_empresa', 'left')
               ->join('sir_empleado g','g.id_empleado = a.id_personal')
               ->join('sct_personaci h', 'a.id_expedienteci = h.id_expedienteci')
               ->where("a.id_expedienteci", $id)
               ->limit(1)
               ->order_by('h.id_personaci', 'DESC');

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
