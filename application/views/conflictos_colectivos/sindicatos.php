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

function resolucion(id_expedienteci) {
  $.ajax({
    url: "<?php echo site_url(); ?>/conflictos_colectivos/diferencias_laborales/resolucion_expediente",
    type: "post",
    dataType: "html",
    data: {id : id_expedienteci}
  })
  .done(function(res){
    $('#cnt_modal_acciones').html(res);
    $('#modal_resolucion').modal('show');
  });
}

function gestionar_directivos(id_sindicato) {
    $("#btnadd2").hide(0);
    $("#paso2").hide(0);
    $("#btnedit5").show(0);
    $("#cnt_tabla").hide(0);
    $("#cnt_tabla_sindicatos").hide(0);
    $("#cnt_form_main").show(0);
    $("#id_sindicato").val(id_sindicato);
    tabla_directivos();
}

function audiencias(id_expedienteci) {
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/audiencias/programar_audiencias",
    type: "post",
    dataType: "html",
    data: {id : id_expedienteci}
  })
  .done(function(res){
    console.log(res)
    $('#cnt_actions').html(res);
    $("#cnt_actions").show(0);
    $("#cnt_tabla").hide(0);
    $("#cnt_tabla_solicitudes").hide(0);
    $("#cnt_form_main").hide(0);
    tabla_audiencias(id_expedienteci);
  });
}

function visualizar(id_expedienteci,id_empresa) {
  $.ajax({
    url: "<?php echo site_url(); ?>/conflictos_colectivos/diferencias_laborales/ver_expediente",
    type: "post",
    dataType: "html",
    data: {id_e : id_expedienteci, id_p : id_empresa}
  })
  .done(function(res){
    $('#cnt_actions').html(res);
    $("#cnt_actions").show(0);
    $("#cnt_tabla").hide(0);
    $("#cnt_tabla_sindicatos").hide(0);
    $("#cnt_form_main").hide(0);
  });
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

function combo_actividad_economica(){

  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/establecimiento/combo_actividad_economica",
    type: "post",
    dataType: "html"
  })
  .done(function(res){
    $('#div_combo_actividad_economica').html(res);
    $(".select2").select2();
  });

}

function combo_delegado(seleccion){

  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/combo_delegado",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_delegado').html(res);
    $(".select2").select2();
  });
}

function combo_establecimiento(seleccion){
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/combo_establecimiento",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_establecimiento').html(res);
    $(".est").select2({
      'minimumInputLength': 3,
      'language': {
        noResults: function () {
          return '<a href="javascript:;" data-toggle="modal" data-target="#modal_establecimiento" title="Agregar nuevos establecimientos" onClick="cerrar_combo_establecimiento()">Agregar uno nuevo</a>';
        }
      },
      'escapeMarkup': function (markup) {
        return markup;
      }
    });
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
    $("#paso2").show(0);
    $("#btnadd2").show(0);
    $("#btnedit5").hide(0);
    $("#cnt_tabla").show(0);
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
            //$('[data-toggle="tooltip"]').tooltip();
            $('#myTable').DataTable();
        }
    }
    xmlhttpB.open("GET","<?php echo site_url(); ?>/conflictos_colectivos/sindicato/tabla_sindicatos?nr="+nr_empleado+"&tipo="+estado_pestana,true);
    xmlhttpB.send();
}

function tabla_audiencias(id_expedienteci){
  //alert(id_expedienteci);
    if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttpB=new XMLHttpRequest();
    }else{// code for IE6, IE5
        xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
    }
    xmlhttpB.onreadystatechange=function(){
        if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
            document.getElementById("cnt_tabla_audiencias").innerHTML=xmlhttpB.responseText;
            $('[data-toggle="tooltip"]').tooltip();
            $('#myTable2').DataTable();
        }
    }
    xmlhttpB.open("GET","<?php echo site_url(); ?>/resolucion_conflictos/audiencias/tabla_audiencias?id_expedienteci="+id_expedienteci,true);
    xmlhttpB.send();
}

  function tabla_directivos(){
      open_form(3);
      var id_sindicato = $("#id_sindicato").val();
      if(window.XMLHttpRequest){ xmlhttpB=new XMLHttpRequest();
      }else{ xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB"); }
      xmlhttpB.onreadystatechange=function(){
          if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
              document.getElementById("cnt_tabla_directivos").innerHTML=xmlhttpB.responseText;
              $('[data-toggle="tooltip"]').tooltip();
              $('#myTable2').DataTable();
          }
      }
      xmlhttpB.open("GET","<?php echo site_url(); ?>/conflictos_colectivos/directivos/tabla_directivos?id_sindicato="+id_sindicato,true);
      xmlhttpB.send();
  }

