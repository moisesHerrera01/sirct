<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audiencias_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

		public function obtener_audiencias($id, $orden=FALSE, $estado=FALSE) {

			$this->db->select(
												'f.id_expedienteci,
												 f.id_fechasaudienciasci,
												 f.fecha_fechasaudienciasci,
												 f.hora_fechasaudienciasci,
												 f.estado_audiencia,
												 f.numero_fechasaudienciasci,
												 f.id_representaci,
												 f.id_defensorlegal,
												 f.id_delegado,
												 f.tipo_pago,
												 f.asistieron,
												 f.resultado,
												 (select count(*) from sct_fechasaudienciasci fe where fe.id_expedienteci=f.id_expedienteci) AS cuenta,
												 (select e.id_estadosci from sct_expedienteci e where e.id_expedienteci=f.id_expedienteci) AS estado'
											  )
						 ->from('sct_fechasaudienciasci f')
						 ->where('f.id_expedienteci', $id)
						 ->order_by('f.estado_audiencia','desc')
						 ->order_by('f.id_fechasaudienciasci','asc');
			if ($orden && $estado) {
				$this->db->where('f.estado_audiencia',$estado)
								 ->where('f.numero_fechasaudienciasci',$orden);
			}
			$query=$this->db->get();
			if ($query->num_rows() > 0) {
					return $query;
			}
			else {
					return FALSE;
			}
	}

	public function obtener_audiencias_delegado($id_delegado,$fecha=FALSE,$hora=FALSE,$tipo=FALSE,$fecha=FALSE) {
		$this->db->select('s.nombre_sindicato,e.motivo_expedienteci,e.numerocaso_expedienteci,f.id_expedienteci,f.id_fechasaudienciasci,
												f.fecha_fechasaudienciasci,f.hora_fechasaudienciasci,tiposolicitud_expedienteci,
												(CASE WHEN e.tiposolicitud_expedienteci=1 THEN "Persona natural"
													WHEN e.tiposolicitud_expedienteci=2 THEN "Retiro voluntario"
													WHEN e.tiposolicitud_expedienteci=3 THEN "Persona jurídica"
													WHEN e.tiposolicitud_expedienteci=4 THEN "Diferencias laborales"
													WHEN e.tiposolicitud_expedienteci=5 THEN "Indemnización y Prestaciones Laborales"
													ELSE e.tiposolicitud_expedienteci END) AS tipo,
												CONCAT_WS(" ",em.primer_nombre,em.segundo_nombre,em.primer_apellido,em.segundo_apellido) delegado,
												CONCAT_WS(" ",p.nombre_personaci,p.apellido_personaci) persona,
												d.nombre_delegado_actual')
					->from('sct_fechasaudienciasci f')
					->join('sct_expedienteci e','e.id_expedienteci=f.id_expedienteci')
					->join('sct_personaci P','p.id_personaci=e.id_personaci','left')
					->join('sge_sindicato s','s.id_expedientecc=e.id_expedienteci','left')
					->join('sir_empleado em','em.id_empleado=e.id_personal')
					->join("(
								SELECT de.id_expedienteci,de.id_personal delegado_actual,emp.nr nr_delegado_actual,
								CONCAT_WS(' ',emp.primer_nombre,emp.segundo_nombre,emp.tercer_nombre,emp.primer_apellido,emp.segundo_apellido,emp.apellido_casada) nombre_delegado_actual
								FROM sct_delegado_exp de
								JOIN sir_empleado emp ON emp.id_empleado=de.id_personal
								WHERE de.id_delegado_exp = (SELECT MAX(de2.id_delegado_exp)
																						FROM sct_delegado_exp de2
																						WHERE de2.id_expedienteci=de.id_expedienteci
																					 )
							) d" , "d.id_expedienteci=e.id_expedienteci")
					->group_by('f.id_fechasaudienciasci');
		if ($id_delegado) {
			$this->db->where('d.nr_delegado_actual', $id_delegado);
		}
		if ($fecha) {
			$this->db->where('f.fecha_fechasaudienciasci', $fecha);
		}
		if ($tipo==1) {
			$this->db->where('e.id_personaci<>0');
		}else {
			$this->db->where('e.id_personaci=0');
		}
		if ($fecha && $hora) {
			$hora_fin = date('H:i:s',strtotime($hora.'+ 1 hours'));
			$this->db->where('f.fecha_fechasaudienciasci',$fecha)
							 ->where("f.hora_fechasaudienciasci>=",$hora)
							 ->where("f.hora_fechasaudienciasci<=",$hora_fin)
							 ->or_where("f.hora_fechasaudienciasci<=",$hora)
							 ->where("(f.hora_fechasaudienciasci + INTERVAL 1 hour)>=",$hora)
							 ->where('f.fecha_fechasaudienciasci',$fecha);
		}
		$query=$this->db->get();
		if ($query->num_rows() > 0) {
				return $query;
		}
		else {
				return FALSE;
		}
	}

	public function insertar_audiencia($data){
		if ($this->db->insert('sct_fechasaudienciasci', $data)) {
			return $this->db->insert_id();
		}else {
			return "fracaso";
		}
	}

	public function editar_audiencia($data){
		$this->db->where("id_fechasaudienciasci",$data["id_fechasaudienciasci"]);
		if ($this->db->update('sct_fechasaudienciasci', $data)) {
			return "exito";
		}else {
			return "fracaso";
		}
	}
	public function eliminar_audiencia($data){
		$this->db->delete('sct_fechasaudienciasci', array('id_fechasaudienciasci' => $data['id_fechasaudienciasci']));
	}

	public function obtener_procuradores(){
		$this->db->select('CONCAT_WS(" ",nombre_procuradorci, apellido_procurador) nombre_procurador, dui_procuradorci, acreditacion_procuradorci, id_procuradorci')
						 ->from('sct_procuradorci');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query;
		}else {
			return FALSE;
		}
	}

}
