<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud_juridica_model extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function mostrar_solicitud(){
		$query = $this->db->get("sct_personaci");
		if($query->num_rows() > 0) return $query;
		else return false;
	}

	function insertar_solicitado($data){
		if($this->db->insert('sct_personaci', array(
			'nombre_personaci' => $data['nombre_personaci'],
			'apellido_personaci' => $data['apellido_personaci'],
			'telefono_personaci' => $data['telefono_personaci'],
			'id_municipio' => $data['id_municipio'],
			'direccion_personaci' => $data['direccion_personaci'],
			'sexo_personaci' => $data['sexo_personaci'],
			'discapacidad_personaci' => $data['discapacidad_personaci']
		))){
			return "exito,".$this->db->insert_id();
		}else{
			return "fracaso";
		}
	}

	function editar_solicitado($data){
		$this->db->where("id_personaci",$data["id_personaci"]);
		if($this->db->update('sct_personaci', array(
			'nombre_personaci' => $data['nombre_personaci'],
			'apellido_personaci' => $data['apellido_personaci'],
			'telefono_personaci' => $data['telefono_personaci'],
			'id_municipio' => $data['id_municipio'],
			'direccion_personaci' => $data['direccion_personaci'],
			'sexo_personaci' => $data['sexo_personaci'],
			'discapacidad_personaci' => $data['discapacidad_personaci']
		))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function eliminar_solicitud($data){
  		if($this->db->delete("sct_personaci",array('id_personaci' => $data['id_personaci']))){
  			return "exito";
  		}else{
  			return "fracaso";
  		}
	}

	function insertar_expediente($data){
		if($this->db->insert('sct_expedienteci', array(
			'id_empresaci' => $data['id_empresaci'],
			'id_personal' => $data['id_personal'],
			'numerocaso_expedienteci' => '11',
			'id_personaci' => $data['id_personaci'],
			'id_representanteci' => $data['id_representanteci'],
			'causa_expedienteci' => $data['causa_expedienteci'],
			'motivo_expedienteci' => $data['motivo_expedienteci'],
			'descripmotivo_expedienteci' => $data['descripmotivo_expedienteci'],
			'tiposolicitud_expedienteci' => '3',
			'id_estadosci' => '1',
			'fechacrea_expedienteci' => date("Y-m-d H:i:s")
		))){
			return "exito,".$this->db->insert_id();
		}else{
			return "fracaso";
		}
	}

	function editar_expediente($data){
		$this->db->where("id_expedienteci",$data["id_expedienteci"]);
		if($this->db->update('sct_expedienteci', array(
			'id_empresaci' => $data['id_empresaci'],
			'id_personal' => $data['id_personal'],
			'id_personaci' => $data['id_personaci'],
			'causa_expedienteci' => $data['causa_expedienteci'],
			'id_representanteci' => $data['id_representanteci'],
			'motivo_expedienteci' => $data['motivo_expedienteci'],
			'descripmotivo_expedienteci' => $data['descripmotivo_expedienteci']
		))){
			return "exito,".$data["id_expedienteci"];
		}else{
			return "fracaso";
		}
	}

	function eliminar_expediente($data){
  		if($this->db->delete("sct_expedienteci",array('id_expedienteci' => $data['id_expedienteci']))){
  			return "exito";
  		}else{
  			return "fracaso";
  		}
	}

	function insertar_establecimiento($data){
		if($this->db->insert('sge_empresa', $data)){
			return "exito,".$this->db->insert_id();
		}else{
			return "fracaso";
		}
	}

	public function editar_establecimiento($data){
		$this->db->where("id_empresa",$data["id_empresa"]);
		if ($this->db->update('sge_empresa', $data)) {
			return "exito";//return $data['id_partida'];
		}else {
			return "fracaso";
		}
	}

	// function insertar_representante($data){
	// 	if($this->db->insert('sge_representante', array(
	// 		'id_empresa' => $data['id_empresa'],
	// 		'nombres_representante' => $data['nombres_representante'],
	// 		'dui_representante' => $data['dui_representante'],
	// 		'acreditacion_representante' => $data['acreditacion_representante'],
	// 		'tipo_representante' => $data['tipo_representante']
	// 	))){
	// 		return "exito";
	// 	}else{
	// 		return "fracaso";
	// 	}
	// }

	function insertar_representante($data){
		if ($this->db->insert('sge_representante',$data)) {
			return "exito";//return $this->db->insert_id();
		}else{
			return "fracaso";
		}
	}

	public function editar_representante($data){
		$this->db->where("id_representante",$data["id_representante"]);
		if ($this->db->update('sge_representante', $data)) {
			return "exito";//return $data['id_partida'];
		}else {
			return "fracaso";
		}
	}

	// function editar_representante($data){
	// 	$this->db->where("id_representante",$data["id_representante"]);
	// 	if($this->db->update('sge_representante', array(
	// 		'id_empresa' => $data['id_empresa'],
	// 		'nombres_representante' => $data['nombres_representante'],
	// 		'dui_representante' => $data['dui_representante'],
	// 		'acreditacion_representante' => $data['acreditacion_representante'],
	// 		'tipo_representante' => $data['tipo_representante']
	// 	))){
	// 		return "exito";
	// 	}else{
	// 		return "fracaso";
	// 	}
	// }

	function eliminar_representante($data){
  		$this->db->where("id_representante",$data["id_representante"]);
		if($this->db->update('sge_representante', array(
			'estado_representante' => $data['estado_representante']
		))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	public function obtener_registros_expedientes($id) {

		$this->db->select('e.id_personaci AS id_personacie, p.*, e.*, ep.*, em.*, r.*, m.*,d.nombre_delegado_actual')
			->from('sct_expedienteci e')
			->join('sge_empresa em','em.id_empresa = e.id_empresaci')
			->join('sir_empleado ep','ep.id_empleado=e.id_personal')
			->join('sct_personaci p ', ' p.id_personaci = e.id_personaci')
			->join('sge_representante r ', ' r.id_representante = e.id_representanteci')
			->join('org_municipio m','m.id_municipio=p.id_municipio')
			->join("(
						SELECT de.id_expedienteci,de.id_personal delegado_actual,
						CONCAT_WS(' ',emp.primer_nombre,emp.segundo_nombre,emp.tercer_nombre,emp.primer_apellido,emp.segundo_apellido,emp.apellido_casada) nombre_delegado_actual
						FROM sct_delegado_exp de
						JOIN sir_empleado emp ON emp.id_empleado=de.id_personal
						WHERE de.id_delegado_exp = (SELECT MAX(de2.id_delegado_exp)
																				FROM sct_delegado_exp de2
																				WHERE de2.id_expedienteci=de.id_expedienteci
																			 )
					) d" , "d.id_expedienteci=e.id_expedienteci")
			/*->join('sge_catalogociuo cat','cat.id_catalogociuo=p.id_catalogociuo')
			->join('org_municipio m','m.id_municipio=p.id_municipio')
			->join('sge_representante r ', ' r.id_empresa = e.id_empresaci')
			*/
			->where('e.id_expedienteci', $id);
		$query=$this->db->get();
		if ($query->num_rows() > 0) {
			return  $query;
		}else{
			return FALSE;
		}
	}

	public function obtener_personaci($id) {
		$this->db->select('')
			->from('sct_personaci p')
			/*->join('sge_catalogociuo cat','cat.id_catalogociuo=p.id_catalogociuo')*/
			->join('org_municipio m','m.id_municipio=p.id_municipio')
			->where('p.id_personaci', $id);
		$query=$this->db->get();
		if ($query->num_rows() > 0) {
			return  $query;
		}else{
			return FALSE;
		}
	}

	public function obtener_representantes($id) {
		$this->db->select('')
			->from('sge_representante r')
			->where('r.id_empresa', $id);
		$query=$this->db->get();
		if ($query->num_rows() > 0) {
			return  $query;
		}else{
			return FALSE;
		}
	}

	function obtener_ultimo_id($tabla,$nombreid){
		$this->db->order_by($nombreid, "asc");
		$query = $this->db->get($tabla);
		$ultimoid = 0;
		if($query->num_rows() > 0){
			foreach ($query->result() as $fila) {
				$ultimoid = $fila->$nombreid;
			}
			$ultimoid++;
		}else{
			$ultimoid = 1;
		}
		return $ultimoid;
	}
}
