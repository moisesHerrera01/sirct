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

    <?php if(isset($band_mantto)){ ?>
      cambiar_nuevo();
    <?php } ?>

    <?php if(tiene_permiso($segmentos=2,$permiso=1)){ ?>
    combo_delegado_tabla();
    <?php }else{ ?>
        $("#cnt_tabla").html("Usted no tiene permiso para este formulario.");
    <?php } ?>
}

function combo_delegado_tabla(seleccion){

  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/combo_delegado_tabla",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_delegado_tabla').html(res);
    <?php if(obtener_rango($segmentos=2, $permiso=1)>1){?>
            $("#nr_search").select2();
      <?php } ?>
    tablasolicitudes();
  });
}

function combo_resultados(seleccion){
  $.ajax({
    url: "<?php echo site_url(); ?>/conflictos_colectivos/sindicato/combo_resultados",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_resultados').html(res);
    $("#resolucion").select2();
  });
}

function combo_motivos(seleccion){

  $.ajax({
    url: "<?php echo site_url(); ?>/conflictos_colectivos/solicitud_indemnizacion/combo_motivo_solicitud",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_motivos').html(res);
    $("#motivo").select2();
  });
}

function combo_tipo_directivos(seleccion){

  $.ajax({
    url: "<?php echo site_url(); ?>/conflictos_colectivos/sindicato/combo_tipo_directivos",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_tipo_directivos').html(res);
    $("#tipo_directivo").select2();
  });
}

function cerrar_combo_defensores() {
    $("#defensor").select2('close');
    combo_tipo_representante();
}

function combo_delega2(seleccion){

  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/combo_delega2",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_delegado2').html(res);
    $("#delegado").select2();
  });
}


// function combo_representante_empresa(seleccion){
//   var id_emp = $("#id_empresaci").val();
//   $.ajax({
//     url: "<?php echo site_url(); ?>/resolucion_conflictos/establecimiento/combo_representante_empresa?id_empresaci="+id_emp,
//     type: "post",
//     dataType: "html",
//     data: {id : seleccion}
//   })
//   .done(function(res){
//     $('#div_combo_representante_empresa').html(res);
//     $("#representante_empresa").select2();
//   });
// }

function combo_representante_empresa(seleccion){
  var id_emp = $("#id_empresaci").val();
  $("#establecimiento").val(id_emp);
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/establecimiento/combo_representante_empresa?id_empresaci="+id_emp,
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_representante_empresa').html(res);
    $("#representante_empresa").select2({

        'language': {
            noResults: function () {
                return '<div align="right"><a href="javascript:;" data-toggle="modal" title="Agregar nuevo registro" class="btn btn-success2" onClick="cerrar_combo_representante()"><span class="mdi mdi-plus"></span>Agregar nuevo registro</a></div>';
            }
        }, 'escapeMarkup': function (markup) { return markup; }
    });
    $('#representante_empresa').trigger('change.select2');
  });
}

function cerrar_combo_representante() {

    $.ajax({
        url: "<?php echo site_url(); ?>/conflictos_colectivos/solicitud_indemnizacion/modal_representante",
        type: "post",
        dataType: "html"
    }) .done(function(res){
        $('#cnt_modal_acciones').html(res);
        combo_profesiones();
        combo_municipio2();
        combo_estados_civiles();
        $('#modal_representante').modal('show');
    });

    $("#representante_empresa").select2('close');
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
    $("#tipo_representante_persona").select2();
  });
}

function combo_defensores(seleccion){
    $.ajax({
      async: true,
      url: "<?php echo site_url(); ?>/resolucion_conflictos/representante_persona/combo_defensores",
      type: "post",
      dataType: "html",
      data: {id : seleccion}
    })
    .done(function(res){
        $.when($('#div_combo_defensores').html(res) ).then(function( data, textStatus, jqXHR ) {
            $("#defensor").select2({

                'language': {
                    noResults: function () {
                        return '<div align="right"><a href="javascript:;" data-toggle="modal" data-target="#modal_defensores" title="Agregar nuevo registro" class="btn btn-success2" onClick="cerrar_combo_defensores()"><span class="mdi mdi-plus"></span>Agregar nuevo registro</a></div>';
                    }
                }, 'escapeMarkup': function (markup) { return markup; }
            });
            //tabla_representantes()
        });
    });
}

