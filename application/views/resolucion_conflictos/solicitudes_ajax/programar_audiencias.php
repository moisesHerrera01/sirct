<?php
$expediente = $expediente->result()[0];
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<script>

function abrir_resolucion(){
  $('#modal_pagos').modal('show');
  $('#modal_resolucion').modal('hide');
}

function resolucion(id_expedienteci,id_fechasaudienciasci, id_sindicato) {
  // alert(id_sindicato);
   $("#id_sindicato").val(id_sindicato);
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/audiencias/resolucion_audiencia",
    type: "post",
    dataType: "html",
    data: {id : id_expedienteci, id_audiencia:id_fechasaudienciasci,id_sindicato:id_sindicato}
  })
  .done(function(res){
    combo_defensores();
    if (id_sindicato!=false) {
      combo_directivos(0,id_sindicato);
    }
    combo_representante_empresa('', $("#id_empresa_audiencia").val());
    combo_delega2($("#id_empleado_audiencia").val());
    // combo_directivos(id_sindicato);
    combo_resultados();
    $('#cnt_modal_actions').html(res);
    $('#modal_resolucion').modal('show');
  });
}

function pagos(id_expedienteci, tipo_pago) {
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/pagos/pagos",
    type: "post",
    dataType: "html",
    data: {id : id_expedienteci, tipo : tipo_pago}
  })
  .done(function(res){
    $('#cnt_modal_pagos').html(res);
    $('#modal_pagos').modal('show');
    $('#modal_resolucion').modal('hide');
    tipo = $("#tipo_conciliacion").val();
    // alert(tipo)
    if (tipo==2) {
      $("#primer_pago").attr("required",'required');
      $("#p_pago").show(500);
      $("#boton_agregar").show(500);
    }else {
      $("#primer_pago").removeAttr("required");
      $("#p_pago").hide(0);
      $("#boton_agregar").hide(0);
    }
  });
}

var i =1;
function agregar(){
  var html = "<div id='fhpago"+i+"' class='form-group col-lg-5'>" +
        "<div class='controls'>" +
          "<input type='date' class='form-control' id='fecha_pago"+i+"' nombre='fecha_pago"+i+"'>" +
        "</div>" +
      "</div>" +

      "<div id='p_pago"+i+"' class='form-group col-lg-5' style='height: 50px;'>" +
          "<input type='number' id='primer_pago"+i+"' name='primer_pago"+i+"' class='form-control' placeholder='Monto del pago' step='0.01'>" +
          "<div class='help-block'></div>" +
      "</div>" +

      "<div class='form-group col-lg-2 col-sm-2'>" +
      "</div>";
  $('#nuevo').append(html);
  i++;
}

</script>

