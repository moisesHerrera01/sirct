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

          <div class="row">
            <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Resulado de intervenci&oacute;n conciliator&iacute;a: <span class="text-danger">*</span></h5>
              <div class="controls">
                <select onchange="mostrar()" id="resolucion" name="resolucion" class="form-control" required>
                  <option value="">[Seleccione]</option>
                  <option value="Conciliado">Conciliado</option>
                  <option value="Sin conciliar">Sin conciliar</option>
                  <option value="Inasistencia">Inasistencia</option>
                  <option value="Desistida">Desistida</option>
                  <option value="A multas">A multas</option>
                  <option value="No notificada">No notificada</option>
                  <option value="Reinstalo">Reinstalo</option>
                </select>
              </div>
            </div>

            <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                <h5>Fecha del resultado: <span class="text-danger">*</span></h5>
                <input type="text" required="" class="form-control" id="fecha_resultado" name="fecha_resultado" placeholder="dd/mm/yyyy" readonly="">
                <div class="help-block"></div>
            </div>

            <div class="form-group col-lg-12 col-sm-12" style="height: 83px;">
                <h5>Detalle del resultado:<span class="text-danger">*</span></h5>
                <textarea type="text" id="detalle_resultado" name="detalle_resultado" class="form-control" placeholder="Detalles del resultado" required=""></textarea>
                <div class="help-block"></div>
            </div>
          </div>

          <div class="row" style="display: none;" id='tipo_pago'>
            <div  class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Tipo de conciliación: <span class="text-danger">*</span></h5>
              <div class="controls">
                <select id="tipo_conciliacion" name="tipo_conciliacion" class="form-control">
                  <option value="">[Seleccione]</option>
                  <option value="Pago en el momento">Pago en el momento</option>
                  <option value="Pago diferido">Pago diferido</option>
                </select>
              </div>
            </div>

            <div class="form-group col-lg-6" style="height: 83px;">
                <h5>Monto de pago($):<span class="text-danger">*</h5>
                <input type="number" id="monto_pago" name="monto_pago" class="form-control" placeholder="Monto de pago total" step="0.01">
                <div class="help-block"></div>
            </div>
          </div>

          <div style="display:none;" id="especifique" class="row">
            <div  class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
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
        $('#modal_resolucion').modal('hide');

        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/gestionar_resolucion_expediente",
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
            if(res == "exito"){
                //cerrar_mantenimiento();
                swal({ title: "¡La resolucion se aplicó con exito!", type: "success", showConfirmButton: true });
                //tablaEstados();
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });
      $('#modal_resolucion').remove();
      $('.modal-backdrop').remove();
      $('body').removeClass('modal-open');
      tablasolicitudes();
    });
});

function mostrar(){
  $("#tipo_pago").hide(0);
  $("#especifique").hide(0);
  $("#tipo_conciliacion").removeAttr("required");
  $("#monto_pago").removeAttr("required");
  $("#inasistencia").removeAttr("required");
  var value = $("#resolucion").val();
  switch (value) {
    case 'Conciliado':
      $("#tipo_pago").show(500);
      break;
    case 'Inasistencia':
      $("#especifique").show(500);
      break;
    default:
  }
}

$(function(){
    $(document).ready(function(){
    	var date = new Date(); var currentMonth = date.getMonth(); var currentDate = date.getDate(); var currentYear = date.getFullYear();
        $('#fecha_resultado').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true}).datepicker("setDate", new Date());
    });
    });

</script>