function modal_estado(id_expedienteci, id_estadosci) {
    $("#id_expedienteci_copia").val(id_expedienteci);
    $("#id_estado_copia").val(id_estadosci).trigger('change.select2');
    $("#modal_estado").modal("show");
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
      if(res != "fracaso"){
        cerrar_mantenimiento()
        tablasolicitudes();
        swal({ title: "¡Persona delegada modificada exitosamente!", type: "success", showConfirmButton: true });
      }else{
          swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
      }
    });
}

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

function modal_delegado(id_expedienteci,id_personal) {
    $("#id_expedienteci_copia").val(id_expedienteci);
    $("#modal_delegado").modal("show");
    combo_cambiar_delegado(id_personal);
}

function tabla_delegados(id_expedienteci){
    if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttpB=new XMLHttpRequest();
    }else{// code for IE6, IE5
        xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
    }
    xmlhttpB.onreadystatechange=function(){
        if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
            document.getElementById("cnt_tabla_delegados").innerHTML=xmlhttpB.responseText;
            //$('[data-toggle="tooltip"]').tooltip();
            $('#myTable').DataTable();
        }
    }
    xmlhttpB.open("GET","<?php echo site_url(); ?>/resolucion_conflictos/expediente/tabla_delegados?id="+id_expedienteci,true);
    xmlhttpB.send();
}

function modal_bitacora_delegados(id_expedienteci) {
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/bitacora_delegados",
    type: "post",
    dataType: "html",
    data: {id : id_expedienteci}
  })
  .done(function(res){
    $('#cnt_modal_bitacora_delegado').html(res);
    $('#modal_bitacora_delegados').modal('show');
    tabla_delegados(id_expedienteci);
  });
}

function combo_cambiar_delegado(seleccion){

  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/combo_cambiar_delegado",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_cambiar_delegado').html(res);
    $("#id_personal_copia").select2();
  });
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
    $("#btnvolver").hide(0);
    $("#btncerrar").show(0);
    $("#btnadd2").hide(0);
    $("#paso2").hide(0);
    $("#btnedit5").show(0);
    $("#cnt_tabla").hide(0);
    $("#cnt_tabla_sindicatos").hide(0);
    $("#cnt_form_main").show(0);
    $("#id_sindicato").val(id_sindicato);
    tabla_directivos();
}

// function audiencias(id_expedienteci) {
//   $.ajax({
//     url: "<?php echo site_url(); ?>/resolucion_conflictos/audiencias/programar_audiencias",
//     type: "post",
//     dataType: "html",
//     data: {id : id_expedienteci}
//   })
//   .done(function(res){
//     console.log(res)
//     $('#cnt_actions').html(res);
//     $("#cnt_actions").show(0);
//     $("#cnt_tabla").hide(0);
//     $("#cnt_tabla_solicitudes").hide(0);
//     $("#cnt_form_main").hide(0);
//     tabla_audiencias(id_expedienteci);
//   });
// }

function audiencias(id_empresaci, id_expedienteci, origen, id_sindicato) {
  $("#id_empresaci").val(id_empresaci);
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/audiencias/programar_audiencias",
    type: "post",
    dataType: "html",
    data: {id : id_expedienteci,id_sindicato:id_sindicato}
  })
  .done(function(res){
    //console.log(res)
    $('#cnt_actions').html(res);
    $("#cnt_actions").show(0);
    $("#cnt_tabla").hide(0);
    $("#cnt_tabla_solicitudes").hide(0);
    $("#cnt_form_main").hide(0);
    combo_defensores();
    combo_directivos(0,id_sindicato);
    combo_representante_empresa();
    combo_delega2();
    if (origen==1) {
        $("#paso4").show(0);
        tabla_audiencias(id_expedienteci, id_sindicato);
        $("#div_finalizar").show(0);
    }else {
      tabla_audiencias(id_expedienteci, id_sindicato);
    }

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
    tablasolicitudes();
}

function combo_municipio(){
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/establecimiento/combo_municipio",
    type: "post",
    dataType: "html"
  })
  .done(function(res){
    $('#div_combo_municipio').html(res);
    $("#municipio2").select2();
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
    $("#act_economica").select2();
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
    $("#id_personal").select2();
  });
}

