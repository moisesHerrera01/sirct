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
  <?php if(isset($tipo_solicitud)){ ?>
      $("#motivo").val('<?=$tipo_solicitud?>');
      <?php if($band_mantto == "save"){ ?>
          nuevo_reg_post();
      <?php }else{ ?>
          cambiar_editar('<?=$id_personaci?>','edit');
      <?php } ?>
  <?php }else{ ?>
      $("#motivo").val('');
  <?php } ?>

    <?php if(tiene_permiso($segmentos=2,$permiso=1)){ ?>
    tablasolicitudes();
    <?php }else{ ?>
        $("#cnt_tabla").html("Usted no tiene permiso para este formulario.");
    <?php } ?>
}

function nuevo_reg_post(){
    $("#cnt_tabla").hide(0);
    $("#cnt_form_main").show(0);
}

function adjuntar_actas(id_expediente) {
    $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/acta",
        type: "post",
        dataType: "html",
        data: {
            id: id_expediente
        }
    })
    .done(function (res) {
        $('#cnt_actions').html(res);
        $("#cnt_actions").show(0);
        $("#cnt_tabla").hide(0);
        $("#cnt_tabla_solicitudes").hide(0);
        $("#cnt_form_main").hide(0);

        tabla_acta(id_expediente);

        $("#myAwesomeDropzone").dropzone({
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            successmultiple: function (data, response) {
                $("#uploaded_files").val(response);
            },
            init: function () {
                var submitButton = document.querySelector("#submit_dropzone_form");
                myDropzone = this;
                submitButton.addEventListener("click", function () {
                    myDropzone.processQueue();
                });
            },
            success: function () {
                swal({
                    title: "¡Registro exitoso!",
                    type: "success",
                    showConfirmButton: true
                });
                tabla_acta(id_expediente);
            }
        });
    });
}

function convert_lim_text(lim){
    var tlim = "-"+lim+"d";
    return tlim;
}

function modal_delegado(id_expedienteci, id_personal) {
    $("#id_expedienteci_copia").val(id_expedienteci);
    $("#id_personal_copia").val(id_personal).trigger('change.select2');
    $("#modal_delegado").modal("show");
}

function cambiar_delegado() {
    var id_expedienteci = $("#id_expedienteci_copia").val();
    var id_personal = $("#id_personal_copia").val();
    $.ajax({
      url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/cambiar_delegado",
      type: "post",
      dataType: "html",
      data: {
        id_expedienteci: id_expedienteci,
        id_personal: id_personal,
      }
    })
    .done(function (res) {
      if(res == "exito"){
        cerrar_mantenimiento()
        tablasolicitudes();
        swal({ title: "¡Delegado modificado exitosamente!", type: "success", showConfirmButton: true });
      }else{
          swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
      }
    });
}

function modal_actas_tipo(id_expedienteci, cuenta_audiencias,tipo_conciliacion,posee_representante,estado) {
      $("#solicitud_pn_pj").show();
      $("#diferido_con").hide();
      $("#diferido_sin").hide();
      $("#acto_con").hide();
      $("#acto_sin").hide();
      $("#segunda_con").hide();
      $("#segunda_sin").hide();

    if (cuenta_audiencias<2) {
      $("#solicitud_pn_pj").hide();
    }if (posee_representante) {
      $("#segunda_con").show();
    }else {
      $("#segunda_sin").show();
    }

    if (estado=="2") {
      $("#separador1").show();
      if (tipo_conciliacion=='Pago Diferido') {
        if (posee_representante) {
          $("#diferido_con").show();
        }else {
          $("#diferido_sin").show();
        }
      }else {
        if (posee_representante) {
          $("#acto_con").show();
        }else {
          $("#acto_sin").show();
        }
      }
    }
    $("#id_expedienteci_copia2").val(id_expedienteci);
    $("#tipo_acta").val('').trigger('');
    $("#modal_actas_tipo").modal("show");

}

function generar_actas_tipo() {
    var id_expedienteci = $("#id_expedienteci_copia2").val();
    var tipo_acta = $("#tipo_acta").val();
    $.ajax({
      url: "<?php echo site_url(); ?>/resolucion_conflictos/acta/generar_acta_tipo",
      type: "post",
      dataType: "html",
      data: {
        id_expedienteci: id_expedienteci,
        tipo_acta: tipo_acta,
      }
    })
    .done(function (res) {
      if(res == "exito"){
        cerrar_mantenimiento()
        tablasolicitudes();
        swal({ title: "¡Acta generada exitosamente!", type: "success", showConfirmButton: true });
      }else{
          swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
      }
    });
}

