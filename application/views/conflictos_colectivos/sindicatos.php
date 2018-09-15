<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<script type="text/javascript">
function nav(value) {
  var id_expedienteci = $("#id_expedienteci_copia2").val();
  if (value != "") { location.href = value+id_expedienteci; }
  cerrar_mantenimiento();
}

function iniciar(){

    <?php if(tiene_permiso($segmentos=2,$permiso=1)){ ?>
    tablasindicatos();
    <?php }else{ ?>
        $("#cnt_tabla").html("Usted no tiene permiso para este formulario.");
    <?php } ?>
}

var estado_pestana = "";
function cambiar_pestana(tipo){
    estado_pestana = tipo;
    tablasindicatos();
}

function combo_municipio(){

  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/establecimiento/combo_municipio",
    type: "post",
    dataType: "html"
  })
  .done(function(res){
    $('#div_combo_municipio').html(res);
    $(".select2").select2();
  });

}

function open_form(num){
    $(".cnt_form").hide(0);
    $("#cnt_form"+num).show(0);

    if($("#band").val() == "save"){
        $("#btnadd"+num).show(0);
        $("#btnedit"+num).hide(0);
    }else{
        $("#btnadd"+num).hide(0);
        $("#btnedit"+num).show(0);
    }
}

function cerrar_mantenimiento(){
    $("#cnt_tabla").show(0);
    $("#cnt_tabla_sindicatos").show(0);
    $("#cnt_form_main").hide(0);
    $("#cnt_actions").hide(0);
    $("#modal_delegado").modal('hide');
    $("#modal_actas_tipo").modal('hide');
    $("#modal_estado").modal('hide');
    $("#cnt_actions").remove('.card');
    open_form(1);
    tablasindicatos();
}

function objetoAjax(){
    var xmlhttp = false;
    try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) { try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { xmlhttp = false; } }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp = new XMLHttpRequest(); }
    return xmlhttp;
}

function tablasindicatos(){
  var nr_empleado = $("#nr_search").val();
    if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttpB=new XMLHttpRequest();
    }else{// code for IE6, IE5
        xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
    }
    xmlhttpB.onreadystatechange=function(){
        if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
            document.getElementById("cnt_tabla_sindicatos").innerHTML=xmlhttpB.responseText;
            $('[data-toggle="tooltip"]').tooltip();
            $('#myTable').DataTable();
        }
    }
    xmlhttpB.open("GET","<?php echo site_url(); ?>/conflictos_colectivos/sindicato/tabla_sindicatos?nr="+nr_empleado+"&tipo="+estado_pestana,true);
    xmlhttpB.send();
}


function alertFunc() {
    $('[data-toggle="tooltip"]').tooltip()
}

function cambiar_nuevo(){
    /*Inicio Solicitante*/
    $("#id_personaci").val('');
    $("#nr").val($("#nr_search").val()).trigger('change.select2');
    $("#nombres").val('');
    $("#apellidos").val('');
    $("#dui").val('');
    $("#telefono").val('');
    $("#municipio").val('').trigger('change.select2');
    $("#direccion").val('');
    $("#fecha_nacimiento").val('');
    $("#sexo").val('');
    $("#estudios").val('');
    $("#nacionalidad").val('');
    $("#discapacidad").val('');
    $("#posee_representante").val('');
    /*Fin Solicitante*/

    /*Inicio represnetante persona*/
    $("#nombre_representante_persona").val('');
    $("#apellido_representante_persona").val('');
    $("#tipo_representante_persona").val('');
    $("#dui_representante_persona").val('');
    $("#telefono_representante_persona").val('');
    $("#acreditacion_representante_persona").val('');
    /*Fin representante persona*/

    /*Inicio Expediente*/
    combo_ocupacion('');
    combo_delegado('');
    combo_actividad_economica();
    combo_municipio();
    $("#id_empleador").val($("#id_empleador").val()).trigger('change.select2');
    $("#nombres_jefe").val('');
    $("#apellidos_jefe").val('');
    $("#cargo_jefe").val('');
    $("#motivo").val("").trigger('change.select2');
    $("#id_personal").val('');
    $("#establecimiento").val('');
    $("#salario").val('');
    $("#funciones").val('');
    $("#forma_pago").val('');
    $("#horario").val('');
    $("#fecha_conflicto").val('');
    /*Fin expediente*/

    $("#band").val("save");
    $("#band1").val("save");
    $("#band2").val("save");
    $("#band6").val('save');

    $("#ttl_form").addClass("bg-success");
    $("#ttl_form").removeClass("bg-info");

    $("#btnadd").show(0);
    $("#btnedit").hide(0);

    $("#cnt_tabla").hide(0);
    $("#cnt_form_main").show(0);

    $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> Nueva Solicitud");
    combo_establecimiento('');
}



