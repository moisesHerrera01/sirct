<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detalle_Solicitante extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('Solicitantes_model');
    }

    public function index() {
        # code...
    }

    public function tabla_solicitante() {
        $this->load->view(
            'conflictos_colectivos/solicitud_indemnizacion_ajax/tabla_solicitantes',
            array('solicitantes' => $this->Solicitantes_model->obtener_solicitantes_expediente( $this->input->get('expediente')) ));
    }
}  
?>