function modal_estado(id_expedienteci, id_estadosci) {
    $("#id_expedienteci_copia").val(id_expedienteci);
    $("#id_estado_copia").val(id_estadosci).trigger('change.select2');
    $("#modal_estado").modal("show");
}

function cambiar_estado() {
    var id_expedienteci = $("#id_expedienteci_copia").val();
    var id_estadosci = $("#id_estado_copia").val();
    $.ajax({
      url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/cambiar_estado",
      type: "post",
      dataType: "html",
      data: {
        id_expedienteci: id_expedienteci,
        id_estadosci: id_estadosci,
      }
    })
    .done(function (res) {
      if(res == "exito"){
        cerrar_mantenimiento()
        tablasolicitudes();
        swal({ title: "¡Estado modificado exitosamente!", type: "success", showConfirmButton: true });
      }else{
          swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
      }
    });
}

function resolucion(id_expedienteci) {
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/resolucion_expediente",
    type: "post",
    dataType: "html",
    data: {id : id_expedienteci}
  })
  .done(function(res){
    $('#cnt_modal_acciones').html(res);
    $('#modal_resolucion').modal('show');
  });
}

var estado_pestana = "";
function cambiar_pestana(tipo){
    estado_pestana = tipo;
    tablasolicitudes();
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


function combo_nacionalidades(seleccion){

  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/combo_nacionalidades",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_nacionalidad').html(res);
    $(".select2").select2();
  });
}

function combo_tipo_representante(seleccion){

  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/combo_tipo_representante",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_tipo_representante').html(res);
    $(".select2").select2();
  });
}

function combo_doc_identidad(seleccion){

  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/combo_tipo_doc",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_tipo_doc').html(res);
    $(".select2").select2();
  });
}

function visualizar(id_personaci,id_empresaci) {
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/ver_expediente",
    type: "post",
    dataType: "html",
    data: {id : id_personaci, id_emp : id_empresaci}
  })
  .done(function(res){
    $('#cnt_actions').html(res);
    $("#cnt_actions").show(0);
    $("#cnt_tabla").hide(0);
    $("#cnt_tabla_solicitudes").hide(0);
    $("#cnt_form_main").hide(0);
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

/*function combo_ocupacion(seleccion){
  $.ajax({
    url: "</*?php echo site_url(); ?>/resolucion_conflictos/solicitudes/combo_ocupacion",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_ocupacion').html(res);
    $(".select2").select2();
  });

}*/

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

    $('#partida_div').hide();
    $('#div_numero_doc_identidad').show();
    $("#dui").attr("required",'required');
    $("#ocultar_div").hide();
    $('#div_numero_doc_identidad').show();
    $("#cnt_tabla").show(0);
    $("#cnt_tabla_solicitudes").show(0);
    $("#cnt_form_main").hide(0);
    $("#cnt_actions").hide(0);
    $("#modal_delegado").modal('hide');
    $("#modal_actas_tipo").modal('hide');
    $("#modal_estado").modal('hide');
    $("#cnt_actions").remove('.card');
    open_form(1);
    tablasolicitudes();
}

function objetoAjax(){
    var xmlhttp = false;
    try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) { try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { xmlhttp = false; } }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp = new XMLHttpRequest(); }
    return xmlhttp;
}

function tablasolicitudes(){
  var nr_empleado = $("#nr_search").val();
    if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttpB=new XMLHttpRequest();
    }else{// code for IE6, IE5
        xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
    }
    xmlhttpB.onreadystatechange=function(){
        if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
            document.getElementById("cnt_tabla_solicitudes").innerHTML=xmlhttpB.responseText;
            $('[data-toggle="tooltip"]').tooltip();
            $('#myTable').DataTable();
        }
    }
    xmlhttpB.open("GET","<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/tabla_solicitudes?nr="+nr_empleado+"&tipo="+estado_pestana,true);
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
            if ($("#myTable2 tbody tr").length>=2) {
              $('#div_orden').hide(0);
            }
        }
    }
    xmlhttpB.open("GET","<?php echo site_url(); ?>/resolucion_conflictos/audiencias/tabla_audiencias?id_expedienteci="+id_expedienteci,true);
    xmlhttpB.send();
}

