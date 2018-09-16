<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitantes_model extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    public function obtener_solicitantes_expediente($expediente) {
        
        $this->db->select('')
                ->from('sct_personaci a')
                ->join('sct_representantepersonaci b', 'a.id_personaci = b.id_personaci', 'left')
                ->where('a.id_expedienteci', $expediente);
        $query=$this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query;
        }
        else {
            return FALSE;
        }

    }

    public function obtener_solicitante($id) {
        
        $this->db->select('')
                ->from('sct_personaci a')
                ->join('sct_representantepersonaci b', 'a.id_personaci = b.id_personaci', 'left')
                ->join('sct_expedienteci c', 'a.id_expedienteci = c.id_expedienteci', 'left')
                ->where('a.id_personaci', $id);
        $query=$this->db->get();
        
        if ($query->num_rows() > 0) {
            return $query;
        }
        else {
            return FALSE;
        }

    }

}

?>