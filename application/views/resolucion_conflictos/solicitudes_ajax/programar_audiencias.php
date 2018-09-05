<?php
$expediente = $expediente->result()[0];
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<div class="page-wrapper">
  <div class="container-fluid">
    <div class="row">

      <div class="col-lg-1"></div>
      <div class="col-lg-10">
        <div class="card">
          <div class="card-header bg-success2" id="ttl_form">
            <div class="card-actions text-white">
              <a style="font-size: 16px;" onclick="cerrar_mantenimiento();">
                <i class="mdi mdi-window-close"></i>
              </a>
            </div>
            <h4 class="card-title m-b-0 text-white">Programar audiencias</h4>
          </div>
          <div class="card-body b-t">
            <blockquote class="m-t-0">
                <table class="table no-border">
                    <tbody>
                      <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                          N&uacute;mero de caso:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                              <h5><?= $expediente->numerocaso_expedienteci ?></h5>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                          Nombre delegado(a) actual:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                              <h5><?= $expediente->primer_nombre.' '.$expediente->segundo_nombre.' '.
                              $expediente->primer_apellido.' '.$expediente->segundo_apellido.' '.
                              (($expediente->apellido_casada) ? $expediente->apellido_casada : ' ') ?></h5>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                          Nombre de solicitante:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                              <h5><?= $expediente->nombre_personaci.' '.$expediente->apellido_personaci ?></h5>
                        </div>
                      </div>
                    </tbody>
                </table>
            </blockquote>
            <div id="cnt_form6" class="cnt_form">
              <?php echo form_open('', array('id' => 'formajax6', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

                <hr class="m-t-0 m-b-30">

                <input type="hidden" id="id_expedienteci" name="id_expedienteci" value="10">

                <div class="row">
                  <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Fecha de audiencia: <span class="text-danger">*</span></h5>
                      <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="fecha_audiencia" name="fecha_audiencia" placeholder="dd/mm/yyyy" readonly="">
                      <div class="help-block"></div>
                  </div>
                  <div class="form-group col-lg-4" style="height: 83px;">
                      <h5>Hora de audiencia:</h5>
                      <input type="time" id="hora_audiencia" name="hora_audiencia" class="form-control" placeholder="Hora de audiencia">
                      <div class="help-block"></div>
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
          <div class="col-lg-12" id="cnt_tabla_audiencias" style="/*display:none;*/"></div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
$(function(){
    $("#formajax6").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax6"));

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
              cerrar_mantenimiento();
              if($("#band3").val() == "save"){
                  swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
              }else if($("#band3").val() == "edit"){
                  swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
              }else{
                  swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
              }
              tablasolicitudes();
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
