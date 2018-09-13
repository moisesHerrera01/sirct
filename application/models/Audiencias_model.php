<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audiencias_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	public function obtener_audiencias($id) {

			$this->db->select('id_expedienteci,id_fechasaudienciasci,fecha_fechasaudienciasci,hora_fechasaudienciasci')
						 ->from('sct_fechasaudienciasci')
						 ->where('id_expedienteci', $id);
			$query=$this->db->get();
			if ($query->num_rows() > 0) {
					return $query;
			}
			else {
					return FALSE;
			}
	}

	public function obtener_audiencias_delegado($id_delegado) {
			$this->db->select('e.motivo_expedienteci,e.numerocaso_expedienteci,f.id_expedienteci,f.id_fechasaudienciasci,
												 f.fecha_fechasaudienciasci,f.hora_fechasaudienciasci, e.tiposolicitud_expedienteci,
												 CONCAT_WS(" ",em.primer_nombre,em.segundo_nombre,em.primer_apellido,em.segundo_apellido) delegado,CONCAT_WS(" ",p.nombre_personaci,p.apellido_personaci) persona')
						 ->from('sct_fechasaudienciasci f')
						 ->join('sct_expedienteci e','e.id_expedienteci=f.id_expedienteci')
						 ->join('sct_personaci P','p.id_personaci=e.id_personaci')
						 ->join('sir_empleado em','em.id_empleado=e.id_personal');
			if ($id_delegado) {
					$this->db->where('em.nr', $id_delegado);
			}
			$query=$this->db->get();
			if ($query->num_rows() > 0) {
					return $query;
			}
			else {
					return FALSE;
			}
	}


	function insertar_audiencia($data){
		if($this->db->insert('sct_fechasaudienciasci', array(
			'fecha_fechasaudienciasci' => $data['fecha_fechasaudienciasci'],
			'hora_fechasaudienciasci' => $data['hora_fechasaudienciasci'],
			'id_expedienteci' => $data['id_expedienteci']
		))){
			return "exito,".$this->db->insert_id();
		}else{
			return "fracaso";
		}
	}

	public function editar_audiencia($data){
		$this->db->where("id_fechasaudienciasci",$data["id_fechasaudienciasci"]);
		if ($this->db->update('sct_fechasaudienciasci', $data)) {
			return "exito";
		}else {
			return "fracaso";
		}
	}
	public function eliminar_audiencia($data){
		$this->db->delete('sct_fechasaudienciasci', array('id_fechasaudienciasci' => $data['id_fechasaudienciasci']));
	}
}
