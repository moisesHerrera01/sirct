<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitantes_model extends CI_Model {

	function __construct(){
		parent::__construct();
    }

    public function obtener_solicitantes_expediente($expediente) {

        $this->db->select('
                    a.id_personaci,
                    a.nombre_personaci,
                    a.apellido_personaci,
                    a.estado_persona,
										a.sexo_personaci,
										a.estudios_personaci,
										(SELECT COUNT(f.id_fechasaudienciasci)
										 FROM sct_fechasaudienciasci f
										 WHERE f.id_expedienteci=a.id_expedienteci
										 AND f.resultado=10)  conciliado,
										 (SELECT COUNT(f.id_fechasaudienciasci)
 										 FROM sct_fechasaudienciasci f
 										 WHERE f.id_expedienteci=a.id_expedienteci
 										 AND f.estado_audiencia=1) activas'
                )
                ->from('sct_personaci a')
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
                    UPPER(CONCAT_WS(" ", a.nombre_personaci, a.apellido_personaci)) nombre_solicitante,
                    TIMESTAMPDIFF( YEAR,a.fnacimiento_personaci,CURDATE() ) AS edad,
                    a.ocupacion,
                    a.direccion_personaci,
                    d.departamento,
                    a.dui_personaci,
										c.municipio
                ')
                ->from('sct_personaci a')
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

        $this->db->select('a.*, c.*, a.ocupacion, a.formapago_personaci, a.funciones_personaci, a.salario_personaci,
				 									 a.horarios_personaci, p.id_municipio_partida, p.id_municipio_menor, p.fecha_partida,
													 p.fnacimiento_menor,
													 p.id_partida,
													 p.numero_partida,
													 p.libro_partida,
													 a.nombre_representante_menor,
													 a.tipo_representante_menor')
                ->from('sct_personaci a')
                ->join('sct_expedienteci c', 'a.id_expedienteci = c.id_expedienteci')
								->join('sct_partida p','p.id_partida = a.id_partida','left')
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
