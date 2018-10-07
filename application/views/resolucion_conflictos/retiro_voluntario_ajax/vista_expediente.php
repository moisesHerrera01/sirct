<?php
    $expediente = $expediente->result()[0];
    // Características del navegador
    $ua=$this->config->item("navegator");
    $navegatorless = false;
    if(floatval($ua['version']) < $this->config->item("last_version")){
        $navegatorless = true;
    }
?>

<div class="card">
    <div class="card-header bg-info" id="ttl_form">
        <div class="card-actions text-white">
            <a style="font-size: 16px;" onclick="cerrar_mantenimiento();">
                <i class="mdi mdi-window-close"></i>
            </a>
        </div>
        <h4 class="card-title m-b-0 text-white">Informaci&oacute;n de Expediente</h4>
    </div>

    <div class="card-body b-t" style="padding-top: 7px;">
        <blockquote class="m-t-0">
            <table class="table no-border">
                <tbody>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            N&uacute;mero de caso:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $expediente->numerocaso_expedienteci ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Fecha y Hora de Creaci&oacute;n del expediente:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= date("d-M-Y g:i:s A", strtotime($expediente->fechacrea_expedienteci)) ?>
                            </h5>
                        </div>
                    </div>
                </tbody>
            </table>
        </blockquote>

        <!--Datos del solicitante !-->

        <span class="label label-success" style="font-size: 16px;">Datos del solicitante</span>
        <blockquote class="m-t-0">
            <table class="table no-border">
                <tbody>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Nombres:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $expediente->nombre_personaci ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Apellidos:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $expediente->apellido_personaci ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            N&uacute;mero de DUI:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $expediente->dui_personaci ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Tel&eacute;fono:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $expediente->telefono_personaci ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Tel&eacute;fono:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $expediente->municipio ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Direci&oacute;n:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $expediente->direccion_personaci ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Fecha de nacimiento:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $expediente->fnacimiento_personaci ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Sexo:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= ($expediente->sexo_personaci == "M") ? "Masculino" : "Femenino" ; ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Estudios realizados:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $expediente->estudios_personaci ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Nacionalidad:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $expediente->nacionalidad ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Posee discapacidad:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= ($expediente->discapacidad_personaci) ? "SI" : "NO" ; ?>
                            </h5>
                        </div>
                    </div>
                </tbody>
            </table>
        </blockquote>

        <!--Datos del solicitado !-->
        <span class="label label-success" style="font-size: 16px;">Datos del solicitado</span>
        <blockquote class="m-t-0">
            <table class="table no-border">
                <tbody>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            N&uacute;mero de Inscripci&oacute;n de empresa:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $expediente->numinscripcion_empresa ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Municipio:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $empresa->municipio ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Actividad:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $empresa->actividad_catalogociiu ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Nombre de empresa:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $empresa->nombre_empresa ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Dirección:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $empresa->direccion_empresa ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Telefono:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $empresa->telefono_empresa ?>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Representante:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $empresa->nombres_representante ?>
                            </h5>
                        </div>
                    </div>
                </tbody>
            </table>
        </blockquote>

        <span class="label label-success" style="font-size: 16px;">Datos de la solicitud</span>
        <blockquote class="m-t-0">
            <table class="table no-border">
                <tbody>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Tipo de Solicitud:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Renuncia Voluntaria
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                            Delegado asignado:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                            <h5>
                                <?= $expediente->primer_nombre.' '.$expediente->segundo_nombre.' '.
                          $expediente->primer_apellido.' '.$expediente->segundo_apellido.' '.
                          (($expediente->apellido_casada) ? $expediente->apellido_casada : ' ') ?>
                            </h5>
                        </div>
                    </div>
                </tbody>
            </table>
        </blockquote>
    </div>
</div>
