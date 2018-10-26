<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Establecimiento_model extends CI_Model {

	function __construct(){
		parent::__construct();
    }

		function insertar_establecimiento($data){
			if($this->db->insert('sge_empresa', $data)){
				return "exito,".$this->db->insert_id();
			}else{
				return "fracaso";
			}
		}


		public function obtener_representantes($id) {
			$this->db->select('')
				->from('sge_representante r')
				->where('r.id_empresa', $id);
			$query=$this->db->get();
			if ($query->num_rows() > 0) {
				return  $query;
			}else{
				return FALSE;
			}
		}
		
}

?>
