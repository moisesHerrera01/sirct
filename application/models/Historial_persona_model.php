<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Historial_persona_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function insertar_solicitud($data){
		if($this->db->insert('sct_personaci', array(
			'nombre_personaci' => $data['nombre_personaci'],
			'apellido_personaci' => $data['apellido_personaci'],
			'conocido_por' => $data['conocido_por'],
			'dui_personaci' => $data['dui_personaci'],
			'id_doc_identidad' => $data['id_doc_identidad'],
			'telefono_personaci' => $data['telefono_personaci'],
			'telefono2_personaci' => $data['telefono2_personaci'],
			'id_municipio' => $data['id_municipio'],
			'direccion_personaci' => $data['direccion_personaci'],
			'fnacimiento_personaci' => $data['fnacimiento_personaci'],
			'sexo_personaci' => $data['sexo_personaci'],
			'estudios_personaci' => $data['estudios_personaci'],
			'nacionalidad_personaci' => $data['nacionalidad_personaci'],
			'discapacidad_personaci' => $data['discapacidad_personaci'],
			'posee_representante' => $data['posee_representante'],
			'pertenece_lgbt' => $data['pertenece_lgbt'],
			'tipopeticion_personaci' => 0
		))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function mostrar_solicitud(){
		$query = $this->db->get("sct_personaci");
		if($query->num_rows() > 0) return $query;
		else return false;
	}

	public function editar_solicitud($data){
		$this->db->where("id_personaci",$data["id_personaci"]);
		if ($this->db->update('sct_personaci', $data)) {
			return "exito";
		}else {
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

	public function obtener_personaci_complete($id) {
		$this->db->select('')
             ->from('sct_personaci p')
             ->join('sct_doc_identidad di', 'di.id_doc_identidad = p.id_doc_identidad')
             ->join('org_municipio m','m.id_municipio=p.id_municipio')
             ->join('sct_nacionalidad n','n.id_nacionalidad=p.nacionalidad_personaci')
             ->where('p.id_personaci',$id);
        $query=$this->db->get();
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_persona($id) {
		$this->db->select('')->from('sct_personaci')->where('id_personaci', $id);
		$query=$this->db->get();
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
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

	function obtener_nacionalidades(){
		$this->db->select('n.id_nacionalidad,n.nacionalidad')
						 ->from('sct_nacionalidad n');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query;
		}else {
			return FALSE;
		}
	}

	function obtener_tipo_documentos(){
		$this->db->select('t.id_doc_identidad ,t.doc_identidad')
						 ->from('sct_doc_identidad t');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query;
		}else {
			return FALSE;
		}
	}

	function obtener_discapacidades(){
		$this->db->select('d.id_discapacidad ,d.discapacidad')
						 ->from('sct_discapacidad d');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query;
		}else {
			return FALSE;
		}
	}
}
