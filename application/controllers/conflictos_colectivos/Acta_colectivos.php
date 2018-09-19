<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acta_colectivos extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model( array('expediente_cc_model','expedientes_model','directivos_model','audiencias_model', 'persona_cc_model'));
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

    public function generar_acta_solicitud($id_expedienteci) {
        $audiencias = $this->audiencias_model->obtener_audiencias($id_expedienteci);
        $primera= $audiencias->result()[0];
        $segunda= $audiencias->result()[1];
        $expediente = $this->expediente_cc_model->expedientes_diferencia_laboral( $id_expedienteci )->result()[0];
        $empresa = $this->expedientes_model->obtener_municipio($expediente->id_empresaci);
        $directivos = $this->directivos_model->obtener_directivos_sindicato($expediente->id_sindicato);
        $concat_directivos='';
        foreach ($directivos->result() as $d) {
          $concat_directivos.= $d->nombre_directivo.', identificándose por medio de su respectivo Documento Único de Identidad número '.
          convertir_dui($d->dui_directivo).', actuando en su calidad de '.$d->tipo_directivo.', ';
        }

        $this->load->library("phpword");

        $PHPWord = new PHPWord();

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/templateDocSRCCT/ActaSolicitudDifL.docx');
        $templateWord->setValue('hora_expediente', hora(date('G', strtotime($expediente->fechacrea_expedienteci))));
        $templateWord->setValue('minuto_expediente', minuto(date('i', strtotime($expediente->fechacrea_expedienteci))));
        $templateWord->setValue('dia_expediente', dia(date('d', strtotime($expediente->fechacrea_expedienteci))));
        $templateWord->setValue('mes_expediente', strtoupper(mes(date('m', strtotime($expediente->fechacrea_expedienteci)))));
        $templateWord->setValue('anio_expediente', anio(date('Y', strtotime($expediente->fechacrea_expedienteci))));
        $templateWord->setValue('directivos', $concat_directivos);

        $templateWord->setValue('hora_audiencia', hora(date('G', strtotime($primera->hora_fechasaudienciasci))));
        $templateWord->setValue('minuto_audiencia', minuto(date('i', strtotime($primera->hora_fechasaudienciasci))));
        $templateWord->setValue('dia_audiencia', dia(date('d', strtotime($primera->fecha_fechasaudienciasci))));
        $templateWord->setValue('mes_audiencia', strtoupper(mes(date('m', strtotime($primera->fecha_fechasaudienciasci)))));
        $templateWord->setValue('hora_audiencia2', hora(date('G', strtotime($segunda->hora_fechasaudienciasci))));
        $templateWord->setValue('minuto_audiencia2', minuto(date('i', strtotime($segunda->hora_fechasaudienciasci))));
        $templateWord->setValue('dia_audiencia2', dia(date('d', strtotime($segunda->fecha_fechasaudienciasci))));
        $templateWord->setValue('mes_audiencia2', strtoupper(mes(date('m', strtotime($segunda->fecha_fechasaudienciasci)))));

        $templateWord->setValue('no_expediente', $expediente->numerocaso_expedienteci);
        $templateWord->setValue('direccion_empresa', $empresa->direccion_empresa);
        $templateWord->setValue('representante_legal', $empresa->nombres_representante);
        $templateWord->setValue('nombre_empresa', $empresa->nombre_empresa);
        $templateWord->setValue('nombre_sindicato', $expediente->nombre_sindicato);
        $templateWord->setValue('direccion_sindicato', $expediente->direccion_sindicato);
        $templateWord->setValue('nombre_delegado',$expediente->delegado);
        $templateWord->setValue('motivo',$expediente->motivo_expedienteci);

        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename='ActaSolicitudDifL_".date('dmy_His').".docx'");
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord2, 'Word2007');
        $objWriter->save('php://output');

        unlink($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

    }

    public function generar_ficha_indemnizacion($id_expedienteci) {
        $expediente = $this->expediente_cc_model->obtener_expediente_indemnizacion( $id_expedienteci )->result()[0];
        $estadisticas = $this->persona_cc_model->obtener_estadisticas_ficha( $id_expedienteci )->result()[0];

        $this->load->library("phpword");

        $PHPWord = new PHPWord();

        $templateWord = $PHPWord->loadTemplate($_SERVER['DOCUMENT_ROOT'].'/sirct/files/templates/templateDocSRCCT/FichaSolicitud_SRCCT.docx');
        $templateWord->setValue('no_expediente', $expediente->numerocaso_expedienteci);
        $templateWord->setValue('fecha_actual', date('d/m/Y'));
        $templateWord->setValue('direccion_empresa', $expediente->direccion_empresa);
        $templateWord->setValue('representante_legal', $expediente->nombres_representante);
        $templateWord->setValue('especificacion', $expediente->actividad_catalogociiu);
        $templateWord->setValue('actividad', $expediente->grupo_catalogociiu);
        $templateWord->setValue('nombre_solicitante', $expediente->nombre_solicitante);
        $templateWord->setValue('telefono_solicitante', $expediente->telefono_solicitante);
        $templateWord->setValue('direccion_solicitante', $expediente->direccion_solicitante);
        $templateWord->setValue('nombre_delegado',$expediente->delegado);
        $templateWord->setValue('num_hombres',$estadisticas->hombres);
        $templateWord->setValue('num_mujeres',$estadisticas->mujeres);
        $templateWord->setValue('num_menores',$estadisticas->menores);
        $templateWord->setValue('num_discapacitados',$estadisticas->discapacitados);
        $templateWord->setValue('salario_solicitante',$expediente->salario_solicitante);
        $templateWord->setValue('forma_pago',$expediente->formapago_solicitante);
        $templateWord->setValue('cargo_solicitante',$expediente->funciones_solicitante);
        $templateWord->setValue('horario_solicitante',$expediente->horarios_solicitante);
        $templateWord->setValue('fecha_conflicto',$expediente->fechaconflicto_personaci);
        $templateWord->setValue('persona_conflicto',$expediente->nombre_personaci .' '. $expediente->apellido_personaci);


        $nombreWord = $this->random();

        $templateWord->saveAs($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        $phpWord2 = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/sirct/files/generate/'.$nombreWord.'.docx');

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename='FichaSolicitud_colectivos_".date('dmy_His').".docx'");
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
