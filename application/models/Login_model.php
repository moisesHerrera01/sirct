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
}
