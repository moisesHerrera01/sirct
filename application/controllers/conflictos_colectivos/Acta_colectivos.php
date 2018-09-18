<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acta_colectivos extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model( array('expediente_cc_model','expedientes_model'));
    }

    public function generar_acta($id_expedienteci) {
        $expediente = $this->expediente_cc_model->expedientes_diferencia_laboral( $id_expedienteci )->result()[0];
        $empresa = $this->expedientes_model->obtener_municipio($expediente->id_empresaci);
        $this->load->library("phpword");

        $PHPWord = new PHPWord();

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/templateDocSRCCT/FichaSolicitud_DifL.docx');
        $templateWord->setValue('no_expediente', $expediente->numerocaso_expedienteci);
        $templateWord->setValue('fecha_actual', date('d/m/Y'));
        $templateWord->setValue('direccion_empresa', $empresa->direccion_empresa);
        $templateWord->setValue('representante_legal', $empresa->nombres_representante);
        $templateWord->setValue('especificacion', $empresa->actividad_catalogociiu);
        $templateWord->setValue('actividad', $empresa->grupo_catalogociiu);
        $templateWord->setValue('nombre_sindicato', $expediente->nombre_sindicato);
        $templateWord->setValue('telefono_sindicato', $expediente->telefono_sindicato);
        $templateWord->setValue('direccion_sindicato', $expediente->direccion_sindicato);
        $templateWord->setValue('nombre_delegado',$expediente->delegado);

        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename='FichaSolicitud_DifL_".date('dmy_His').".docx'");
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
        $objWriter->save('php://output');

        unlink($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

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
}
?>
