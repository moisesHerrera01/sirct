<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}

	public function obtener_estadistica_clase_asociacion(){
		$query=$this->db->query("
			SELECT 'Persona Natural con Rep.' AS nombre, COUNT(*) AS cantidad FROM sct_expedienteci AS a WHERE a.tiposolicitud_expedienteci = 'Conciliación' AND (SELECT count(*) FROM sct_personaci WHERE id_personaci = a.id_personaci AND posee_representante = 1) > 0 UNION 
			SELECT 'Persona Natural sin Rep.' AS nombre, COUNT(*) AS cantidad FROM sct_expedienteci AS a WHERE a.tiposolicitud_expedienteci = 'Conciliación' AND (SELECT count(*) FROM sct_personaci WHERE id_personaci = a.id_personaci AND posee_representante = 0) > 0 UNION 
			SELECT 'Persona Jurídica' AS nombre, COUNT(*) AS cantidad FROM sct_expedienteci AS a WHERE a.tiposolicitud_expedienteci = 'conciliacion juridica' UNION 
			SELECT 'Renuncia Voluntaria' AS nombre, COUNT(*) AS cantidad FROM sct_expedienteci AS a WHERE a.tiposolicitud_expedienteci = 'Renuncia Voluntaria' UNION 
			SELECT 'Indemnización y Prestaciones Laborales' AS nombre, COUNT(*) AS cantidad FROM sct_expedienteci AS a WHERE a.tiposolicitud_expedienteci = 'Indemnización y Prestaciones Laborales' UNION
			SELECT 'Diferencia Laboral' AS nombre, COUNT(*) AS cantidad FROM sct_expedienteci AS a WHERE a.tiposolicitud_expedienteci = 'Diferencia Laboral'
			");
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_estadistica_tipo_asociacion(){
		$query=$this->db->query('SELECT ta.NOMBRE_TIPO_ASOCIACION AS nombre, (SELECT COUNT(*) FROM sap_asociacion AS a WHERE a.ID_TIPO_ASOCIACION = ta.ID_TIPO_ASOCIACION) AS cantidad FROM sap_tipo_asociacion AS ta');
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_estadistica_estado_asociacion(){
		$query=$this->db->query('SELECT ea.NOMBRE_ESTADO_ASOCIACION AS nombre, (SELECT COUNT(*) FROM sap_asociacion AS a WHERE a.ESTADO_ASOCIACION = ea.ID_ESTADO_ASOCIACION) AS cantidad FROM sap_estado_asociacion AS ea');
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

	public function obtener_estadistica_sector_asociacion(){
		$query=$this->db->query('SELECT sa.NOMBRE_SECTOR_ASOCIACION AS nombre, (SELECT COUNT(*) FROM sap_asociacion AS a WHERE a.ID_SECTOR_ASOCIACION = sa.ID_SECTOR_ASOCIACION) AS cantidad FROM sap_sector_asociacion AS sa');
		if ($query->num_rows() > 0) { return $query;
		}else{ return FALSE; }
	}

}