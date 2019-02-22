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

	public function obtener_delegados_expediente($id_expedienteci){
		$this->db->select('DATE_FORMAT(fecha_cambio_delegado, "%d-%m-%Y") fecha_cambio_delegado,u.nombre_completo,
											 CASE WHEN d.id_personal = 0 THEN d.cambios
											 ELSE UPPER(CONCAT_WS(" ",d.cambios,(SELECT CONCAT_WS(" ",e.primer_nombre,e.segundo_nombre,e.tercer_nombre,e.primer_apellido,e.segundo_apellido,e.apellido_casada) FROM sir_empleado e WHERE e.id_empleado=d.id_personal)) ) END cambios'
										  )
						 ->from('sct_delegado_exp d')
						 ->join('org_usuario u','u.id_usuario=d.id_usuario_guarda')
						 // ->join('org_rol r','r.id_rol=d.id_rol_guarda')
						 // ->join('sir_empleado e','e.id_empleado=d.id_personal','left')
						 ->where('d.id_expedienteci',$id_expedienteci)
						 ->order_by('d.id_delegado_exp','DESC');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query;
		}else {
			return FALSE;
		}
	}

	public function obtener_delegado_actual($id_expedienteci){
		$this->db->select('CONCAT_WS(" ",e.primer_nombre,e.segundo_nombre,e.tercer_nombre,e.primer_apellido,e.segundo_apellido,e.apellido_casada) nombre_empleado')
						 ->from('sir_empleado e')
						 ->join('sct_delegado_exp d','d.id_personal=e.id_empleado')
						 ->where('d.id_expedienteci',$id_expedienteci)
						 ->where("d.id_delegado_exp = (SELECT MAX(de.id_delegado_exp)
						  														 FROM sct_delegado_exp de
																					 WHERE de.id_expedienteci=d.id_expedienteci
																					 AND de.id_personal <> 0)");
    $query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		}else {
			return FALSE;
		}
	}

}
