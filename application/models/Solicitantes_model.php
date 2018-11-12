<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitantes_model extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    public function obtener_solicitantes_expediente($expediente) {

        $this->db->select('
                    a.id_personaci,
                    b.tipo_representantepersonaci,
                    a.nombre_personaci,
                    a.apellido_personaci,
                    a.estado_persona,
                    b.nombre_representantepersonaci,
                    b.apellido_representantepersonaci,
                    c.estado_audiencia'
                )
                ->from('sct_personaci a')
                ->join('sct_representantepersonaci b', 'a.id_personaci = b.id_personaci', 'left')
                ->join('sct_fechasaudienciasci c', 'c.id_expedienteci = a.id_expedienteci', 'left')
                ->where('a.id_expedienteci', $expediente)
                ->group_by('a.id_personaci');
        $query=$this->db->get();

        if ($query->num_rows() > 0) {
            return $query;
        }
        else {
            return FALSE;
        }

    }

    public function obtener_solicitantes_expediente_acta($expediente) {

        $this->db->select('
                    CONCAT_WS(" ", a.nombre_personaci, a.apellido_personaci) nombre_solicitante,
                    TIMESTAMPDIFF( YEAR,a.fnacimiento_personaci,CURDATE() ) AS edad,
                    b.primarios_catalogociuo,
                    a.direccion_personaci,
                    d.departamento,
                    a.dui_personaci
                ')
                ->from('sct_personaci a')
                ->join('sge_catalogociuo b', 'a.id_catalogociuo = b.id_catalogociuo')
                ->join('org_municipio c', 'c.id_municipio = a.id_municipio')
                ->join('org_departamento d', 'd.id_departamento = c.id_departamento_pais')
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