function tabla_pagos(id_expedienteci){
  //alert(id_expedienteci);
    if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttpB=new XMLHttpRequest();
    }else{// code for IE6, IE5
        xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
    }
    xmlhttpB.onreadystatechange=function(){
        if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
            document.getElementById("cnt_tabla_pagos").innerHTML=xmlhttpB.responseText;
            $('[data-toggle="tooltip"]').tooltip();
            $('#myTable3').DataTable();
        }
    }
    xmlhttpB.open("GET","<?php echo site_url(); ?>/resolucion_conflictos/pagos/tabla_pagos?id_expedienteci="+id_expedienteci,true);
    xmlhttpB.send();
}

function alertFunc() {
    $('[data-toggle="tooltip"]').tooltip()
}

function cambiar_nuevo(){
    open_form(1);
    /*Inicio Solicitante*/
    $("#id_personaci").val('');
    $("#nr").val($("#nr_search").val()).trigger('change.select2');
    $("#nombres").val('');
    $("#apellidos").val('');
    $("#conocido_por").val('');
    $("#dui").val('');
    $("#telefono").val('');
    $("#telefono2").val('');
    $("#municipio").val('').trigger('change.select2');
    $("#direccion").val('');
    $("#fecha_nacimiento").val('');
    $("#sexo").val('');
    $("#estudios").val('');
    $("#nacionalidad").val('');
    $("#discapacidad_desc").val('');
    $("#discapacidad").val('');
    $("#posee_representante").val('');
    $("#pertenece_lgbt").val('');
    //Partida de nacimiento
    $("#id_partida").val('');
    $("#numero_partida").val('');
    $("#folio_partida").val('');
    $("#libro_partida").val('');
    $("#asiento_partida").val('');
    $("#anio_partida").val('');
    /*Fin Solicitante*/

    /*Inicio represnetante persona*/
    combo_tipo_representante('');
    $("#id_representante_persona").val('');
    $("#nombre_representante_persona").val('');
    $("#apellido_representante_persona").val('');
    $("#dui_representante_persona").val('');
    $("#telefono_representante_persona").val('');
    $("#acreditacion_representante_persona").val('');
    /*Fin representante persona*/

    /*Inicio Expediente*/
    combo_nacionalidades('');
    combo_doc_identidad('');
    //combo_ocupacion('');
    combo_delegado('');
    combo_actividad_economica();
    combo_municipio();
    $("#id_empleador").val($("#id_empleador").val()).trigger('change.select2');
    $("#nombres_jefe").val('');
    $("#ocupacion").val('');
    $("#apellidos_jefe").val('');
    $("#cargo_jefe").val('');
    $("#motivo").val('');
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
    $("#ocultar_div").hide();

    $("#cnt_tabla").hide(0);
    $("#cnt_form_main").show(0);

    $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> Nueva Solicitud");
    combo_establecimiento('');
}



