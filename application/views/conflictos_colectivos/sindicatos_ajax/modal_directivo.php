<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<!-- ============================================================== -->
<!-- INICIO MODAL DIRECTIVOS -->
<!-- ============================================================== -->
<div id="modal_directivo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <?php echo form_open('', array('id' => 'formajax2', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
          <input type="hidden" id="band2" name="band2" value="save">
          <input type="hidden" id="id_directivo" name="id_directivo" value="<?=$id_directivo ?>">
          <input type="hidden" id="id_sindicato" name="id_sindicato" value="<?=$id_sindicato ?>">
          <input type="hidden" id="tipo" name="tipo" value="<?= $tipo ?>">
            <div class="modal-header">
                <h4 class="modal-title">Gestión de personas directivas</h4>
            </div>
            <div class="modal-body" id="">
                <div class="row">
                  <div class="form-group col-lg-6 col-sm-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Nombre de la persona: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" id="nombre_directivo" name="nombre_directivo" class="form-control" required="">
                      </div>
                  </div>

                  <div class="form-group col-lg-6 col-sm-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Apellido de la persona: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" id="apellido_directivo" name="apellido_directivo" class="form-control">
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-4" style="height: 83px;">
                      <h5>Sexo:</h5>
                      <input name="sexo_directivo" type="radio" id="masculino" checked="" value="M">
                      <label for="masculino">Hombre</label>
                      <input name="sexo_directivo" type="radio" id="femenino" value="F">
                      <label for="femenino">Mujer</label>
                      <div class="help-block"></div>
                </div>

                  <div class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>DUI de la persona: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input data-mask="99999999-9" type="text" id="dui_directivo" name="dui_directivo" class="form-control">
                      </div>
                  </div>

                  <!-- <div class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Tipo de persona directiva: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" id="tipo_directivo" name="tipo_directivo" class="form-control">
                      </div>
                  </div> -->
                  <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_tipo_directivos"></div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Acreditación de la persona: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <textarea type="text" id="acreditacion_directivo" name="acreditacion_directivo" class="form-control"></textarea>
                      </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cerrar_directivos(<?= $id_sindicato ?>)" class="btn btn-danger waves-effect text-white" data-dismiss="modal">Cerrar</button>
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
<script type="text/javascript">
/*AJAX DIRECTIVOS*/
$(function(){
    $("#formajax2").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax2"));
        formData.append("id_sindicato", $('#id_sindicato').val());
        formData.append("id_exp", $('#id_exp').val());
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
              cerrar_directivos($('#id_sindicato').val());
            }
        });

    });
});

function cerrar_directivos(id_sindicato){
  if ($("#tipo").val()==2) {
    $("#modal_directivo").modal('hide');
    $("#modal_resolucion").modal('show');
    combo_directivos(0,id_sindicato);
  }else {
    tabla_directivos();
    $("#modal_directivo").modal('hide');
  }
}
</script>
