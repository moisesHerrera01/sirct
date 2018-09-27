<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acta extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model( array('expedientes_model', 'acta_model','audiencias_model'));
    }

    public function index() {
        $this->load->view('resolucion_conflictos/retiro_voluntario_ajax/adjuntar_actas', array('id' => $this->input->post('id') ));
    }

    public function tabla_acta() {
        $this->load->view('resolucion_conflictos/acta_ajax/tabla_actas');
    }

    public function gestionar_adjuntar_actas() {
        $data = $this->expedientes_model->obtener_expediente($this->input->post('id_expediente'))->result_array()[0];

        $targetPath = $this->directorio( str_replace( "/", "_", $data['numerocaso_expedienteci'] ) );

		if (!empty($_FILES)) {
            $filesCount = count($_FILES['file']['name']);
	        for ($i = 0; $i < $filesCount; $i++) {

                $_FILES['uploadFile']['name'] = $_FILES['file']['name'][$i];
                $_FILES['uploadFile']['type'] = $_FILES['file']['type'][$i];
                $_FILES['uploadFile']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                $_FILES['uploadFile']['error'] = $_FILES['file']['error'][$i];
                $_FILES['uploadFile']['size'] = $_FILES['file']['size'][$i];

                $config['upload_path'] = $targetPath;
                $config['allowed_types'] = 'pdf|doc|docx';
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('uploadFile')) {

                    $this->acta_model->insertar_acta(
                        array(
                            'id_expedienteci' => $this->input->post('id_expediente'),
                            'nombre_actasci' => $this->upload->data('file_name'),
                            'archivo_actasci' => $this->upload->data('full_path'),
                            'fechacrea_actasci' => date("Y-m-d H:i:s")
                        )
                    );

                }
            }

            echo "exito";

		} else {

			echo "fracaso";

		}
    }

    public function eliminar_acta() {
        $data = $this->acta_model->obtener_acta($this->input->post('id_acta'))->result_array()[0];

        if (file_exists($data['archivo_actasci'])) {

            if ("exito" == $this->acta_model->eliminar_estado($data)) {
                unlink($data['archivo_actasci']);
                echo "exito";
            } else {
                echo "fracaso";
            }
        } else {
            echo "fracaso";
        }
    }

    public function descargar_acta($id_acta) {
        $data = $this->acta_model->obtener_acta($id_acta)->result_array()[0];

		if(file_exists( $data['archivo_actasci'] )) {
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header('Content-disposition: attachment; filename='.basename($data['archivo_actasci']));
			header("Content-Type: application/pdf");
			header("Content-Transfer-Encoding: binary");
			readfile($data['archivo_actasci']);
		} else {
			return redirect('/resolucion_conflictos/retiro_voluntario');
		}
    }

    private function directorio($expediente) {

        if(!is_dir("./files/pdfs/" . $expediente)) {

            mkdir("./files", 0777);
            mkdir("./files/pdfs", 0777);
            mkdir("./files/pdfs/" . $expediente, 0777);
		}

		return "./files/pdfs/" . $expediente;
	}

  private function random() {
      $alpha = "123qwertyuiopa456sdfghjklzxcvbnm789";
      $code = "";
      $longitud=5;
      for($i=0;$i<$longitud;$i++){
          $code .= $alpha[rand(0, strlen($alpha)-1)];
      }
      return $code;
  }

    public function generar_acta($id) {
        $data = $this->expedientes_model->obtener_expediente( $id )->result_array()[0];
        $expediente = $this->expedientes_model->obtener_registros_expedientes( $data['id_personaci'] )->result()[0];

        //$jefe = $this->reglamento_model->jefe_direccion_trabajo()->result()[0];

        $this->load->library("phpword");
        $this->load->library("NumeroALetras");
        $this->load->library("CifrasEnLetras");

        $PHPWord = new PHPWord();

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actasSolicitud/FichaSolicitud_PNPJ.docx');
        $templateWord->setValue('no_expediente', $expediente->numerocaso_expedienteci);
        $templateWord->setValue('fecha_actual',dia(date('d')).' DE '.mb_strtoupper(mes(date('m'))).' DE '.anio(date('Y')));

        $templateWord->setValue('direccion_empresa', convertir_numeros_cadena($expediente->direccion_empresa));
        $templateWord->setValue('representante_legal', mb_strtoupper($expediente->nombres_representante));
        $templateWord->setValue('actividad', mb_strtoupper($expediente->actividad_catalogociiu));
        $templateWord->setValue('nombre_solicitante', mb_strtoupper($expediente->nombre_personaci.' '.$expediente->apellido_personaci));
        $templateWord->setValue('nombre_representante',   mb_strtoupper($expediente->nombres_representante));
        $templateWord->setValue('telefono_solicitante', mb_strtoupper(convertir_dui($expediente->telefono_personaci)));
        $templateWord->setValue('salario_solicitante', mb_strtoupper(CifrasEnLetras::convertirEurosEnLetras(number_format($expediente->salario_personaci,2,',',''))));
        $templateWord->setValue('direccion_solicitante', (convertir_numeros_cadena($expediente->direccion_personaci)));
        $templateWord->setValue('forma_pago', mb_strtoupper($expediente->formapago_personaci));
        $templateWord->setValue('cargo_solicitante', mb_strtoupper($expediente->funciones_personaci));
        $templateWord->setValue('horario_solicitante', convertir_numeros_cadena($expediente->horarios_personaci));
        $templateWord->setValue('nombre_delegado',
                                $expediente->primer_nombre . ' '
                                . $expediente->segundo_nombre . ' '
                                . $expediente->tercer_nombre . ' '
                                . $expediente->primer_apellido . ' '
                                . $expediente->segundo_apellido . ' '
                                . $expediente->apellido_casada);

        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename='ficha_solicitud_pnpj_".date('dmy_His').".docx'");
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
        $objWriter->save('php://output');

        unlink($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

    }

    public function generar_acta_tipo($caso,$id_expedienteci) {
        $data = $this->expedientes_model->obtener_expediente( $id_expedienteci )->result_array()[0];
        $expediente = $this->expedientes_model->obtener_registros_expedientes( $data['id_personaci'])->result()[0];
        //$jefe = $this->reglamento_model->jefe_direccion_trabajo()->result()[0];

        $this->load->library("phpword");

        $PHPWord = new PHPWord();

        switch ($caso) {
          case '1':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actasDeConciliacion/CONCILIADA_EN_EL_ACTO_CON_DEFENSOR.docx');
            break;
          case '2':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actasDeConciliacion/CONCILIADA_EN_EL_ACTO_SIN_DEFENSOR.docx');
            break;
          case '3':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actasDeConciliacion/CONCILIADA_PAGO_DIFERIDO_CON_DEFENSOR.docx');
            break;
          case '4':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actasDeConciliacion/CONCILIADA_PAGO_DIFERIDO_SIN_DEFENSOR.docx');
            break;
          case '5':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actasSolicitud/SOLICITUD_PN_PJ_estandar.docx');
            break;
          case '6':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actasSegundaCita/SEGUNDA_CITA_PN_PJ_CON_DEFENSOR.docx');
            break;
          case '7':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actasSegundaCita/SEGUNDA_CITA_PN_PJ_SIN_DEFENSOR.docx');
            break;
          case '8':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actasDeDesistimiento/DESISTIMIENTO_PN_PJ.docx');
            break;
          default:
            // code...
            break;
        }
        $templateWord->setValue('no_expediente', $expediente->numerocaso_expedienteci);
        $templateWord->setValue('departamento', departamento($expediente->numerocaso_expedienteci));
        $templateWord->setValue('hora_audiencia', hora(date('G', strtotime($expediente->hora_fechasaudienciasci))));
        $templateWord->setValue('minuto_audiencia', minuto(INTVAL(date('i', strtotime($expediente->hora_fechasaudienciasci)))));
        $templateWord->setValue('dia_audiencia', dia(date('d', strtotime($expediente->fecha_fechasaudienciasci))));
        $templateWord->setValue('mes_audiencia', mb_strtoupper(mes(date('m', strtotime($expediente->fecha_fechasaudienciasci)))));
        $templateWord->setValue('anio_audiencia', anio(date('Y', strtotime($expediente->fecha_fechasaudienciasci))));
        $templateWord->setValue('nombre_solicitante', mb_strtoupper($expediente->nombre_personaci.' '.$expediente->apellido_personaci));
        $templateWord->setValue('nombre_empresa', mb_strtoupper($expediente->nombre_empresa));
        if ($caso== 1 || $caso==3 || $caso==6) {
              $templateWord->setValue('representante_persona', mb_strtoupper($expediente->nombre_representantepersonaci.' '.$expediente->apellido_representantepersonaci));
        }
        if ($caso==5) {
          $audiencias = $this->audiencias_model->obtener_audiencias($id_expedienteci);
          $segunda= $audiencias->result()[1];
          $templateWord->setValue('direccion_empresa', mb_strtoupper($expediente->direccion_empresa));
          $templateWord->setValue('direccion_solicitante', mb_strtoupper($expediente->direccion_personaci));
          $templateWord->setValue('hora_expediente', hora(date('G', strtotime($expediente->fechacrea_expedienteci))));
          $templateWord->setValue('minuto_expediente', minuto(INTVAL(date('i', strtotime($expediente->fechacrea_expedienteci)))));
          $templateWord->setValue('dia_expediente', dia(date('d', strtotime($expediente->fechacrea_expedienteci))));
          $templateWord->setValue('mes_expediente', mb_strtoupper(mes(date('m', strtotime($expediente->fechacrea_expedienteci)))));
          $templateWord->setValue('anio_expediente', anio(date('Y', strtotime($expediente->fechacrea_expedienteci))));
          $templateWord->setValue('edad', calcular_edad(date("Y-m-d", strtotime($expediente->fnacimiento_personaci))));
          $templateWord->setValue('dui_persona', convertir_dui($expediente->dui_personaci));
          $templateWord->setValue('nacionalidad_persona', $expediente->nacionalidad_personaci);
          $templateWord->setValue('nombre_empleador', $expediente->nombre_empleador.' '.$expediente->apellido_empleador);
          $templateWord->setValue('funciones_persona', $expediente->funciones_personaci);
          $templateWord->setValue('horario_persona', $expediente->horarios_personaci);
          $templateWord->setValue('salario_solicitante', '$'.number_format( $expediente->salario_personaci,2));
          $templateWord->setValue('forma_pago', $expediente->formapago_personaci);
          $templateWord->setValue('dia_conflicto', dia(date('d', strtotime($expediente->fechaconflicto_personaci))));
          $templateWord->setValue('mes_conflicto', mb_strtoupper(mes(date('m', strtotime($expediente->fechaconflicto_personaci)))));
          $templateWord->setValue('anio_conflicto', anio(date('Y', strtotime($expediente->fechaconflicto_personaci))));
          $templateWord->setValue('hora_audiencia2', hora(date('G', strtotime($segunda->hora_fechasaudienciasci))));
          $templateWord->setValue('minuto_audiencia2', minuto(INTVAL(date('i', strtotime($segunda->hora_fechasaudienciasci)))));
          $templateWord->setValue('dia_audiencia2', dia(date('d', strtotime($segunda->fecha_fechasaudienciasci))));
          $templateWord->setValue('mes_audiencia2', mb_strtoupper(mes(date('m', strtotime($segunda->fecha_fechasaudienciasci)))));
        }
        $templateWord->setValue('representante_empresa', mb_strtoupper($expediente->nombres_representante));
        $templateWord->setValue('resolucion', mb_strtoupper($expediente->resultado_expedienteci));
        $templateWord->setValue('nombre_delegado',
                                $expediente->primer_nombre . ' '
                                . $expediente->segundo_nombre . ' '
                                . $expediente->tercer_nombre . ' '
                                . $expediente->primer_apellido . ' '
                                . $expediente->segundo_apellido . ' '
                                . $expediente->apellido_casada);

        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        switch ($caso) {
          case '1':
            header("Content-Disposition: attachment; filename='CONCILIADA_EN_EL_ACTO_CON_DEFENSOR_".date('dmy_His').".docx'");
            break;
          case '2':
            header("Content-Disposition: attachment; filename='CONCILIADA_EN_EL_ACTO_SIN_DEFENSOR_".date('dmy_His').".docx'");
            break;
          case '3':
            header("Content-Disposition: attachment; filename='CONCILIADA_PAGO_DIFERIDO_CON_DEFENSOR_".date('dmy_His').".docx'");
            break;
          case '4':
            header("Content-Disposition: attachment; filename='CONCILIADA_PAGO_DIFERIDO_SIN_DEFENSOR_".date('dmy_His').".docx'");
            break;
          case '5':
            header("Content-Disposition: attachment; filename='SOLICITUD_PN_PJ_".date('dmy_His').".docx'");
            break;
          case '6':
            header("Content-Disposition: attachment; filename='SEGUNDA_CITA_PN_PJ_CON_DEFENSOR_".date('dmy_His').".docx'");
            break;
          case '7':
            header("Content-Disposition: attachment; filename='SEGUNDA_CITA_PN_PJ_SIN_DEFENSOR_".date('dmy_His').".docx'");
            break;
          case '8':
            header("Content-Disposition: attachment; filename='DESISTIMIENTO_PN_PJ_".date('dmy_His').".docx'");
            break;
          default:
            break;
        }
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
        $objWriter->save('php://output');

        unlink($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

    }
}
?>