function cambiar_editar(id_personaci,bandera){
  open_form(1);
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
      $("#conocido_por").val(result.conocido_por);
      $("#apellidos").val(result.apellido_personaci);
      $("#dui").val(result.dui_personaci);
      $("#telefono").val(result.telefono_personaci);
      $("#telefono2").val(result.telefono2_personaci);
      $("#municipio").val(result.id_municipio.padStart(5,"00000")).trigger('change.select2');
      $("#direccion").val(result.direccion_personaci);
      $("#fecha_nacimiento").val(result.fnacimiento_personaci);
      $("#estudios").val(result.estudios_personaci);
      $("#nacionalidad").val(result.nacionalidad_personaci);
      $("#discapacidad_desc").val(result.discapacidad);
      /*Inicio partida nacimiento*/
      if(result.discapacidad_personaci==0){
          $("#ocultar_div").hide();
      }else{
          $("#ocultar_div").show();
      }
      if (result.id_doc_identidad!=1) {
        $('#dui').mask('', {reverse: true});
        $('#dui').unmask();
        if (result.id_doc_identidad==4) {
          $('#partida_div').show();
          $('#div_numero_doc_identidad').hide();
          $("#dui").removeAttr("required");
        }else {
          $('#partida_div').hide();
          $('#div_numero_doc_identidad').show();
          $("#dui").attr("required",'required');
        }
      }else {
         $('#dui').mask('99999999-9', {reverse: true});
         $('#partida_div').hide();
         $('#div_numero_doc_identidad').show();
         $("#dui").attr("required",'required');
      }
      $("#id_partida").val(result.id_partida);
      $("#numero_partida").val(result.numero_partida);
      $("#folio_partida").val(result.folio_partida);
      $("#libro_partida").val(result.libro_partida);
      $("#asiento_partida").val(result.asiento_partida);
      $("#anio_partida").val(result.anio_partida);
      /*Fin partida de nacimiento*/
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
      if (result.pertenece_lgbt=='1') {
        document.getElementById('si_lgbt').checked =true;
      }else {
        document.getElementById('no_lgbt').checked =true;
      }
      /*Inicio represnetante persona*/
      $("#id_representante_persona").val(result.id_representantepersonaci);
      $("#nombre_representante_persona").val(result.nombre_representantepersonaci);
      $("#apellido_representante_persona").val(result.apellido_representantepersonaci);
      combo_tipo_representante(result.tipo_representantepersonaci);
      $("#dui_representante_persona").val(result.dui_representantepersonaci);
      $("#telefono_representante_persona").val(result.tel_representantepersonaci);
      $("#acreditacion_representante_persona").val(result.acreditacion_representantepersonaci);
      /*Fin representante persona*/

      /*Inicio Expediente*/
      combo_doc_identidad(result.id_doc_identidad);
      combo_nacionalidades(result.nacionalidad_personaci);
      //combo_ocupacion(result.id_catalogociuo);
      combo_delegado(result.id_personal);
      combo_actividad_economica(result.id_catalogociiu);
      combo_municipio(result.id_municipio1);
      $("#ocupacion").val(result.ocupacion);
      $("#fecha_creacion_exp").val(result.fechacrea_expedienteci);
      $("#id_empleador").val(result.id_empleador);
      $("#id_emplea").val(result.id_empleador);
      $("#nombres_jefe").val(result.nombre_empleador);
      $("#apellidos_jefe").val(result.apellido_empleador);
      $("#cargo_jefe").val(result.cargo_empleador);
      $("#motivo").val(result.motivo_expedienteci);
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
    $("#cnt_tabla_solicitudes").hide(0);
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
                <h3 class="text-themecolor m-b-0 m-t-0">Solicitud de Resolución de Conflictos de Trabajo</h3>
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
                            <input type="hidden" id="id_partida" name="id_partida" value="">

                            <span class="etiqueta">Expediente</span>
                            <blockquote class="m-t-0">

                            <div class="row">
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Nombres: <span class="text-danger">*</span></h5>
                                    <input type="text" id="nombres" name="nombres" class="form-control" placeholder="Nombres de la persona" required="">
                                    <div class="help-block"></div>
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Apellidos: <span class="text-danger">*</span></h5>
                                    <input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Apellidos de la persona" required="">
                                    <div class="help-block"></div>
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Conocido por: </h5>
                                    <input type="text" id="conocido_por" name="conocido_por" class="form-control" placeholder="Conocido por">
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="row">
                              <div class="form-group col-lg-4" style="height: 83px;">
                                  <h5>Teléfono 1: </h5>
                                  <input data-mask="9999-9999" type="text" id="telefono" name="telefono" class="form-control" placeholder="Número de Telefóno">
                                  <div class="help-block"></div>
                              </div>

                              <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_tipo_doc"></div>

                                <div id="div_numero_doc_identidad" class="form-group col-lg-4" style="height: 83px;">
                                    <h5>Número de documento identidad: <span class="text-danger">*</span></h5>
                                    <input data-mask="99999999-9" data-mask-reverse="true" type="text" id="dui" name="dui" class="form-control" placeholder="Documento Unico de Identidad" required="">
                                    <div class="help-block"></div>
                                </div>

                            </div>

                            <div id="partida_div" style="display: none;">
                              <div class="row">
                                <div class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Número:</h5>
                                    <div class="controls">
                                        <input type="text" placeholder="Número partida nacimiento" id="numero_partida" name="numero_partida" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Folio:</h5>
                                    <div class="controls">
                                        <input type="text" placeholder="Folio partida nacimiento" id="folio_partida" name="folio_partida" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Libro: <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="text" placeholder="Libro partida nacimiento" id="libro_partida" name="libro_partida" class="form-control">
                                    </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Asiento: <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <input type="text" placeholder="Asiento partida nacimiento" id="asiento_partida" name="asiento_partida" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo " pull-left"; } ?>">
                                    <h5>Año: </h5>
                                    <div class="controls">
                                        <input type="text" placeholder="Año partida nacimiento" id="anio_partida" name="anio_partida" class="form-control">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                              </div>
                            </div>

                            <div class="row">
                              <div class="form-group col-lg-4" style="height: 83px;">
                                  <h5>Teléfono 2: </h5>
                                  <input data-mask="9999-9999" type="text" id="telefono2" name="telefono2" class="form-control" placeholder="Número de Telefóno casa">
                                  <div class="help-block"></div>
                              </div>

                              <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                  <h5>Municipio: <span class="text-danger">*</span></h5>
                                  <select id="municipio" name="municipio" class="select2" style="width: 100%" required>
                                      <option value=''>[Seleccione el municipio]</option>
                                      <?php
                                          $municipio = $this->db->query("SELECT * FROM org_municipio ORDER BY municipio");
                                          if($municipio->num_rows() > 0){
                                              foreach ($municipio->result() as $fila2) {
                                                 echo '<option class="m-l-50" value="'.$fila2->id_municipio.'">'.$fila2->municipio.'</option>';
                                              }
                                          }
                                      ?>
                                  </select>
                              </div>
                              <div class="form-group col-lg-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                  <h5>Fecha de nacimiento: <span class="text-danger">*</span></h5>
                                  <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="dd/mm/yyyy" readonly="">
                                  <div class="help-block"></div>
                              </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-lg-2" style="height: 83px;">
                              <h5>Sexo:</h5>
                              <input name="sexo" type="radio" id="masculino" checked="" value="M">
                              <label for="masculino">Masculino</label>
                              <input name="sexo" type="radio" id="femenino" value="F">
                              <label for="femenino">Femenino</label>
                              <div class="help-block"></div>
                        </div>

                       <div class="form-group col-lg-2" style="height: 83px;">
                           <h5>Pertenece LGTBI:</h5>
                           <input name="pertenece_lgbt" type="radio" id="si_lgbt" value='1'>
                           <label for="si_lgbt">Si </label><Br>
                           <input name="pertenece_lgbt" type="radio" id="no_lgbt" checked="" value='0'>
                           <label for="no_lgbt">No</label>
                      <div class="help-block"></div>
                    </div>
                          <div class="form-group col-lg-8" style="height: 83px;">
                              <h5>Dirección:</h5>
                              <textarea type="text" id="direccion" name="direccion" class="form-control" placeholder="Dirección completa"></textarea>
                              <div class="help-block"></div>
                          </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-2" style="height: 83px;">
                                <h5>Representante:</h5>
                                <input name="posee_representante" type="radio" id="si_posee" value='1'>
                                <label for="si_posee">Si </label><Br>
                                <input name="posee_representante" type="radio" id="no_posee" checked="" value='0' required>
                                <label for="no_posee">No</label>
                           <div class="help-block"></div>
                         </div>

                         <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                             <h5>Estudios realizados: <span class="text-danger">*</span></h5>
                             <div class="controls">
                               <select id="estudios" name="estudios" class="custom-select col-4" onchange="" required>
                                 <option value="">[Seleccione]</option>
                                 <option value="Sin estudio">Sin estudio</option>
                                 <option value="Educacion Básica">Educacion Básica</option>
                                 <option value="Bachillerato">Bachillerato</option>
                                 <option value="Universidad">Universidad</option>
                               </select>
                             </div>
                         </div>

                         <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_nacionalidad"></div>
                        </div>
                        <div class="row">
                          <div class="form-group col-lg-2" style="height: 83px;">
                              <h5>Posee discapacidad:</h5>
                              <input name="discapacidad" type="radio" id="si" value='1'>
                              <label for="si">Si </label><Br>
                              <input name="discapacidad" type="radio" id="no" checked="" value='0'>
                              <label for="no">No</label>
                         <div class="help-block"></div>
                       </div>

                       <div id="ocultar_div" class="form-group col-lg-8" style="height: 83px;">
                           <h5>Discapacidad:</h5>
                           <textarea type="text" id="discapacidad_desc" name="discapacidad_desc" class="form-control" placeholder="Ingrese la discapacidad"></textarea>
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
                                  <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_tipo_representante"></div>

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
                                      <h5>Fecha del conflicto/despido: <span class="text-danger">*</span></h5>
                                      <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="fecha_conflicto" name="fecha_conflicto" placeholder="dd/mm/yyyy" readonly="">
                                      <div class="help-block"></div>
                                  </div>
                                </div>

                                <div class="row">
                                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                                      <h5>Motivo de la solicitud: <span class="text-danger">*</span></h5>
                                      <div class="controls">
                                        <!-- <select id="motivo" name="motivo" class="custom-select col-4" onchange="" required>
                                          <option value="">[Seleccione]</option>
                                          <option value="Despido">Despido</option>
                                          <option value="Diferencia laboral">Diferencia laboral</option>
                                        </select> -->
                                        <select id="motivo" name="motivo" class="custom-select" required style="width: 100%">
                                            <option value="">[Seleccione el motivo]</option>
                                            <option value="1">Despido de hecho o injustificado</option>
                                            <option value="2">Diferencia laboral</option>
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
                                  <!-- <div class="col-lg-8 form-group <//?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_ocupacion"></div> -->
                                  <div class="form-group col-lg-8" style="height: 83px;">
                                      <h5>Ocupación según DUI:<span class="text-danger">*</h5>
                                      <textarea type="text" id="ocupacion" name="ocupacion" class="form-control" placeholder="Ocupación según DUI" required=""></textarea>
                                      <div class="help-block"></div>
                                  </div>

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

                                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                                      <h5>Forma de pago: <span class="text-danger">*</span></h5>
                                      <div class="controls">
                                        <select id="forma_pago" name="forma_pago" class="custom-select col-4" onchange="" required>
                                          <option value="">[Seleccione]</option>
                                          <option value="Diario">Diario</option>
                                          <option value="Semanal">Semanal</option>
                                          <option value="Catorcenal">Catorcenal</option>
                                          <option value="Quincenal">Quincenal</option>
                                          <option value="Mensual">Mensual</option>
                                        </select>
                                      </div>
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
                                        <input type="text" id="nombres_jefe" name="nombres_jefe" class="form-control" placeholder="Nombres de jefe inmediato">
                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Apellidos de jefe inmediato:</h5>
                                        <input type="text" id="apellidos_jefe" name="apellidos_jefe" class="form-control" placeholder="Apellidos de jefe inmediato">
                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Cargo de jefe inmediato: </h5>
                                        <input type="text" id="cargo_jefe" name="cargo_jefe" class="form-control" placeholder="Cargo de jefe inmediato">
                                        <div class="help-block"></div>
                                    </div>
                              </div>
                            </blockquote>
                            <div align="right" id="btnadd2">
                              <button type="reset" class="btn waves-effect waves-light btn-success">
                                <i class="mdi mdi-recycle"></i> Limpiar
                              </button>
                              <button type="submit" class="btn waves-effect waves-light btn-success2">Siguiente
                                <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                            <div align="right" id="btnedit2" style="display: none;">
                              <button type="reset" class="btn waves-effect waves-light btn-success">
                                <i class="mdi mdi-recycle"></i> Limpiar</button>
                              <button type="submit" class="btn waves-effect waves-light btn-info">Siguiente
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
                                <select id="nr_search" name="nr_search" class="select2" style="width: 100%" required="" onchange="tablasolicitudes();">
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
                    <div id="cnt_tabla_solicitudes"></div>
                    </div>
                </div>
            </div>

        </div>
        <!-- ============================================================== -->
        <!-- Fin CUERPO DE LA SECCIÓN -->
        <!-- ============================================================== -->
    </div>
    <div id="cnt_modal_acciones"></div>
