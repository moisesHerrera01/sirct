<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retiro_voluntario extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model( array('expedientes_model', 'solicitudes_model'));
    }
    
    public function index() {
        $this->load->view('templates/header');
        $this->load->view('resolucion_conflictos/retiro_voluntario');
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
                'discapacidad_personaci' => $this->input->post('discapacidad')
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
			'fnacimiento_personaci' => $this->input->post('fecha_nacimiento'),
			'sexo_personaci' => $this->input->post('sexo'),
			'estudios_personaci' => $this->input->post('estudios'),
			'nacionalidad_personaci' => $this->input->post('nacionalidad'),
			'discapacidad_personaci' => $this->input->post('discapacidad'),
			'id_empleador' => $this->input->post('id_empleador')
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
                'id_empresaci' => $this->input->post('establecimiento'),
                'id_estadosci' => 1,
                'fechacrea_expedienteci' => $fecha_actual,
                'tiposolicitud_expedienteci' => "Renuncia Voluntaria",
                'numerocaso_expedienteci' =>10
            );
            
            if ("fracaso" == $this->expedientes_model->insertar_expediente($data)) {
                echo "fracaso";
            } else {
                echo "exito";
            }

       } else if($this->input->post('band2') == "edit"){

           $data3 = array(
               'id_empleador' => $this->input->post('id_emplea'),
               'nombre_empleador' => $this->input->post('nombres_jefe'),
               'apellido_empleador' => $this->input->post('apellidos_jefe'),
               'cargo_empleador' => $this->input->post('cargo_jefe')
           );

           $data = $this->solicitudes_model->obtener_persona($this->input->post('id_persona'))->result_array()[0];
           $data['id_personaci'] = $this->input->post('id_persona');
           $data['salario_personaci'] = $this->input->post('salario');
           $data['funciones_personaci'] = $this->input->post('funciones');
           $data['formapago_personaci'] = $this->input->post('forma_pago');
           $data['horarios_personaci'] = $this->input->post('horario');
           $data['fechaconflicto_personaci'] = date("Y-m-d",strtotime($this->input->post('fecha_conflicto')));
           $data['id_catalogociuo'] = $this->input->post('ocupacion');

           $data2 = array(
                'id_expedienteci' => $this->input->post('id_expedienteci'),
                'motivo_expedienteci' => $this->input->post('motivo'),
                'descripmotivo_expedienteci' => $this->input->post('descripcion_motivo'),
                'id_personaci' => $this->input->post('id_persona'),
                'id_personal' => $this->input->post('id_personal'),
                'id_empresaci' => $this->input->post('establecimiento'),
                'fechacrea_expedienteci' => $fecha_actual,
                'tiposolicitud_expedienteci' =>"Conciliación",
           );

           if ("fracaso" != $this->empleadores_model->editar_empleador($data3)) {
                $this->solicitudes_model->editar_solicitud($data);
                $this->expedientes_model->editar_expediente($data2);
           } else {
               echo "fracaso";
           }

       }

   }

}
?>