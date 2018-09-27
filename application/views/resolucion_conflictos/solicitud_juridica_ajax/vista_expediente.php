<?php
    $expediente = $expediente->result()[0];
    $personaci = $personaci->result()[0];
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
                      Representante/Delegados:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?php foreach ($representantes->result() as $fila2){ 
                          	echo $fila2->nombres_representante;
                          	if($fila2->tipo_representante == '1'){
                          		echo " <small>(Legal)</small><br>";
                          	}else{
                          		echo " <small>(Delegado)</small><br>";
                          	}
                          }?></h5>
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
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Ocupación:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $personaci->primarios_catalogociuo ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Salario:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $personaci->salario_personaci ?></h5>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-lg-5" style="height: 20px;">
                      Horario laboral:
                    </div>
                    <div class="form-group col-lg-5" style="height: 20px;">
                          <h5><?= $personaci->horarios_personaci ?></h5>
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
                          <h5><?= $expediente->motivo_expedienteci ?></h5>
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
                          <h5><?php echo implode(" ", array($fila->primer_nombre, $fila->segundo_nombre, $fila->tercer_nombre, $fila->primer_apellido, $fila->segundo_apellido, $fila->apellido_casada)); ?></h5>
                    </div>
                  </div>
                </tbody>
            </table>
        </blockquote>
    </div>
</div>
