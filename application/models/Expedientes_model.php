<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expedientes_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	public function insertar_expediente($data){
		if ($this->db->insert('sct_expedienteci', $data)) {
			return $this->db->insert_id();
		}else {
			return "fracaso";
		}
	}

	public function obtener_registros_expedientes($id_expedienteci,$id_audiencia=FALSE) {

			$this->db->select('pa.*,n.*,e.*,rp.*,f.*,m.*,em.*,c.*,r.*,ep.*,p.*,
												 p.discapacidad,
												 e.id_expedienteci,
												 e.ocupacion,
												 e.salario_personaci,
												 e.funciones_personaci,
												 e.formapago_personaci,
												 e.horarios_personaci,
												 e.fechaconflicto_personaci,
												 m.municipio,
												 mu.municipio municipio_empresa,
												 mur.municipio municipio_representante,
												 tr.tipo_representante tipo_representante_empresa,
												 ec.estado_civil estado_civil_representante,
												 ta.titulo_academico profesion_representante,
												 CONCAT_WS(" ",ea.primer_nombre,ea.segundo_nombre,ea.tercer_nombre,ea.primer_apellido,ea.segundo_apellido,ea.apellido_casada) delegado_audiencia,
												 CONCAT_WS(" ",ep.primer_nombre,ep.segundo_nombre,ep.tercer_nombre,ep.primer_apellido,ep.segundo_apellido,ep.apellido_casada) delegado_expediente,
												 ea.id_empleado id_delegado_audiencia,
												 ep.id_empleado id_delegado_expediente,
												 emp.id_empleador,
												 emp.nombre_empleador,
												 emp.apellido_empleador,
												 emp.cargo_empleador'
											  )
						 ->from('sct_expedienteci e')
						 ->join('sct_personaci p ', ' p.id_personaci = e.id_personaci')
						 ->join('sct_nacionalidad n','n.id_nacionalidad=p.nacionalidad_personaci')
						 ->join('sct_partida pa','pa.id_partida=p.id_partida','left')
						 ->join('sct_fechasaudienciasci f','f.id_expedienteci=e.id_expedienteci','left')
						 ->join('sct_representantepersonaci rp','rp.id_representantepersonaci=f.id_defensorlegal','left')
						 ->join('org_municipio m','m.id_municipio=p.id_municipio')
						 ->join('sge_empresa em','em.id_empresa = e.id_empresaci','left')
						 ->join('org_municipio mu','mu.id_municipio=em.id_municipio')
						 ->join('sge_catalogociiu c','c.id_catalogociiu=em.id_catalogociiu')
						 ->join('sge_representante r ', ' r.id_empresa = e.id_empresaci')
						 ->join('org_municipio mur','mur.id_municipio = r.id_municipio')
						 ->join('sir_estado_civil ec','ec.id_estado_civil=r.id_estado_civil')
						 ->join('sir_titulo_academico ta','ta.id_titulo_academico=r.id_titulo_academico')
						 ->join('sct_tipo_representante tr','tr.id_tipo_representante=r.tipo_representante','left')
						 ->join('sge_empleador emp','emp.id_empleador=e.id_empleador', 'left')
						 ->join('sir_empleado ep','ep.id_empleado=e.id_personal')
						 ->join('sir_empleado ea','ea.id_empleado=f.id_delegado')
						 ->where('e.id_expedienteci', $id_expedienteci);
		 	if ($id_audiencia) {
		 		$this->db->where('f.id_fechasaudienciasci',$id_audiencia);
		 	}
			$this->db->group_by('e.id_expedienteci')
						 ->where('f.estado_audiencia>0');
			$query=$this->db->get();
			if ($query->num_rows() > 0) {
					return  $query;
			}
			else {
					return FALSE;
			}
	}

	public function obtener_municipio($id) {

		$this->db->select(
											'e.numinscripcion_empresa,
											 m.municipio,
											 cat.actividad_catalogociiu,
											 cat.grupo_catalogociiu,
											 e.nombre_empresa,
											 e.direccion_empresa,
											 e.telefono_empresa,
											 r.nombres_representante'
										  )
						->from('sge_empresa e')
						->join('org_municipio m', ' m.id_municipio = e.id_municipio')
						->join('sge_catalogociiu cat','cat.id_catalogociiu=e.id_catalogociiu', 'left')
						->join('sge_representante r','r.id_empresa=e.id_empresa')
						->where('e.id_empresa', $id);
		$query=$this->db->get();
		if ($query->num_rows() > 0) {
				return $query->row();
		}
		else {
				return FALSE;
		}
	}

	public function editar_expediente($data){
		$this->db->where("id_expedienteci",$data["id_expedienteci"]);
		if ($this->db->update('sct_expedienteci', $data)) {
			return $data['id_expedienteci'];
		}else {
			return "fracaso";
		}
	}


  public function obtener_expediente($id, $id_persona = false) {

      $this->db->select(
											  'e.id_expedienteci,
												 e.numerocaso_expedienteci,
												 e.tiposolicitud_expedienteci,
												 e.tipocociliacion_expedienteci,
												 e.tiposolicitud_expedienteci,
												 ep.apellido_casada,
												 ep.primer_nombre,
												 ep.segundo_nombre,
												 ep.primer_apellido,
												 ep.segundo_apellido,
												 ep.nr,
												 p.id_personaci,
												 p.nombre_personaci,
												 p.apellido_personaci,
												 em.nombre_empresa,
												 s.nombre_sindicato'
												)
             ->from('sct_expedienteci e')
						 ->join('sct_personaci p ', ' p.id_personaci = e.id_personaci','left')
						 ->join('sge_empresa em','em.id_empresa = e.id_empresaci')
						 ->join('sge_sindicato s','s.id_expedientecc=e.id_expedienteci','left')
						 ->join('sir_empleado ep','ep.id_empleado=e.id_personal')
						 ->group_by('e.id_expedienteci')
						 ->where('e.id_expedienteci', $id);
	  $query=$this->db->get();
      if ($query->num_rows() > 0) {
          return $query;
      }
      else {
          return FALSE;
      }

	}

	public function cambiar_delegado($data){
		$this->db->where("id_expedienteci",$data["id_expedienteci"]);
		if ($this->db->update('sct_expedienteci', array('id_personal' => $data['id_personal']) )) {
			return "exito";
		}else {
			return "fracaso";
		}
	}

	public function obtener_registro_expediente_retiro($id) {

		$this->db->select('e.*,p.*,n.*,m.*,em.*,ep.*,e.id_expedienteci,m.id_municipio')
					 ->from('sct_expedienteci e')
					 ->join('sct_personaci p ', ' p.id_personaci = e.id_personaci')
					 ->join('sct_nacionalidad n','n.id_nacionalidad=p.nacionalidad_personaci')
					 ->join('org_municipio m','m.id_municipio=p.id_municipio')
					 ->join('sge_empresa em','em.id_empresa = e.id_empresaci')
					 ->join('sir_empleado ep','ep.id_empleado=e.id_personal')
					 ->where('e.id_expedienteci', $id);
		$query=$this->db->get();
		if ($query->num_rows() > 0) {
				return  $query;
		}
		else {
				return FALSE;
		}
	}

	public function obtener_expediente_pagos_indemnizacion($id_persona) {

		$this->db->select('')
			    ->from('sct_expedienteci a')
				->join('sct_personaci b', 'a.id_expedienteci = b.id_expedienteci')
				->join('sir_empleado c', 'a.id_personal = c.id_empleado')
				->where('b.id_personaci', $id_persona);
		$query=$this->db->get();
		//print $this->db->get_compiled_select();
		if ($query->num_rows() > 0) {
			return $query;
		}
		else {
			return FALSE;
		}

	}