function cambiar_editar(id_personaci,bandera){

  if(bandera == "edit"){

    $.ajax({
      url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/registros_expedientes",
      type: "POST",
      data: {id : id_personaci}
    })
    .done(function(res){
      result = JSON.parse(res)[0];

      $("#id_personaci").val(id_personaci);
      $("#id_expedienteci").val(result.id_expedienteci);
      $("#nr").val($("#nr_search").val()).trigger('change.select2');
      $("#nombres").val(result.nombre_personaci);
      $("#apellidos").val(result.apellido_personaci);
      $("#dui").val(result.dui_personaci);
      $("#telefono").val(result.telefono_personaci);
      $("#municipio").val(result.id_municipio.padStart(5,"00000")).trigger('change.select2');
      $("#direccion").val(result.direccion_personaci);
      $("#fecha_nacimiento").val(result.fnacimiento_personaci);
      $("#estudios").val(result.estudios_personaci);
      $("#nacionalidad").val(result.nacionalidad_personaci);
      if (result.discapacidad_personaci=='1') {
          document.getElementById('si').checked = true;
      }else {
          document.getElementById('no').checked = true;
      }
      if (result.posee_representante=='1') {
        document.getElementById('si_posee').checked =true;
      }else {
        document.getElementById('no_posee').checked =true;
      }
      if (result.sexo_personaci=='M') {
        document.getElementById('masculino').checked =true;
      }else {
        document.getElementById('femenino').checked =true;
      }
      /*Inicio represnetante persona*/
      $("#id_representante_persona").val(result.id_representantepersonaci);
      $("#nombre_representante_persona").val(result.nombre_representantepersonaci);
      $("#apellido_representante_persona").val(result.apellido_representantepersonaci);
      $("#tipo_representante_persona").val(result.tipo_representantepersonaci);
      $("#dui_representante_persona").val(result.dui_representantepersonaci);
      $("#telefono_representante_persona").val(result.tel_representantepersonaci);
      $("#acreditacion_representante_persona").val(result.acreditacion_representantepersonaci);
      /*Fin representante persona*/

      /*Inicio Expediente*/
      combo_ocupacion(result.id_catalogociuo);
      combo_delegado(result.id_personal);
      combo_actividad_economica(result.id_catalogociiu);
      combo_municipio(result.id_municipio1);

      $("#fecha_creacion_exp").val(result.fechacrea_expedienteci);
      $("#id_empleador").val(result.id_empleador);
      $("#id_emplea").val(result.id_empleador);
      $("#nombres_jefe").val(result.nombre_empleador);
      $("#apellidos_jefe").val(result.apellido_empleador);
      $("#cargo_jefe").val(result.cargo_empleador);
      $("#motivo").val(result.motivo_expedienteci).trigger('change.select2');
      $("#salario").val(result.salario_personaci);
      $("#funciones").val(result.funciones_personaci);
      $("#forma_pago").val(result.formapago_personaci);
      $("#horario").val(result.horarios_personaci);
      $("#fecha_conflicto").val(result.fechaconflicto_personaci);
      $("#descripcion_motivo").val(result.descripmotivo_expedienteci);
      combo_establecimiento(result.id_empresaci);
      /*Fin expediente*/

      $("#band").val("edit");
      $("#band1").val("edit");
      $("#band2").val("edit");
      $("#band6").val('edit');
    });

    $("#ttl_form").removeClass("bg-success");
    $("#ttl_form").addClass("bg-info");
    $("#btnadd1").hide(0);
    $("#btnedit1").show(0);
    $("#btnadd2").hide(0);
    $("#btnedit2").show(0);
    $("#btnadd3").hide(0);
    $("#btnedit3").show(0);
    $("#cnt_tabla_sindicatos").hide(0);
    $("#cnt_tabla").hide(0);
    $("#cnt_form_main").show(0);
    $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Editar Expediente");
  }
}

