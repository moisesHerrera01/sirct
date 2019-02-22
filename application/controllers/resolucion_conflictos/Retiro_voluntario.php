<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retiro_voluntario extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model( array('expedientes_model', 'solicitudes_model','empleadores_model','delegados_model'));
    }

    public function index() {
        $data['tipo_solicitud'] = $this->input->post('tipo_solicitud');
        $data['id_personaci'] = $this->input->post('id_personaci');
        $data['band_mantto'] = $this->input->post('band_mantto');
        $this->load->view('templates/header');
        $this->load->view('resolucion_conflictos/retiro_voluntario', $data);
        $this->load->view('templates/footer');
    }

    public function gestionar_solicitante(){

		if($this->input->post('band1') == "save") {
			$data = array(
                'nombre_personaci' => $this->input->post('nombres'),
                'apellido_personaci' => $this->input->post('apellidos'),
                'dui_personaci' => $this->input->post('dui'),
                'telefono_personaci' => $this->input->post('telefono'),
                'id_municipio' => $this->input->post('municipio'),
                'direccion_personaci' => $this->input->post('direccion'),
                'fnacimiento_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_nacimiento'))),
                'sexo_personaci' => $this->input->post('sexo'),
                'estudios_personaci' => $this->input->post('estudios'),
                'nacionalidad_personaci' => $this->input->post('nacionalidad'),
                'discapacidad_personaci' => $this->input->post('discapacidad'),
                'posee_representante' => null

			);
			echo $this->solicitudes_model->insertar_solicitud($data);

		}else if($this->input->post('band1') == "edit"){

			$data = array(
			'id_personaci' => $this->input->post('id_personaci'),
			'nombre_personaci' => $this->input->post('nombres'),
			'apellido_personaci' => $this->input->post('apellidos'),
			'dui_personaci' => $this->input->post('dui'),
			'telefono_personaci' => $this->input->post('telefono'),
			'id_municipio' => $this->input->post('municipio'),
			'direccion_personaci' => $this->input->post('direccion'),
			'fnacimiento_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_nacimiento'))),
			'sexo_personaci' => $this->input->post('sexo'),
			'estudios_personaci' => $this->input->post('estudios'),
			'nacionalidad_personaci' => $this->input->post('nacionalidad'),
			'discapacidad_personaci' => $this->input->post('discapacidad')
			);
			echo $this->solicitudes_model->editar_solicitud($data);
		}
    }

    public function gestionar_expediente() {
        $fecha_actual=date("Y-m-d H:i:s");

        if($this->input->post('band2') == "save"){
            $data = array(
                'motivo_expedienteci' => '',
                'descripmotivo_expedienteci' => '',
                'id_personaci' => $this->input->post('id_persona'),
                'id_personal' => $this->input->post('id_personal'),
                'causa_expedienteci' => $this->input->post('causa_expedienteci'),
                'id_empresaci' => $this->input->post('establecimiento'),
                'id_representanteci' => $this->input->post('id_representanteci'),
                'id_estadosci' => 1,
                'fechacrea_expedienteci' => $fecha_actual,
                'tiposolicitud_expedienteci' => "2",
                'numerocaso_expedienteci' =>10,
                'fechaconflicto_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_preaviso'))),
                'fecha_renuncia' => date("Y-m-d",strtotime($this->input->post('fecha_renuncia'))),
                'descripmotivo_expedienteci' => $this->input->post('descripcion_motivo'),
                'id_usuario' => $this->session->userdata('id_usuario'),
                'fecha_modifica' => date('Y-m-d')
            );

              echo $id_expedienteci = $this->expedientes_model->insertar_expediente($data);
              $delegado = array(
                'id_expedienteci' => $id_expedienteci,
  							'id_personal' => $data['id_personal'],
  							'fecha_cambio_delegado' => date('Y-m-d'),
  							'id_rol_guarda' => $this->session->userdata('id_rol'),
  							'id_usuario_guarda' => $this->session->userdata('id_usuario'),
  							'cambios' => "Asignación de expediente"
  						);
  						$this->delegados_model->insertar_delegado_exp($delegado);


       } else if($this->input->post('band2') == "edit"){
           $data2 = array(
                'id_expedienteci' => $this->input->post('id_expedienteci'),
                'motivo_expedienteci' => $this->input->post('motivo'),
                'descripmotivo_expedienteci' => $this->input->post('descripcion_motivo'),
                'id_personaci' => $this->input->post('id_persona'),
                'id_personal' => $this->input->post('id_personal'),
                'causa_expedienteci' => $this->input->post('causa_expedienteci'),
                'id_empresaci' => $this->input->post('establecimiento'),
                'id_representanteci' => $this->input->post('id_representanteci'),
                'fechacrea_expedienteci' => $fecha_actual,
                'tiposolicitud_expedienteci' =>"2",
                'fechaconflicto_personaci' => date("Y-m-d",strtotime($this->input->post('fecha_preaviso'))),
                'fecha_renuncia' => date("Y-m-d",strtotime($this->input->post('fecha_renuncia'))),
                'descripmotivo_expedienteci' => $this->input->post('descripcion_motivo'),
                'id_usuario' => $this->session->userdata('id_usuario'),
                'fecha_modifica' => date('Y-m-d')
           );
            echo $this->expedientes_model->editar_expediente($data2);
       }
    }

    public function tabla_solicitudes() {
        $this->load->view('resolucion_conflictos/retiro_voluntario_ajax/tabla_solicitudes');
    }

    public function registro_expediente() {

        print json_encode(
            $this->expedientes_model->obtener_registro_expediente_retiro($this->input->post('id'))->result()
        );
    }

    public function ver_expediente() {
        $data['empresa'] = $this->expedientes_model->obtener_municipio($this->input->post('id_emp'));
        $data['expediente'] = $this->expedientes_model->obtener_registro_expediente_retiro( $this->input->post('id') );

        $this->load->view('resolucion_conflictos/retiro_voluntario_ajax/vista_expediente', $data);
    }

    public function combo_resultados() {
      $resultados = $this->expedientes_model->obtener_resultados_rv();
      $this->load->view('resolucion_conflictos/solicitudes_ajax/combo_resultados',
        array(
          'id' => $this->input->post('id'),
          'resultados' => $resultados
        )
      );

    }

}
?>