function cerrar_combo_establecimiento() {
    var select2 = $('.select2-search__field').val();
    $("#nombre_establecimiento").val(select2);
    $("#establecimiento").select2('close');
}

function combo_establecimiento(seleccion){
  $.ajax({
    async: true,
    url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/combo_establecimiento",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_establecimiento').html(res);
    $("#establecimiento").select2({

      'language': {
        noResults: function () {
          return '<div align="right"><a href="javascript:;" data-toggle="modal" data-target="#modal_establecimiento" title="Agregar nuevo registro" class="btn btn-success2" onClick="cerrar_combo_establecimiento()"><span class="mdi mdi-plus"></span>Agregar nuevo registro</a></div>';
        }
      },
      'escapeMarkup': function (markup) {
        return markup;
      }
    });
  });
}
//
// function combo_directivos(seleccion,id_sindicato){
//    // alert(id_sindicato)
//     $.ajax({
//       async: true,
//       url: "<?php echo site_url(); ?>/conflictos_colectivos/directivos/combo_directivos",
//       type: "post",
//       dataType: "html",
//       data: {id : seleccion, id_sindicato: id_sindicato}
//     })
//     .done(function(res){
//         $.when($('#div_combo_directivos').html(res) ).then(function( data, textStatus, jqXHR ) {
//             $("#directivo").select2({
//
//                 'language': {
//                     noResults: function () {
//                         return '<div align="right"><a href="javascript:;" data-toggle="modal" data-target="#modal_directivo" title="Agregar nuevo registro" class="btn btn-success2" onClick="cerrar_combo_directivo('+id_sindicato+')"><span class="mdi mdi-plus"></span>Agregar nuevo registro</a></div>';
//                     }
//                 }, 'escapeMarkup': function (markup) { return markup; }
//             });
//         });
//     });
// }

function combo_directivos(seleccion, id_sindicato){
  var id_emp = $("#id_empresaci").val();

  $("#establecimiento").val(id_emp);
  $.ajax({
    async: true,
    url: "<?php echo site_url(); ?>/conflictos_colectivos/directivos/combo_directivos",
    type: "post",
    dataType: "html",
    data: {id : seleccion, id_sindicato: id_sindicato}
  })
  .done(function(res){
    $('#div_combo_directivos').html(res);
    if (id_ultimos_directivos!="") {
      $('#directivo').val(id_ultimos_directivos);
    }
    $("#directivo").select2({

        'language': {
            noResults: function () {
                return '<div align="right"><a href="javascript:;" data-toggle="modal" title="Agregar nuevo registro" class="btn btn-success2" onClick="cerrar_combo_directivo('+id_sindicato+')"><span class="mdi mdi-plus"></span>Agregar nuevo registro</a></div>';
            }
        }, 'escapeMarkup': function (markup) { return markup; }
    });
    // $('#representante_empresa').trigger('change.select2');
  });
}

var id_ultimos_directivos = "";

function modal_directivo(id_sindicato,tipo) {
  // alert($('#directivo').val());
  id_ultimos_directivos = $('#directivo').val();
  $('#id_sindicato').val(id_sindicato);
  $.ajax({
    url: "<?php echo site_url(); ?>/conflictos_colectivos/directivos/modal_directivos",
    type: "post",
    dataType: "html",
    data: {id_sindicato : id_sindicato, tipo: tipo}
  })
  .done(function(res){
    $('#cnt_modal_directivo').html(res);
    combo_tipo_directivos();
    $('#modal_directivo').modal('show');
    // $("#solicitud_pn_pj").hide();
    // $("#sc_conciliada_pago").hide();
    // $("#pc_sin_conciliar").hide();
    // $("#inasistencia").hide();
  });
}

