<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Establecimiento extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model(array("establecimiento_model", "representante_model"));
	}

	public function combo_actividad_economica() {

		$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_actividad_economica',
			array(
				'id' => $this->input->post('id'),
				'catalogo' => $this->db->get('sge_catalogociiu')
			)
		);

    }

    public function combo_municipio() {

		$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_municipio',
			array(
				'id' => $this->input->post('id'),
				'municipio' => $this->db->get('org_municipio')
			)
		);

	}

	public function combo_municipio2() {

	$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_municipio2',
		array(
			'id' => $this->input->post('id'),
			'municipio' => $this->db->get('org_municipio')
		)
	);

}

	public function gestionar_establecimiento() {
		if($this->input->post('band3') == "save"){

			$data = array(
                'numinscripcion_empresa' => '1-2018 SS',
                'nombre_empresa' => $this->input->post('nombre_establecimiento'),
								'razon_social' => $this->input->post('razon_social'),
                'abreviatura_empresa' => $this->input->post('abre_establecimiento'),
                'direccion_empresa'  => $this->input->post('dir_establecimiento'),
                'telefono_empresa' => $this->input->post('telefono_establecimiento'),
                'id_catalogociiu' => $this->input->post('act_economica'),
                'id_municipio' => $this->input->post('municipio2'),
								'tiposolicitud_empresa' => $this->input->post('tipo_establecimiento')
            );
			echo $this->establecimiento_model->insertar_establecimiento($data);
		}
	}

	public function combo_representante_empresa() {
		$data = $this->establecimiento_model->obtener_representantes($this->input->get('id_empresaci'));
		$this->load->view('resolucion_conflictos/solicitudes_ajax/combo_representante_empresa',
			array(
				'id' => $this->input->post('id'),
				'rep_empresa' => $data
			)
		);
	}
}
?>
