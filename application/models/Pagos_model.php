<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagos_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	public function obtener_pagos($id,$ordenar=FALSE) {

			$this->db->select('id_expedienteci,id_fechaspagosci,fechapago_fechaspagosci,montopago_fechaspagosci,indemnizacion_fechaspagosci')
						 ->from('sct_fechaspagosci')
						 ->where('id_expedienteci', $id);
			if ($ordenar) {
				$this->db->order_by('id_fechaspagosci DESC');
			}
			$query=$this->db->get();
			if ($query->num_rows() > 0) {
					return $query;
			}
			else {
					return FALSE;
			}
	}

	public function obtener_pagos_delegado($id_delegado,$tipo=FALSE) {
	  	$this->db->select('s.nombre_sindicato,e.numerocaso_expedienteci,e.id_expedienteci,f.id_fechaspagosci,f.fechapago_fechaspagosci,f.montopago_fechaspagosci,
			e.tiposolicitud_expedienteci,CONCAT_WS(" ",em.primer_nombre,em.segundo_nombre,em.primer_apellido,em.segundo_apellido) nombre_completo,
			CONCAT_WS(" ",p.nombre_personaci,p.apellido_personaci) persona')
				->from('sct_fechaspagosci f')
				->join('sct_expedienteci e','e.id_expedienteci=f.id_expedienteci')
				->join('sir_empleado em','em.id_empleado=e.id_personal')
				->join('sct_personaci p','p.id_personaci=e.id_personaci','left')
				->join('sge_sindicato s','s.id_expedientecc=e.id_expedienteci','left')
				->group_by('f.id_fechaspagosci');
				if ($id_delegado) {
				$this->db->where('em.nr', $id_delegado);
				}
				if ($tipo==1) {
					$this->db->where('e.id_personaci<>0');
				}else {
					$this->db->where('e.id_personaci=0');
				}

		$sql[] = '('.$this->db->get_compiled_select().')';

		$this->db->select('p.apellido_personaci nombre_sindicato,e.numerocaso_expedienteci,e.id_expedienteci,f.id_fechaspagosci,f.fechapago_fechaspagosci,f.montopago_fechaspagosci,
			e.tiposolicitud_expedienteci,CONCAT_WS(" ",em.primer_nombre,em.segundo_nombre,em.primer_apellido,em.segundo_apellido) nombre_completo,
			CONCAT_WS(" ",p.nombre_personaci,p.apellido_personaci) persona')
				->from('sct_fechaspagosci f')
				->join('sct_personaci p', 'p.id_personaci = f.id_persona')
				->join('sct_expedienteci` e ', ' e.id_expedienteci = p.id_expedienteci')
				->join('sir_empleado em', 'em.id_empleado = e.id_personal')
				->group_by('f.id_fechaspagosci');
		if ($id_delegado) {
			$this->db->where('em.nr', $id_delegado);
		}

		$sql[] = '('.$this->db->get_compiled_select().')';

		$sql = implode(' UNION ', $sql);

		$query=$this->db->query($sql);
		if ($query->num_rows() > 0) {
				return $query;
		}
		else {
				return FALSE;
		}
	}

	public function insertar_pago($data){
		if ($this->db->insert('sct_fechaspagosci', $data)) {
			return $this->db->insert_id();
		}else {
			return "fracaso";
		}
	}

	public function editar_pago($data){
		$this->db->where("id_fechaspagosci",$data["id_fechaspagosci"]);
		if ($this->db->update('sct_fechaspagosci', $data)) {
			return "exito";
		}else {
			return "fracaso";
		}
	}

	public function eliminar_pago($data){
		$this->db->delete('sct_fechaspagosci', array('id_fechaspagosci' => $data['id_fechaspagosci']));
	}

	public function obtener_pagos_persona($id) {

		$this->db->select('id_persona,id_fechaspagosci,fechapago_fechaspagosci,montopago_fechaspagosci')
					 ->from('sct_fechaspagosci')
					 ->where('id_persona', $id);
		$query=$this->db->get();

		if ($query->num_rows() > 0) {
				return $query;
		} else {
				return FALSE;
		}
	}
}
