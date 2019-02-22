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
        <h4 class="card-title m-b-0 text-white">Informaci&oacute;n de  Expediente</h4>
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
                          <h5><?= $expediente->numerocaso_expedienteci ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Fecha y Hora de Creaci&oacute;n del expediente:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= date("d-M-Y g:i:s A", strtotime($expediente->fechacrea_expedienteci)) ?></h5>
                    </div>
                  </div>
                </tbody>
            </table>
        </blockquote>

          <!--Datos del solicitante !-->

            <span class="label label-success" style="font-size: 16px;">Datos de la persona solicitante</span>
        <blockquote class="m-t-0">
            <table class="table no-border">
                <tbody>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Nombre del sindicato:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $expediente->nombre_sindicato ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Municipio del sindicato:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $expediente->municipio ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Dirección del sindicato:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $expediente->direccion_sindicato ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Tel&eacute;fono del sindicato:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $expediente->telefono_sindicato ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Total de afiliados:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $expediente->totalafiliados_sindicato ?></h5>
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
                          <h5><?= $empresa->numinscripcion_empresa ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Municipio:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $empresa->municipio ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Actividad:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $empresa->actividad_catalogociiu ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Nombre de empresa:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $empresa->nombre_empresa ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Dirección:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $empresa->direccion_empresa ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Telefono:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $empresa->telefono_empresa ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Persona representante:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $empresa->nombres_representante ?></h5>
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
                      Motivo de la solicitud:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $expediente->nombre_motivo ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Descripción del motivo:
                    </div>
                    <div class="form-group col-lg-5">
                          <h5><?= $expediente->descripmotivo_expedienteci ?></h5>
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Persona delegada asignada:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $expediente->nombre_delegado_actual ?></h5>
                    </div>
                  </div>
                </tbody>
            </table>
        </blockquote>
    </div>
</div>