</div>
<!-- ============================================================== -->
<!-- Fin de DIV de inicio (ENVOLTURA) -->
<!-- ============================================================== -->

<div style="display:none;">
    <button  id="submit_ubi" name="submit_ubi" type="button"  >clicks</button>
</div>


    <!--INICIA MODAL DE ESTABLECIMIENTOS -->
<div class="modal fade" id="modal_establecimiento" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <?php echo form_open('', array('id' => 'formajax3', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
          <input type="hidden" id="band3" name="band3" value="save">
          <input type="hidden" id="id_representante" name="id_representante" value="">
            <div class="modal-header">
                <h4 class="modal-title">Gestión de representantes</h4>
            </div>
            <div class="modal-body" id="">

                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Razon social del establecimiento:</h5>
                      <div class="controls">
                          <input type="text" placeholder="Nombre" id="razon_social" name="razon_social" class="form-control" required="">
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Nombre del establecimiento:</h5>
                      <div class="controls">
                          <input type="text" placeholder="Nombre" id="nombre_establecimiento" name="nombre_establecimiento" class="form-control">
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Abreviatura del establecimiento: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" placeholder="Abreviatura" id="abre_establecimiento" name="abre_establecimiento" class="form-control">
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

    <!--INICIO MODAL DE DELEGADO -->
    <div class="modal fade" id="modal_delegado" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Cambiar asignación de delegado:</h4>
          </div>

          <div class="modal-body" id="">
              <input type="hidden" id="id_expedienteci_copia" name="id_expedienteci_copia" value="">
              <div class="row">
                <div class="form-group col-lg-12 col-sm-12">
                    <div class="form-group">
                        <h5>Delegado/a:<span class="text-danger">*</h5>
                        <select id="id_personal_copia" name="id_personal_copia" class="select2" style="width: 100%" required="">
                        <option value="">[Todos los empleados]</option>
                        <?php
                            $otro_empleado = $this->db->query("SELECT e.id_empleado, e.nr, UPPER(CONCAT_WS(' ', e.primer_nombre,
                                                                      e.segundo_nombre, e.tercer_nombre, e.primer_apellido,
                                                                      e.segundo_apellido, e.apellido_casada)) AS nombre_completo
                                                              FROM sir_empleado AS e WHERE e.id_estado = '00001'
                                                              ORDER BY e.primer_nombre, e.segundo_nombre, e.tercer_nombre,
                                                              e.primer_apellido, e.segundo_apellido, e.apellido_casada");
                            if($otro_empleado->num_rows() > 0){
                                foreach ($otro_empleado->result() as $fila) {
                                    echo '<option class="m-l-50" value="'.$fila->id_empleado.'">'.preg_replace ('/[ ]+/', ' ', $fila->nombre_completo.' - '.$fila->nr).'</option>';
                                }
                            }
                        ?>
                        </select>
                    </div>
                </div>
              </div>
              <div align="right">
                <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="cambiar_delegado();" class="btn waves-effect waves-light btn-success2"> Guardar
                </button>
              </div>
          </div>
        </div>
      </div>
    </div>
    <!--FIN MODAL DE DELEGADO -->

    <!--INICIO MODAL GENERAR ACTA -->
    <div class="modal fade" id="modal_actas_tipo" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Emitir acta:</h4>
          </div>

          <div class="modal-body" id="">
              <input type="hidden" id="id_expedienteci_copia2" name="id_expedienteci_copia2" value="">
              <input type="hidden" id="cuenta_audiencias" name="cuenta_audiencias" value="">
              <div class="row">
                <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Seleccione el tipo de acta: <span class="text-danger">*</span></h5>
                    <div class="controls">
                      <select id="tipo_acta" name="tipo_acta" class="custom-select col-4" onchange="nav(this.value)" required>
                        <option value="">[Seleccione]</option>
                        <option id="acto_con" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/1/')?>">Conciliada en el acto con defensor público</option>
                        <option id="acto_sin" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/2/')?>">Conciliada en el acto sin defensor público</option>
                        <option id="diferido_con" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/3/')?>">Conciliada pago diferido con defensor público</option>
                        <option id="diferido_sin" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/4/')?>">Conciliada pago diferido sin defensor público</option>
                        <option id="solicitud_pn_pj" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/5/')?>">Solicitud de persona natural a persona juridica</option>
                        <option value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta/')?>">Ficha de persona natural a persona juridica</option>
                        <option id="segunda_con" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/6/')?>">Segunda cita PN-PJ con defensor</option>
                        <option id="segunda_sin" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/7/')?>">Segunda cita PN-PJ sin defensor</option>
                        <option value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/8/')?>">Desistimiento de persona natural a persona juridica</option>
                      </select>
                    </div>
                </div>
              </div>
              <div align="right">
                <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal">Cerrar</button>
              <!--  <button type="button" onclick="generar_actas_tipo();" class="btn waves-effect waves-light btn-success2"> Generar
              </button> !-->
              </div>
          </div>
        </div>
      </div>
    </div>
    <!--FIN MODAL GENERAR ACTA -->

    <!--INICIO MODAL DE ESTADO -->
    <div class="modal fade" id="modal_estado" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Cambiar estado del expediente:</h4>
          </div>

          <div class="modal-body" id="">
              <input type="hidden" id="id_expedienteci_copia" name="id_expedienteci_copia" value="">
              <div class="row">
                <div class="form-group col-lg-12 col-sm-12">
                    <div class="form-group">
                        <h5>Estado:<span class="text-danger">*</h5>
                        <select id="id_estado_copia" name="id_estado_copia" class="select2" style="width: 100%" required="">
                        <option value="">[Todos los estados]</option>
                        <?php
                            $otro_estado = $this->db->query("SELECT e.id_estadosci, e.nombre_estadosci FROM sct_estadosci AS e ");
                            if($otro_estado->num_rows() > 0){
                                foreach ($otro_estado->result() as $fila) {
                                    echo '<option class="m-l-50" value="'.$fila->id_estadosci.'">'.preg_replace ('/[ ]+/', ' ', $fila->nombre_estadosci).'</option>';
                                }
                            }
                        ?>
                        </select>
                    </div>
                </div>
              </div>
              <div align="right">
                <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="cambiar_estado();" class="btn waves-effect waves-light btn-success2"> Guardar
                </button>
              </div>
          </div>
        </div>
      </div>
    </div>
    <!--FIN MODAL DE ESTADO -->
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
              $("#id_representante_persona").val(res);
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
        formData.append("id_representante_persona", $('#id_representante_persona').val());
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
              audiencias(res,1);
              /*if($("#band2").val() == "save"){
                  swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
              }else if($("#band2").val() == "edit"){
                  swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
              }else{
                  swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
              }*/
              //tablasolicitudes();
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

function inhabilitar(id_expedienteci) {
  swal({
    title: "Inhabilitar Expediente",
    text: "Motivo de Inhabilitar Expediente: *",
    type: "input",
    showCancelButton: true,
    closeOnConfirm: false,
    inputPlaceholder: "Motivo para inhabilitar"
  }, function (inputValue) {
    if (inputValue === false) return false;
    if (inputValue === "") {
      swal.showInputError("Se necesita un motivo para inhabilitar.");
      return false
    }
    $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/gestionar_inhabilitar_expediente",
        type: "post",
        dataType: "html",
        data: {
          id_exp: id_expedienteci,
          mov_inhabilitar: inputValue
        }
      })
      .done(function (res) {
        if(res == "exito"){
          tablasolicitudes();
          swal({ title: "¡Expediente inhabilitado exitosamente!", type: "success", showConfirmButton: true });
        }else{
              swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
          }
      });
  });
}

