<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud_juridica_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function mostrar_solicitud(){
		$query = $this->db->get("sct_personaci");
		if($query->num_rows() > 0) return $query;
		else return false;
	}

	function insertar_solicitado($data){
		if($this->db->insert('sct_personaci', array(
			'nombre_personaci' => $data['nombre_personaci'],
			'apellido_personaci' => $data['apellido_personaci'],
			'telefono_personaci' => $data['telefono_personaci'],
			'id_municipio' => $data['id_municipio'],
			'direccion_personaci' => $data['direccion_personaci'],
			'sexo_personaci' => $data['sexo_personaci'],
			'salario_personaci' => $data['salario_personaci'],
			'horarios_personaci' => $data['horarios_personaci'],
			'id_catalogociuo' => $data['id_catalogociuo'],
			'discapacidad_personaci' => $data['discapacidad_personaci']
		))){
			return "exito,".$this->db->insert_id();
		}else{
			return "fracaso";
		}
	}

	function editar_solicitud($data){
		$this->db->where("id_personaci",$data["id_personaci"]);
		if($this->db->update('sct_personaci', array(
			'nombre_personaci' => $data['nombre_personaci'],
			'apellido_personaci' => $data['apellido_personaci'],
			'telefono_personaci' => $data['telefono_personaci'],
			'id_municipio' => $data['id_municipio'],
			'direccion_personaci' => $data['direccion_personaci'],
			'sexo_personaci' => $data['sexo_personaci'],
			'salario_personaci' => $data['salario_personaci'],
			'horarios_personaci' => $data['horarios_personaci'],
			'id_catalogociuo' => $data['id_catalogociuo'],
			'discapacidad_personaci' => $data['discapacidad_personaci']
		))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function eliminar_solicitud($data){
  		if($this->db->delete("sct_personaci",array('id_personaci' => $data['id_personaci']))){
  			return "exito";
  		}else{
  			return "fracaso";
  		}
	}

	function insertar_expediente($data){
		if($this->db->insert('sct_expedienteci', array(
			'id_empresaci' => $data['id_empresaci'],
			'id_personal' => $data['id_personal'],
			'id_personaci' => $data['id_personaci'],
			'motivo_expedienteci' => $data['motivo_expedienteci'],
			'descripmotivo_expedienteci' => $data['descripmotivo_expedienteci']
		))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function editar_expediente($data){
		$this->db->where("id_expedienteci",$data["id_expedienteci"]);
		if($this->db->update('sct_expedienteci', array(
			'nombre_personaci' => $data['nombre_personaci'],
			'apellido_personaci' => $data['apellido_personaci'],
			'telefono_personaci' => $data['telefono_personaci'],
			'id_municipio' => $data['id_municipio'],
			'direccion_personaci' => $data['direccion_personaci'],
			'sexo_personaci' => $data['sexo_personaci'],
			'salario_personaci' => $data['salario_personaci'],
			'horarios_personaci' => $data['horarios_personaci'],
			'id_catalogociuo' => $data['id_catalogociuo'],
			'discapacidad_personaci' => $data['discapacidad_personaci']
		))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function eliminar_expediente($data){
  		if($this->db->delete("sct_expedienteci",array('id_expedienteci' => $data['id_expedienteci']))){
  			return "exito";
  		}else{
  			return "fracaso";
  		}
	}

	function insertar_establecimiento($data){
		if($this->db->insert('sge_empresa', array(
			'nombre_empresa' => $data['nombre_empresa'],
			'telefono_empresa' => $data['telefono_empresa'],
			'id_catalogociiu' => $data['id_catalogociiu'],
			'id_municipio' => $data['id_municipio'],
			'direccion_empresa' => $data['direccion_empresa'],
		))){
			return "exito,".$this->db->insert_id();
		}else{
			return "fracaso";
		}
	}

	function insertar_representante($data){
		if($this->db->insert('sge_representante', array(
			'id_empresa' => $data['id_empresa'], 
			'nombres_representante' => $data['nombres_representante'],
			'dui_representante' => $data['dui_representante'],
			'acreditacion_representante' => $data['acreditacion_representante'],
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
			'dui_representante' => $data['dui_representante'],
			'acreditacion_representante' => $data['acreditacion_representante'],
			'tipo_representante' => $data['tipo_representante']
		))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function eliminar_representante($data){
  		$this->db->where("id_representante",$data["id_representante"]);
		if($this->db->update('sge_representante', array(
			'estado_representante' => $data['estado_representante']
		))){
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
