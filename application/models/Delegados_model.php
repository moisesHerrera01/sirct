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
		$this->db->select('CONCAT_WS(" ",e.primer_nombre,e.segundo_nombre,e.tercer_nombre,e.primer_apellido,e.segundo_apellido,e.apellido_casada) nombre_delegado_actual,
											 DATE_FORMAT(fecha_cambio_delegado, "%d-%m-%Y") fecha_cambio_delegado,r.nombre_rol,u.nombre_completo, d.cambios')
						 ->from('sct_delegado_exp d')
						 ->join('org_usuario u','u.id_usuario=d.id_usuario_guarda')
						 ->join('org_rol r','r.id_rol=d.id_rol_guarda')
						 ->join('sir_empleado e','e.id_empleado=d.id_personal')
						 ->where('d.id_expedienteci',$id_expedienteci)
						 ->order_by('d.id_delegado_exp','DESC');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query;
		}else {
			return FALSE;
		}
	}


}
