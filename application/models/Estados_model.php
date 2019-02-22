<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estados_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function insertar_estado($data){
		$id = $this->obtener_ultimo_id("sct_estadosci","id_estadosci");
		if($this->db->insert('sct_estadosci', array('id_estadosci' => $id, 'descripcion_estadosci' => $data['descripcion_estadosci'], 'nombre_estadosci'=>$data['nombre_estadosci']))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function mostrar_estado(){
		$query = $this->db->get("sct_estadosci");
		if($query->num_rows() > 0) return $query;
		else return false;
	}

	function editar_estado($data){
		$this->db->where("id_estadosci",$data["id_estadosci"]);
		if($this->db->update('sct_estadosci', array('descripcion_estadosci' => $data['descripcion_estadosci'], 'nombre_estadosci'=>$data['nombre_estadosci']))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function eliminar_estado($data){
		$this->db->where("id_estadosci",$data["id_estadosci"]);
		if($this->db->update('sct_estadosci', array('estado' => $data['estado']))){
			return "exito";
		}else{
			return "fracaso";
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