//Tipo 1: Individuales, Tipo 2: Colectivos
	public function obtener_delegados_rol($tipo) {
			$this->db->select("
							e.id_empleado,
							e.nr,
							upper(concat_ws(' ', e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada)) as nombre_completo,
							r.nombre_rol,
							r.id_rol
							")
						 ->from('sir_empleado e')
						 ->join('org_usuario u', 'e.nr = u.nr')
						 ->join('org_usuario_rol ur', 'u.id_usuario = ur.id_usuario')
						 ->join('org_rol r', 'ur.id_rol = r.id_rol')
						 ->where('e.id_estado', '00001');
			if ($tipo==1) {
				$this->db->where('r.nombre_rol', 'FILTRO CCIT')
								 ->or_where('r.nombre_rol', 'Delegado(a) CCIT');
			}else {
				$this->db->where('r.nombre_rol', 'FILTRO CCCT')
								 ->or_where('r.nombre_rol', 'Delegado(a) CCCT');
			}
			$this->db->order_by('e.primer_nombre,
									e.segundo_nombre,
									e.tercer_nombre,
									e.primer_apellido,
									e.segundo_apellido,
									e.apellido_casada');
			$query = $this->db->get();
			if ($query->num_rows() > 0) {
					return $query;
			}
			else {
					return FALSE;
			}
	}

	public function obtener_estados_civiles(){
		$this->db->select('*')->from('sir_estado_civil');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query;
		}else {
			return FALSE;
		}
	}

	public function obtener_profesiones(){
		$this->db->select('*')->from('sir_titulo_academico');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query;
		}else {
			return FALSE;
		}
	}

	public function obtener_resultados(){
		$this->db->select('*')
						 ->from('sct_resultadosci')
						 ->where('id_tipo_solicitud',1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query;
		}else {
			return FALSE;
		}
	}

}
