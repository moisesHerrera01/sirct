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
        return $data["id_expedienteci"];
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
      $this->db->select('a.*,b.*,a.fechaconflicto_personaci')
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
                         e.forma_pago,
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
                         s.abreviatura_sindicato,
                         s.direccion_sindicato,
                         s.telefono_sindicato,
                         s.totalafiliados_sindicato,
                         m.municipio,
                         CONCAT_WS(" ",em.primer_nombre,em.segundo_nombre,em.primer_apellido,em.segundo_apellido,em.apellido_casada) delegado,
                         mu.id_municipio municipio_empresa,
                         cat.id_catalogociiu,
                         e.causa_expedienteci,
                         d.nombre_delegado_actual,
                         ms.nombre_motivo,
                         re.nombres_representante rlegal_empresa,
                         re.sexo_representante,
                         na.nivel_academico rlegal_nivel_academico,
                         es.nombre_empresa'
                       )
               ->from('sct_expedienteci e')
               ->join('sct_motivo_solicitud ms','ms.id_motivo_solicitud=e.motivo_expedienteci')
               ->join('sge_empresa es','es.id_empresa=e.id_empresaci')
               ->Join('sge_representante re ', 're.id_empresa = es.id_empresa AND re.tipo_representante=1','left')
               ->join('sir_titulo_academico ta','ta.id_titulo_academico=re.id_titulo_academico','left')
  						 ->join('sir_nivel_academico na','na.id_nivel_academico=ta.id_nivel_academico','left')
               ->join('sge_catalogociiu cat','cat.id_catalogociiu=es.id_catalogociiu')
               ->join('org_municipio mu','mu.id_municipio=es.id_municipio')
               ->join('sge_sindicato s','s.id_expedientecc=e.id_expedienteci')
               ->join('org_municipio m','m.id_municipio=s.id_municipio')
               ->join('sir_empleado em','em.id_empleado=e.id_personal')
               ->join("(
                    SELECT de.id_expedienteci,de.id_personal delegado_actual,
                    CONCAT_WS(' ',emp.primer_nombre,emp.segundo_nombre,emp.tercer_nombre,emp.primer_apellido,emp.segundo_apellido,emp.apellido_casada) nombre_delegado_actual
                    FROM sct_delegado_exp de
                    JOIN sir_empleado emp ON emp.id_empleado=de.id_personal
                    WHERE de.id_delegado_exp = (SELECT MAX(de2.id_delegado_exp)
                                                FROM sct_delegado_exp de2
                                                WHERE de2.id_expedienteci=de.id_expedienteci
                                                AND de2.id_personal <> 0
                                               )
                  ) d" , "d.id_expedienteci=e.id_expedienteci")
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
                  UPPER(a.forma_pago) forma_pago,
                  a.id_personal,
                  a.numerocaso_expedienteci,
                  a.fechacrea_expedienteci,
                  a.fechaconflicto_personaci,
                  b.nombre_personaci,
                  b.apellido_personaci,
                  b.funciones_personaci,
                  UPPER(c.nombre_empresa) nombre_empresa,
                  c.numinscripcion_empresa,
                  c.direccion_empresa,
                  c.telefono_empresa,
                  c.direccion_empresa,
                  c.tiposolicitud_empresa,
                  c.abreviatura_empresa,
                  UPPER(c.abreviatura_empresa) abreviatura_empresa,
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
                  h.horarios_personaci horarios_solicitante,
                  h.email,
                  d.nombre_delegado_actual,
                  de.departamento,
                  m.municipio mun_solicitante,
                  dep.departamento depto_solicitante
              ')
               ->from('sct_expedienteci a')
               ->join('sct_personaci b', 'a.id_personaci = b.id_personaci', 'left')
               ->join('sge_empresa c', 'a.id_empresaci = c.id_empresa')
               ->join('org_municipio d', 'c.id_municipio = d.id_municipio')
               ->join('org_municipio m','m.id_municipio = b.id_municipio','left')
               ->join('org_departamento dep','dep.id_departamento = m.id_departamento_pais','left')
               ->join('org_departamento de','de.id_departamento = d.id_departamento_pais')
               ->join('sge_catalogociiu e', 'c.id_catalogociiu = e.id_catalogociiu', 'left')
               ->Join('sge_representante f ', 'c.id_empresa = f.id_empresa AND f.tipo_representante=1','left')
               ->join('sir_empleado g','g.id_empleado = a.id_personal')
               ->join('sct_personaci h', 'a.id_expedienteci = h.id_expedienteci')
               ->join("(
                    SELECT de.id_expedienteci,de.id_personal delegado_actual,
                    CONCAT_WS(' ',emp.primer_nombre,emp.segundo_nombre,emp.tercer_nombre,emp.primer_apellido,emp.segundo_apellido,emp.apellido_casada) nombre_delegado_actual
                    FROM sct_delegado_exp de
                    JOIN sir_empleado emp ON emp.id_empleado=de.id_personal
                    WHERE de.id_delegado_exp = (SELECT MAX(de2.id_delegado_exp)
                                                FROM sct_delegado_exp de2
                                                WHERE de2.id_expedienteci=de.id_expedienteci
                                                AND de2.id_personal <> 0
                                               )
                  ) d" , "d.id_expedienteci=a.id_expedienteci")
               ->where("a.id_expedienteci", $id)
               ->limit(1)
               ->order_by('h.id_personaci', 'ASC');

      $query = $this->db->get();

			if ($query->num_rows() > 0) {
					return  $query;
			}
			else {
					return FALSE;
			}
    }

    public function obtener_motivos(){
      $this->db->select('id_motivo_solicitud,nombre_motivo,id_tipo_solicitud,estado_motivo')
               ->from('sct_motivo_solicitud')
               ->where('id_tipo_solicitud>3')
               ->where('estado_motivo',1);
      $query = $this->db->get();
      if ($query->num_rows() > 0) {
        return $query;
      }else {
        FALSE;
      }
    }

    public function obtener_resultados(){
      $this->db->select('*')
               ->from('sct_resultadosci')
               ->where('id_tipo_solicitud',"5");
      $query = $this->db->get();
      if ($query->num_rows() > 0) {
        return $query;
      }else {
        return FALSE;
      }
    }

}

?>
