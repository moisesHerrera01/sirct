<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audiencias_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	public function obtener_audiencias($id) {

			$this->db->select('fecha_fechasaudienciasci,hora_fechasaudienciasci')
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
}
