<script>
function combo_delegado2(seleccion){
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/combo_delegado2",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_delegado2').html(res);
    $(".select2").select2();
  });
}
</script>
<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<div class="modal fade" id="modal_delegado" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Cambiar delegado</h4>
      </div>

      <div class="modal-body" id="">
        <div id="cnt_form4" class="cnt_form">
          <?php echo form_open('', array('id' => 'formajax4', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

          <input type="hidden" id="id_expedienteci" name="id_expedienteci" value="<?= $id?>">

          <div class="row">
            <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Delegado: <span class="text-danger">*</span></h5>
                <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_delegado2"></div>
            </div>
          </div>

          <div align="right" id="btnadd1">
            <button type="reset" class="btn waves-effect waves-light btn-success">
              <i class="mdi mdi-recycle"></i> Limpiar</button>
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
        $('#modal_delegado').modal('hide');

        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/cambiar_delegado",
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
      $('#modal_delegado').remove();
      $('.modal-backdrop').remove();
      tablasolicitudes();
    });
});

</script>
