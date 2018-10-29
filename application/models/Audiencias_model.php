<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audiencias_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	public function obtener_audiencias($id, $orden=FALSE, $estado=FALSE) {

			$this->db->select(
												'id_expedienteci,
												 id_fechasaudienciasci,
												 fecha_fechasaudienciasci,
												 hora_fechasaudienciasci,
												 estado_audiencia,
												 numero_fechasaudienciasci,
												 id_representaci,
												 id_defensorlegal,
												 id_delegado'
											  )
						 ->from('sct_fechasaudienciasci')
						 ->where('id_expedienteci', $id)
						 ->order_by('estado_audiencia','desc')
						 ->order_by('id_fechasaudienciasci','asc');
			if ($orden && $estado) {
				$this->db->where('estado_audiencia',$estado)
								 ->where('numero_fechasaudienciasci',$orden);
			}
			$query=$this->db->get();
			if ($query->num_rows() > 0) {
					return $query;
			}
			else {
					return FALSE;
			}
	}

	public function obtener_audiencias_delegado($id_delegado,$fecha=FALSE,$hora=FALSE) {
		$this->db->select('s.nombre_sindicato,e.motivo_expedienteci,e.numerocaso_expedienteci,f.id_expedienteci,f.id_fechasaudienciasci,
												f.fecha_fechasaudienciasci,f.hora_fechasaudienciasci,e.tiposolicitud_expedienteci,
												CONCAT_WS(" ",em.primer_nombre,em.segundo_nombre,em.primer_apellido,em.segundo_apellido) delegado,
												CONCAT_WS(" ",p.nombre_personaci,p.apellido_personaci) persona')
					->from('sct_fechasaudienciasci f')
					->join('sct_expedienteci e','e.id_expedienteci=f.id_expedienteci')
					->join('sct_personaci P','p.id_personaci=e.id_personaci','left')
					->join('sge_sindicato s','s.id_expedientecc=e.id_expedienteci','left')
					->join('sir_empleado em','em.id_empleado=e.id_personal')
					->group_by('f.id_fechasaudienciasci');
		if ($id_delegado) {
			$this->db->where('em.nr', $id_delegado);
		}if ($fecha && $hora) {
			$hora_fin = date('H:i:s',strtotime($hora.'+ 1 hours'));
			$this->db->where('f.fecha_fechasaudienciasci',$fecha)
							 ->where("f.hora_fechasaudienciasci>=",$hora)
							 ->where("f.hora_fechasaudienciasci<=",$hora_fin)
							 ->or_where("f.hora_fechasaudienciasci<=",$hora)
							 ->where("(f.hora_fechasaudienciasci + INTERVAL 1 hour)>=",$hora)
							 ->where('f.fecha_fechasaudienciasci',$fecha);
		}
		$query=$this->db->get();
		if ($query->num_rows() > 0) {
				return $query;
		}
		else {
				return FALSE;
		}
	}

	public function insertar_audiencia($data){
		if ($this->db->insert('sct_fechasaudienciasci', $data)) {
			return $this->db->insert_id();
		}else {
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

	public function obtener_procuradores(){
		$this->db->select('CONCAT_WS(" ",nombre_procuradorci, apellido_procurador) nombre_procurador, dui_procuradorci, acreditacion_procuradorci, id_procuradorci')
						 ->from('sct_procuradorci');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query;
		}else {
			return FALSE;
		}
	}

}
