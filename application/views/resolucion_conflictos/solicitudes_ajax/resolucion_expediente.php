<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<script>
  function abrir_resolucion(){
    $('#modal_pagos').modal('hide');
    $('#modal_resolucion').modal('show');
  }
</script>

<div class="modal fade" id="modal_resolucion" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Registrar resultado de la cita</h4>
      </div>

      <div class="modal-body" id="">
        <div id="cnt_form4" class="cnt_form">
          <?php echo form_open('', array('id' => 'formajax4', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

          <input type="hidden" id="id_expedienteci" name="id_expedienteci" value="<?= $id?>">
          <input type="hidden" id="id_fechasaudienciasci" name="id_fechasaudienciasci" value="<?= $id_audiencia?>">
          <input type="hidden" id="id_sindicato" name="id_sindicato" value="<?php (isset($id_sindicato)) ? $id_sindicato : "" ; ?>">

          <div class="row">
            <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_resultados"></div>

            <div class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                <h5>Fecha del resultado: <span class="text-danger">*</span></h5>
                <input type="text" required="" class="form-control" id="fecha_resultado" name="fecha_resultado" placeholder="dd/mm/yyyy" readonly="">
                <div class="help-block"></div>
            </div>

              <div id="asist" class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo " pull-left"; } ?>">
                <h5>Parte solicitante : </h5>
                <div class="controls">
                  <select id="asistieron" name="asistieron" class="form-control">
                    <option value="">[Seleccione]</option>
                    <option value="1">Persona defensora pública</option>
                    <option value="2">Persona defensora pública y trabajadora</option>
                    <option value="3">Persona trabajadora</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_defensores"></div>

              <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_representante_empresa"></div>

              <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_delegado2"></div>

            <div id="det_resultado" class="form-group col-lg-12 col-sm-12" >
                <h5>Detalle del resultado:<span class="text-danger">*</span></h5><span><small class="text-muted db">Por favor complete la información en lugar de los asteriscos de ser necesario</small></span>
                <textarea type="text" id="detalle_resultado" name="detalle_resultado" class="form-control" placeholder="Detalles del resultado" required="" rows="6"></textarea>
                <div class="help-block"></div>
            </div>
          </div>

          <div class="row" id='tipo_pago'>

          <div id="num_folios" class="form-group col-lg-4" style="height: 83px;">
              <h5>Número folios:<span class="text-danger">*</h5>
              <input type="number" id="numero_folios" name="numero_folios" class="form-control" placeholder="Número folios" step="1" min="1">
              <div class="help-block"></div>
          </div>

            <div id='tipo_cc' class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Tipo de pago: <span class="text-danger">*</span></h5>
              <div class="controls">
                <select id="tipo_conciliacion" name="tipo_conciliacion" class="form-control">
                  <option value="">[Seleccione]</option>
                  <option value="1">Pago en el momento</option>
                  <option value="2">Pago diferido</option>
                </select>
              </div>
            </div>

          <div id="registrar" class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo " pull-left"; } ?>">
            <h5>Registrar fechas de pagos:<span class="text-danger">*</h5>
                  <button type="button" onclick="pagos(<?= $id ?>);" class="btn waves-effect waves-light btn-info">Registrar</button>
            </div>

            <div id="mod_pago" class="form-group col-lg-8 col-sm-8" style="height: 83px; display:none;*/">
                <h5>Modalidad pago:</h5>
                <textarea type="text" id="modalidad_pago" name="modalidad_pago" class="form-control" placeholder="Especifique banco y/o forma"></textarea>
                <div class="help-block"></div>
            </div>

            <div  id="especifique" class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Especifique : <span class="text-danger">*</span></h5>
              <div class="controls">
                <select id="inasistencia" name="inasistencia" class="form-control" required>
                  <option value="">[Seleccione]</option>
                  <option value="1">Parte empleadora</option>
                  <option value="2">Parte trabajadora</option>
                  <option value="3">Parte empleadora y trabajadora</option>
                </select>
              </div>
            </div>

            <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_directivos"></div>

          </div>

          <div align="right" id="btnadd1">
            <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn waves-effect waves-light btn-success2">
              Guardar <i class="mdi mdi-chevron-right"></i>
            </button>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

$(function(){
    $("#formajax4").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax4"));
        if (typeof($("#directivo").val())!="undefined") {
          formData.append("directivos_audiencia", $("#directivo").val().toString());
          formData.append("id_sindicato", $("#id_sindicato").val());
        }
        // alert(typeof($("#directivo").val()))
        formData.append("fecha_pago", $("#fecha_pago").val());


        $('#modal_resolucion').modal('hide');

        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/audiencias/gestionar_resolucion_audiencia",
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
            if(res != "fracaso"){
                swal({ title: "¡La resolucion se aplicó con éxito!", type: "success", showConfirmButton: true });
                tabla_audiencias(formData.get('id_expedienteci'));
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });
      $('#modal_resolucion').remove();
      $('.modal-backdrop').remove();
      $('body').removeClass('modal-open');
      // tablasolicitudes();
    });
});

