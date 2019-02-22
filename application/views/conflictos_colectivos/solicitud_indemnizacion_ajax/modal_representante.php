<script>

function combo_profesiones(seleccion){
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/combo_profesiones",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_profesiones').html(res);
    $("#profesion").select2();
  });
}

function combo_municipio2(seleccion){

    $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/establecimiento/combo_municipio2",
        type: "post",
        dataType: "html",
        data: {id : seleccion}
    })
    .done(function(res){
        $('#div_combo_municipio2').html(res);
        $("#municipio_representante").select2();
    });

}

function combo_estados_civiles(seleccion){
  $.ajax({
    url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/combo_estados_civiles",
    type: "post",
    dataType: "html",
    data: {id : seleccion}
  })
  .done(function(res){
    $('#div_combo_estados_civiles').html(res);
    $("#estado_civil").select2();
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

<!--INICIA MODAL DE REPRESENTANTE -->
<div class="modal fade" id="modal_representante" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <?php echo form_open('', array('id' => 'formajax9', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
              <input type="hidden" id="band4" name="band4" value="save">
              <input type="hidden" id="id_representante" name="id_representante" value="">
                <div class="modal-header">
                    <h4 class="modal-title">Gestión de personas representantes</h4>
                </div>
                <div class="modal-body" id="">
                    <div class="row">
                      <div class="form-group col-lg-6 col-sm-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                          <h5>Nombre de la persona: <span class="text-danger">*</span></h5>
                          <div class="controls">
                              <input type="text" id="nombres_representante" name="nombres_representante" class="form-control" required="">
                          </div>
                      </div>

                    <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                        <h5>Fecha de nacimiento: <span class="text-danger">*</span></h5>
                        <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" class="form-control" id="f_nacimiento_representante" name="f_nacimiento_representante" placeholder="dd/mm/yyyy" readonly="">
                        <div class="help-block"></div>
                    </div>

                    </div>
                    <div class="row">
                      <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                          <h5>DUI: <span class="text-danger">*</span></h5>
                          <div class="controls">
                              <input type="text" id="dui_representante" name="dui_representante" class="form-control" data-mask="99999999-9">
                          </div>
                      </div>

                      <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                          <h5>Tipo: <span class="text-danger">*</span></h5>
                          <select id="tipo_representante" name="tipo_representante" class="form-control custom-select"  style="width: 100%" required="">
                              <option value=''>[Seleccione el tipo]</option>
                              <option class="m-l-50" value="1">Legal</option>
                              <option class="m-l-50" value="2">Designado</option>
                              <option class="m-l-50" value="3">Apoderado</option>
                          </select>
                      </div>

                      <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_estados_civiles"></div>
                    </div>
                    <div class="row">


                      <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                          <h5>Acreditación: <span class="text-danger">*</span></h5>
                          <div class="controls">
                              <textarea id="acreditacion_representante" name="acreditacion_representante" class="form-control"></textarea>
                          </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_profesiones"></div>

                      <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_municipio2"></div>

                      <div class="form-group col-lg-4" style="height: 83px;">
                          <h5>Sexo de la persona:</h5>
                          <input name="sexo" type="radio" id="rmasculino" checked="" value="M">
                          <label for="rmasculino">Hombre</label>
                          <input name="sexo" type="radio" id="rfemenino" value="F">
                          <label for="rfemenino">Mujer</label>
                          <div class="help-block"></div>
                    </div>
                    </div>

                    <div style="display: none;"> class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                        <h5>Estado: <span class="text-danger">*</span></h5>
                        <select id="estado_representante" name="estado_representante" class="form-control custom-select"  style="width: 100%" required="">
                            <option class="m-l-50" value="1">Activo</option>
                            <option class="m-l-50" value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect text-white" data-dismiss="modal">Cerrar</button>
                    <button type="submit" id="submit3" class="btn btn-info waves-effect text-white">Aceptar</button>
                </div>
              <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!--FIN MODAL DE REPRESENTANTE -->

<script>

$(function () {
    $("#formajax9").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax9"));
        formData.append("id_empresa", $('#id_empresaci').val());


        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/gestionar_representante",
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
            if(res == "exito"){
                if($("#band4").val() == "save"){
                    swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
                }else if($("#band4").val() == "edit"){
                    swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                }else{
                    if($("#estado_representante").val() == '1'){
                        swal({ title: "¡Activado exitosamente!", type: "success", showConfirmButton: true });
                    }else{
                        swal({ title: "¡Desactivado exitosamente!", type: "success", showConfirmButton: true });
                    }
                }

                $.ajax({
                    url: "<?php echo site_url(); ?>/conflictos_colectivos/solicitud_indemnizacion/obtener_respresentante_mayor",
                    type: "post",
                    dataType: "html",
                    data: {id : $('#id_empresaci').val()}
                }) .done(function(res){
                    result = JSON.parse(res);
                    var newOption = new Option(result.nombres_representante, result.id_representante, false, false);
                    $('#representante_empresa').append(newOption).trigger('change');
                    $('#representante_empresa').val(result.id_representante).trigger("change");
                    $("#modal_representante").modal('hide');
                });

            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });
    });
});

$(function() {
  $(document).ready(function () {
    $('#f_nacimiento_representante').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true, endDate: moment().format("DD-MM-YYYY")}).datepicker("setDate", new Date());
    $('#dui_representante').mask('99999999-9');
  });
});

</script>
