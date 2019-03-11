<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Establecimiento_model extends CI_Model {

	function __construct(){
		parent::__construct();
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

		function upgrade_establecimiento($data){
			$id_sistema = $this->config->item("id_sistema");
			if($this->db->query("INSERT INTO sge_empresa_actualizacion (
					id_empresa, numinscripcion_empresa, id_oficina, id_municipio, id_catalogociiu, nombre_empresa,
					abreviatura_empresa, nit_empresa, telefono_empresa, direccion_empresa, correoelectronico_empresa,
					activobalance_empresa, capitalsocial_empresa, trabajadores_adomicilio_empresa, tipo_empresa,
					archivo_empresa, tiposolicitud_empresa, fechacrea_empresa, fechamodf_empresa, numtotal_empresa,
					numinscripanterior_empresa, actualizada_empresa, razon_social, id_sistema
				) SELECT
					id_empresa, numinscripcion_empresa, id_oficina, id_municipio, id_catalogociiu, nombre_empresa,
					abreviatura_empresa, nit_empresa, telefono_empresa, direccion_empresa, correoelectronico_empresa,
					activobalance_empresa, capitalsocial_empresa, trabajadores_adomicilio_empresa, tipo_empresa,
					archivo_empresa, tiposolicitud_empresa, fechacrea_empresa, fechamodf_empresa, numtotal_empresa,
					numinscripanterior_empresa, actualizada_empresa, razon_social, ".$id_sistema."
				FROM sge_empresa WHERE id_empresa = '".$data["id_empresa"]."'")){
				return $this->editar_establecimiento($data);
			}else{
				return "fracaso";
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

		public function obtener_respresentante_mayor($id) {
			$this->db->select('a.id_representante, nombres_representante')
				->from('sge_representante a')
				->where("id_representante = (
						SELECT max(b.id_representante) FROM sge_representante b WHERE b.id_empresa = $id
					)");
			$query=$this->db->get();
			if ($query->num_rows() > 0) {
				return  $query;
			}else{
				return FALSE;
			}
		}

		public function obtener_empresas($search = '' , $final = 30) {
				$this->db->select('id_empresa id, abreviatura_empresa text')
						->from('sge_empresa')
						->like('nombre_empresa', $search)
						->limit(30, $final);

				$query=$this->db->get();
				if ($query->num_rows() > 0) {
						return $query->result();
				}
				else {
						return FALSE;
				}
		}

		public function obtener_empresa($id_empresa){
			$this->db->select('id_empresa,nombre_empresa')
							 ->from('sge_empresa')
							 ->where('id_empresa',$id_empresa);
			$query = $this->db->get()->row();
			return $query;
		}

		public function cantidad_empresas() {
				$this->db->select()
						->from('sge_empresa');

				$query=$this->db->get();
				return $query->num_rows();
		}

}

?>
