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
    tablasolicitudes(1);
    <?php }else{ ?>
        $("#cnt_tabla").html("Usted no tiene permiso para este formulario.");
    <?php } ?>
}

function modal_estado(id_expedienteci, id_estadosci) {
    $("#id_expedienteci_copia").val(id_expedienteci);
    $("#id_estado_copia").val(id_estadosci).trigger('change.select2');
    $("#modal_estado").modal("show");
}

var tipo_persona = 1;
function nuevo_registro_persona(){
    if(tipo_persona == "1"){
        cambiar_nuevo();
    }else{
        cambiar_nuevo2();
    }
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
        tablasolicitudes(1);
        swal({ title: "¡Estado modificado exitosamente!", type: "success", showConfirmButton: true });
      }else{
          swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
      }
    });
}


var estado_pestana = "";
function cambiar_pestana(tipo){
    estado_pestana = tipo;
    tablasolicitudes(1);
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
      
      'language': {
        noResults: function () {
          return '<a href="javascript:;" data-toggle="modal" data-target="#modal_establecimiento" title="Agregar nuevo registro" onClick="cerrar_combo_establecimiento()">Agregar nuevo registro</a>';
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
    $("#div_combo_nacionalidad").children('h5').html("Nacionalidad:");
    document.getElementById("nacionalidad").required = false;
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
    $("#div_combo_tipo_doc").children('h5').html("Tipo documento identidad:");
    document.getElementById("id_doc_identidad").required = false;
    $(".select2").select2();
  });
}

function visualizar(id_personaci) {
  $.ajax({
    url: "<?php echo site_url(); ?>/persona/historial_persona/ver_persona",
    type: "post",
    dataType: "html",
    data: {id : id_personaci}
  })
  .done(function(res){
    $('#cnt_actions').html(res);
    $("#cnt_actions").show(0);
    $("#cnt_tabla").hide(0);
    $("#cnt_tabla_persona_natural").hide(0);
    $("#cnt_form_main").hide(0);
  });
}

function visualizar_empresa(id_empresa) {
  $.ajax({
    url: "<?php echo site_url(); ?>/persona/historial_persona/ver_persona_juridica",
    type: "post",
    dataType: "html",
    data: {id : id_empresa}
  })
  .done(function(res){
    $('#cnt_actions').html(res);
    $("#cnt_actions").show(0);
    $("#cnt_tabla").hide(0);
    $("#cnt_tabla_persona_natural").hide(0);
    $("#cnt_form_main").hide(0);
  });
}


function combo_ocupacion(seleccion){
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/combo_ocupacion",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_ocupacion').html(res);
    $(".select2").select2();
  });

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
    $("#cnt_tabla_persona_natural").show(0);
    $("#cnt_form_main").hide(0);
    $("#cnt_form_main2").hide(0);
    $("#cnt_actions").hide(0);
    $("#modal_delegado").modal('hide');
    $("#modal_actas_tipo").modal('hide');
    $("#modal_estado").modal('hide');
    //$("#cnt_actions").remove('.card');
}

function objetoAjax(){
    var xmlhttp = false;
    try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) { try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { xmlhttp = false; } }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp = new XMLHttpRequest(); }
    return xmlhttp;
}

function tablasolicitudes(tipo){
    tipo_persona = tipo;
    if(tipo == "1"){
        tabla_persona_natural();
    }else{
        tabla_persona_juridica();
    }
}

function tabla_persona_natural(){
    var nr_empleado = $("#nr_search").val();
    if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttpB=new XMLHttpRequest();
    }else{// code for IE6, IE5
        xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
    }
    xmlhttpB.onreadystatechange=function(){
        if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
            document.getElementById("cnt_tabla_persona_natural").innerHTML=xmlhttpB.responseText;
            $('[data-toggle="tooltip"]').tooltip();
            $('#myTable').DataTable();
        }
    }
    xmlhttpB.open("GET","<?php echo site_url(); ?>/persona/historial_persona/tabla_persona_natural?nr="+nr_empleado+"&tipo="+estado_pestana,true);
    xmlhttpB.send();
}

