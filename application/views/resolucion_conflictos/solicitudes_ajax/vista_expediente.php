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
                    <tr>
                        <td>N&uacute;mero de caso:</td>
                        <td class="font-medium">
                            <?= $expediente->numerocaso_expedienteci ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Fecha y Hora de Creaci&oacute;n del expediente:</td>
                        <td class="font-medium">
                            <?= date("d-M-Y g:i:s A", strtotime($expediente->fechacrea_expedienteci)) ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </blockquote>

          <!-- <span class="label label-success" style="font-size: 16px;">Datos del establecimiento</span> !-->

            <span class="label label-success" style="font-size: 16px;">Datos del solicitante</span>
        <blockquote class="m-t-0">
            <table class="table no-border">
                <tbody>
                    <tr>
                        <td>Nombres:</td>
                        <td class="font-medium">
                            <?= $expediente->nombre_personaci ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Apellidos:</td>
                        <td class="font-medium">
                            <?= $expediente->apellido_personaci ?>
                        </td>
                    </tr>
                    <tr>
                        <td>N&uacute;mero de DUI:</td>
                        <td class="font-medium">
                            <?= $expediente->dui_personaci ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Tel&eacute;fono:</td>
                        <td class="font-medium">
                            <?= $expediente->telefono_personaci ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Municipio:</td>
                        <td class="font-medium">
                            <?= $expediente->municipio ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Direci&oacute;n:</td>
                        <td class="font-medium">
                            <?= $expediente->direccion_personaci ?>
                        </td>
                    </tr>

                    <tr>
                      <td>Fecha de nacimiento:</td>
                      <td class="font-medium">
                          <?= $expediente->fnacimiento_personaci ?>
                      </td>
                  </tr>

                  <tr>
                    <td>Sexo:</td>
                    <td class="font-medium">
                        <?= ($expediente->sexo_personaci == "M") ? "Masculino" : "Femenino" ; ?>
                    </td>
                    </tr>

                  <tr>
                      <td>Estudios realizados:</td>
                      <td class="font-medium">
                          <?= $expediente->estudios_personaci ?>
                      </td>
                  </tr>

                  <tr>
                      <td>Nacionalidad:</td>
                      <td class="font-medium">
                          <?= $expediente->nacionalidad_personaci ?>
                      </td>
                  </tr>

                  <tr>
                      <td>Posee discapacidad:</td>
                      <td class="font-medium">
                          <?= ($expediente->discapacidad_personaci) ? "SI" : "NO" ; ?>
                      </td>
                  </tr>

                </tbody>
            </table>
        </blockquote>

        <span class="label label-success" style="font-size: 16px;">Datos del solicitado</span>
        <blockquote class="m-t-0">
            <table class="table no-border">
                <tbody>
                    <tr>
                        <td>N&uacute;mero de Inscripci&oacute;n de empresa:</td>
                        <td class="font-medium">
                            <?= $expediente->numinscripcion_empresa ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Municipio:</td>
                        <td class="font-medium">
                            <?= $empresa->municipio ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Actividad:</td>
                        <td class="font-medium">
                            <?= $empresa->actividad_catalogociiu ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Nombre de empresa:</td>
                        <td class="font-medium">
                            <?= $empresa->nombre_empresa ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Dirección:</td>
                        <td class="font-medium">
                            <?= $empresa->direccion_empresa ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Telefono:</td>
                        <td class="font-medium">
                            <?= $empresa->telefono_empresa ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Representante:</td>
                        <td class="font-medium">
                            <?= $empresa->nombres_representante ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </blockquote>

        <span class="label label-success" style="font-size: 16px;">Datos de la solicitud</span>
        <blockquote class="m-t-0">
            <table class="table no-border">
                <tbody>
                    <tr>
                        <td>Motivo de la solicitud:</td>
                        <td class="font-medium">
                            <?= $expediente->motivo_expedienteci ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Descripción del motivo:</td>
                        <td class="font-medium">
                            <?= $expediente->descripmotivo_expedienteci ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Fecha del conflicto:</td>
                        <td class="font-medium">
                            <?= $expediente->fechaconflicto_personaci ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Ocupación:</td>
                        <td class="font-medium">
                            <?= $expediente->primarios_catalogociuo ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Salario:</td>
                        <td class="font-medium">
                            <?= $expediente->salario_personaci ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Funciones laborales:</td>
                        <td class="font-medium">
                            <?= $expediente->funciones_personaci ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Forma de pago:</td>
                        <td class="font-medium">
                            <?= $expediente->formapago_personaci ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Horario laboral:</td>
                        <td class="font-medium">
                            <?= $expediente->horarios_personaci ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Delegado asignado:</td>
                        <td class="font-medium">
                            <?= $expediente->primer_nombre.' '.$expediente->segundo_nombre.' '.$expediente->primer_apellido.
                            ' '.$expediente->segundo_apellido.' '.(($expediente->apellido_casada) ? $expediente->apellido_casada : ' ') ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Nombres de jefe inmediato:</td>
                        <td class="font-medium">
                            <?= $expediente->nombre_empleador ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Apellidos de jefe inmediato:</td>
                        <td class="font-medium">
                            <?= $expediente->apellido_empleador ?>
                        </td>
                    </tr>

                    <tr>
                        <td>Cargo de jefe inmediato:</td>
                        <td class="font-medium">
                            <?= $expediente->cargo_empleador ?>
                        </td>
                    </tr>

                </tbody>
            </table>
        </blockquote>
    </div>
</div>
