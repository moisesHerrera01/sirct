<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function verificar_usuario($data){
		$query = $this->db->query("SELECT * FROM org_usuario WHERE usuario = '".$data['usuario']."'");
		if($query->num_rows() > 0){
			return "existe";
		}else{
			return "falta";
		}
	}

	function verificar_estado($data){
		$query = $this->db->query("SELECT * FROM org_usuario WHERE usuario = '".$data['usuario']."' AND estado = 1");
		if($query->num_rows() > 0){
			return "activo";
		}else{
			return "inactivo";
		}
	}

	function verificar_usuario_password($data){
		$query = $this->db->query("SELECT * FROM org_usuario WHERE usuario = '".$data['usuario']."' AND password = '".md5($data['password'])."' AND estado = 1");
		if($query->num_rows() > 0){
			return "existe";
		}else{
			return "falta";
		}
	}

	function get_data_user($data){
		// $query = $this->db->query("SELECT * FROM org_usuario WHERE usuario = '".$data['usuario']."' AND estado = 1");

		$this->db->select('')
						 ->from('org_usuario o')
						 ->join('sir_empleado e','e.nr=o.nr')
						 ->where('o.usuario',$data['usuario'])
						 ->where('o.estado','1');
		$query = $this->db->get();
		return $query;
	}

	public function cambiar_rol($empleado, $rol) {
		$roles = $this->obtener_roles_sistema()->result_array();
		$usuario_rol = $this->obtener_usuario_rol_sistema($empleado)->row();

		$this->db->where("id_usuario_rol", $usuario_rol->id_usuario_rol);
		if ($this->db->update('org_usuario_rol',
			array('id_rol' => $rol))) {
			return "exito";
		}else {
			return "fracaso";
		}

		echo $empleado;
	}

	public function obtener_roles_sistema() {
		$this->db->select('d.id_rol, d.nombre_rol')
               ->from('org_sistema a')
               ->join('org_modulo b', 'a.id_sistema = b.id_sistema')
               ->join('org_rol_modulo_permiso c', 'b.id_modulo = c.id_modulo')
               ->join('org_rol d', 'c.id_rol = d.id_rol')
               ->where('a.id_sistema', $this->config->item('id_sistema'))
               ->where('c.estado', '1')
			   ->group_by('d.id_rol')
			   ->order_by('d.id_rol');
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return  $query;
        }
        else {
            return FALSE;
        }
	}

	public function obtener_usuario_rol_sistema($empleado) {
		$this->db->select('d.id_rol, d.nombre_rol, f.nombre_completo, e.id_usuario_rol')
               ->from('org_sistema a')
               ->join('org_modulo b', 'a.id_sistema = b.id_sistema')
               ->join('org_rol_modulo_permiso c', 'b.id_modulo = c.id_modulo')
               ->join('org_rol d', 'c.id_rol = d.id_rol')
               ->join('org_usuario_rol e', 'd.id_rol = e.id_rol')
               ->join('org_usuario f', 'e.id_usuario = f.id_usuario')
               ->join('sir_empleado g', 'f.nr = g.nr')
               ->where('a.id_sistema', $this->config->item('id_sistema'))
               ->where('c.estado', '1')
               ->where('g.id_empleado', $empleado)
			   ->group_by('f.id_usuario')
			   ->order_by('d.id_rol');
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return  $query;
        }
        else {
            return FALSE;
        }
	}
}