function habilitar(id_expedienteci) {
  swal({
      title: "Confirmar Habilitación",
      text: "¿Está seguro que desea habilitar el expediente?",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-success2",
      confirmButtonText: "Si",
      closeOnConfirm: false
    },
    function () {
      $.ajax({
          url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/gestionar_habilitar_expediente",
          type: "post",
          dataType: "html",
          data: {
            id_exp: id_expedienteci,
          }
        })
        .done(function (res) {
          if(res == "exito"){
            tablasolicitudes();
            swal({ title: "¡Expediente habilitado exitosamente!", type: "success", showConfirmButton: true });
          }else{
              swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
          }
        });
    });
}

function audiencias(id_expedienteci,origen) {
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/audiencias/programar_audiencias",
    type: "post",
    dataType: "html",
    data: {id : id_expedienteci}
  })
  .done(function(res){
    //console.log(res)
    $('#cnt_actions').html(res);
    $("#cnt_actions").show(0);
    $("#cnt_tabla").hide(0);
    $("#cnt_tabla_solicitudes").hide(0);
    $("#cnt_form_main").hide(0);
    if (origen==1) {
        $("#paso4").show(0);
        tabla_audiencias(id_expedienteci);
        $("#div_finalizar").show(0);
    }else {
      tabla_audiencias(id_expedienteci);
    }

  });
}

