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
}
?>