function volver(num) {
  open_form(num);
  $("#band"+num).val("edit")
}

  function editar_solicitud(){ $("#band").val("edit"); enviarDatos(); }

</script>

<input type="hidden" id="address" name="">
<div class="page-wrapper">
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- TITULO de la página de sección -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="align-self-center" align="center">
                <h3 class="text-themecolor m-b-0 m-t-0">Solicitud por diferencia laboral</h3>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Fin TITULO de la página de sección -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Inicio del CUERPO DE LA SECCIÓN -->
        <!-- ============================================================== -->
        <div class="row" <?php if($navegatorless){ echo "style='margin-right: 80px;'"; } ?>>
            <!-- ============================================================== -->
            <!-- Inicio del FORMULARIO INFORMACIÓN DEL SOLICITANTE -->
            <!-- ============================================================== -->
            <div class="col-lg-1"></div>
            <div class="col-lg-10" id="cnt_form_main" style="display: none;">
                <div class="card">
                    <div class="card-header bg-success2" id="ttl_form">
                        <div class="card-actions text-white">
                            <a style="font-size: 16px;" onclick="cerrar_mantenimiento();"><i class="mdi mdi-window-close"></i></a>
                        </div>
                        <h4 class="card-title m-b-0 text-white">Listado de Solicitudes</h4>
                    </div>
                    <div class="card-body b-t">

                      <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                        <div id="cnt_form1" class="cnt_form">
                          <h3 class="box-title" style="margin: 0px;">
                              <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 1</button>&emsp;
                              Información del solicitante
                            </h3><hr class="m-t-0 m-b-30">
                            <input type="hidden" id="band" name="band" value="save">
                            <input type="hidden" id="band1" name="band1" value="save">
                            <input type="hidden" id="estado" name="estado" value="1">
                            <input type="hidden" id="id_personaci" name="id_personaci" value="">
                            <input type="hidden" id="id_empleador" name="id_empleador" value="">


                            <span class="etiqueta">Expediente</span>
                            <blockquote class="m-t-0">

                            <div class="row">
                              <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                  <h5>Nombre del sindicato: <span class="text-danger">*</span></h5>
                                  <input type="text" id="nombre_sindicato" name="nombre_sindicato" class="form-control" placeholder="Nombres del sindicato" required="">
                                  <div class="help-block"></div>
                              </div>

                                <div class="col-lg-12 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_municipio"></div>
                            </div>

                            <div class="row">
                              <div class="form-group col-lg-12" style="height: 83px;">
                                  <h5>Dirección del sindicato:</h5>
                                  <textarea type="text" id="direccion_sindicato" name="direccion_sindicato" class="form-control" placeholder="Dirección completa"></textarea>
                                  <div class="help-block"></div>
                              </div>
                            </div>

                              <div class="row">
                                <div class="form-group col-lg-4" style="height: 83px;">
                                    <h5>Teléfono del sindicato: </h5>
                                    <input data-mask="9999-9999" type="text" id="telefono_sindicato" name="telefono_sindicato" class="form-control" placeholder="Número de Telefóno">
                                    <div class="help-block"></div>
                                </div>

                              <div class="form-group col-lg-4" style="height: 83px;">
                                  <h5>Total de afiliados:</h5>
                                  <input type="number" id="nacionalidad" name="nacionalidad" class="form-control" placeholder="Nacionalidad">
                                  <div class="help-block"></div>
                              </div>
                        </div>
                            </blockquote>

                          <div align="right" id="btnadd1">
                            <button type="reset" class="btn waves-effect waves-light btn-success">
                              <i class="mdi mdi-recycle"></i> Limpiar</button>
                            <button type="submit" class="btn waves-effect waves-light btn-success2">
                              Siguiente <i class="mdi mdi-chevron-right"></i>
                            </button>
                          </div>
                          <div align="right" id="btnedit1" style="display: none;">
                            <button type="reset" class="btn waves-effect waves-light btn-success">
                              <i class="mdi mdi-recycle"></i> Limpiar</button>
                            <button type="submit" class="btn waves-effect waves-light btn-info">
                              Siguiente <i class="mdi mdi-chevron-right"></i>
                            </button>
                          </div>
                        </div>
                        <?php echo form_close(); ?>
                        <!-- ============================================================== -->
                        <!-- Fin del FORMULARIO INFORMACIÓN DEL SOLICITANTE -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Inicio del FORMULARIO REPRESENTANTE DEL SOLICITANTE -->
                        <!-- ============================================================== -->
                        <?php echo form_open('', array('id' => 'formajax8', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                            <div id="cnt_form3" class="cnt_form" style="display: block;">
                            <h3 class="box-title" style="margin: 0px;">
                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 2</button>&emsp;
                                Representante de la persona
                              </h3><hr class="m-t-0 m-b-30">
                              <input type="hidden" id="band6" name="band6" value="save">
                              <input type="hidden" id="id_representante_persona" name="id_representante_persona" value="">


                              <span class="etiqueta">Expediente</span>
                              <blockquote class="m-t-0">

                                <div class="row">
                                  <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                      <h5>Nombres del representante: <span class="text-danger">*</span></h5>
                                      <div class="controls">
                                          <input type="text" id="nombre_representante_persona" name="nombre_representante_persona" class="form-control" placeholder="Nombres del representante" required>
                                      </div>
                                  </div>

                                  <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                      <h5>Apellidos del representante: <span class="text-danger">*</span></h5>
                                      <div class="controls">
                                          <input type="text" id="apellido_representante_persona" name="apellido_representante_persona" class="form-control" placeholder="Apellidos del representante" required>
                                      </div>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                      <h5>DUI de representante: <span class="text-danger">*</span></h5>
                                      <div class="controls">
                                          <input type="text" id="dui_representante_persona" name="dui_representante_persona" class="form-control" placeholder="Dui del representante" required>
                                      </div>
                                  </div>

                                  <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                      <h5>Tel&eacute;fono representante: <span class="text-danger">*</span></h5>
                                      <div class="controls">
                                          <input type="text" id="telefono_representante_persona" name="telefono_representante_persona" class="form-control" placeholder="telefono del representante" required>
                                      </div>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="form-group col-lg-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                      <h5>Tipo de representaci&oacute;n: <span class="text-danger">*</span></h5>
                                      <div class="controls">
                                          <input type="text" id="tipo_representante_persona" name="tipo_representante_persona" class="form-control" placeholder="Tipo de representante" required>
                                      </div>
                                  </div>

                                  <div class="form-group col-lg-8 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                      <h5>Acreditaci&oacute;n: <span class="text-danger">*</span></h5>
                                      <div class="controls">
                                          <textarea type="text" id="acreditacion_representante_persona" name="acreditacion_representante_persona" class="form-control" required></textarea>
                                      </div>
                                  </div>
                                </div>
                            </blockquote>

                            <div align="right" id="btnadd3">
                              <button type="reset" class="btn waves-effect waves-light btn-success">
                                <i class="mdi mdi-recycle"></i> Limpiar</button>
                              <button type="submit" class="btn waves-effect waves-light btn-success2">
                                Siguiente <i class="mdi mdi-chevron-right"></i>
                              </button>
                            </div>
                            <div align="right" id="btnedit3" style="display: none;">
                              <button type="reset" class="btn waves-effect waves-light btn-success">
                                <i class="mdi mdi-recycle"></i> Limpiar</button>
                              <button type="submit" class="btn waves-effect waves-light btn-info">
                                Siguiente <i class="mdi mdi-chevron-right"></i>
                              </button>
                            </div>
                          </div>
                          <?php echo form_close(); ?>
                          <!-- ============================================================== -->
                          <!-- FIN del FORMULARIO REPRESENTANTE DEL SOLICITANTE -->
                          <!-- ============================================================== -->


                        <!-- ============================================================== -->
                        <!-- Inicio del FORMULARIO INFORMACIÓN DE LA SOLICITUD -->
                        <!-- ============================================================== -->
                        <?php echo form_open('', array('id' => 'formajax2', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                          <div id="cnt_form2" class="cnt_form" style="display: block;">
                            <h3 class="box-title" style="margin: 0px;">
                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 3</button>&emsp;
                                Información de la solicitud
                                <input type="hidden" id="band2" name="band2" value="save">
                                <input type="hidden" id="id_emplea" name="id_emplea" value="">
                                <input type="hidden" id="id_expedienteci" name="id_expedienteci" value="">
                                <input type="hidden" id="fecha_creacion_exp" name="fecha_creacion_exp" value="">

                              </h3><hr class="m-t-0 m-b-30">
                              <span class="etiqueta">Expediente</span>
                              <blockquote class="m-t-0">

                                <div class="row">
                                  <div class="col-lg-8 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_establecimiento"></div>

                                  <div class="form-group col-lg-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                      <h5>Fecha del conflicto: <span class="text-danger">*</span></h5>
                                      <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="fecha_conflicto" name="fecha_conflicto" placeholder="dd/mm/yyyy" readonly="">
                                      <div class="help-block"></div>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                                      <h5>Motivo de la solicitud: <span class="text-danger">*</span></h5>
                                      <div class="controls">
                                        <select id="motivo" name="motivo" class="custom-select col-4" onchange="" required>
                                          <option value="">[Seleccione]</option>
                                          <option value="Indemnización">Indemnización</option>
                                          <option value="Inasistencia Laboral">Inasistencia Laboral</option>
                                          <option value="Despido Injustificado">Despido Injustificado</option>
                                          <option value="Exige indeminización">Exige indeminización</option>
                                          <option value="Insubordinación">Insubordinación</option>
                                        </select>
                                      </div>
                                  </div>

                                  <div class="form-group col-lg-8" style="height: 83px;">
                                      <h5>Descripción del motivo:<span class="text-danger">*</h5>
                                      <textarea type="text" id="descripcion_motivo" name="descripcion_motivo" class="form-control" placeholder="Descipción del motivo"></textarea>
                                      <div class="help-block"></div>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="col-lg-8 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_ocupacion"></div>

                                  <div class="form-group col-lg-4" style="height: 83px;">
                                      <h5>Salario($):<span class="text-danger">*</h5>
                                      <input type="number" id="salario" name="salario" class="form-control" placeholder="Salario" step="0.01">
                                      <div class="help-block"></div>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="form-group col-lg-8" style="height: 83px;">
                                      <h5>Funciones:<span class="text-danger">*</h5>
                                      <textarea type="text" id="funciones" name="funciones" class="form-control" placeholder="Funciones laborales" required=""></textarea>
                                      <div class="help-block"></div>
                                  </div>

                                  <div class="form-group col-lg-4" style="height: 83px;">
                                      <h5>Forma de pago:<span class="text-danger">*</h5>
                                      <input type="text" id="forma_pago" name="forma_pago" class="form-control" placeholder="Forma de pago">
                                      <div class="help-block"></div>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="form-group col-lg-8" style="height: 83px;">
                                      <h5>Horario laboral:<span class="text-danger">*</h5>
                                      <textarea type="text" id="horario" name="horario" class="form-control" placeholder="Horario laboral"></textarea>
                                      <div class="help-block"></div>
                                  </div>

                                  <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_delegado"></div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Nombres de jefe inmediato: </h5>
                                        <input type="text" id="nombres_jefe" name="nombres_jefe" class="form-control" placeholder="Nombres de jefe inmediato" required="">
                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Apellidos de jefe inmediato:</h5>
                                        <input type="text" id="apellidos_jefe" name="apellidos_jefe" class="form-control" placeholder="Apellidos de jefe inmediato" required="">
                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Cargo de jefe inmediato: </h5>
                                        <input type="text" id="cargo_jefe" name="cargo_jefe" class="form-control" placeholder="Cargo de jefe inmediato" required="">
                                        <div class="help-block"></div>
                                    </div>
                              </div>
                            </blockquote>
                            <div align="right" id="btnadd2">
                              <button type="reset" class="btn waves-effect waves-light btn-success">
                                <i class="mdi mdi-recycle"></i> Limpiar
                              </button>
                              <button type="submit" class="btn waves-effect waves-light btn-success2">Finalizar
                                <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                            <div align="right" id="btnedit2" style="display: none;">
                              <button type="reset" class="btn waves-effect waves-light btn-success">
                                <i class="mdi mdi-recycle"></i> Limpiar</button>
                              <button type="submit" class="btn waves-effect waves-light btn-info">Finalizar
                                <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                          </div>
                          <?php echo form_close(); ?>
                        <!-- ============================================================== -->
                        <!-- Fin del FORMULARIO INFORMACIÓN DE LA SOLICITUD -->
                        <!-- ============================================================== -->
                    </div>
                </div>
            </div>
            <div class="col-lg-12" id="cnt_actions" style="display:none;"></div>
            <div class="col-lg-1"></div>
            <div class="col-lg-12" id="cnt_tabla">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-b-0">Listado de Solicitudes</h4>
                    </div>
                    <div class="card-body b-t" style="padding-top: 7px;">
                    <div>
                        <div class="pull-left">
                            <div class="form-group" style="width: 400px;">
                                <select id="nr_search" name="nr_search" class="select2" style="width: 100%" required="" onchange="tablasindicatos();">
                                    <option value="">[Todos los empleados]</option>
                                <?php
                                    $otro_empleado = $this->db->query("SELECT e.id_empleado, e.nr, UPPER(CONCAT_WS(' ', e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada)) AS nombre_completo FROM sir_empleado AS e WHERE e.id_estado = '00001' ORDER BY e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada");
                                    if($otro_empleado->num_rows() > 0){
                                        foreach ($otro_empleado->result() as $fila) {
                                            if($nr_usuario == $fila->nr){
                                               echo '<option class="m-l-50" value="'.$fila->nr.'" selected>'.preg_replace ('/[ ]+/', ' ', $fila->nombre_completo.' - '.$fila->nr).'</option>';
                                            }else{
                                                echo '<option class="m-l-50" value="'.$fila->nr.'">'.preg_replace ('/[ ]+/', ' ', $fila->nombre_completo.' - '.$fila->nr).'</option>';
                                            }
                                        }
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="pull-right">
                            <?php if(tiene_permiso($segmentos=2,$permiso=2)){ ?>
                            <button type="button" onclick="cambiar_nuevo();" class="btn waves-effect waves-light btn-success2" data-toggle="tooltip" title="Clic para agregar un nuevo registro"><span class="mdi mdi-plus"></span> Nuevo registro</button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row" style="width: 100%"></div>
                    <div class="row col-lg-12">
                        <ul class="nav nav-tabs customtab2 <?php if($navegatorless){ echo "pull-left"; } ?>" role="tablist" style='width: 100%;'>
                            <li class="nav-item <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <a class="nav-link active" onclick="cambiar_pestana('');" data-toggle="tab" href="#">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">Todas</span></a>
                            </li>
                            <li class="nav-item <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <a class="nav-link" onclick="cambiar_pestana('1');" data-toggle="tab" href="#">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">Esperando Audiencia</span></a>
                            </li>
                            <li class="nav-item <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <a class="nav-link" onclick="cambiar_pestana('2');" data-toggle="tab" href="#">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">Con Resultado</span></a>
                            </li>
                            <li class="nav-item <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <a class="nav-link" onclick="cambiar_pestana('3');" data-toggle="tab" href="#">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">Archivado</span></a>
                            </li>
                            <li class="nav-item <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <a class="nav-link" onclick="cambiar_pestana('4');" data-toggle="tab" href="#">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">Inhabilitado</span></a>
                            </li>
                        </ul>
                    </div>
                    <div id="cnt_tabla_sindicatos"></div>
                    </div>
                </div>
            </div>

        </div>
        <!-- ============================================================== -->
        <!-- Fin CUERPO DE LA SECCIÓN -->
        <!-- ============================================================== -->
    </div>
</div>
<!-- ============================================================== -->
<!-- Fin de DIV de inicio (ENVOLTURA) -->
<!-- ============================================================== -->

<div style="display:none;">
    <button  id="submit_ubi" name="submit_ubi" type="button"  >clicks</button>
</div>

<script>

$(function(){
    $("#formajax").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax"));
        formData.append("dato", "valor");

        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/gestionar_solicitudes",
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
            if(res == "fracaso"){
              swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }else{
              if (formData.get('posee_representante') == 1) {
                open_form(3);
              }else {
                open_form(2);
              }
              $("#id_personaci").val(res);
              $("#id_persona").val(res);
              $("#band1").val( $("#band").val() );
              $("#band2").val( $("#band").val() );
              if($("#band").val() == "delete"){
                swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
              }
            }
        });

    });
});

$(function(){
    $("#formajax8").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax8"));
        formData.append("id_personaci", $('#id_personaci').val());
        $.ajax({
          url: "<?php echo site_url(); ?>/resolucion_conflictos/representante_persona/gestionar_representantes",
          type: "post",
          dataType: "html",
          data: formData,
          cache: false,
          contentType: false,
          processData: false
        })
        .done(function(res){
            if(res == "fracaso"){
              swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }else{
              open_form(2);
            }
        });

    });
});