function pagos(id_expedienteci) {
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/pagos/programar_pagos",
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
    tabla_pagos(id_expedienteci);
  });
}

function ocultar(){
  var value = $("#id_doc_identidad").val();
  if (value!=1) {
    $('#dui').mask('', {reverse: true});
    $('#dui').unmask();
    if (value==4) {
      $('#partida_div').show(500);
      $('#div_numero_doc_identidad').hide(500);
      $("#dui").removeAttr("required");
    }else {
      $('#partida_div').hide(500);
      $('#div_numero_doc_identidad').show(500);
      $("#dui").attr("required",'required');
    }
  }else {
     $('#dui').mask('99999999-9', {reverse: true});
     $('#partida_div').hide(500);
     $('#div_numero_doc_identidad').show(500);
     $("#dui").attr("required",'required');
  }
}

$(function(){
    $(document).ready(function(){

      $("input[name=discapacidad]").click(function(evento){
            var valor = $(this).val();
            if(valor == 0){
                $("#ocultar_div").hide(500);
            }else{
                $("#ocultar_div").show(500);
            }
    });

    	var date = new Date(); var currentMonth = date.getMonth(); var currentDate = date.getDate(); var currentYear = date.getFullYear();
        $('#fecha_nacimiento').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true, endDate: moment().format("DD-MM-YYYY")}).datepicker("setDate", new Date());
        $('#fecha_conflicto').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true, endDate: moment().format("DD-MM-YYYY")}).datepicker("setDate", new Date());
    });
    });
</script>