function alertFunc() {
    $('[data-toggle="tooltip"]').tooltip()
}

function cambiar_nuevo(){
    open_form(1);
    /*Inicio Sindicato*/
    $("#id_sindicato").val('');
    $("#nr").val($("#nr_search").val()).trigger('change.select2');
    $("#nombre_sindicato").val('');
    $("#direccion_sindicato").val('');
    $("#telefono_sindicato").val('');
    $("#totalafiliados_sindicato").val('');
    //combo_municipio2('');
    $("#municipio").val('').trigger('change.select2');
    /*Fin Sindicato*/

    /*Inicio Expediente*/
    combo_delegado('');
    combo_actividad_economica('');
    combo_municipio('');
    $("#motivo").val("").trigger('change.select2');
    $("#descripcion_motivo").val();
    $("#id_personal").val('');
    $("#establecimiento").val('');
    /*Fin expediente*/

    $("#band").val("save");
    $("#band1").val("save");
    $("#band3").val("save");
    $("#band4").val('save');

    $("#ttl_form").addClass("bg-success");
    $("#ttl_form").removeClass("bg-info");

    $("#btnadd").show(0);
    $("#btnedit").hide(0);

    $("#cnt_tabla").hide(0);
    $("#cnt_form_main").show(0);

    $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> Nueva Solicitud");
    combo_establecimiento('');
}

function cambiar_nuevo2(){
  $("#id_directivo").val('');
  $("#nombre_directivo").val('');
  $("#apellido_directivo").val('');
  $("#dui_directivo").val('');
  $("#tipo_directivo").val('');
  $("#acreditacion_directivo").val('');
  $("#band2").val('save');

  $("#modal_directivo").modal('show');
}



function cambiar_editar(id_expedienteci,bandera){
    open_form(1);
  if(bandera == "edit"){

    $.ajax({
      url: "<?php echo site_url(); ?>/conflictos_colectivos/diferencias_laborales/registros_expedientes",
      type: "POST",
      data: {id : id_expedienteci}
    })
    .done(function(res){
      result = JSON.parse(res)[0];
      /*Inicio sindicato*/
      $("#id_sindicato").val(result.id_sindicato);
      //combo_municipio2(result.id_municipio);
      $("#municipio").val(result.id_municipio.padStart(5,"00000")).trigger('change.select2');
      $("#nr").val($("#nr_search").val()).trigger('change.select2');
      $("#nombre_sindicato").val(result.nombre_sindicato);
      $("#direccion_sindicato").val(result.direccion_sindicato);
      $("#telefono_sindicato").val(result.telefono_sindicato);
      $("#totalafiliados_sindicato").val(result.totalafiliados_sindicato);
      /*Fin sindicato*/

      /*Inicio Expediente*/
      combo_delegado(result.id_personal);
      $("#fecha_creacion_exp").val(result.fechacrea_expedienteci);
      $("#motivo").val(result.motivo_expedienteci).trigger('change.select2');
      $("#descripcion_motivo").val(result.descripmotivo_expedienteci);
      combo_establecimiento(result.id_empresaci);
      /*Fin expediente*/

      $("#band").val("edit");
      $("#band1").val("edit");
      $("#band3").val("edit");
      $("#band4").val('edit');
    });

    $("#ttl_form").removeClass("bg-success");
    $("#ttl_form").addClass("bg-info");
    $("#btnadd1").hide(0);
    $("#btnedit1").show(0);
    $("#btnadd3").hide(0);
    $("#btnedit3").show(0);
    $("#btnadd4").hide(0);
    $("#btnedit4").show(0);
    $("#cnt_tabla_sindicatos").hide(0);
    $("#cnt_tabla").hide(0);
    $("#cnt_form_main").show(0);
    $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Editar Expediente");
  }
}