<div class="page-wrapper">
  <div class="container-fluid">
    <div class="row">

      <div class="col-lg-1"></div>
       <div class="col-lg-10">
        <div class="card">
          <div class="card-header bg-success2" id="ttl_form">
            <div class="card-actions text-white">
              <a style="font-size: 16px;" onclick="finalizar();">
                <i class="mdi mdi-window-close"></i>
              </a>
            </div>
            <h4 class="card-title m-b-0 text-white">Audiencias</h4>
          </div>
          <div class="card-body b-t">
            <h3 id="paso4" class="box-title" style="margin: 0px; display:none;">
                <button  type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 4</button>&emsp;
                Programar audiencias
              </h3>
            <h3 id="paso3" class="box-title" style="margin: 0px; display:none;">
                <button  type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 3</button>&emsp;
                Programar audiencias
              </h3>
              <hr class="m-t-0 m-b-30">
            <blockquote class="m-t-0">
                <table class="table no-border">
                    <tbody>
                      <div class="row">
                        <div class="form-group col-lg-2" style="height: 20px;">
                        <small class="text-muted db">N&uacute;mero de caso</small>
                        <h5><?=$expediente->numerocaso_expedienteci?></h5>
                        </div>

                        <div class="form-group col-lg-5" style="height: 20px;">
                        <small class="text-muted db"> Nombre delegado/a expediente</small>
                        <h5><?=$expediente->nombre_delegado_actual?></h5>
                        </div>

                        <div class="form-group col-lg-5" style="height: 20px;">
                        <small class="text-muted db">Nombre de solicitante</small>
                        <h5><?php if($expediente->tiposolicitud_expedienteci == "3"){
                            echo MB_STRTOUPPER($expediente->nombre_empresa);
                        }elseif ($expediente->tiposolicitud_expedienteci == "1" || $expediente->tiposolicitud_expedienteci == "2" ) {
                            echo MB_STRTOUPPER($expediente->nombre_personaci.' '.$expediente->apellido_personaci);
                        }elseif ($expediente->tiposolicitud_expedienteci == "4") {
                            echo MB_STRTOUPPER($expediente->nombre_sindicato);
                        }else {
                            echo MB_STRTOUPPER($expediente->nombre_personaci.' '.$expediente->apellido_personaci);
                        }?></h5>
                        </div>
                      </div>
                    </tbody>
                </table>
            </blockquote>
            <input type="hidden" name="id_empleado_audiencia" id="id_empleado_audiencia" value="<?= $expediente->id_empleado ?>">
            <input type="hidden" name="id_empresa_audiencia" id="id_empresa_audiencia" value="<?= $expediente->id_empresa ?>">
            <input type="hidden" name="id_empresa_audiencia" id="id_empresa_audiencia" value="<?= $id_sindicato ?>">
            <div id="cnt_form6" class="cnt_form">
              <?php echo form_open('', array('id' => 'formajax6', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

                <hr class="m-t-0 m-b-30">

                <input type="hidden" id="id_expedienteci1" name="id_expedienteci1" value="<?=$expediente->id_expedienteci?>">
                <input type="hidden" id="tipo_solicitud" name="tipo_solicitud" value="<?=$expediente->tiposolicitud_expedienteci?>">
                <input type="hidden" id="id_fechasaudienciasci" name="id_fechasaudienciasci" value= "">
                <input type="hidden" id="band4" name="band4" value="save">

                <div class="row">
                  <div class="form-group col-lg-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Fecha de audiencia: <span class="text-danger">*</span></h5>
                      <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="fecha_audiencia" name="fecha_audiencia" placeholder="dd/mm/yyyy" readonly="" required>
                      <div class="help-block"></div>
                  </div>
                  <div class="form-group col-lg-4" style="height: 83px;">
                      <h5>Hora de audiencia:</h5>
                      <input type="time" id="hora_audiencia" name="hora_audiencia" class="form-control" placeholder="Hora de audiencia" required>
                      <div class="help-block"></div>
                  </div>

                   <div id="div_orden" class="form-group col-lg-4" style="height: 83px;">
                      <h5>Orden:</h5>
                      <input name="numero_audiencia" type="radio" id="primera" checked="" value="1">
                      <label for="primera">Primera</label>
                      <input name="numero_audiencia" type="radio" id="segunda" value="2">
                      <label for="segunda">Segunda</label>
                      <div class="help-block"></div>
                </div>
                </div>
              <div align="right" id="btnadd6">
                <button type="reset" onclick="cambiar();" class="btn waves-effect waves-light btn-success">
                  <i class="mdi mdi-recycle"></i> Limpiar</button>
                <button type="submit" onclick="cambiar_nuevo5();" class="btn waves-effect waves-light btn-success2">
                  Guardar <i class="mdi mdi-chevron-right"></i>
                </button>
              </div>
              <div align="right" id="btnedit6" style="display: none;">
                <button type="reset" onclick="cambiar();" class="btn waves-effect waves-light btn-success">
                  <i class="mdi mdi-recycle"></i> Limpiar</button>
                <button type="submit" class="btn waves-effect waves-light btn-info">
                  Editar <i class="mdi mdi-chevron-right"></i>
                </button>
              </div>
            <?php echo form_close(); ?>
            </div>
          </div>
          <div class="col-lg-12" id="cnt_tabla_audiencias"></div>
          <div style="display:none" class="row" id="div_finalizar">
            <div class="col-lg-12" align="right">
              <div class="card-body">
                <button type="submit" onclick="finalizar();" class="btn waves-effect waves-light btn-success2">
                  Finalizar <i class="mdi mdi-chevron-right"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="cnt_modal_actions"></div>
  <div id="cnt_modal_pagos"></div>
</div>

<script>
function eliminar_audiencia(){
  $("#band4").val("delete");
  swal({
    title: "¿Está seguro?",
    text: "¡Desea eliminar el registro!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#fc4b6c",
    confirmButtonText: "Sí, deseo eliminar!",
    closeOnConfirm: false
  }, function(){

    $( "#formajax6" ).submit();

  });
 }

 function finalizar(){
   if ($("#tipo_solicitud").val()==2) {
     if ($("#myTable2 tbody tr td").length>=6) {
       // alert($("#myTable2 tbody tr td").length)
       cerrar_mantenimiento();
       swal({ title: "¡Programación de horarios exitosa!", type: "success", showConfirmButton: true });
     }else {
       swal({ title: "¡Se deben programar una audiencia!", type: "warning", showConfirmButton: true });
     }
   }else {
     if ($("#myTable2 tbody tr td").length>=12) {
      // alert($("#myTable2 tbody tr td").length)
       cerrar_mantenimiento();
       swal({ title: "¡Programación de horarios exitosa!", type: "success", showConfirmButton: true });
     }else {
       swal({ title: "¡Se deben programar dos audiencias!", type: "warning", showConfirmButton: true });
     }
   }
 }

 // function cerrar(){
 //   if (true) {
 //
 //   }
 //   $("#ocultar_div").hide();
 //   $("#cnt_tabla").show(0);
 //   $("#cnt_tabla_solicitudes").show(0);
 //   $("#cnt_form_main").hide(0);
 //   $("#cnt_actions").hide(0);
 //   $("#cnt_actions").remove('.card');
 //   open_form(1);
 //   tablasolicitudes();
 // }

 function cambiar(){
   $("#ttl_form").addClass("bg-success");
   $("#ttl_form").removeClass("bg-info");
   $("#btnadd6").show(0);
   $("#btnedit6").hide(0);
   $("#band4").val("save");
   combo_defensores('');
   combo_representante_empresa('');
   combo_delega2('');
 }

function cambiar_nuevo5(){
    //$("#id_fechasaudienciasci").val('');
    //$("#id_expedienteci1").val('');
    $("#fecha_fechasaudienciasci").val('');
    $("#hora_fechasaudienciasci").val('');
    $("#numero_audiencia").val('');
    $("#band4").val("save");

    $("#ttl_form").addClass("bg-success");
    $("#ttl_form").removeClass("bg-info");
    $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> programar_audiencias");
}

function cambiar_editar5(id_fechasaudienciasci,fecha_fechasaudienciasci,hora_fechasaudienciasci,id_expedienteci,estado_audiencia,
                         numero_fechasaudienciasci,id_defensorlegal,id_representaci,id_delegado,bandera){
    // combo_procuradores(id_procuradorci);
    $("#id_fechasaudienciasci").val(id_fechasaudienciasci);
    $("#fecha_audiencia").val(fecha_fechasaudienciasci);
    $("#hora_audiencia").val(hora_fechasaudienciasci);
    $("#id_expedienteci1").val(id_expedienteci);
    $("#estado_audiencia").val(estado_audiencia);
    combo_defensores(id_defensorlegal);
    combo_representante_empresa(id_representaci);
    combo_delega2(id_delegado);
    if (numero_fechasaudienciasci=='1') {
        document.getElementById('primera').checked = true;
    }else {
        document.getElementById('segunda').checked = true;
    }

    if(bandera == "edit"){
        $("#ttl_form").removeClass("bg-success");
        $("#ttl_form").addClass("bg-info");
        $("#btnadd6").hide(0);
        $("#btnedit6").show(0);
        $("#band4").val("edit")
    }else{
        eliminar_audiencia();
    }
}

$(function(){
    $("#formajax6").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax6"));
        var hora;
        $.ajax({
          url: "<?php echo site_url(); ?>/resolucion_conflictos/audiencias/gestionar_audiencia",
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
              if($("#band4").val() == "save"){
                  if (res =='ya_existe') {
                      cambiar_nuevo5();
                      swal({ title: "¡Choque de horarios!", type: "warning", showConfirmButton: true });
                  }else if (res == 'reprogramar') {
                    /*Validar si pedir motivo*/
                    swal({
                      title: "Reprogramar audiencias",
                      text: "Motivo para reprogramar audiencia: *",
                      type: "input",
                      showCancelButton: true,
                      closeOnConfirm: false,
                      inputPlaceholder: "Motivo para reprogramar"
                    }, function (inputValue) {
                      if (inputValue === false) return false;
                      if (inputValue === "") {
                        swal.showInputError("Se necesita un motivo para reprogramar.");
                        return false
                      }
                      $.ajax({
                          url: "<?php echo site_url(); ?>/resolucion_conflictos/audiencias/reprogramar_audiencia",
                          type: "post",
                          dataType: "html",
                          data: {
                            id: formData.get('id_expedienteci1'),
                            orden: formData.get('numero_audiencia'),
                            fecha: formData.get('fecha_audiencia'),
                            hora: formData.get('hora_audiencia'),
                            defensor: formData.get('defensor'),
                            representante_empresa: formData.get('representante_persona'),
                            delegado: formData.get('delegado'),
                            motivo: inputValue
                          }
                        })
                        .done(function (res) {
                            swal({ title: "¡Audiencia reprogramada!", type: "success", showConfirmButton: true });
                            tabla_audiencias(formData.get('id_expedienteci1'));
                            $('#formajax6').trigger("reset");
                            cambiar();
                        });
                    });
                    /*FIN Validar si pedir motivo*/
                  }else {
                    hora = formData.get('hora_audiencia')
                    cambiar_nuevo5();
                    swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
                    $('#formajax6').trigger("reset");
                    cambiar();
                    $("#hora_audiencia").val(hora);
                  }
                }else if($("#band4").val() == "edit"){
                  swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                  $("#btnadd6").show(0);
                  $("#btnedit6").hide(0);
              }else{
                  swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
              }
              tabla_audiencias(formData.get('id_expedienteci1'));
              // $('#formajax6').trigger("reset");
            }
        });

    });
});

$(function(){
    $(document).ready(function(){
      var date = new Date(); var currentMonth = date.getMonth(); var currentDate = date.getDate(); var currentYear = date.getFullYear();
        $('#fecha_audiencia').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true, startDate: '+1d'}).datepicker("setDate", new Date());
    });
    });
</script>
