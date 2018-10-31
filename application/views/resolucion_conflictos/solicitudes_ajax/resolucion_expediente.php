<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<div class="modal fade" id="modal_resolucion" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Registrar Resultado del Expediente</h4>
      </div>

      <div class="modal-body" id="">
        <div id="cnt_form4" class="cnt_form">
          <?php echo form_open('', array('id' => 'formajax4', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

          <input type="hidden" id="id_expedienteci" name="id_expedienteci" value="<?= $id?>">
          <input type="hidden" id="id_fechasaudienciasci" name="id_fechasaudienciasci" value="<?= $id_audiencia?>">

          <div class="row">
            <div class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Resulado de audiencia: <span class="text-danger">*</span></h5>
              <div class="controls">
                <select onchange="mostrar()" id="resolucion" name="resolucion" class="form-control" required>
                  <option value="">[Seleccione]</option>
                  <option value="1">Conciliado</option>
                  <option value="2">Sin conciliar</option>
                  <option value="3">Inasistencia</option>
                  <option value="4">Desistida</option>
                  <option value="5">A multas</option>
                  <option value="6">No notificada</option>
                  <option value="7">Reinstalo</option>
                </select>
              </div>
            </div>

            <div class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                <h5>Fecha del resultado: <span class="text-danger">*</span></h5>
                <input type="text" required="" class="form-control" id="fecha_resultado" name="fecha_resultado" placeholder="dd/mm/yyyy" readonly="">
                <div class="help-block"></div>
            </div>

              <div  class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo " pull-left"; } ?>">
                <h5>Parte solicitante : <span class="text-danger">*</span></h5>
                <div class="controls">
                  <select id="asistieron" name="asistieron" class="form-control" required>
                    <option value="">[Seleccione]</option>
                    <option value="1">Defensor público</option>
                    <option value="2">Defensor público y trabajador</option>
                  </select>
                </div>
              </div>

            <div class="form-group col-lg-12 col-sm-12" style="height: 83px;">
                <h5>Detalle del resultado:<span class="text-danger">*</span></h5>
                <textarea type="text" id="detalle_resultado" name="detalle_resultado" class="form-control" placeholder="Detalles del resultado" required=""></textarea>
                <div class="help-block"></div>
            </div>
          </div>

          <div class="row" id='tipo_pago'>

          <div class="form-group col-lg-4" style="height: 83px;">
              <h5>Número folios:<span class="text-danger">*</h5>
              <input type="number" id="numero_folios" name="numero_folios" class="form-control" placeholder="Número folios" step="1">
              <div class="help-block"></div>
          </div>

            <div  class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Tipo de pago: <span class="text-danger">*</span></h5>
              <div class="controls">
                <select id="tipo_conciliacion" name="tipo_conciliacion" class="form-control">
                  <option value="">[Seleccione]</option>
                  <option value="1">Pago en el momento</option>
                  <option value="2">Pago diferido</option>
                </select>
              </div>
            </div>

            <div class="form-group col-lg-4" style="height: 83px;">
                <h5>Monto de pago($):<span class="text-danger">*</h5>
                <input type="number" id="monto_pago" name="monto_pago" class="form-control" placeholder="Monto total de pago " step="0.01">
                <div class="help-block"></div>
            </div>

            <div id="fhpago" class="form-group col-lg-6 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Fecha y hora de pago: <span class="text-danger">*</span></h5>
              <div class="controls">
                <input type="datetime-local" class="form-control" id="fecha_pago" nombre="fecha_pago">
              </div>
            </div>

            <div id="p_pago" class="form-group col-lg-6" style="height: 83px;">
                <h5>Monto de primer pago:<span class="text-danger">*</h5>
                <input type="number" id="primer_pago" name="primer_pago" class="form-control" placeholder="Monto de primer pago" step="0.01">
                <div class="help-block"></div>
            </div>

            <div  id="especifique" class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Especifique : <span class="text-danger">*</span></h5>
              <div class="controls">
                <select id="inasistencia" name="inasistencia" class="form-control" required>
                  <option value="">[Seleccione]</option>
                  <option value="1">Parte solicitada</option>
                  <option value="2">Parte solictante</option>
                  <option value="3">Ambas partes</option>
                </select>
              </div>
            </div>
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
                swal({ title: "¡La resolucion se aplicó con exito!", type: "success", showConfirmButton: true });
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
  $("#especifique").hide(0);
  $("#p_pago").hide(0);
  $("#f_pago").hide(0);
  $("#fhpago").hide(0);
  $("#tipo_conciliacion").removeAttr("required");
  $("#monto_pago").removeAttr("required");
  $("#inasistencia").removeAttr("required");
  $("#primer_pago").removeAttr("required");
  $("#fecha_pago").removeAttr("required");
  var value = $("#resolucion").val();

  if(value == ""){ $("#tipo_pago").hide(0); }else{ $("#tipo_pago").show(0); }

  switch (value) {
    case '1':
    $(divs[1]).show(0); $(divs[2]).show(0);
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
          }else {
            $("#primer_pago").removeAttr("required");
            $("#p_pago").hide(0);
          }
        });
      $("#monto_pago").attr("required",'required');
      $("#fecha_pago").attr("required",'required');
      break;
    case '3':
      $("#especifique").show(500);
      $("#inasistencia").attr("required",'required');
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