function tabla_persona_juridica(){
    var nr_empleado = $("#nr_search").val();
    if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttpB=new XMLHttpRequest();
    }else{// code for IE6, IE5
        xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
    }
    xmlhttpB.onreadystatechange=function(){
        if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
            document.getElementById("cnt_tabla_persona_juridica").innerHTML=xmlhttpB.responseText;
            $('[data-toggle="tooltip"]').tooltip();
            $('#myTable2').DataTable();
        }
    }
    xmlhttpB.open("GET","<?php echo site_url(); ?>/persona/historial_persona/tabla_persona_juridica?nr="+nr_empleado+"&tipo="+estado_pestana,true);
    xmlhttpB.send();
}


function alertFunc() {
    $('[data-toggle="tooltip"]').tooltip()
}

function cambiar_nuevo() {
    open_form(1);
    /*Inicio Solicitante*/
    $("#email").val('');
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
    $("#fecha_partida").val('');
    $("#fnacimiento_partida").val('');
    $("#sexo").val('');
    $("#estudios").val('');
    $("#nacionalidad").val('');
    $("#discapacidad_desc").val('');
    $("#discapacidad").val('');
    $("#posee_representante").val('');
    $("#pertenece_lgbt").val('');
    /*Fin Solicitante*/

    /*Inicio Expediente*/
    combo_nacionalidades('');
    combo_doc_identidad('');
    //combo_ocupacion('');
    combo_municipio();
    
    /*Fin expediente*/

    $("#band").val("save");
    $("#bandx").val("save");
    $("#band1").val("save");
    $("#band2").val("save");

    // $("#band6").val('save');

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


function cambiar_nuevo2(){
    $("#id_empresa").val('');
    $("#tiposolicitud_empresa").val('1');
    $("#nombre_empresa").val('');
    $("#abreviatura_empresa").val('');
    $("#telefono_empresa").val('');
    $("#numtotal_empresa").val('');
    $("#id_catalogociiu").val('').trigger('change.select2');
    $("#nit_empresa").val('');
    $("#id_municipio").val('').trigger('change.select2');
    $("#correoelectronico_empresa").val('');
    $("#direccion_empresa").val('');

    $("#estado_empresa").val('1');
    $("#band2").val('save');

    $("#ttl_form2").addClass("bg-success");
    $("#ttl_form2").removeClass("bg-info");

    $("#btnadd2").show(0);
    $("#btnedit2").hide(0);

    $("#cnt_tabla").hide(0);
    $("#cnt_form_main2").show(0);

    $("#ttl_form2").children("h4").html("<span class='mdi mdi-plus'></span> Nueva parte empleadora");
}



function cambiar_editar(id_expedienteci,bandera){
  open_form(1);
  if(bandera == "edit"){
    $.ajax({
      url: "<?php echo site_url(); ?>/persona/historial_persona/registros_expedientes",
      type: "POST",
      data: {id : id_expedienteci}
    })
    .done(function(res){
      result = JSON.parse(res)[0];
      $("#email").val(result.email);
      $("#id_personaci").val(result.id_personaci);
      $("#id_expedienteci").val(result.id_expedienteci);
      $("#nr").val($("#nr_search").val()).trigger('change.select2');
      $("#nombres").val(result.nombre_personaci);
      $("#conocido_por").val(result.conocido_por);
      $("#apellidos").val(result.apellido_personaci);
      $("#telefono").val(result.telefono_personaci);
      $("#telefono2").val(result.telefono2_personaci);
      $("#municipio").val(result.id_municipio).trigger('change.select2');
      $("#direccion").val(result.direccion_personaci);
      $("#fecha_nacimiento").datepicker("setDate", moment(result.fnacimiento_personaci).format("DD-MM-YYYY"));
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
          $('#tipo_aco').show(500);
          // $('#div_numero_doc_identidad').hide();
          // $("#dui").removeAttr("required");
          $('#dui').mask('99999999-9', {reverse: true});
          $('#div_numero_doc_identidad').show();
          $("#dui").attr("required",'required');
        }else {
          $('#partida_div').hide();
          $('#tipo_aco').hide(500);
          $('#div_numero_doc_identidad').show();
          $("#dui").attr("required",'required');
        }
      }else {
         $('#dui').mask('99999999-9', {reverse: true});
         $('#partida_div').hide();
         $('#tipo_aco').hide(500);
         $('#div_numero_doc_identidad').show();
         $("#dui").attr("required",'required');
      }
      $("#dui").val(result.dui_personaci);
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

      /*Inicio Expediente*/
      combo_doc_identidad(result.id_doc_identidad);
      combo_nacionalidades(result.nacionalidad_personaci);

      $("#band").val("edit");
      $("#bandx").val("edit");
      $("#band1").val("edit");
      $("#band2").val("edit");
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



function ocultar(){
  var value = $("#id_doc_identidad").val();
  if (value!=1) {
    $('#dui').mask('', {reverse: true});
    $('#dui').unmask();
    if (value==4) {
      $('#partida_div').show(500);
      // $('#div_numero_doc_identidad').hide(500);
      // $("#dui").removeAttr("required");
      $('#dui').mask('99999999-9', {reverse: true});
      $('#div_numero_doc_identidad').show(500);
    }else {
      $('#partida_div').hide(500);
      $('#div_numero_doc_identidad').show(500);
    }
  }else {
     $('#dui').mask('99999999-9', {reverse: true});
     $('#partida_div').hide(500);
     $('#div_numero_doc_identidad').show(500);
  }
}

function ocultar_pn(){
  var value = $("#tipo_establecimiento").val();
  if (value==1) {
    $("#razon_social").removeAttr("required");
    $("#abre_establecimiento").removeAttr("required");
    $('#ocultar_pn').hide(500);
  }else {
     $('#ocultar_pn').show(500);
     $("#razon_social").attr("required",'required');
     $("#abre_establecimiento").attr("required",'required');
  }
}

function cambiar_editar2(id_empresa,band){
        $.ajax({
          url: "<?php echo site_url(); ?>/persona/historial_persona/registro_empresa",
          type: "POST",
          data: {id : id_empresa}
        })
        .done(function(res){
          result = JSON.parse(res)[0];

          $("#id_empresa").val(result.id_empresa);
          $("#tiposolicitud_empresa").val(result.tiposolicitud_empresa);
          $("#id_oficina").val(result.id_oficina).trigger('change.select2');
          $("#nombre_empresa").val(result.nombre_empresa);
          $("#abreviatura_empresa").val(result.abreviatura_empresa);
          $("#telefono_empresa").val(result.telefono_empresa);
          $("#numtotal_empresa").val(result.numtotal_empresa);
          $("#id_catalogociiu").val(result.id_catalogociiu).trigger('change.select2');
          $("#nit_empresa").val(result.nit_empresa);
          $("#id_municipio").val(result.id_municipio).trigger('change.select2');
          $("#correoelectronico_empresa").val(result.correoelectronico_empresa);
          $("#direccion_empresa").val(result.direccion_empresa);
          $("#activobalance_empresa").val(result.activobalance_empresa);
          $("#capitalsocial_empresa").val(result.capitalsocial_empresa);
          $("#trabajadores_adomicilio_empresa").val(result.trabajadores_adomicilio_empresa);
          $("#tipo_empresa").val(result.tipo_empresa);
          $("#estado_empresa").val(result.estado_empresa);
          $("#band2").val(band);
        });

        $("#ttl_form2").removeClass("bg-success");
        $("#ttl_form2").addClass("bg-info");
        $("#cnt_tabla").hide(0);
        $("#cnt_form_main2").show(0);
        $("#ttl_form2").children("h4").html("<span class='fa fa-wrench'></span> Editar empresa");
    }


function OpenWindowWithPost(url, params){
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", url);
    form.setAttribute("target", "_SELF");

    for (var i in params) {
        if (params.hasOwnProperty(i)) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = i;
            input.value = params[i];
            form.appendChild(input);
        }
    }
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}


function redireccionar_despido_hecho(tipo, id, band){
    if(tipo == "1"){
        var param = { 'id_personaci' : id, 'tipo_solicitud' : '1', 'band_mantto' : band };
        OpenWindowWithPost("<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/", param);
    }else{
        var param = { 'id_empresa' : id, 'tipo_solicitud' : '1', 'band_mantto' : band };
        OpenWindowWithPost("<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica/", param);  
    }
}

function redireccionar_diferencia_laboral(tipo, id, band){
    if(tipo == "1"){
        var param = { 'id_personaci' : id, 'tipo_solicitud' : '2', 'band_mantto' : band };
        OpenWindowWithPost("<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/", param);
    }else{
        var param = { 'id_empresa' : id, 'tipo_solicitud' : '2', 'band_mantto' : band };
        OpenWindowWithPost("<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica/", param);  
    }
}

function redireccionar_retiro_voluntario(tipo, id, band){
    var param = { 'id_personaci' : id, 'tipo_solicitud' : '3', 'band_mantto' : band };
    OpenWindowWithPost("<?php echo site_url(); ?>/resolucion_conflictos/retiro_voluntario/", param);
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
                <h3 class="text-themecolor m-b-0 m-t-0">Gestión de personas solicitantes</h3>
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
            <div class="col-lg-12 row" id="cnt_form_main" style="display: none;">
                <div class="col-lg-1"></div>
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-header bg-success2" id="ttl_form">
                            <div class="card-actions text-white">
                                <a style="font-size: 16px;" onclick="cerrar_mantenimiento();"><i class="mdi mdi-window-close"></i></a>
                            </div>
                            <h4 class="card-title m-b-0 text-white">Listado de personas solicitantes</h4>
                        </div>
                        <div class="card-body b-t">

                        <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40', 'autocomplete' => 'off')); ?>
                        <div id="cnt_form1" class="cnt_form">
                          <h3 class="box-title" style="margin: 0px;">
                            <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 1</button>&emsp;Información de la persona solicitante
                          </h3><hr class="m-t-0 m-b-30">
                            <input type="hidden" id="band" name="band" value="save">
                            <input type="hidden" id="band1" name="band1" value="save">
                            <input type="hidden" id="estado" name="estado" value="1">
                            <input type="hidden" id="id_personaci" name="id_personaci" value="">
                            <input type="hidden" id="id_partida" name="id_partida" value="">

                            <span class="etiqueta">Datos de la persona</span>
                            <blockquote class="m-t-0">

                              <div class="row">
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                  <h5>Nombres: <span class="text-danger">*</span></h5>
                                  <input type="text" id="nombres" name="nombres" class="form-control" placeholder="Nombres de la persona" required="">
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                  <h5>Apellidos: <span class="text-danger">*</span></h5>
                                  <input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Apellidos de la persona" required="">
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                  <h5>Conocido por: </h5>
                                  <input type="text" id="conocido_por" name="conocido_por" class="form-control" placeholder="Conocido por">
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_tipo_doc"></div>
                                <div id="div_numero_doc_identidad" class="form-group col-lg-4" style="height: 83px;">
                                  <h5>Número de documento identidad: <span class="text-danger">*</span></h5>
                                  <input data-mask="99999999-9" data-mask-reverse="true" type="text" id="dui" name="dui" class="form-control" placeholder="Documento Unico de Identidad" required="">
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-lg-2" style="height: 83px;">
                                  <h5>Teléfono 1: </h5>
                                  <input data-mask="9999-9999" type="text" id="telefono" name="telefono" class="form-control" placeholder="Teléfono 1">
                                </div>
                                <div class="form-group col-lg-2" style="height: 83px;">
                                  <h5>Teléfono 2: </h5>
                                  <input data-mask="9999-9999" type="text" id="telefono2" name="telefono2" class="form-control" placeholder="Teléfono 2">
                                </div>

                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                  <h5>Municipio: <span class="text-danger">*</span></h5>
                                  <select id="municipio" name="municipio" class="select2" style="width: 100%" required>
                                    <option value=''>[Seleccione el municipio]</option>
                                    <?php
                                      $municipio = $this->db->query("SELECT * FROM org_municipio ORDER BY municipio");
                                      if($municipio->num_rows() > 0){
                                        foreach ($municipio->result() as $fila2) {
                                          echo '<option class="m-l-50" value="'.intval($fila2->id_municipio).'">'.$fila2->municipio.'</option>';
                                        }
                                      }
                                    ?>
                                  </select>
                                </div>
                                <div class="form-group col-lg-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                  <h5>Fecha de nacimiento: <span class="text-danger">*</span></h5>
                                  <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="dd-mm-yyyy">
                                  <div class="help-block"></div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-lg-8" style="height: 83px;">
                                  <h5>Dirección: <span class="text-danger">*</span></h5>
                                  <textarea type="text" id="direccion" name="direccion" class="form-control" placeholder="Dirección completa" required></textarea>
                                </div>
                                <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_nacionalidad"></div>
                              </div>
                              <div class="row">
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                                  <h5>Estudios realizados: </h5>
                                    <div class="controls">
                                      <select id="estudios" name="estudios" class="custom-select col-4" onchange="">
                                        <option value="">[Seleccione]</option>
                                        <option value="Sin estudio">Sin estudio</option>
                                        <option value="Educacion Básica">Educacion Básica</option>
                                        <option value="Bachillerato">Bachillerato</option>
                                        <option value="Universidad">Universidad</option>
                                      </select>
                                    </div>
                                </div>
                                <div class="form-group col-lg-2" style="height: 83px; display: none;">
                                  <h5>Persona representante:</h5>
                                  <input name="posee_representante" type="radio" id="si_posee" value='1'>
                                  <label for="si_posee">Si </label><Br>
                                  <input name="posee_representante" type="radio" id="no_posee" checked="" value='0' required>
                                  <label for="no_posee">No</label>
                                </div>

                                <div class="form-group col-lg-2" style="height: 83px;">
                                  <h5>Pertenece a LGTBI:</h5>
                                  <input name="pertenece_lgbt" type="radio" id="si_lgbt" value='1'>
                                  <label for="si_lgbt">Si </label><Br>
                                  <input name="pertenece_lgbt" type="radio" id="no_lgbt" checked="" value='0'>
                                  <label for="no_lgbt">No</label>
                                </div>

                                <div class="form-group col-lg-2" style="height: 83px;">
                                  <h5>Sexo de la persona:</h5>
                                  <input name="sexo" type="radio" id="masculino" checked="" value="M">
                                  <label for="masculino">Hombre</label>
                                  <input name="sexo" type="radio" id="femenino" value="F">
                                  <label for="femenino">Mujer</label>
                                  <div class="help-block"></div>
                                </div>
                                <div class="form-group col-lg-2" style="height: 83px;">
                                  <h5>Posee discapacidad:</h5>
                                  <input name="discapacidad" type="radio" id="si" value='1'>
                                  <label for="si">Si </label><Br>
                                  <input name="discapacidad" type="radio" id="no" checked="" value='0'>
                                  <label for="no">No</label>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-lg-8 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                  <h5>Dirección de correo electrónico: </h5>
                                  <input type="email" id="email" name="email" class="form-control" placeholder="example@dominio.com">
                                  <div class="help-block"></div>
                                </div>
                              </div>
                              <div class="row">
                                <div id="ocultar_div" class="form-group col-lg-8" style="height: 83px; display: none;">
                                  <h5>Discapacidad:</h5>
                                  <textarea type="text" id="discapacidad_desc" name="discapacidad_desc" class="form-control" placeholder="Ingrese la discapacidad"></textarea>
                                </div>
                              </div>
                            </blockquote>
                          <div class="pull-left">
                              <button type="button" class="btn waves-effect waves-light btn-default" onclick="cerrar_mantenimiento();"><i class="mdi mdi-chevron-left"></i> Salir</button>
                          </div>

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

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 row" id="cnt_form_main2" style="display: none;">
                <div class="col-lg-1"></div>
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-header bg-success2" id="ttl_form2">
                            <div class="card-actions text-white">
                                <a style="font-size: 16px;" onclick="cerrar_mantenimiento();"><i class="mdi mdi-window-close"></i></a>
                            </div>
                            <h4 class="card-title m-b-0 text-white">Listado de personas solicitantes</h4>
                        </div>
                        <div class="card-body b-t">

                            <?php echo form_open('', array('id' => 'formajax2', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                                <h3 class="box-title" style="margin: 0px;">
                                    <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 1</button>&emsp;
                                    Datos de la parte empleadora
                                </h3><hr class="m-t-0 m-b-30">
                                <input type="hidden" id="band2" name="band2" value="save">
                                <input type="hidden" id="id_empresa" name="id_empresa" value="">

                                <span class="etiqueta">Parte empleadora</span>
                                <blockquote class="m-t-0">
                                  <div class="row">
                                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                      <h5>Tipo de solicitud: <span class="text-danger">*</span></h5>
                                      <select id="tiposolicitud_empresa" name="tiposolicitud_empresa" class="form-control custom-select"  style="width: 100%" required="">
                                        <option class="m-l-50" value="1">Inscripción persona Natural</option>
                                        <option class="m-l-50" value="2">Inscripción persona jurídica</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Nombre de la parte empleadora: <span class="text-danger">*</span></h5>
                                        <textarea type="text" id="nombre_empresa" name="nombre_empresa" class="form-control" placeholder="Nombre de la parte empleadora" required=""></textarea>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Abreviatura: </h5>
                                        <div class="controls">
                                            <input type="text" id="abreviatura_empresa" name="abreviatura_empresa" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Teléfono:</h5>
                                        <div class="controls">
                                            <input type="text" id="telefono_empresa" name="telefono_empresa" data-mask="9999-9999" class="form-control" >
                                        </div>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Municipio: <span class="text-danger">*</span></h5>
                                        <select id="id_municipio" name="id_municipio" class="select2" style="width: 100%" required>
                                            <option value=''>[Seleccione el municipio]</option>
                                            <?php 
                                                $municipio = $this->db->query("SELECT * FROM org_municipio ORDER BY municipio");
                                                if($municipio->num_rows() > 0){
                                                    foreach ($municipio->result() as $fila2) {              
                                                       echo '<option class="m-l-50" value="'.intval($fila2->id_municipio).'">'.$fila2->municipio.'</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-8 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Correo Electrónico:</h5>
                                        <div class="controls">
                                            <input type="text" id="correoelectronico_empresa" name="correoelectronico_empresa" class="form-control">
                                        </div>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Dirección completa: <span class="text-danger">*</span></h5>
                                        <textarea type="text" id="direccion_empresa" name="direccion_empresa" class="form-control" placeholder="Dirección completa de la empresa"></textarea>
                                    </div>
                                  </div>
                                  <div class="row">
                                    <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Actividad económica: <span class="text-danger">*</span></h5>
                                        <select id="id_catalogociiu" name="id_catalogociiu" class="select2" style="width: 100%" required>
                                            <option value=''>[Seleccione la actividad]</option>
                                            <?php 
                                                $catalogociiu = $this->db->query("SELECT * FROM sge_catalogociiu ORDER BY actividad_catalogociiu");
                                                if($catalogociiu->num_rows() > 0){
                                                    foreach ($catalogociiu->result() as $fila2) {              
                                                       echo '<option class="m-l-50" value="'.$fila2->id_catalogociiu.'">'.$fila2->actividad_catalogociiu.'</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                  </div>
                                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>" style="display: none;">
                                        <h5>Estado: <span class="text-danger">*</span></h5>
                                        <select id="estado_empresa" name="estado_empresa" class="form-control custom-select"  style="width: 100%" required="">
                                            <option class="m-l-50" value="1">Activo</option>
                                            <option class="m-l-50" value="0">Inactivo</option>
                                        </select>
                                    </div>
                                </blockquote>

                                <button id="submit2" type="submit" style="display: none;"></button>
                                <div align="right" id="btnadd2">
                                    <button type="reset" class="btn waves-effect waves-light btn-success"><i class="mdi mdi-recycle"></i> Limpiar</button>
                                    <button type="submit" class="btn waves-effect waves-light btn-success2">Siguiente  <i class="mdi mdi-chevron-right"></i></button>
                                </div>

                            <?php echo form_close(); ?>
                            <!-- ============================================================== -->
                            <!-- Fin del FORMULARIO INFORMACIÓN DEL SOLICITANTE -->
                            <!-- ============================================================== -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12" id="cnt_actions" style="display:block;"></div>
            <div class="col-lg-1"></div>
            <div class="col-lg-12" id="cnt_tabla">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-b-0">Listado de personas solicitantes</h4>
                    </div>
                    <div class="card-body b-t" style="padding-top: 7px;">
                    <div>
                        <div class="pull-left" style="width: 50%;">
                            <div class="form-group">
                                <h5>Persona delegada:</h5>
                                <select id="nr_search" name="nr_search" class="select2" style="width: 100%" required="" onchange="tablasolicitudes(1);">
                                    <option value="">[Todos las personas delegadas]</option>
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
                            <button type="button" onclick="nuevo_registro_persona();" class="btn waves-effect waves-light btn-success2" data-toggle="tooltip" title="Clic para agregar un nuevo registro"><span class="mdi mdi-plus"></span> Nuevo registro</button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row" style="width: 100%"></div>
                    <div class="row col-lg-12">
                        <ul class="nav nav-tabs customtab2 <?php if($navegatorless){ echo "pull-left"; } ?>" role="tablist" style='width: 100%;'>
                            <li class="nav-item <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <a class="nav-link active" onclick="tablasolicitudes('1');" data-toggle="tab" href="#tab_persona_natural">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">Persona trabajadora</span></a>
                            </li>
                            <li class="nav-item <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <a class="nav-link" onclick="tablasolicitudes('2');" data-toggle="tab" href="#tab_persona_juridica">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">Parte empleadora</span></a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_persona_natural" role="tabpanel">
                            <div class="p-20">
                                <div id="cnt_tabla_persona_natural"></div>
                            </div>
                        </div>
                        <div class="tab-pane  p-20" id="tab_persona_juridica" role="tabpanel">
                            <div id="cnt_tabla_persona_juridica"></div>
                        </div>
                    </div>
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

<div id="cnt_modal_acciones"></div>
 

<script>

  function ocultar(){
  var value = $("#id_doc_identidad").val();
  if (value!=1) {
    if (value==4) {
      $('#partida_div').show(500);
      $('#tipo_aco').show(500);
      // $('#div_numero_doc_identidad').hide(500);
      // $("#dui").removeAttr("required");
      $('#dui').mask('99999999-9', {reverse: true});
      $('#div_numero_doc_identidad').show(500);
      $("#dui").attr("required",'required');
    }else {
      $('#dui').mask('', {reverse: true});
      $('#dui').unmask();
      $('#partida_div').hide(500);
      $('#tipo_aco').hide(500);
      $('#div_numero_doc_identidad').show(500);
      $("#dui").attr("required",'required');
    }
  }else {
     $('#dui').mask('99999999-9', {reverse: true});
     $('#partida_div').hide(500);
     $('#tipo_aco').hide(500);
     $('#div_numero_doc_identidad').show(500);
     $("#dui").attr("required",'required');
  }
}

function ocultar_tipo_doc_rep(){
  var value = $("#rep_tipo_doc").val();
  if (value!=1) {
    $('#dui_representante').mask('', {reverse: true});
    $('#dui_representante').unmask();
    if (value==4) {
      $('#dui_representante').mask('99999999-9', {reverse: true});
      $("#dui_representante").attr("required",'required');
    }else {
      $("#dui_representante").attr("required",'required');
    }
  }else {
     $('#dui_representante').mask('99999999-9', {reverse: true});
     $("#dui_representante").attr("required",'required');
  }
}

function ocultar_pn(){
  var value = $("#tipo_establecimiento").val();
  if (value==1) {
    // $("#razon_social").removeAttr("required");
    $("#abre_establecimiento").removeAttr("required");
    $('#ocultar_pn').hide(500);
  }else {
     $('#ocultar_pn').show(500);
     // $("#razon_social").attr("required",'required');
     $("#abre_establecimiento").attr("required",'required');
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

    $("input[name=sexo]").click(function(evento){
          var valor = $(this).val();
          if(valor == "M"){
              $("#embarazada").hide(500);
          }else{
              $("#embarazada").show(500);
          }
  });

      var date = new Date(); var currentMonth = date.getMonth(); var currentDate = date.getDate(); var currentYear = date.getFullYear();
        $('#fecha_nacimiento').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true, endDate: moment().format("DD-MM-YYYY")});
        $('#fnacimiento_menor').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true, endDate: moment().format("DD-MM-YYYY")});
        $('#fecha_partida').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true, endDate: moment().format("DD-MM-YYYY")});
        $('#f_nacimiento_representante').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true, endDate: moment().format("DD-MM-YYYY")});
        $('#fecha_conflicto').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true, endDate: moment().format("DD-MM-YYYY")});
    });
    });

