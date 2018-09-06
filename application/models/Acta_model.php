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
}