function mostrar(){

  var divs = $("#tipo_pago").children(".form-group");
  $(divs[0]).show(0); $(divs[1]).hide(0); $(divs[2]).hide(0);
  $("#num_folios").show(0);
  $("#numero_folios").attr("required","required");
  $("#div_combo_defensores").show(0);
  $("#defensor").attr("required","required");
  $("#representante_empresa").attr("required","required");
  $("#div_combo_delegado2").show(0);
  $("#delegado").attr("required","required");
  $("#div_combo_representante_empresa").show(0);
  $("#representante_empresa").attr("required","required");
  $("#especifique").hide(0);
  $("#p_pago").hide(0);
  $("#f_pago").hide(0);
  $("#fhpago").hide(0);
  $("#mod_pago").hide(0);
  $("#det_resultado").show(0);
  $("#asist").show(0);
  $("#tipo_conciliacion").removeAttr("required");
  $("#modalidad_pago").removeAttr("required");
  $("#monto_pago").removeAttr("required");
  $("#inasistencia").removeAttr("required");
  $("#primer_pago").removeAttr("required");
  $("#fecha_pago").removeAttr("required");
  $("#detalle_resultado").attr("required","required");
  $("#asistieron").attr("required","required");
  var value = $("#resolucion").val();

  $("#asistieron").change(
      function(){
        var esp = $("#asistieron").val();
        if (esp==3) {
          $("#defensor").removeAttr("required");
        }else {
          $("#asistieron").attr("required","required");
        }
    });

  if(value == ""){ $("#tipo_pago").hide(0); }else{ $("#tipo_pago").show(0); }

  switch (value) {
    case '1':
    $(divs[1]).show(0); $(divs[2]).show(0);
      $("#registrar").hide(0);
      $("#f_pago").show(500);
      $("#fhpago").show(0);
      $("#tipo_conciliacion").attr("required",'required');
      $("#tipo_conciliacion").change(
          function(){
          $("#primer_pago").removeAttr("required");
          var tipo = $("#tipo_conciliacion").val();
          if (tipo=='2'){
            $("#primer_pago").attr("required",'required');
            $("#p_pago").show(500);
            $("#registrar").show(500);
          }else if(tipo=='1') {
            $("#primer_pago").removeAttr("required");
            $("#p_pago").hide(0);
            $("#registrar").show(500);
          }else {
            $("#registrar").hide(0);
          }
        });
      $("#monto_pago").attr("required",'required');
      $("#fecha_pago").attr("required",'required');
      break;
    case '3':
    case '23':
      $("#det_resultado").hide(0);
      $("#detalle_resultado").removeAttr("required");
      $("#especifique").show(500);
      $("#inasistencia").attr("required",'required');
      $("#inasistencia").change(
          function(){
            var esp = $("#inasistencia").val();
            if (esp==2 || esp==3) {
              $("#asist").hide(500);
              $("#asistieron").removeAttr("required");
            }else {
              $("#asist").show(500);
              $("#asistieron").attr("required","required");
            }
        });
      break;
    case '5':
      $("#det_resultado").hide(0);
      $("#detalle_resultado").removeAttr("required");
      break;
    case '9':
      $("#det_resultado").hide(0);
      $("#detalle_resultado").removeAttr("required");
      $("#num_folios").hide(0);
      $("#numero_folios").removeAttr("required");
      $("#div_combo_representante_empresa").hide(0);
      $("#representante_empresa").removeAttr("required");
      // $("#div_combo_delegado2").hide(0);
      // $("#delegado").removeAttr("required");
      // $("#div_combo_defensores").hide(0);
      // $("#defensor").removeAttr("required");
      // $("#asist").hide(500);
      // $("#asistieron").removeAttr("required");
      break;
    case '10':
        $("#tipo_conciliacion").attr("required",'required');
        $("#tipo_cc").show();
        $("#numero_folios").removeAttr("required");
        $("#num_folios").hide(0);
        //$("#modalidad_pago").attr("required",'required');
        $("#mod_pago").show();
      break;
    case '6':
        $("#representante_empresa").removeAttr("required");
        $("#asistieron").removeAttr("required");
        $("#defensor").removeAttr("required");
      break;
    case '8':
        $("#representante_empresa").removeAttr("required");
        $("#asistieron").removeAttr("required");
        $("#defensor").removeAttr("required");
      break;
    case '12':
        $("#numero_folios").removeAttr("required");
        $("#num_folios").hide(0);
      break;
    default:
  }
}

$(function(){
    $(document).ready(function(){
      $("#tipo_pago").hide(0);
    	var date = new Date(); var currentMonth = date.getMonth(); var currentDate = date.getDate(); var currentYear = date.getFullYear();
        $('#fecha_resultado').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true}).datepicker("setDate", new Date());
    });
    });

</script>
