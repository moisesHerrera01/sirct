<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acta extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model( array('expedientes_model'));
    }
    
    public function index() {
        $this->load->view('resolucion_conflictos/retiro_voluntario_ajax/adjuntar_actas', array('id' => $this->input->post('id') ));
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
                    $fileData = $this->upload->data();
                    $uploadData[$i]['file_name'] = $fileData['file_name'];
                }
            }

            echo "exito";

		} else {

			echo "fracaso";

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