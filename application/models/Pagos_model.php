<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagos_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	public function obtener_pagos($id) {

			$this->db->select('id_expedienteci,id_fechaspagosci,fechapago_fechaspagosci,montopago_fechaspagosci')
						 ->from('sct_fechaspagosci')
						 ->where('id_expedienteci', $id);
			$query=$this->db->get();
			if ($query->num_rows() > 0) {
					return $query;
			}
			else {
					return FALSE;
			}
	}

	public function obtener_pagos_delegado($id_delegado) {

	  $this->db->select('e.numerocaso_expedienteci,e.id_expedienteci,f.id_fechaspagosci,f.fechapago_fechaspagosci,f.montopago_fechaspagosci,
		e.tiposolicitud_expedienteci,CONCAT_WS(" ",em.primer_nombre,em.segundo_nombre,em.primer_apellido,em.segundo_apellido) nombre_completo,
		CONCAT_WS(" ",p.nombre_personaci,p.apellido_personaci) persona')
						 ->from('sct_fechaspagosci f')
						 ->join('sct_expedienteci e','e.id_expedienteci=f.id_expedienteci')
						 ->join('sir_empleado em','em.id_empleado=e.id_personal')
						 ->join('sct_personaci P','p.id_personaci=e.id_personaci');
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

	function insertar_pago($data){
		if($this->db->insert('sct_fechaspagosci', array(
			'fechapago_fechaspagosci' => $data['fechapago_fechaspagosci'],
			'montopago_fechaspagosci' => $data['montopago_fechaspagosci'],
			'id_expedienteci' => $data['id_expedienteci']
		))){
			return "exito,".$this->db->insert_id();
		}else{
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
}
