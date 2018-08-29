<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitudes_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function insertar_establecimiento($data){
		if($this->db->insert('sge_empresa', array(
			'tiposolicitud_empresa' => $data['tiposolicitud_empresa'],
			'id_oficina' => $data['id_oficina'],
			'nombre_empresa' => $data['nombre_empresa'],
			'numtotal_empresa' => $data['numtotal_empresa'],
			'id_catalogociiu' => $data['id_catalogociiu'],
			'nit_empresa' => $data['nit_empresa'],
			'id_municipio' => $data['id_municipio'],
			'correoelectronico_empresa' => $data['correoelectronico_empresa'],
			'direccion_empresa' => $data['direccion_empresa'],
			'activobalance_empresa' => $data['activobalance_empresa'],
			'capitalsocial_empresa' => $data['capitalsocial_empresa'],
			'trabajadores_adomicilio_empresa' => $data['trabajadores_adomicilio_empresa'],
			'tipo_empresa' => $data['tipo_empresa'],
			'estado_empresa' => $data['estado_empresa']
		))){
			return "exito,".$this->db->insert_id();
		}else{
			return "fracaso";
		}
	}

	function mostrar_actividad(){
		$query = $this->db->get("vyp_actividades");
		if($query->num_rows() > 0) return $query;
		else return false;
	}

	function editar_establecimiento($data){
		$this->db->where("id_empresa",$data["id_empresa"]);
		if($this->db->update('sge_empresa', array(
			'tiposolicitud_empresa' => $data['tiposolicitud_empresa'],
			'id_oficina' => $data['id_oficina'],
			'nombre_empresa' => $data['nombre_empresa'],
			'numtotal_empresa' => $data['numtotal_empresa'],
			'id_catalogociiu' => $data['id_catalogociiu'],
			'nit_empresa' => $data['nit_empresa'],
			'id_municipio' => $data['id_municipio'],
			'correoelectronico_empresa' => $data['correoelectronico_empresa'],
			'direccion_empresa' => $data['direccion_empresa'],
			'activobalance_empresa' => $data['activobalance_empresa'],
			'capitalsocial_empresa' => $data['capitalsocial_empresa'],
			'trabajadores_adomicilio_empresa' => $data['trabajadores_adomicilio_empresa'],
			'tipo_empresa' => $data['tipo_empresa'],
			'estado_empresa' => $data['estado_empresa']
		))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function eliminar_actividad($data){
  		if($this->db->delete("sge_empresa",array('id_empresa' => $data['id_empresa']))){
  			return "exito";
  		}else{
  			return "fracaso";
  		}
	}

	function insertar_representante($data){
		if($this->db->insert('sge_representante', array(
			'id_empresa' => $data['id_empresa'],
			'nombres_representante' => $data['nombres_representante'],
			'alias_representante' => $data['alias_representante'],
			'tipo_representante' => $data['tipo_representante']
		))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function editar_representante($data){
		$this->db->where("id_representante",$data["id_representante"]);
		if($this->db->update('sge_representante', array(
			'id_empresa' => $data['id_empresa'],
			'nombres_representante' => $data['nombres_representante'],
			'alias_representante' => $data['alias_representante'],
			'tipo_representante' => $data['tipo_representante']
		))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function eliminar_representante($data){
  		if($this->db->delete("sge_representante",array('id_representante' => $data['id_representante']))){
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
