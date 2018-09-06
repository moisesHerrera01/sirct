<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acta_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	public function insertar_acta($data) {

		if(
            $this->db->insert('sct_actasci',$data
        )) {
            return "exito";
        } else{
            return "fracaso";
        }
    }
    
    public function obtener_acta($id) {
        
        $this->db->select('')
               ->from('sct_actasci')
               ->where('id_actasci', $id);
        $query=$this->db->get();
        if ($query->num_rows() > 0) {
            return $query;
        }
        else {
            return FALSE;
        }

    }

    public function eliminar_estado($data){
        if($this->db->delete(
            "sct_actasci",
            $data
        )){
            return "exito";
        }else{
            return "fracaso";
        }
    }

}
