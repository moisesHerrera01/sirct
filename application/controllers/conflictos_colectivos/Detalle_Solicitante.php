<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detalle_Solicitante extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model( array('Persona_cc_model', 'Solicitantes_model'));
    }

    public function index() {
        # code...
    }

    public function tabla_solicitante() {
        $this->load->view(
            'conflictos_colectivos/solicitud_indemnizacion_ajax/tabla_solicitantes',
            array('solicitantes' => $this->Solicitantes_model->obtener_solicitantes_expediente( $this->input->get('expediente')) ));
    }

    public function obtener_solicitantes_json() {
        
        print json_encode(
            $this->Solicitantes_model->obtener_solicitante( $this->input->post('id') )->result()
        );

    }

    public function bajar_solicitante() {
        $data = $this->Persona_cc_model->obtener_persona($this->input->post('id'))->result_array()[0];
        $data['estado_persona'] = 0;
        echo $this->Persona_cc_model->editar_persona($data);
    }

    public function activar_solicitante() {
        $data = $this->Persona_cc_model->obtener_persona($this->input->post('id'))->result_array()[0];
        $data['estado_persona'] = 1;
        echo $this->Persona_cc_model->editar_persona($data);
    }
}  
?>