$(function(){
    $("#formajax").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax"));
        formData.append("dato", "valor");

        $.ajax({
            url: "<?php echo site_url(); ?>/persona/historial_persona/gestionar_solicitudes",
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
              visualizar(res);
              if($("#band").val() == "save"){
                $.toast({ heading: '¡Registro exitoso!', text: 'Los datos de la persona solicitante fueron registrados', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
              }else if($("#band").val() == "edit"){
                $.toast({ heading: '¡Modificación exitosa!', text: 'Los datos de la persona solicitante fueron modificados', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
              }else if($("#band").val() == "delete"){
                $.toast({ heading: 'Borrado exitoso!', text: 'Los datos de la persona solicitante fueron eliminados', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
              }
              cerrar_mantenimiento()
              tablasolicitudes(1)
            }
        });

    });
});
$("#formajax2").on("submit", function(e){
    e.preventDefault();
    var f = $(this);
    var formData = new FormData(document.getElementById("formajax2"));
    formData.append("dato", "valor");
    $.ajax({
        url: "<?php echo site_url(); ?>/persona/historial_persona/gestionar_establecimiento",
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(res){
      console.log(res)
      res = res.split(",");
        if(res[0] == "exito"){
            if($("#band2").val() == "save"){
                $("#id_empresa").val(res[1])
                $.toast({ heading: '¡Registro exitoso!', text: 'Los datos de la persona solicitante fueron registrados', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
            }else if($("#band2").val() == "edit"){
                $.toast({ heading: '¡Modificación exitosa!', text: 'Los datos de la persona solicitante fueron modificados', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
            }
            cerrar_mantenimiento()
            visualizar_empresa(res[1])
        }else{
            swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
        }
    });
});

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
