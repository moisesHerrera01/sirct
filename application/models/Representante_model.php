<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Representante_model extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    public function insertar_representante($data) {
        
        if(
            $this->db->insert(
                'sge_representante', 
                array(
                    'nombres_representante' => $data['nombres_representante'], 
                    'id_empresa' => $data['id_empresa']
                )
            )) {
            return $this->db->insert_id();
        } else{
            return "fracaso";
        }

    }
    
}

?>