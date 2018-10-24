<?php
    $expediente = $expediente->row();
    $personaci = $personaci->row();
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
        <h4 class="card-title m-b-0 text-white">Información de  Expediente</h4>
    </div>

    <div class="card-body b-t" style="padding-top: 7px;">
        <blockquote class="m-t-0">
            <table class="table no-border">
                <tbody>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Número de caso:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $expediente->numerocaso_expedienteci ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Fecha y hora de creación del expediente:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= date("d-M-Y g:i:s A", strtotime($expediente->fechacrea_expedienteci)) ?></h5>
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
                      Nombre del establecimiento:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $expediente->nombre_empresa ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Representante:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5>
                          	<?php if($expediente->tipo_representante == '1'){
                          		echo $expediente->nombres_representante." <small>(Legal)</small><br>";
                          	}else{
                          		echo $expediente->nombres_representante." <small>(Apoderado)</small><br>";
                          	} ?>
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
                      Nombres :
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $personaci->nombre_personaci ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Apellidos :
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $personaci->apellido_personaci ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Teléfono :
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $personaci->telefono_personaci ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Dirección:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $personaci->direccion_personaci ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Municipio:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $personaci->municipio ?></h5>
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
                          <h5><?php if($expediente->motivo_expedienteci == '1'){
                              echo "Despido de hecho o injustificado";
                            }else{
                              echo "Diferencia laboral";
                            } ?>
                          </h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Descripción del motivo:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $expediente->descripmotivo_expedienteci ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Delegado asignado:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?php echo implode(" ", array($expediente->primer_nombre, $expediente->segundo_nombre, $expediente->tercer_nombre, $expediente->primer_apellido, $expediente->segundo_apellido, $expediente->apellido_casada)); ?></h5>
                    </div>
                  </div>
                </tbody>
            </table>
        </blockquote>
    </div>
</div>
