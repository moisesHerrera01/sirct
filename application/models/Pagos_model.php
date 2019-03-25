<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagos_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	public function obtener_pagos($id,$ordenar=FALSE) {

			$this->db->select('id_expedienteci,id_fechaspagosci,fechapago_fechaspagosci,montopago_fechaspagosci,indemnizacion_fechaspagosci')
						 ->from('sct_fechaspagosci')
						 ->where('id_expedienteci', $id);
			if ($ordenar) {
				$this->db->order_by('id_fechaspagosci DESC');
			}
			$query=$this->db->get();
			if ($query->num_rows() > 0) {
					return $query;
			}
			else {
					return FALSE;
			}
	}

	public function obtener_pagos_delegado($id_delegado,$tipo=FALSE,$fecha=FALSE) {
	  	$this->db->select('s.nombre_sindicato,e.numerocaso_expedienteci,e.id_expedienteci,f.id_fechaspagosci,f.fechapago_fechaspagosci,f.montopago_fechaspagosci,
			e.tiposolicitud_expedienteci,
			ms.nombre_motivo tipo,
			CONCAT_WS(" ",em.primer_nombre,em.segundo_nombre,em.primer_apellido,em.segundo_apellido) nombre_completo,
			CONCAT_WS(" ",p.nombre_personaci,p.apellido_personaci) persona')
				->from('sct_fechaspagosci f')
				->join('sct_expedienteci e','e.id_expedienteci=f.id_expedienteci')
				// ->join('sir_empleado em','em.id_empleado=e.id_personal')
				->join('sct_personaci p','p.id_personaci=e.id_personaci','left')
				->join('sge_sindicato s','s.id_expedientecc=e.id_expedienteci','left')
				->join('sct_motivo_solicitud ms','ms.id_motivo_solicitud=e.causa_expedienteci','left')
				->join("(
							SELECT de.id_expedienteci,de.id_personal delegado_actual,emp.nr nr_delegado_actual,
							CONCAT_WS(' ',emp.primer_nombre,emp.segundo_nombre,emp.tercer_nombre,emp.primer_apellido,emp.segundo_apellido,emp.apellido_casada) nombre_delegado_actual
							FROM sct_delegado_exp de
							JOIN sir_empleado emp ON emp.id_empleado=de.id_personal
							WHERE de.id_delegado_exp = (SELECT MAX(de2.id_delegado_exp)
																					FROM sct_delegado_exp de2
																					WHERE de2.id_expedienteci=de.id_expedienteci
																					AND de2.id_personal <> 0
																				 )
						) d" , "d.id_expedienteci=e.id_expedienteci")
				->join('sir_empleado em','em.id_empleado=d.delegado_actual')
				->group_by('f.id_fechaspagosci');
				if ($id_delegado) {
				$this->db->where('em.nr', $id_delegado);
				}
				if ($fecha) {
				$this->db->where("DATE_FORMAT(f.fechapago_fechaspagosci, '%Y-%m-%d') = ", $fecha);
				}
				if ($tipo==1) {
					$this->db->where('e.tiposolicitud_expedienteci<4');
				}else {
					$this->db->where('e.tiposolicitud_expedienteci>3');
				}

		$sql[] = '('.$this->db->get_compiled_select().')';

		$this->db->select('p.apellido_personaci nombre_sindicato,e.numerocaso_expedienteci,e.id_expedienteci,f.id_fechaspagosci,f.fechapago_fechaspagosci,f.montopago_fechaspagosci,
			e.tiposolicitud_expedienteci,
			ms.nombre_motivo tipo,
			CONCAT_WS(" ",em.primer_nombre,em.segundo_nombre,em.primer_apellido,em.segundo_apellido) nombre_completo,
			CONCAT_WS(" ",p.nombre_personaci,p.apellido_personaci) persona')
				->from('sct_fechaspagosci f')
				->join('sct_personaci p', 'p.id_personaci = f.id_persona')
				->join('sct_expedienteci` e ', ' e.id_expedienteci = p.id_expedienteci')
				// ->join('sir_empleado em', 'em.id_empleado = e.id_personal')
				->join('sct_motivo_solicitud ms','ms.id_motivo_solicitud=e.causa_expedienteci','left')
				->join("(
							SELECT de.id_expedienteci,de.id_personal delegado_actual,emp.nr nr_delegado_actual,
							CONCAT_WS(' ',emp.primer_nombre,emp.segundo_nombre,emp.tercer_nombre,emp.primer_apellido,emp.segundo_apellido,emp.apellido_casada) nombre_delegado_actual
							FROM sct_delegado_exp de
							JOIN sir_empleado emp ON emp.id_empleado=de.id_personal
							WHERE de.id_delegado_exp = (SELECT MAX(de2.id_delegado_exp)
																					FROM sct_delegado_exp de2
																					WHERE de2.id_expedienteci=de.id_expedienteci
																					AND de2.id_personal <> 0
																				 )
						) d" , "d.id_expedienteci=e.id_expedienteci")
				->join('sir_empleado em','em.id_empleado=d.delegado_actual')
				->group_by('f.id_fechaspagosci');
		if ($id_delegado) {
			$this->db->where('em.nr', $id_delegado);
		}
		if ($tipo==1) {
			$this->db->where('e.tiposolicitud_expedienteci<4');
		}else {
			$this->db->where('e.tiposolicitud_expedienteci>3');
		}
		if ($fecha) {
			$this->db->where("DATE_FORMAT(f.fechapago_fechaspagosci, '%Y-%m-%d') = ", $fecha);
		}

		$sql[] = '('.$this->db->get_compiled_select().')';

		$sql = implode(' UNION ', $sql);



		$query=$this->db->query($sql);
		if ($query->num_rows() > 0) {
				return $query;
		}
		else {
				return FALSE;
		}
	}

	public function insertar_pago($data){
		if ($this->db->insert('sct_fechaspagosci', $data)) {
			return $this->db->insert_id();
		}else {
			return "fracaso";
		}
	}

	public function editar_pago($data){
		$this->db->where("id_fechaspagosci",$data["id_fechaspagosci"]);
		if ($this->db->update('sct_fechaspagosci', $data)) {
			return "exito";
		}else {
			return "fracaso";
		}
	}

	public function eliminar_pago($data){
		$this->db->delete('sct_fechaspagosci', array('id_fechaspagosci' => $data['id_fechaspagosci']));
	}

	public function obtener_pagos_persona($id) {

		$this->db->select('id_persona,id_fechaspagosci,fechapago_fechaspagosci,montopago_fechaspagosci')
					 ->from('sct_fechaspagosci')
					 ->where('id_persona', $id);
		$query=$this->db->get();

		if ($query->num_rows() > 0) {
				return $query;
		} else {
				return FALSE;
		}
	}

	public function obtener_pagos_personas($id_expedienteci,$numero){
		$this->db->select('UPPER(CONCAT_WS(" ",p.nombre_personaci,p.apellido_personaci)) trabajador,
											 f.montopago_fechaspagosci
											')
						 ->from('sct_fechaspagosci f')
						 ->join('sct_personaci p','p.id_personaci=f.id_persona')
						 ->join('sct_expedienteci e','e.id_expedienteci=p.id_expedienteci')
						 ->join('sir_empleado em','em.id_empleado=e.id_personal')
						 ->where('e.id_expedienteci',$id_expedienteci)
						 ->where('f.numero_pago',$numero)
						 ->group_by('f.id_fechaspagosci');
		$query = $this->db->get();
		return $query;
	}

	public function obtener_numero_pagos($id_personaci){
		$this->db->select('COUNT(f.id_fechaspagosci) cantidad')
						 ->from('sct_fechaspagosci f')
						 ->join('sct_personaci p','p.id_personaci=f.id_persona')
						 ->where('f.id_persona',$id_personaci);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row();
		}else {
			return FALSE;
		}
	}

	public function obtener_cantidad_actas_pagos($id_expedienteci){
		$this->db->select('f.numero_pago')
						 ->from('sct_fechaspagosci f')
						 ->join('sct_personaci p','p.id_personaci=f.id_persona')
						 ->join('sct_expedienteci e','e.id_expedienteci=p.id_expedienteci')
						 ->where('e.id_expedienteci',$id_expedienteci)
						 ->group_by('f.numero_pago');
		$query = $this->db->get();

		return $query;
	}

	public function obtener_datos_pago($id_expedienteci){
		$this->db->select("CONCAT_WS(' ',p.nombre_personaci,p.apellido_personaci) solicitante, rl.nombres_representante r_legal, em.nombre_empresa solicitado, em.tiposolicitud_empresa")
						 ->from('sct_fechaspagosci fp')
						 ->join('sct_expedienteci e','e.id_expedienteci = fp.id_expedienteci')
						 ->join('sge_empresa em','em.id_empresa = e.id_empresaci','left')
						 ->join('sge_representante rl','rl.id_empresa = em.id_empresa AND rl.tipo_representante = 1','left')
						 ->join('sct_personaci p','p.id_personaci = e.id_personaci')
						 // ->join('sct_fechasaudienciasci fa','fa.id_expedienteci = e.id_expedienteci','left')
						 // ->join('sge_representante r','r.id_representante = fa.id_representaci','left')
						 // ->where('fa.id_fechasaudienciasci','(SELECT MAX(fea.id_fechasaudienciasci) FROM sct_fechasaudienciasci fea WHERE fea.id_expedienteci=fp.id_expedienteci AND fea.id_resultadoci IN (1,10,26))')
						 ->where('e.id_expedienteci',$id_expedienteci);
		$query = $this->db->get();
		return $query;
	}
}
