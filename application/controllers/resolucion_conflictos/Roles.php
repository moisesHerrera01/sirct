<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller {

    function __construct(){
		parent::__construct();
		$this->load->model(array("expedientes_model", "login_model"));
    }

    public function index(){
      $nombre_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->nombre_rol;
      if ($nombre_rol == 'Delegado(a) CCIT' || $nombre_rol == 'FILTRO CCIT' || $nombre_rol == 'JEFE CCIT') {
        $tipo = 1;
      }else {
        $tipo = 2;
      }
			$data['colaboradores'] = $this->expedientes_model->obtener_delegados_rol($tipo);
			$this->load->view('templates/header');
			$this->load->view('resolucion_conflictos/roles', $data);
			$this->load->view('templates/footer');
    }

	public function gestionar_roles() {
    $nombre_rol = $this->login_model->obtener_rol_usuario($_SESSION['id_usuario'])->nombre_rol;
    if ($nombre_rol == 'Delegado(a) CCIT' || $nombre_rol == 'FILTRO CCIT' || $nombre_rol == 'JEFE CCIT') {
      $tipo = 1;
    }else {
      $tipo = 2;
    }
		$colaboradores = $this->expedientes_model->obtener_delegados_rol($tipo);

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
