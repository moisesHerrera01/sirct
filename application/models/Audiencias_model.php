<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audiencias_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

		public function obtener_audiencias($id, $orden=FALSE, $estado=FALSE, $id_audiencia=FALSE) {

			$this->db->select(
												'f.id_expedienteci,
												 f.id_fechasaudienciasci,
												 f.fecha_fechasaudienciasci,
												 f.hora_fechasaudienciasci,
												 f.estado_audiencia,
												 f.numero_fechasaudienciasci,
												 f.id_representaci,
												 f.id_defensorlegal,
												 UPPER(CONCAT_WS(" ",d.nombre_representantepersonaci,d.apellido_representantepersonaci)) defensor,
												 UPPER(r.nombres_representante) representante_asiste,
												 r.dui_representante representante_asiste_dui,
												 UPPER(dt.departamento) representante_asiste_depto,
												 UPPER(m.municipio) representante_asiste_municipio,
												 UPPER(ta.titulo_academico) representante_asiste_profesion,
												 UPPER(r.acreditacion_representante) representante_asiste_acreditacion,
												 UPPER(f.detalle_resultado) detalle_resultado,
												 d.dui_representantepersonaci dui_defensor,
												 UPPER(d.acreditacion_representantepersonaci) acreditacion_defensor,
												 f.id_delegado,
												 f.tipo_pago,
												 f.asistieron,
												 f.resultado,
												 CONCAT_WS(" ",e.primer_nombre,e.segundo_nombre,e.primer_apellido,e.segundo_apellido,e.apellido_casada) delegado_audiencia,
												 (select count(*) from sct_fechasaudienciasci fe where fe.id_expedienteci=f.id_expedienteci) AS cuenta,
												 (select e.id_estadosci from sct_expedienteci e where e.id_expedienteci=f.id_expedienteci) AS estado,
												 na.nivel_academico representante_asiste_nacademico,
												 r.sexo_representante'
											  )
						 ->from('sct_fechasaudienciasci f')
						 ->join('sge_representante r','r.id_representante=f.id_representaci','left')
						 ->join('sir_titulo_academico ta','ta.id_titulo_academico=r.id_titulo_academico','left')
						 ->join('sir_nivel_academico na','na.id_nivel_academico=ta.id_nivel_academico','left')
						 ->join('sir_empleado e','e.id_empleado=f.id_delegado','left')
						 ->join('org_municipio m','m.id_municipio=e.id_muni_residencia','left')
						 ->join('org_departamento dt','dt.id_departamento = m.id_departamento_pais','left')
						 ->join('sct_representantepersonaci d','d.id_representantepersonaci=f.id_defensorlegal','left')
						 ->where('f.id_expedienteci', $id)
						 ->order_by('f.estado_audiencia','desc')
						 ->order_by('f.id_fechasaudienciasci','asc');
			if ($orden && $estado) {
				$this->db->where('f.estado_audiencia',$estado)
								 ->where('f.numero_fechasaudienciasci',$orden);
			}
			if ($id_audiencia) {
				$this->db->where('f.id_fechasaudienciasci',$id_audiencia);
			}
			$query=$this->db->get();
			if ($query->num_rows() > 0) {
					return $query;
			}
			else {
					return FALSE;
			}
	}

	public function obtener_audiencias_delegado($id_delegado,$fecha=FALSE,$hora=FALSE,$tipo=FALSE) {
		$this->db->select("s.nombre_sindicato,e.motivo_expedienteci,e.numerocaso_expedienteci,f.id_expedienteci,f.id_fechasaudienciasci,
												f.fecha_fechasaudienciasci,f.hora_fechasaudienciasci,tiposolicitud_expedienteci,
												ms.nombre_motivo tipo,
												CONCAT_WS(' ',em.primer_nombre,em.segundo_nombre,em.primer_apellido,em.segundo_apellido) delegado,
												CONCAT_WS(' ',p.nombre_personaci,p.apellido_personaci) persona,
												COALESCE(r.resultadoci,'Aún no se registra resolución' ) resultado,
												d.nombre_delegado_actual,
												emp.nombre_empresa")
					->from('sct_fechasaudienciasci f')
					->join('sct_expedienteci e','e.id_expedienteci=f.id_expedienteci')
					->join('sge_empresa emp','emp.id_empresa=e.id_empresaci','left')
					->join('sct_personaci p','p.id_personaci=e.id_personaci','left')
					->join('sge_sindicato s','s.id_expedientecc=e.id_expedienteci','left')
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
					->join('sct_resultadosci r','r.id_resultadoci = f.resultado','left')
					->join('sct_motivo_solicitud ms','ms.id_motivo_solicitud=e.causa_expedienteci','left')
					->group_by('f.id_fechasaudienciasci');
		if ($id_delegado) {
			$this->db->where('d.nr_delegado_actual', $id_delegado);
		}
		if ($fecha) {
			$this->db->where('f.fecha_fechasaudienciasci', $fecha);
		}
		if ($tipo==1) {
			$this->db->where('e.tiposolicitud_expedienteci<4');
		}else {
			$this->db->where('e.tiposolicitud_expedienteci>3');
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

		$this->db->where("(f.id_fechasaudienciasci =(SELECT fa.id_fechasaudienciasci
																							  FROM sct_fechasaudienciasci fa
																							  WHERE fa.id_expedienteci=f.id_expedienteci AND fa.estado_audiencia=1
										 														LIMIT 1)
										 OR f.estado_audiencia = 2)");
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
