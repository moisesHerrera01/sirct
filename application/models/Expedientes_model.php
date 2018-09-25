<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expedientes_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

  function insertar_expediente($data){
    if($this->db->insert('sct_expedienteci', array(
      'motivo_expedienteci' => $data['motivo_expedienteci'],
      'descripmotivo_expedienteci' => $data['descripmotivo_expedienteci'],
      'id_personaci' => $data['id_personaci'],
      'id_personal' => $data['id_personal'],
      'id_empresaci' => $data['id_empresaci'],
			'id_estadosci' => $data['id_estadosci'],
			'fechacrea_expedienteci' => $data['fechacrea_expedienteci'],
			'tiposolicitud_expedienteci' => $data['tiposolicitud_expedienteci'],
			'numerocaso_expedienteci' => $data['numerocaso_expedienteci']

    ))){
      return "exito,".$this->db->insert_id();
    }else{
      return "fracaso";
    }
  }

	public function obtener_registros_expedientes($id) {

			$this->db->select('n.*,e.*,rp.*,f.*,cat.*,m.*,em.*,c.*,r.*,emp.*,ep.*,p.*, p.discapacidad')
						 ->from('sct_expedienteci e')
						 ->join('sct_personaci p ', ' p.id_personaci = e.id_personaci')
						 ->join('sct_nacionalidad n','n.id_nacionalidad=p.nacionalidad_personaci')
						 ->join('sct_representantepersonaci rp','rp.id_personaci=p.id_personaci','left')
						 ->join('sct_fechasaudienciasci f','f.id_expedienteci=e.id_expedienteci','left')
						 ->join('sge_catalogociuo cat','cat.id_catalogociuo=p.id_catalogociuo', 'left')
						 ->join('org_municipio m','m.id_municipio=p.id_municipio')
						 ->join('sge_empresa em','em.id_empresa = e.id_empresaci','left')
						 ->join('sge_catalogociiu c','c.id_catalogociiu=em.id_catalogociiu')
						 ->join('sge_representante r ', ' r.id_empresa = e.id_empresaci')
						 ->join('sge_empleador emp','emp.id_empleador=p.id_empleador', 'left')
						 ->join('sir_empleado ep','ep.id_empleado=e.id_personal')
						 ->where('p.id_personaci', $id)
						 ->group_by('e.id_expedienteci');
			$query=$this->db->get();
			if ($query->num_rows() > 0) {
					return  $query;
			}
			else {
					return FALSE;
			}
	}

	/*e.numerocaso_expedienteci,
	 em.direccion_empresa,
	 r.nombres_representante,
	 c.actividad_catalogociiu,
	 p.nombre_personaci,
	 p.apellido_personaci,
	 p.telefono_personaci,
	 p.salario_personaci,
	 p.direccion_personaci,
	 p.formapago_personaci,
	 p.funciones_personaci,
	 p.horarios_personaci,
	 ep.primer_nombre,
	 ep.segundo_nombre,
	 ep.primer_apellido,
	 ep.segundo_apellido,
	 ep.apellido_casada*/

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
			return "exito";
		}else {
			return "fracaso";
		}
	}


  public function obtener_expediente($id, $id_persona = false) {

      $this->db->select(
											  'e.id_expedienteci,
												 e.numerocaso_expedienteci,
												 e.tiposolicitud_expedienteci,
												 ep.apellido_casada,
												 ep.primer_nombre,
												 ep.segundo_nombre,
												 ep.primer_apellido,
												 ep.segundo_apellido,
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
	  //print $this->db->get_compiled_select();
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

		$this->db->select('')
					 ->from('sct_expedienteci e')
					 ->join('sct_personaci p ', ' p.id_personaci = e.id_personaci')
					 ->join('sge_catalogociuo cat','cat.id_catalogociuo=p.id_catalogociuo', 'left')
					 ->join('org_municipio m','m.id_municipio=p.id_municipio')
					 ->join('sge_empresa em','em.id_empresa = e.id_empresaci')
					 ->join('sge_representante r ', ' r.id_empresa = e.id_empresaci', 'left')
					 ->join('sir_empleado ep','ep.id_empleado=e.id_personal')
					 ->where('p.id_personaci', $id);
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

}
