<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller {

    function __construct(){
		parent::__construct();
		$this->load->model(array("expedientes_model", "login_model"));
    }

    public function index(){
			$data['colaboradores'] = $this->expedientes_model->obtener_delegados_rol();
			$this->load->view('templates/header');
			$this->load->view('resolucion_conflictos/roles', $data);
			$this->load->view('templates/footer');
    }

	public function gestionar_roles() {

		$colaboradores = $this->expedientes_model->obtener_delegados_rol();

		if ($colaboradores) {
			foreach ($colaboradores->result() as $delegado) {
				if ( $this->input->post($delegado->id_empleado) ) {
					$this->login_model->cambiar_rol($delegado->id_empleado, FILTRO);
				} else {
					$this->login_model->cambiar_rol($delegado->id_empleado, DELEGADO);
				}
			}
		} else {
			echo "fracaso";
		}

	}

}

?>