$(function(){
    $("#formajax2").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax2"));
        formData.append("id_personaci", $('#id_personaci').val());
        $.ajax({
          url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/gestionar_expediente",
          type: "post",
          dataType: "html",
          data: formData,
          cache: false,
          contentType: false,
          processData: false
        })
        .done(function(res){
            if(res == "fracaso"){
              swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }else{
              cerrar_mantenimiento();
              if($("#band2").val() == "save"){
                  swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
              }else if($("#band2").val() == "edit"){
                  swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
              }else{
                  swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
              }
              tablasindicatos();
            }
        });

    });
});

$(function(){
    $("#formajax3").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax3"));

        $.ajax({
          url: "<?php echo site_url(); ?>/resolucion_conflictos/establecimiento/gestionar_establecimiento",
          type: "post",
          dataType: "html",
          data: formData,
          cache: false,
          contentType: false,
          processData: false
        })
        .done(function(res){
            if(res == "fracaso"){
              swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }else{
              swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });

              var data = {
                  id: res,
                  text: $("#nombre_establecimiento").val()
              };

              var newOption = new Option(data.text, data.id, false, false);
              $('#establecimiento').append(newOption).trigger('change');
              $('#establecimiento').val(data.id).trigger("change");
              $('#modal_establecimiento').modal('toggle');
            }
        });

    });
});

/*$(function(){
    $(document).ready(function(){
    	var date = new Date(); var currentMonth = date.getMonth(); var currentDate = date.getDate(); var currentYear = date.getFullYear();
        $('#fecha_nacimiento').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true, endDate: moment().format("DD-MM-YYYY")}).datepicker("setDate", new Date());
        $('#fecha_conflicto').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true, endDate: moment().format("DD-MM-YYYY")}).datepicker("setDate", new Date());
    });
  });*/
</script>