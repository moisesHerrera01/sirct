<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<div class="page-wrapper">
  <div class="container-fluid">
    <div class="row page-titles">
      <div class="align-self-center" align="center">
        <h3 class="text-themecolor m-b-0 m-t-0">Gestión de audiencias</h3>
      </div>
    </div>
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
            <h4 class="card-title m-b-0 text-white">Listado de Actividades</h4>
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
                          Delegado asignado:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                              <h5><?= $expediente->primer_nombre.' '.$expediente->segundo_nombre.' '.
                              $expediente->primer_apellido.' '.$expediente->segundo_apellido.' '.
                              (($expediente->apellido_casada) ? $expediente->apellido_casada : ' ') ?></h5>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                          Nombres:
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

                <input type="hidden" id="id_expediente" name="id_expediente">

                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Motivo de Inhabilitar Expediente: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <textarea type="text" id="mov_inhabilitar" name="mov_inhabilitar" class="form-control" required=""></textarea>
                      </div>
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
  </div>

</div>

<script>

$(function(){
    $("#formajax6").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax6"));

        swal({
          title: "¿Está seguro?",
          text: "¡Desea inhabilitar el expediente!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#fc4b6c",
          confirmButtonText: "Sí, deseo inhabilitar!",
          closeOnConfirm: false
        }, function(){

          $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/programar_audiencias",
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
                  swal({ title: "¡Expediente Inhabilitado!", type: "success", showConfirmButton: true });
                  //tablaEstados();
              }else{
                  swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
              }
          });

        });

    });
});

</script>
