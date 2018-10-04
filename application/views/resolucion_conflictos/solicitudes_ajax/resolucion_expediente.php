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
              <h5>Resulato de intervenci&oacute;n conciliator&iacute;a: <span class="text-danger">*</span></h5>
              <div class="controls">
                <select id="resolucion" name="resolucion" class="form-control" required>
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
            <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Tipo de conciliación: <span class="text-danger">*</span></h5>
              <div class="controls">
                <select id="tipo_conciliacion" name="tipo_conciliacion" class="form-control" required>
                  <option value="">[Seleccione]</option>
                  <option value="Pago en el momento">Pago en el momento</option>
                  <option value="Pago diferido">Pago diferido</option>
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

</script>