function cerrar_combo_directivo(id_sindicato) {
    modal_directivo(id_sindicato,2);
    $('#modal_resolucion').modal('hide');
    $("#directivo").select2('close');
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
            document.getElementById("cnt_tabla_sindicatos").innerHTML=xmlhttpB.responseText;
            //$('[data-toggle="tooltip"]').tooltip();
            $('#myTable').DataTable();
        }
    }
    xmlhttpB.open("GET","<?php echo site_url(); ?>/conflictos_colectivos/sindicato/tabla_sindicatos?nr="+nr_empleado+"&tipo="+estado_pestana,true);
    xmlhttpB.send();
}

function tabla_audiencias(id_expedienteci, id_sindicato){
  // alert(id_sindicato);
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
    xmlhttpB.open("GET","<?php echo site_url(); ?>/resolucion_conflictos/audiencias/tabla_audiencias?id_expedienteci="+id_expedienteci+"&id_sindicato="+id_sindicato,true);
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
    combo_motivos();
    $("#motivo").val('');
    $("#descripcion_motivo").val();
    $("#id_personal").val('');
    $("#establecimiento").val('');
    /*Fin expediente*/

    $("#band").val("save");
    $("#band1").val("save");
    $("#band3").val("save");
    $("#band4").val('save');
    $("#bandx").val("save");

    $("#ttl_form").addClass("bg-success");
    $("#ttl_form").removeClass("bg-info");

    $("#btnadd").show(0);
    $("#btnedit").hide(0);
    $("#btnvolver").show(0);
    $("#btncerrar").hide(0);

    $("#cnt_tabla").hide(0);
    $("#cnt_form_main").show(0);

    $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> Nueva Solicitud");
    combo_establecimiento('');
}

function cambiar_nuevo2(sindicato){

  $("#id_directivo").val('');
  $("#nombre_directivo").val('');
  $("#apellido_directivo").val('');
  $("#dui_directivo").val('');
  $("#tipo_directivo").val('');
  $("#acreditacion_directivo").val('');
  $("#band2").val('save');

  // $("#modal_directivo").modal('show');
  modal_directivo(sindicato,1);
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
      $("#id_exp").val(result.id_expedienteci);
      $("#id_expedienteci").val(result.id_expedienteci);
      //combo_municipio2(result.id_municipio);
      $("#municipio").val(result.id_municipio.padStart(5,"00000")).trigger('change.select2');
      $("#nr").val($("#nr_search").val()).trigger('change.select2');
      $("#nombre_sindicato").val(result.nombre_sindicato);
      $("#direccion_sindicato").val(result.direccion_sindicato);
      $("#telefono_sindicato").val(result.telefono_sindicato);
      $("#totalafiliados_sindicato").val(result.totalafiliados_sindicato);
      /*Fin sindicato*/

      /*Inicio Expediente*/
      combo_actividad_economica(result.id_catalogociiu);
      combo_municipio(result.municipio_empresa);
      combo_delegado(result.id_personal);
      combo_motivos(result.causa_expedienteci);
      combo_tipo_directivos(result.tipo_directivo);
      $("#fecha_creacion_exp").val(result.fechacrea_expedienteci);
      $("#motivo").val(result.motivo_expedienteci);
      $("#descripcion_motivo").val(result.descripmotivo_expedienteci);
      combo_establecimiento(result.id_empresaci);
      /*Fin expediente*/

      $("#band").val("edit");
      $("#band1").val("edit");
      $("#band3").val("edit");
      $("#band4").val('edit');
      $("#bandx").val("edit");
    });

    $("#ttl_form").removeClass("bg-success");
    $("#ttl_form").addClass("bg-info");
    $("#btnadd1").hide(0);
    $("#btnvolver").show(0);
    $("#btncerrar").hide(0);
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
      combo_tipo_directivos(result.tipo_directivo);
      $("#acreditacion_directivo").val(result.acreditacion_directivo);
      if (result.sexo_directivo=='M') {
        document.getElementById('masculino').checked =true;
        $("#masculino").attr('checked',result.sexo_directivo);
      }else {
        // document.getElementById('femenino').checked =true;
        $("#femenino").attr('checked',result.sexo_directivo);
      }
      /*Fin directivo*/
      $("#band2").val("edit");
    });
    // $("#modal_directivo").modal('show');
    modal_directivo(result.id_sindicato,1);
  }
}

