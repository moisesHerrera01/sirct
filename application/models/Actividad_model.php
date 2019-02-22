<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Actividad_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function insertar_actividad($data){
		$name = $data['nombre_vyp_actividades'];
		$query = $this->db->query("select * from vyp_actividades where nombre_vyp_actividades = trim('$name')");
		if($query->num_rows() <= 0){
				if($this->db->insert('vyp_actividades', array('nombre_vyp_actividades' => $data['nombre_vyp_actividades'], 'depende_vyp_actividades' => $data['depende_vyp_actividades']))){
					return "exito";
				}else{
					return "fracaso";
				}
		}else{
			return "duplicado";
		}
	}

	function mostrar_actividad(){
		$query = $this->db->get("vyp_actividades");
		if($query->num_rows() > 0) return $query;
		else return false;
	}

	function editar_actividad($data){
		$this->db->where("id_vyp_actividades",$data["id_vyp_actividades"]);
		if($this->db->update('vyp_actividades', array('nombre_vyp_actividades' => $data['nombre_vyp_actividades'], 'depende_vyp_actividades' => $data['depende_vyp_actividades']))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function eliminar_actividad($data){
    $id = $data['id_vyp_actividades'];
    $datos=$this->db->query("select * from vyp_actividades where depende_vyp_actividades='$id'");
    if($datos->num_rows()==0){
  		if($this->db->delete("vyp_actividades",array('id_vyp_actividades' => $data['id_vyp_actividades']))){
  			return "exito";
  		}else{
  			return "fracaso";
  		}
    }else{
      return "depende";
    }
	}

	function obtener_ultimo_id($tabla,$nombreid){
		$this->db->order_by($nombreid, "asc");
		$query = $this->db->get($tabla);
		$ultimoid = 0;
		if($query->num_rows() > 0){
			foreach ($query->result() as $fila) {
				$ultimoid = $fila->$nombreid;
			}
			$ultimoid++;
		}else{
			$ultimoid = 1;
		}
		return $ultimoid;
	}
}