function cambiar_editar2(id_directivo,bandera){

  if(bandera == "edit"){

    $.ajax({
      url: "<?php echo site_url(); ?>/conflictos_colectivos/directivos/obtener_directivo",
      type: "POST",
      data: {id_directivo : id_directivo}
    })
    .done(function(res){
      result = JSON.parse(res)[0];
      /*Inicio directivo*/
      $("#id_directivo").val(result.id_directivo);
      $("#nombre_directivo").val(result.nombre_directivo);
      $("#apellido_directivo").val(result.apellido_directivo);
      $("#dui_directivo").val(result.dui_directivo);
      $("#tipo_directivo").val(result.tipo_directivo);
      $("#acreditacion_directivo").val(result.acreditacion_directivo);
      /*Fin directivo*/
      $("#band2").val("edit");
    });
    $("#modal_directivo").modal('show');
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
                            <input type="hidden" id="id_sindicato" name="id_sindicato" value="">


                            <span class="etiqueta">Expediente</span>
                            <blockquote class="m-t-0">

                            <div class="row">
                              <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                  <h5>Nombre del sindicato: <span class="text-danger">*</span></h5>
                                  <input type="text" id="nombre_sindicato" name="nombre_sindicato" class="form-control" placeholder="Nombres del sindicato" required="">
                                  <div class="help-block"></div>
                              </div>

                              <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                  <h5>Municipio: <span class="text-danger">*</span></h5>
                                  <select id="municipio" name="municipio" class="select2" style="width: 100%" required>
                                      <option value=''>[Seleccione el municipio]</option>
                                      <?php
                                          if($municipio->num_rows() > 0){
                                              foreach ($municipio->result() as $fila2) {
                                                 echo '<option class="m-l-50" value="'.$fila2->id_municipio.'">'.$fila2->municipio.'</option>';
                                              }
                                          }
                                      ?>
                                  </select>
                              </div>
                            </div>

                              <div class="row">
                                <div class="form-group col-lg-6" style="height: 83px;">
                                    <h5>Teléfono del sindicato: </h5>
                                    <input data-mask="9999-9999" type="text" id="telefono_sindicato" name="telefono_sindicato" class="form-control" placeholder="Número de Telefóno">
                                    <div class="help-block"></div>
                                </div>

                              <div class="form-group col-lg-6" style="height: 83px;">
                                  <h5>Total de afiliados:</h5>
                                  <input type="number" id="totalafiliados_sindicato" name="totalafiliados_sindicato" class="form-control" placeholder="Nacionalidad">
                                  <div class="help-block"></div>
                              </div>
                        </div>
                      <div class="row">
                        <div class="form-group col-lg-12" style="height: 83px;">
                            <h5>Dirección del sindicato:</h5>
                            <textarea type="text" id="direccion_sindicato" name="direccion_sindicato" class="form-control" placeholder="Dirección completa"></textarea>
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
                        <!-- INICIA MANTENIMIENTO DE DIRECTIVOS -->
                        <!-- ============================================================== -->
                        <div id="cnt_form3" class="cnt_form" style="display: none;">
                            <h3 class="box-title" style="margin: 0px;">
                                <button id="paso2" type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 2</button>&emsp;
                                Datos de directivo:
                            </h3><hr class="m-t-0 m-b-30">

                            <div id="cnt_tabla_directivos"></div>

                            <div align="right" id="btnadd2">
                              <button type="reset" class="btn waves-effect waves-light btn-success">
                                <i class="mdi mdi-recycle"></i> Limpiar</button>
                                <button type="button" onclick="open_form(4)" class="btn waves-effect waves-light btn-success2">
                                  Siguiente <i class="mdi mdi-chevron-right"></i>
                                </button>
                            </div>
                            <div align="right" id="btnedit2" style="display: none;">
                              <button type="reset" class="btn waves-effect waves-light btn-success">
                                <i class="mdi mdi-recycle"></i> Limpiar</button>
                              <button type="button" onclick="open_form(4)" class="btn waves-effect waves-light btn-info">
                                Siguiente <i class="mdi mdi-chevron-right"></i>
                              </button>
                            </div>

                            <div align="right" id="btnedit5" style="display: none;">
                              <button type="reset" class="btn waves-effect waves-light btn-success">
                                <i class="mdi mdi-recycle"></i> Limpiar</button>
                              <button type="button" onclick="cerrar_mantenimiento()" class="btn waves-effect waves-light btn-success2">
                                Finalizar <i class="mdi mdi-chevron-right"></i>
                              </button>
                            </div>

                        </div>
                        <!-- ============================================================== -->
                        <!-- FIN MANTENIMIENTO DE DIRECTIVOS -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- INICIA FORMULARIO DATOS DE LA SOLICITUD -->
                        <!-- ============================================================== -->
                        <?php echo form_open('', array('id' => 'formajax3', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                          <div id="cnt_form4" class="cnt_form" style="display: block;">
                            <h3 class="box-title" style="margin: 0px;">
                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 3</button>&emsp;
                                Información de la solicitud
                                <input type="hidden" id="band4" name="band4" value="save">
                                <input type="hidden" id="id_expedienteci" name="id_expedienteci" value="">
                                <input type="hidden" id="fecha_creacion_exp" name="fecha_creacion_exp" value="">

                              </h3><hr class="m-t-0 m-b-30">
                              <span class="etiqueta">Expediente</span>
                              <blockquote class="m-t-0">

                                <div class="row">
                                  <div class="col-lg-12 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_establecimiento"></div>
                                </div>

                                <div class="row">
                                  <div class="form-group col-lg-12" style="height: 83px;">
                                      <h5>Descripción del motivo:<span class="text-danger">*</h5>
                                      <textarea type="text" id="descripcion_motivo" name="descripcion_motivo" class="form-control" placeholder="Descipción del motivo"></textarea>
                                      <div class="help-block"></div>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                                      <h5>Motivo de la solicitud: <span class="text-danger">*</span></h5>
                                      <div class="controls">
                                        <select id="motivo" name="motivo" class="custom-select col-4" onchange="" required>
                                          <option value="">[Seleccione]</option>
                                          <option value="Despidos">Despidos</option>
                                          <option value="Horas extra no canceladas">Horas extra no canceladas</option>
                                          <option value="No da vacaciones de ley">No da vacaciones de ley</option>
                                          <option value="Malas condiciones de trabajo">Malas condiciones de trabajo</option>
                                          <option value="Incumplimiento de aumento salarial">Incumplimiento de aumento salarial</option>
                                        </select>
                                      </div>
                                  </div>

                                  <div class="col-lg-6 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_delegado"></div>
                                </div>
                            </blockquote>
                            <div align="right" id="btnadd3">
                              <button type="reset" class="btn waves-effect waves-light btn-success">
                                <i class="mdi mdi-recycle"></i> Limpiar
                              </button>
                              <button type="submit" class="btn waves-effect waves-light btn-success2">Finalizar
                                <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                            <div align="right" id="btnedit3" style="display: none;">
                              <button type="reset" class="btn waves-effect waves-light btn-success">
                                <i class="mdi mdi-recycle"></i> Limpiar</button>
                              <button type="submit" class="btn waves-effect waves-light btn-info">Finalizar
                                <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                          </div>
                          <?php echo form_close(); ?>
                        <!-- ============================================================== -->
                        <!-- FIN FORMULARIO DATOS DE LA SOLICITUD -->
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
                            <button type="button" onclick="cambiar_nuevo();" class="btn waves-effect waves-light btn-success2" data-toggle="tooltip" ><span class="mdi mdi-plus"></span> Nuevo registro</button>
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

<div id="cnt_modal_acciones"></div>
    <!--INICIA MODAL DE ESTABLECIMIENTOS -->
<div class="modal fade" id="modal_establecimiento" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <?php echo form_open('', array('id' => 'formajax4', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
          <input type="hidden" id="band3" name="band3" value="save">
          <input type="hidden" id="id_representante" name="id_representante" value="">
            <div class="modal-header">
                <h4 class="modal-title">Gestión de representantes</h4>
            </div>
            <div class="modal-body" id="">

                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Nombre del establecimiento: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" placeholder="Nombre" id="nombre_establecimiento" name="nombre_establecimiento" class="form-control" required="">
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Abreviatura del establecimiento: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" placeholder="Abreviatura" id="abre_establecimiento" name="abre_establecimiento" class="form-control" required="">
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Direcci&oacute;n: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <textarea type="text" id="dir_establecimiento" name="dir_establecimiento" class="form-control" required=""></textarea>
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                      <h5>Telefono: </h5>
                      <div class="controls">
                          <input type="text" placeholder="Telefono" id="telefono_establecimiento" name="telefono_establecimiento" class="form-control" data-mask="9999-9999">
                          <div class="help-block"></div>
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_actividad_economica"></div>
                </div>

                <div class="row">
                  <div class="col-lg-12 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_municipio"></div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Nombre del representante: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" id="nombre_representante" name="nombre_representante" class="form-control" required>
                      </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-white" data-dismiss="modal">Cerrar</button>
                <button type="submit" id="submit2" class="btn btn-info waves-effect text-white">Aceptar</button>
            </div>
          <?php echo form_close(); ?>
    </div>
  </div>
</div>
    <!--FIN MODAL DE ESTABLECIMIENTOS -->

<!-- ============================================================== -->
<!-- INICIO MODAL DIRECTIVOS -->
<!-- ============================================================== -->
<div id="modal_directivo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <?php echo form_open('', array('id' => 'formajax2', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
          <input type="hidden" id="band2" name="band2" value="save">
          <input type="hidden" id="id_directivo" name="id_directivo" value="">
            <div class="modal-header">
                <h4 class="modal-title">Gestión de directivos</h4>
            </div>
            <div class="modal-body" id="">
                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Nombre del directivo: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" id="nombre_directivo" name="nombre_directivo" class="form-control" required="">
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Apellido del directivo: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" id="apellido_directivo" name="apellido_directivo" class="form-control">
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>DUI del directivo: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" id="dui_directivo" name="dui_directivo" class="form-control">
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Tipo del directivo: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" id="tipo_directivo" name="tipo_directivo" class="form-control">
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Acreditación del directivo: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" id="acreditacion_directivo" name="acreditacion_directivo" class="form-control">
                      </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-white" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-info waves-effect text-white">Aceptar</button>
            </div>
          <?php echo form_close(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- ============================================================== -->
<!-- FIN MODAL DIRECTIVOS -->
<!-- ============================================================== -->



<div style="display:none;">
    <button  id="submit_ubi" name="submit_ubi" type="button"  >clicks</button>
</div>

<script>
/*AJAX SINDICATO*/
$(function(){
    $("#formajax").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax"));
        formData.append("dato", "valor");

        $.ajax({
            url: "<?php echo site_url(); ?>/conflictos_colectivos/sindicato/gestionar_sindicato",
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
                $("#id_sindicato").val(res);
                open_form(3);
                tabla_directivos();
              $("#band1").val( $("#band").val() );
              $("#band2").val( $("#band").val() );
              if($("#band").val() == "delete"){
                swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
              }
            }
        });

    });
});
/*AJAX DIRECTIVOS*/
$(function(){
    $("#formajax2").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax2"));
        formData.append("id_sindicato", $('#id_sindicato').val());
        $.ajax({
          url: "<?php echo site_url(); ?>/conflictos_colectivos/directivos/gestionar_directivos",
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
              if($("#band2").val() == "save"){
                  swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
              }else if($("#band2").val() == "edit"){
                  swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
              }else{
                  swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
              }
              $("#modal_directivo").modal('hide');
              tabla_directivos();
            }
        });

    });
});
/*AJAX ESTABLECIMIENTOS*/
$(function(){
    $("#formajax4").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax4"));

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
/*AJAX EXPEDIENTE DE LA SOLICITUD*/
$(function(){
    $("#formajax3").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax3"));
        formData.append("id_sindicato", $('#id_sindicato').val());
        $.ajax({
          url: "<?php echo site_url(); ?>/conflictos_colectivos/Diferencias_laborales/gestionar_expediente",
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
              if($("#band4").val() == "save"){
                  swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
              }else if($("#band4").val() == "edit"){
                  swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
              }else{
                  swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
              }
              tablasindicatos();
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
