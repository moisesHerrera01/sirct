<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acta extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model( array('expedientes_model', 'acta_model'));
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

  public function generar_acta($id){

        $data = $this->expedientes_model->obtener_expediente( $id )->result_array()[0];

        switch ($data['id_estadosci']) {
            case 1:
                switch ($data['tiposolicitud_expedienteci']) {
                    case 'Conciliación':
                        $this->generar_acta_pnpj($data['id_personaci']);
                        break;
                    case 'Renuncia Voluntaria':
                        $this->generar_acta_pnpj($data['id_personaci']);
                        break;
                    default:
                        $this->generar_acta_pnpj($data['id_personaci']);
                        break;
                }
                break;
            /*case 2:
                switch ($data['tiposolicitud_expedientert']) {
                    case 'Reforma Parcial':
                        $this->generar_acta_denegada_parcial($id);
                        break;
                    case 'Reforma Total':
                        $this->generar_acta_denegada_total($id);
                        break;
                    default:
                        $this->generar_acta_denegada($id);
                        break;
                }
                break;
            case 3:
                $this->generar_acta_observado($id);
                break;
            case 4:
                $this->generar_acta_prevenido($id);
                break;
            default:
                # code...
                break;*/
        }

    }

    public function generar_acta_pnpj($id) {

        $expediente = $this->expedientes_model->obtener_registros_expedientes( $id )->result()[0];

        //$jefe = $this->reglamento_model->jefe_direccion_trabajo()->result()[0];

        $this->load->library("phpword");

        $PHPWord = new PHPWord();

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/actasSolicitud/FichaSolicitud_PNPJ.docx');
        $templateWord->setValue('no_expediente', $expediente->numerocaso_expedienteci);
        $templateWord->setValue('fecha_actual', date('d/m/Y'));
        $templateWord->setValue('dirección_empresa', $expediente->direccion_empresa);
        $templateWord->setValue('representante_legal', $expediente->nombres_representante);
        $templateWord->setValue('actividad', $expediente->actividad_catalogociiu);
        $templateWord->setValue('nombre_solicitante', $expediente->nombre_personaci.' '.$expediente->apellido_personaci);
        $templateWord->setValue('nombre_representante', $expediente->nombres_representante);
        $templateWord->setValue('telefono_solicitante', $expediente->telefono_personaci);
        $templateWord->setValue('salario_solicitante', '$'.number_format( $expediente->salario_personaci,2));
        $templateWord->setValue('direccion_solicitante', $expediente->direccion_personaci);
        $templateWord->setValue('forma_pago', $expediente->formapago_personaci);
        $templateWord->setValue('cargo_solicitante', $expediente->funciones_personaci);
        $templateWord->setValue('horario_solicitante', $expediente->horarios_personaci);
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
}
?>