function volver(num) {
  open_form(num);
  $("#band"+num).val("edit")
}

  function editar_solicitud(){ $("#band").val("edit"); enviarDatos(); }

</script>

<input type="hidden" id="address" name="">
<input type="hidden" id="bandx" name="bandx">
<div class="page-wrapper">
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- TITULO de la página de sección -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="align-self-center" align="center">
                <h3 class="text-themecolor m-b-0 m-t-0">Solicitud por conflicto laboral</h3>
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
                              Información de la persona solicitante
                            </h3><hr class="m-t-0 m-b-30">
                            <input type="hidden" id="band" name="band" value="save">
                            <input type="hidden" id="band1" name="band1" value="save">
                            <input type="hidden" id="estado" name="estado" value="1">
                            <input type="hidden" id="id_sindicato" name="id_sindicato" value="">
                            <input type="hidden" id="id_exp" name="id_exp" value="">


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
                        <!-- ============================================================== -->
                        <!-- INICIA MANTENIMIENTO DE DIRECTIVOS -->
                        <!-- ============================================================== -->
                        <div id="cnt_form3" class="cnt_form" style="display: none;">
                            <h3 class="box-title" style="margin: 0px;">
                                <button id="paso2" type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 2</button>&emsp;
                                Datos de la persona directiva:
                            </h3><hr class="m-t-0 m-b-30">

                            <div id="cnt_tabla_directivos"></div>

                            <div id="btncerrar" class="pull-left" style="display: none;">
                                <button type="button" class="btn waves-effect waves-light btn-default" onclick="cerrar_mantenimiento();"><i class="mdi mdi-chevron-left"></i> Salir</button>
                            </div>

                            <div id="btnvolver" class="pull-left">
                                <button type="button" class="btn waves-effect waves-light btn-default" onclick="open_form(1)"><i class="mdi mdi-chevron-left"></i> Volver</button>
                            </div>

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
                                  <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_establecimiento"></div>

                                  <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_motivos"></div>

                                  <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_delegado"></div>
                                </div>

                                <div class="row">
                                  <div class="form-group col-lg-12">
                                      <h5>Puntos a tratar en la audiencia:<span class="text-danger">*</h5>
                                      <textarea type="text" id="descripcion_motivo" name="descripcion_motivo" rows="8" class="form-control" placeholder="Descipción del motivo"></textarea>
                                      <div class="help-block"></div>
                                  </div>
                                </div>
                            </blockquote>

                            <div class="pull-left">
                                <button type="button" class="btn waves-effect waves-light btn-default" onclick="open_form(3)"><i class="mdi mdi-chevron-left"></i> Volver</button>
                            </div>

                            <div align="right" id="btnadd3">
                              <button type="reset" class="btn waves-effect waves-light btn-success">
                                <i class="mdi mdi-recycle"></i> Limpiar
                              </button>
                              <button type="submit" class="btn waves-effect waves-light btn-success2">Siguiente
                                <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                            <div align="right" id="btnedit3" style="display: none;">
                              <button type="reset" class="btn waves-effect waves-light btn-success">
                                <i class="mdi mdi-recycle"></i> Limpiar</button>
                              <button type="submit" class="btn waves-effect waves-light btn-info">Siguiente
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
                      <?php if (obtener_rango($segmentos=2, $permiso=1) > 1) { ?>
                        <div class="pull-left">
                            <div class="form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_delegado_tabla" style="width: 400px;"></div>
                        </div>
                      <?php }else{ ?>
                        <input type="hidden" id="nr_search" name="nr_search" value="<?= $this->session->userdata('nr')?>">
                      <?php } ?>
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
            <div id="cnt_modal_bitacora_delegado"></div>
            <div id="cnt_modal_directivo"></div>
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
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <?php echo form_open('', array('id' => 'formajax4', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
          <input type="hidden" id="band3" name="band3" value="save">
          <!-- <input type="hidden" id="id_representante" name="id_representante" value=""> -->
          <input type="hidden" id="id_empresaci" name="id_empresaci" value="">
            <div class="modal-header">
                <h4 class="modal-title">Gestión de parte empleadora</h4>
            </div>
            <div class="modal-body" id="">

              <div class="row">
                <div class="form-group col-lg-6 col-sm-6 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Tipo: <span class="text-danger">*</span></h5>
                    <div class="controls">
                      <select id="tipo_establecimiento" name="tipo_establecimiento" class="custom-select col-4" onchange="ocultar_pn()" required>
                        <option value="">[Seleccione]</option>
                        <option value="1">Persona natural</option>
                        <option value="2">Persona jurídica</option>
                      </select>
                    </div>
                </div>

                <div class="form-group col-lg-16 col-sm-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                    <h5>Nombre de la parte empleadora:</h5>
                    <div class="controls">
                        <input type="text" placeholder="Nombre" id="nombre_establecimiento" name="nombre_establecimiento" class="form-control">
                    </div>
                </div>
              </div>

                <div class="row" id="ocultar_pn">
                  <div class="form-group col-lg-6 col-sm-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Razon social:</h5>
                      <div class="controls">
                          <input type="text" placeholder="Nombre" id="razon_social" name="razon_social" class="form-control" required="">
                      </div>
                  </div>

                  <div class="form-group col-lg-6 col-sm-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Abreviatura: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" placeholder="Abreviatura" id="abre_establecimiento" name="abre_establecimiento" class="form-control" required>
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
                  <div class="col-lg-6 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_municipio"></div>

                  <div class="form-group col-lg-6 col-sm-6 <?php if($navegatorless){ echo " pull-left"; } ?>">
                      <h5>Telefono: </h5>
                      <div class="controls">
                          <input type="text" placeholder="Telefono" id="telefono_establecimiento" name="telefono_establecimiento" class="form-control" data-mask="9999-9999" required>
                          <div class="help-block"></div>
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_actividad_economica"></div>
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

<!--INICIA MODAL DE PROCURADOR -->
<div class="modal fade" id="modal_defensores" role="dialog">
<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <?php echo form_open('', array('id' => 'formajax8', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
      <input type="hidden" id="band6" name="band6" value="save">
      <input type="hidden" id="id_procuradorci" name="id_procuradorci" value="">
      <!-- <input type="hidden" id="id_representante" name="id_representante" value=""> -->
        <div class="modal-header">
            <h4 class="modal-title">Gestión de personas defensoras legales</h4>
        </div>
        <div class="modal-body" id="">
          <div class="row">
            <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                <h5>Nombres de la persona: <span class="text-danger">*</span></h5>
                <div class="controls">
                    <input type="text" id="nombre_representante_persona" name="nombre_representante_persona" class="form-control" placeholder="Nombre de la persona representante" required>
                </div>
            </div>

            <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                <h5>Apellidos de la persona: <span class="text-danger">*</span></h5>
                <div class="controls">
                    <input type="text" id="apellido_representante_persona" name="apellido_representante_persona" class="form-control" placeholder="Apellidos de la persona representante" required>
                </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                <h5>DUI de la persona: <span class="text-danger">*</span></h5>
                <div class="controls">
                    <input data-mask="99999999-9" type="text" id="dui_representante_persona" name="dui_representante_persona" class="form-control" placeholder="Dui de la persona representante" required>
                </div>
            </div>

            <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                <h5>Tel&eacute;fono de la persona: <span class="text-danger">*</span></h5>
                <div class="controls">
                    <input data-mask="9999-9999" type="text" id="telefono_representante_persona" name="telefono_representante_persona" class="form-control" placeholder="telefono de la persona representante" required>
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

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger waves-effect text-white" data-dismiss="modal">Cerrar</button>
            <button type="submit" id="submit4" class="btn btn-info waves-effect text-white">Aceptar</button>
        </div>
      <?php echo form_close(); ?>
</div>
</div>
</div>
<!--FIN MODAL DE PROCURADOR -->
<!--INICIO MODAL DE DELEGADO -->
<div class="modal fade" id="modal_delegado" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Cambiar asignación de persona delegada:</h4>
      </div>

      <div class="modal-body" id="">
          <input type="hidden" id="id_expedienteci_copia" name="id_expedienteci_copia" value="">
          <div class="row">
            <div class="col-lg-12 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_cambiar_delegado"></div>
            <!-- <div class="form-group col-lg-12 col-sm-12">
                <div class="form-group">
                    <h5>Persona delegada:<span class="text-danger">*</h5>
                    <select id="id_personal_copia" name="id_personal_copia" class="select2" style="width: 100%" required="">
                    <option value="">[Todas las personas empleadas]</option>
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
            </div> -->
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
<div style="display:none;">
    <button  id="submit_ubi" name="submit_ubi" type="button"  >clicks</button>
</div>

<script>
/*AJAX SINDICATO*/
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
              // $("#band1").val( $("#band").val() );
              // $("#band2").val( $("#band").val() );
              $("#band1").val('edit');
              $("#band2").val($("#bandx").val());
              if($("#band").val() == "delete"){
                swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
              }
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
          console.log(res)
          res = res.split(",");
            if(res[0] == "exito"){
                if($("#band3").val() == "save"){
                    //$("#id_empresa").val(res[1])
                    $("#modal_establecimiento").modal('hide');
                    $.toast({ heading: 'Registro exitoso', text: 'Registro exitoso', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
                    combo_establecimiento(res[1]);
                }else if($("#band3").val() == "edit"){
                    swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                    tabla_representantes();
                }
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
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
        formData.append("id_exp", $('#id_exp').val());
        formData.append("id_empresaci", $('#establecimiento').val());
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
            // if(res == "fracaso"){
            //   swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            // }else{
            //   cerrar_mantenimiento();
            //   if($("#band4").val() == "save"){
            //       swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
            //   }else if($("#band4").val() == "edit"){
            //       swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
            //   }else{
            //       swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
            //   }
            //   tablasolicitudes();
            if(res == "fracaso"){
              swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }else{
              cerrar_mantenimiento();
              audiencias($('#establecimiento').val(),res,1);
              //alert(res)
            }
        });

    });
});

function desactivar(id_directivo) {
    swal({
        title: "Confirmar Dar de Baja",
        text: "¿Está seguro que desea dar de baja a la persona solicitante?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-success2",
        confirmButtonText: "Si",
        closeOnConfirm: false
    },
    function () {
        $.ajax({
            url: "<?php echo site_url(); ?>/conflictos_colectivos/directivos/bajar_directivo",
            type: "post",
            dataType: "html",
            data: {
                id: id_directivo,
            }
        })
        .done(function (res) {
            if (res == "exito") {
                tabla_directivos();
                swal({
                    title: "¡Persona directiva desactivada exitosamente!",
                    type: "success",
                    showConfirmButton: true
                });
            } else {
                swal({
                    title: "¡Ups! Error",
                    text: "Intentalo nuevamente.",
                    type: "error",
                    showConfirmButton: true
                });
            }
        });
    });
}

function activar(id_directivo) {
    swal({
        title: "Confirmar Activación",
        text: "¿Está seguro que desea activar a la persona solicitante?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-success2",
        confirmButtonText: "Si",
        closeOnConfirm: false
    },
    function () {
        $.ajax({
            url: "<?php echo site_url(); ?>/conflictos_colectivos/directivos/activar_directivo",
            type: "post",
            dataType: "html",
            data: {
                id: id_directivo,
            }
        })
        .done(function (res) {
            if (res == "exito") {
                tabla_directivos();
                swal({
                    title: "¡Persona directiva activado exitosamente!",
                    type: "success",
                    showConfirmButton: true
                });
            } else {
                swal({
                    title: "¡Ups! Error",
                    text: "Intentalo nuevamente.",
                    type: "error",
                    showConfirmButton: true
                });
            }
        });
    });
}

$("#formajax8").on("submit", function(e){
    e.preventDefault();

    var f = $(this);
    var formData = new FormData(document.getElementById("formajax8"));
    formData.append("dato", "valor");
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
      console.log(res)
      res = res.split(",");
        if(res[0] == "exito"){
            if($("#band6").val() == "save"){
                //$("#id_empresa").val(res[1])
                $("#modal_defensores").modal('hide');
                $.toast({ heading: 'Registro exitoso', text: 'Registro de persona defensora exitoso', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
                combo_defensores(res[1]);
            }else if($("#band6").val() == "edit"){
                swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                // tabla_representantes();
            }
        }else{
            swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
        }
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
