<?php
$expediente = $expediente->row();
$sub_total=0.0;
if ($pagos) {
  $pagos = $pagos->row();
  $sub_total = $pagos->indemnizacion_fechaspagosci;
}
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
                          Nombre delegado/a actual:
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
                          <h5>
                          <?php if($expediente->tiposolicitud_expedienteci != "3"){
                              echo $expediente->nombre_personaci.' '.$expediente->apellido_personaci;
                          }else{
                              echo $expediente->nombre_empresa;
                          }?>
                          </h5>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                          Acuerdo de pagos:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                        <h5><?= $expediente->tipocociliacion_expedienteci ?></h5>
                        </div>
                      </div>
                      <?php if ($expediente->tipocociliacion_expedienteci=='Pago diferido') {?>
                      <div class="row">
                        <div class="form-group col-lg-5" style="height: 20px;">
                          Total de indemnización restante a pagar:
                        </div>
                        <div class="form-group col-lg-5" style="height: 20px;">
                        <h5><?= '$'.number_format($sub_total,2);?></h5>
                        </div>
                      </div>
                    <?php } ?>
                    </tbody>
                </table>
            </blockquote>
            <div id="cnt_form7" class="cnt_form">
              <?php echo form_open('', array('id' => 'formajax7', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

                <hr class="m-t-0 m-b-30">

                <input type="hidden" id="id_expedienteci1" name="id_expedienteci1" value="<?=$expediente->id_expedienteci?>">
                <input type="hidden" id="id_fechaspagosci" name="id_fechaspagosci" value= "">
                <input type="hidden" id="band5" name="band5" value="save">

                <div class="row">
                  <div class="form-group col-lg-4 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Fecha y hora de pago: <span class="text-danger">*</span></h5>
                    <div class="controls">
                      <input type="datetime-local" class="form-control" id="fecha_pago" nombre="fecha_pago"  required>
                    </div>
                  </div>
                  <?php if ($expediente->tipocociliacion_expedienteci=='Pago diferido'
                  && number_format($sub_total)==0.00) {?>
                  <div class="form-group col-lg-4" style="height: 83px;">
                      <h5>Monto total indemnización:</h5>
                      <input type="number" id="monto_total" name="monto_total" class="form-control" placeholder="Monto total indeminización" step="0.01">
                      <div class="help-block"></div>
                  </div>
                <?php }else{ ?>
                  <input type="hidden" id="monto_total" name="monto_total" value="<?=$sub_total?>">
                <?php } ?>
                  <div class="form-group col-lg-4" style="height: 83px;">
                      <h5>Monto de pago:</h5>
                      <input type="number" id="monto" name="monto" class="form-control" placeholder="Monto de pago" step="0.01">
                      <div class="help-block"></div>
                  </div>
                </div>

              <div align="right" id="btnadd7">
                <button type="reset" class="btn waves-effect waves-light btn-success">
                  <i class="mdi mdi-recycle"></i> Limpiar</button>
                <button type="submit" onclick="cambiar_nuevo6();" class="btn waves-effect waves-light btn-success2">
                  Guardar <i class="mdi mdi-chevron-right"></i>
                </button>
              </div>
              <div align="right" id="btnedit7" style="display: none;">
                <button type="reset" class="btn waves-effect waves-light btn-success">
                  <i class="mdi mdi-recycle"></i> Limpiar</button>
                <button type="submit" class="btn waves-effect waves-light btn-info">
                  Finalizar <i class="mdi mdi-chevron-right"></i>
                </button>
              </div>
            <?php echo form_close(); ?>
            </div>
          </div>
          <div class="col-lg-12" id="cnt_tabla_pagos" style="/*display:none;*/"></div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
function eliminar_pago(){
  $("#band5").val("delete");
  swal({
    title: "¿Está seguro?",
    text: "¡Desea eliminar el registro!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#fc4b6c",
    confirmButtonText: "Sí, deseo eliminar!",
    closeOnConfirm: false
  }, function(){

    $( "#formajax7" ).submit();

  });
 }

function cambiar_nuevo6(){
    //$("#id_fechasaudienciasci").val('');
    //$("#id_expedienteci1").val('');
    $("#fechapago_fechaspagosci").val('');
    $("#montopago_fechaspagosci").val('');
    $("#band5").val("save");

    $("#ttl_form").addClass("bg-success");
    $("#ttl_form").removeClass("bg-info");
}

function cambiar_editar6(id_fechaspagosci,fechapago_fechaspagosci,montopago_fechaspagosci,id_expedienteci,bandera){
    $("#id_fechaspagosci").val(id_fechaspagosci);
    $("#fecha_pago").val(fechapago_fechaspagosci);
    $("#monto").val(montopago_fechaspagosci);
    $("#id_expedienteci1").val(id_expedienteci);

    if(bandera == "edit"){
        $("#ttl_form").removeClass("bg-success");
        $("#ttl_form").addClass("bg-info");
        $("#btnadd7").hide(0);
        $("#btnedit7").show(0);
        $("#band5").val("edit")
    }else{
        eliminar_pago();
    }
}

$(function(){
    $("#formajax7").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax7"));
        formData.append("fecha_pago", $("#fecha_pago").val());
        $.ajax({
          url: "<?php echo site_url(); ?>/resolucion_conflictos/pagos/gestionar_pago",
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
              //cerrar_mantenimiento();
              if($("#band5").val() == "save"){
                  cambiar_nuevo6();
                  swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
                  pagos(formData.get('id_expedienteci1'));
              }else if($("#band5").val() == "edit"){
                  swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                  pagos(formData.get('id_expedienteci1'));
              }else{
                  swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
                  pagos(formData.get('id_expedienteci1'));
              }
              tabla_pagos(formData.get('id_expedienteci1'));
              $('#formajax7').trigger("reset");
            }
        });

    });
});

/*$(function(){
    $(document).ready(function(){
      var date = new Date(); var currentMonth = date.getMonth(); var currentDate = date.getDate(); var currentYear = date.getFullYear();
        $('#fecha_pago').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true, startDate: '+1d'}).datepicker("setDate", new Date());
    });
});*/
</script>
