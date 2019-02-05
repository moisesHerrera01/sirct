<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acta extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model( array('expedientes_model', 'acta_model','audiencias_model','solicitud_juridica_model'));
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
        $expediente = $this->expedientes_model->obtener_registros_expedientes($id)->result()[0];

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
        // $templateWord->setValue('nombre_delegado', $expediente->delegado_expediente);

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

    public function generar_acta_tipo($caso,$id_expedienteci,$id_audiencia=FALSE) {
        if ($id_audiencia) {
          $expediente = $this->expedientes_model->obtener_registros_expedientes($id_expedienteci,$id_audiencia)->result()[0];
        }else {
          $expediente = $this->expedientes_model->obtener_registros_expedientes($id_expedienteci)->result()[0];
        }

        $this->load->library("phpword");
        $this->load->library("CifrasEnLetras");

        $PHPWord = new PHPWord();

        switch ($caso) {
          case '1':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actaAudiencia/AUDIENCIA_PF.docx');
            break;
          case '2':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actaAudiencia/MULTA.docx');
            break;
          case '3':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actaAudiencia/SEGUNDA_CITA.docx');
            break;
          case '4':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actaAudiencia/DESISTIDA.docx');
            break;
          case '5':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actasSolicitud/SOLICITUD_PN_PJ_estandar.docx');
            break;
          case '6':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actasSolicitud/SOLICITUD_PN_PJ_estandar.docx');
            break;
          case '7':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actaAudiencia/SOLICITUD_RV.docx');
            break;
          case '8':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actaAudiencia/SOLICITUD_RV_ST.docx');
            break;
          case '9':
            $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actaAudiencia/SOLICITUD_RV_NCNP.docx');
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


        $audiencias = $this->audiencias_model->obtener_audiencias($id_expedienteci,FALSE,1);
        $primera= $audiencias->result()[0];

        if ($caso<7) {
          $segunda= $audiencias->result()[1];
          $templateWord->setValue('minuto_audiencia2', minuto(INTVAL(date('i', strtotime($segunda->hora_fechasaudienciasci)))));
          $templateWord->setValue('dia_audiencia2', dia(date('d', strtotime($segunda->fecha_fechasaudienciasci))));
          $templateWord->setValue('mes_audiencia2', mb_strtoupper(mes(date('m', strtotime($segunda->fecha_fechasaudienciasci)))));
          $templateWord->setValue('anio_audiencia2', anio(date('Y', strtotime($segunda->fecha_fechasaudienciasci))));
        }
        $templateWord->setValue('representante_expediente', $expediente->representante_expediente);
        $templateWord->setValue('edad_representante_exp', mb_strtoupper(CifrasEnLetras::convertirCifrasEnLetras($expediente->edad_representante_exp)));
        $templateWord->setValue('dui_representante_exp', mb_strtoupper(convertir_dui($expediente->dui_representante_exp)));
        $templateWord->setValue('profesion_representante_exp', $expediente->profesion_representante_exp);
        $templateWord->setValue('municipio_representante_exp', $expediente->municipio_representante_exp);
        $templateWord->setValue('depto_representante_exp', $expediente->depto_representante_exp);
        $templateWord->setValue('acreditacion_representante_exp', mb_strtoupper($expediente->acreditacion_representante_exp));
        $templateWord->setValue('numero_folios', mb_strtoupper(CifrasEnLetras::convertirCifrasEnLetras($expediente->numero_folios)));

        if ($caso<5) {
              $templateWord->setValue('representante_persona', mb_strtoupper($expediente->nombre_representantepersonaci.' '.$expediente->apellido_representantepersonaci));
              $templateWord->setValue('dui_defensor', mb_strtoupper(convertir_dui($expediente->dui_representantepersonaci)));
              $templateWord->setValue('tipo_representante', mb_strtoupper($expediente->tipo_representante_empresa));
              $templateWord->setValue('edad_representante', mb_strtoupper(CifrasEnLetras::convertirCifrasEnLetras(calcular_edad(date("Y-m-d", strtotime($expediente->f_nacimiento_representante))))));
              $templateWord->setValue('municipio_representante', mb_strtoupper($expediente->municipio_representante));
              $templateWord->setValue('estado_civil_representante', mb_strtoupper($expediente->estado_civil_representante));
              $templateWord->setValue('profesion_representante', mb_strtoupper($expediente->profesion_representante));
              $templateWord->setValue('dui_representante', mb_strtoupper(convertir_dui($expediente->dui_representante)));
              $templateWord->setValue('credencial_representante', mb_strtoupper($expediente->acreditacion_representante));
              $templateWord->setValue('credencial_defensor', mb_strtoupper($expediente->acreditacion_representantepersonaci));
              $templateWord->setValue('resultado_audiencia', mb_strtoupper($expediente->detalle_resultado));
              $templateWord->setValue('posee', ($expediente->asistieron=="2") ? "quien se hace acompañar de " : "");

              $nombre_solicitante = mb_strtoupper($expediente->nombre_personaci.' '.$expediente->apellido_personaci);
              $representante_persona = mb_strtoupper($expediente->nombre_representantepersonaci.' '.$expediente->apellido_representantepersonaci);
              $dui_defensor = mb_strtoupper(convertir_dui($expediente->dui_representantepersonaci));
              $credencial_defensor =  mb_strtoupper($expediente->acreditacion_representantepersonaci);
            $inasistencia ="";
            if ($expediente->inasistencia==1) {
              $inasistencia = "Parte patronal";
            }elseif ($expediente->inasistencia==2) {
              $inasistencia = "Parte trabajadora";
            }elseif ($expediente->inasistencia==3) {
              $inasistencia = "Parte patronal y trabajadora";
            }
            switch ($expediente->asistieron) {
              case '1'://defensor
                $solicitante="en representación de el(la) trabajador(a) $nombre_solicitante ambos(as) de generales conocidas en estas diligencias, el(la) Defensor(a) Público(a) Laboral Licenciado(a) $representante_persona, quien se identifica con su documento único de identidad $dui_defensor, y acredita su personería por medio de $credencial_defensor, la cual se agrega a estas diligencias en fotocopia simple luego de haber sido debidamente confrontada con su original,  y ";
                break;
              case '2'://defensor y trabajador
                if ($expediente->id_fechasaudienciasci==$primera->id_fechasaudienciasci) {
                  $solicitante="el(la) trabajador(a) $nombre_solicitante de generales conocidas en estas diligencias, quien se hace acompañar de el(la) Defensor(a) Público(a) Laboral Licenciado(a) $representante_persona, quien se identifica con su documento único de identidad $dui_defensor, y acredita su personería por medio de $credencial_defensor, la cual se agrega a estas diligencias en fotocopia simple luego de haber sido debidamente confrontada con su original,  y ";
                }else {
                  if ($segunda->id_defensorlegal==$primera->id_defensorlegal) {
                    $solicitante="el(la) trabajador(a) $nombre_solicitante, quien se hace acompañar de el(la) Defensor(a) Público(a) Laboral Licenciado(a) $representante_persona, ambos(as) de generales conocidas en estas diligencias. Y ";
                  }else {
                    $solicitante="el(la) trabajador(a) $nombre_solicitante de generales conocidas en estas diligencias, quien se hace acompañar de el(la) Defensor(a) Público(a) Laboral Licenciado(a) $representante_persona, quien se identifica con su documento único de identidad $dui_defensor, y acredita su personería por medio de $credencial_defensor, la cual se agrega a estas diligencias en fotocopia simple luego de haber sido debidamente confrontada con su original,  y ";
                  }
                }
                break;
              case '3'://trabajador
                $solicitante="el(la) trabajador(a) $nombre_solicitante de generales conocidas en estas diligencias,  y";
                break;
              default:
                break;
            }
              switch ($expediente->tipo_pago) {
                case '2'://Pago diferido
                  $resultado = "hace del conocimiento de las partes que la certificación de la presente acta tiene fuerza ejecutiva, por lo que el incumplimiento de cualquiera de los pagos, faculta a el(la) trabajador(a) para hacerlo valer en la vía judicial competente, y RESUELVE: DEJAR PENDIENTE DE PAGO LAS PRESENTES DILIGENCIAS. Y no habiendo nada más que hacer constar se cierra la presente acta y leída que les fue a los(las) comparecientes, la ratifican y para constancia firmamos.";
                  break;
                case '1'://Pago en el momento
                  $resultado = "RESUELVE: ARCHIVAR LAS PRESENTES DILIGENCIAS. Y no habiendo nada más que hacer constar se cierra la presente acta y leída que les fue a los(las) comparecientes, la ratifican y para constancia firmamos.";
                  break;
                default:
                  $resultado="RESUELVE: ARCHIVAR LAS PRESENTES DILIGENCIAS. Y no habiendo nada más que hacer constar se cierra la presente acta y leída que les fue a los(las) comparecientes, la ratifican y para constancia firmamos.";;
                  break;
              }
              $templateWord->setValue('resuelve',$resultado);
              $templateWord->setValue('solicitante',$solicitante);
              $templateWord->setValue('ausente',$inasistencia);

        }
        if ($caso==5 || $caso==6) {
            if ($expediente->tiposolicitud_empresa==2) {
              $persona = "a la Sociedad";
            }else {
              $persona = "al Sr(a)";
            }
          if ($caso==6) {
            $encabezado_esquela="EL INFRAESCRITO SECRETARIO NOTIFICADOR DE LA DIRECCIÓN GENERAL DE TRABAJO HACE SABER: ".$persona." $expediente->nombre_empresa representado(a) legalmente por $expediente->nombres_representante, que en las diligencias promovidas por el trabajador(a) $expediente->solicitante se encuentra la solicitud que literalmente dice’’’’’’’’’’’’";
            $cuerpo_esquela="’’’’’’’’’’’’EMAYARI’’’’’’’’’’ANTE MI XCM SRIA.’’’’’’’’’RUBRICAS’’’’’’’";
            $pie_esquela="Y para que le sirva de legal notificación y citación, se expide la presente esquela en ________________, a las _____________horas y ________________ minutos del día __________________ del mes de ___________ de dos mil ______________.";
            $templateWord->setValue('encabezado_esquela', $encabezado_esquela);
            $templateWord->setValue('cuerpo_esquela',$cuerpo_esquela);
            $templateWord->setValue('pie_esquela', $pie_esquela);
          }else {
            $templateWord->setValue('encabezado_esquela', "");
            $templateWord->setValue('cuerpo_esquela',"");
            $templateWord->setValue('pie_esquela', "");
          }

          $dia_conflicto = dia(date('d', strtotime($expediente->fechaconflicto_personaci)));
          $mes_conflicto = mb_strtoupper(mes(date('m', strtotime($expediente->fechaconflicto_personaci))));
          $anio_conflicto = anio(date('Y', strtotime($expediente->fechaconflicto_personaci)));

          if ($expediente->tiposolicitud_empresa==2) {
            if ($expediente->motivo_expedienteci==1) {
              $tipo_empresa = " quien laboraba para la Sociedad $expediente->nombre_empresa que puede abreviarse $expediente->abreviatura_empresa, ubicada en $expediente->direccion_empresa, DE LA CIUDAD DE $expediente->municipio_empresa; hasta el día $dia_conflicto de $mes_conflicto de $anio_conflicto, en que fue despedido(a) de su trabajo sin que hasta la fecha se le haya pagado su correspondiente indemnización, vacación proporcional, y aguinaldo proporcional, según hoja de liquidación que se agrega a las presentes diligencias. Y es por lo anterior que, ";
            }else {
              $tipo_empresa = " y DICE: Que laboraba para la Sociedad $expediente->nombre_empresa que puede abreviarse $expediente->abreviatura_empresa, ubicada en $expediente->direccion_empresa, DE LA CIUDAD DE $expediente->municipio_empresa; hasta el día $dia_conflicto de $mes_conflicto de $anio_conflicto, en que finalizó la relación laboral. Y con la intención de celebrar audiencia conciliatoria con la Sociedad antes mencionada, ";
            }
          }else {
            if ($expediente->motivo_expedienteci==1) {
              $tipo_empresa = "quien laboraba para el señor $expediente->nombre_empresa, que puede ser ubicado en: $expediente->direccion_empresa, hasta el día $dia_conflicto de $mes_conflicto del año $anio_conflicto, en que fue despedido(a) de su trabajo sin que hasta la fecha se le cancelado su correspondiente indemnización, vacación proporcional, y aguinaldo proporcional, según hoja de liquidación que se agrega a las presentes diligencias. Y es por lo anterior que";
            }else {
              $tipo_empresa = "y DICE Que laboraba para el señor $expediente->nombre_empresa, que puede ser ubicado en: $expediente->direccion_empresa, hasta el día $dia_conflicto de $mes_conflicto del año $anio_conflicto, en que finalizó la relación laboral. Y con la intención de celebrar audiencia conciliatoria con la Sociedad antes mencionada, ";
            }
          }
          $templateWord->setValue('direccion_empresa', mb_strtoupper($expediente->direccion_empresa));
          $templateWord->setValue('direccion_solicitante', mb_strtoupper($expediente->direccion_personaci));
          $templateWord->setValue('hora_expediente', hora(date('G', strtotime($expediente->fechacrea_expedienteci))));
          $templateWord->setValue('minuto_expediente', minuto(INTVAL(date('i', strtotime($expediente->fechacrea_expedienteci)))));
          $templateWord->setValue('dia_expediente', dia(date('d', strtotime($expediente->fechacrea_expedienteci))));
          $templateWord->setValue('mes_expediente', mb_strtoupper(mes(date('m', strtotime($expediente->fechacrea_expedienteci)))));
          $templateWord->setValue('anio_expediente', anio(date('Y', strtotime($expediente->fechacrea_expedienteci))));
          $templateWord->setValue('edad', mb_strtoupper(CifrasEnLetras::convertirCifrasEnLetras(calcular_edad(date("Y-m-d", strtotime($expediente->fnacimiento_personaci))))));
          $templateWord->setValue('dui_persona', convertir_dui($expediente->dui_personaci));
          $templateWord->setValue('nacionalidad_persona', $expediente->nacionalidad);
          $templateWord->setValue('nombre_empleador', $expediente->nombre_empleador.' '.$expediente->apellido_empleador);
          $templateWord->setValue('funciones_persona', $expediente->funciones_personaci);
          $templateWord->setValue('horario_persona', $expediente->horarios_personaci);
          $templateWord->setValue('salario_solicitante', '$'.number_format( $expediente->salario_personaci,2));
          $templateWord->setValue('forma_pago', $expediente->formapago_personaci);
          $templateWord->setValue('dia_conflicto', dia(date('d', strtotime($expediente->fechaconflicto_personaci))));
          $templateWord->setValue('mes_conflicto', mb_strtoupper(mes(date('m', strtotime($expediente->fechaconflicto_personaci)))));
          $templateWord->setValue('anio_conflicto', anio(date('Y', strtotime($expediente->fechaconflicto_personaci))));
          $templateWord->setValue('hora_audiencia2', hora(date('G', strtotime($segunda->hora_fechasaudienciasci))));
          $templateWord->setValue('tipo', $tipo_empresa);
          $templateWord->setValue('nombre_delegado',$expediente->delegado_expediente);
        }
        $templateWord->setValue('representante_empresa', mb_strtoupper($expediente->nombres_representante));
        $templateWord->setValue('representante_legal', mb_strtoupper($expediente->representante_legal));
        $templateWord->setValue('tipo_representante_exp', mb_strtoupper($expediente->tipo_representante_exp));
        $templateWord->setValue('resolucion', mb_strtoupper($expediente->resultado_expedienteci));

        if ($id_audiencia) {
          if ($expediente->id_delegado_audiencia == $expediente->id_delegado_expediente) {
              $templateWord->setValue('nombre_delegado',"");
              $templateWord->setValue('delegado_audiencia', mb_strtoupper($expediente->delegado_audiencia));
              $templateWord->setValue('delegado_titulo', $expediente->delegado_audiencia);
          }else {
            $templateWord->setValue('nombre_delegado',$expediente->delegado_expediente);
            $templateWord->setValue('delegado_audiencia', mb_strtoupper($expediente->delegado_audiencia));
            $templateWord->setValue('delegado_titulo', mb_strtoupper("(Atendió: ".$expediente->delegado_audiencia.")"));
          }
        }

        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        switch ($caso) {
          case '1':
            header("Content-Disposition: attachment; filename='ACTA_AUDIENCIA_".date('dmy_His').".docx'");
            break;
          case '2':
            header("Content-Disposition: attachment; filename='MULTA_".date('dmy_His').".docx'");
            break;
          case '3':
            header("Content-Disposition: attachment; filename='SEGUNDA_CITA_".date('dmy_His').".docx'");
            break;
          case '4':
            header("Content-Disposition: attachment; filename='DESISTIDA_".date('dmy_His').".docx'");
            break;
          case '5':
            header("Content-Disposition: attachment; filename='ACTA_SOLICITUD_".date('dmy_His').".docx'");
            break;
          case '6':
            header("Content-Disposition: attachment; filename='ESQUELA_".date('dmy_His').".docx'");
            break;
          case '7':
            header("Content-Disposition: attachment; filename='ACTA_RV".date('dmy_His').".docx'");
            break;
          case '8':
            header("Content-Disposition: attachment; filename='ACTA_RV_ST_".date('dmy_His').".docx'");
            break;
          case '9':
            header("Content-Disposition: attachment; filename='ACTA_RV_NCNP_".date('dmy_His').".docx'");
            break;
          default:
            break;
        }
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
        $objWriter->save('php://output');

        unlink($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

    }


    public function generar_acta_juridico($caso,$id_expedienteci,$id_audiencia=FALSE){
      $exp = $this->solicitud_juridica_model->obtener_registros_expedientes($id_expedienteci)->result()[0];

      $audiencias = $this->audiencias_model->obtener_audiencias($id_expedienteci,FALSE,1);
      $primera= $audiencias->result()[0];
      $segunda= $audiencias->result()[1];

      $this->load->library("phpword");
      $this->load->library("CifrasEnLetras");

      $PHPWord = new PHPWord();
      $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actasSolicitud/SOLICITUD_PJ.docx');

      $templateWord->setValue('no_expediente', $exp->numerocaso_expedienteci);
      $templateWord->setValue('departamento', departamento($exp->numerocaso_expedienteci));

      $templateWord->setValue('hora_audiencia', hora(date('G', strtotime($primera->hora_fechasaudienciasci))));
      $templateWord->setValue('minuto_audiencia', minuto(INTVAL(date('i', strtotime($primera->hora_fechasaudienciasci)))));
      $templateWord->setValue('dia_audiencia', dia(date('d', strtotime($primera->fecha_fechasaudienciasci))));
      $templateWord->setValue('mes_audiencia', mb_strtoupper(mes(date('m', strtotime($primera->fecha_fechasaudienciasci)))));
      $templateWord->setValue('anio_audiencia', anio(date('Y', strtotime($primera->fecha_fechasaudienciasci))));

      $templateWord->setValue('hora_audiencia2', hora(date('G', strtotime($segunda->hora_fechasaudienciasci))));
      $templateWord->setValue('minuto_audiencia2', minuto(INTVAL(date('i', strtotime($segunda->hora_fechasaudienciasci)))));
      $templateWord->setValue('dia_audiencia2', dia(date('d', strtotime($segunda->fecha_fechasaudienciasci))));
      $templateWord->setValue('mes_audiencia2', mb_strtoupper(mes(date('m', strtotime($segunda->fecha_fechasaudienciasci)))));
      $templateWord->setValue('anio_audiencia2', anio(date('Y', strtotime($segunda->fecha_fechasaudienciasci))));

      $templateWord->setValue('nombre_delegado',$exp->primer_nombre.' '.$exp->segundo_nombre.' '.$exp->primer_apellido.' '.$exp->segundo_apellido.' '.$exp->apellido_casada);
      $templateWord->setValue('hora_expediente', hora(date('G', strtotime($exp->fechacrea_expedienteci))));
      $templateWord->setValue('minuto_expediente', minuto(INTVAL(date('i', strtotime($exp->fechacrea_expedienteci)))));
      $templateWord->setValue('dia_expediente', dia(date('d', strtotime($exp->fechacrea_expedienteci))));
      $templateWord->setValue('mes_expediente', mb_strtoupper(mes(date('m', strtotime($exp->fechacrea_expedienteci)))));
      $templateWord->setValue('anio_expediente', anio(date('Y', strtotime($exp->fechacrea_expedienteci))));
      $templateWord->setValue('nombre_rep_asiste', $exp->nombre_rep_asiste);
      $templateWord->setValue('prefijo_profesion_rep_asiste', AbreviaturaTitulo($exp->nivel_academico_rep_asiste,$exp->sexo_rep_asiste));
      $templateWord->setValue('profesion_rep_asiste', $exp->profesion_rep_asiste);
      $templateWord->setValue('municipio_rep_asiste', $exp->municipio_rep_asiste);
      $templateWord->setValue('depto_rep_asiste', $exp->depto_rep_asiste);
      $templateWord->setValue('tipo_documento_rep', $exp->tipo_documento_rep);
      $templateWord->setValue('documento_identidad_rep', mb_strtoupper(convertir_dui($exp->documento_identidad_rep)));
      $templateWord->setValue('tipo_representacion', $exp->tipo_representante_rep);
      $templateWord->setValue('parte_empleadora', $exp->nombre_empresa);
      $templateWord->setValue('abreviatura_parte_empleadora', $exp->abreviatura_empresa);
      $templateWord->setValue('acreditacion_rep', $exp->acreditacion_representante);
      $templateWord->setValue('nombre_solicitado', mb_strtoupper($exp->nombre_personaci.' '.$exp->apellido_personaci));
      $templateWord->setValue('solicitado_tipo_edad', $exp->solicitado_tipo_edad);
      $templateWord->setValue('solicitado_estado_civil', $exp->solicitado_estado_civil);
      $templateWord->setValue('nacionalidad_solicitado', $exp->nacionalidad_solicitado);
      $templateWord->setValue('municipio_solicitado', $exp->municipio_solicitado);
      $templateWord->setValue('depto_solicitado', $exp->depto_solicitado);
      $templateWord->setValue('profesion_solicitado', $exp->profesion_solicitado);
      $templateWord->setValue('profesion_solicitado', $exp->profesion_solicitado);
      $templateWord->setValue('descripcion_motivo', $exp->descripmotivo_expedienteci);
      $templateWord->setValue('rep_legal', $exp->rep_legal);
      $templateWord->setValue('', $exp->profesion_rep_legal);
      $templateWord->setValue('profesion_rep_legal', AbreviaturaTitulo($exp->nivel_rep_legal,$exp->sexo_rep_legal));

      $nombreWord = $this->random();

      $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

      $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

      header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
      header("Content-Disposition: attachment; filename='SOLICITUD_PJ_".date('dmy_His').".docx'");
      header('Cache-Control: max-age=0');

      $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
      $objWriter->save('php://output');

      unlink($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');
    }

    public function generar_acta_menor($caso,$id_expedienteci,$id_audiencia=FALSE){
      $expediente = $this->expedientes_model->obtener_registros_expedientes($id_expedienteci)->result()[0];

      $audiencias = $this->audiencias_model->obtener_audiencias($id_expedienteci,FALSE,1);
      $primera= $audiencias->result()[0];
      $segunda= $audiencias->result()[1];

      $this->load->library("phpword");
      $this->load->library("CifrasEnLetras");

      $PHPWord = new PHPWord();
      $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actasSolicitud/SOLICITUD_PJ_MENOR.docx');

      $templateWord->setValue('no_expediente', $exp->numerocaso_expedienteci);
      $templateWord->setValue('departamento', departamento($exp->numerocaso_expedienteci));

      $templateWord->setValue('hora_audiencia', hora(date('G', strtotime($primera->hora_fechasaudienciasci))));
      $templateWord->setValue('minuto_audiencia', minuto(INTVAL(date('i', strtotime($primera->hora_fechasaudienciasci)))));
      $templateWord->setValue('dia_audiencia', dia(date('d', strtotime($primera->fecha_fechasaudienciasci))));
      $templateWord->setValue('mes_audiencia', mb_strtoupper(mes(date('m', strtotime($primera->fecha_fechasaudienciasci)))));
      $templateWord->setValue('anio_audiencia', anio(date('Y', strtotime($primera->fecha_fechasaudienciasci))));

      $templateWord->setValue('hora_audiencia2', hora(date('G', strtotime($segunda->hora_fechasaudienciasci))));
      $templateWord->setValue('minuto_audiencia2', minuto(INTVAL(date('i', strtotime($segunda->hora_fechasaudienciasci)))));
      $templateWord->setValue('dia_audiencia2', dia(date('d', strtotime($segunda->fecha_fechasaudienciasci))));
      $templateWord->setValue('mes_audiencia2', mb_strtoupper(mes(date('m', strtotime($segunda->fecha_fechasaudienciasci)))));
      $templateWord->setValue('anio_audiencia2', anio(date('Y', strtotime($segunda->fecha_fechasaudienciasci))));

      $dia_conflicto = dia(date('d', strtotime($expediente->fechaconflicto_personaci)));
      $mes_conflicto = mb_strtoupper(mes(date('m', strtotime($expediente->fechaconflicto_personaci))));
      $anio_conflicto = anio(date('Y', strtotime($expediente->fechaconflicto_personaci)));

      $templateWord->setValue('direccion_empresa', mb_strtoupper($expediente->direccion_empresa));
      $templateWord->setValue('direccion_solicitante', mb_strtoupper($expediente->direccion_personaci));
      $templateWord->setValue('hora_expediente', hora(date('G', strtotime($expediente->fechacrea_expedienteci))));
      $templateWord->setValue('minuto_expediente', minuto(INTVAL(date('i', strtotime($expediente->fechacrea_expedienteci)))));
      $templateWord->setValue('dia_expediente', dia(date('d', strtotime($expediente->fechacrea_expedienteci))));
      $templateWord->setValue('mes_expediente', mb_strtoupper(mes(date('m', strtotime($expediente->fechacrea_expedienteci)))));
      $templateWord->setValue('anio_expediente', anio(date('Y', strtotime($expediente->fechacrea_expedienteci))));
      $templateWord->setValue('edad', mb_strtoupper(CifrasEnLetras::convertirCifrasEnLetras(calcular_edad(date("Y-m-d", strtotime($expediente->fnacimiento_personaci))))));
      $templateWord->setValue('dui_persona', convertir_dui($expediente->dui_personaci));
      $templateWord->setValue('nacionalidad_persona', $expediente->nacionalidad);
      $templateWord->setValue('nombre_empleador', $expediente->nombre_empleador.' '.$expediente->apellido_empleador);
      $templateWord->setValue('funciones_persona', $expediente->funciones_personaci);
      $templateWord->setValue('horario_persona', $expediente->horarios_personaci);
      $templateWord->setValue('salario_solicitante', '$'.number_format( $expediente->salario_personaci,2));
      $templateWord->setValue('forma_pago', $expediente->formapago_personaci);
      $templateWord->setValue('dia_conflicto', dia(date('d', strtotime($expediente->fechaconflicto_personaci))));
      $templateWord->setValue('mes_conflicto', mb_strtoupper(mes(date('m', strtotime($expediente->fechaconflicto_personaci)))));
      $templateWord->setValue('anio_conflicto', anio(date('Y', strtotime($expediente->fechaconflicto_personaci))));
      $templateWord->setValue('hora_audiencia2', hora(date('G', strtotime($segunda->hora_fechasaudienciasci))));
      $templateWord->setValue('tipo', $tipo_empresa);
      $templateWord->setValue('nombre_delegado',$expediente->delegado_expediente);

      $nombreWord = $this->random();

      $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

      $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

      header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
      header("Content-Disposition: attachment; filename='SOLICITUD_PJ_MENOR_".date('dmy_His').".docx'");
      header('Cache-Control: max-age=0');

      $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
      $objWriter->save('php://output');

      unlink($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');
    }
}
?>
