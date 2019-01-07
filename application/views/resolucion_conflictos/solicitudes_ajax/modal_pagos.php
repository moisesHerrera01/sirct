<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<div class="modal fade" id="modal_pagos" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Registrar fechas de pago</h4>
        <button type="button" class="close" data-dismiss="modal" onclick="abrir_resolucion()"><span aria-hidden="true">×</span> <span class="sr-only">Cerrar</span></button>
      </div>
      <div class="modal-body" id="">
        <div id="cnt_form4" class="cnt_form">
          <?php echo form_open('', array('id' => 'formajax11', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

          <input type="hidden" id="id_expedienteci" name="id_expedienteci" value="<?= $id ?>">
          <!-- <input type="hidden" id="id_fechasaudienciasci" name="id_fechasaudienciasci" value="<?= $id_audiencia?>"> -->

          <div class="row">
            <div class="form-group col-lg-5" style="height: 60px;">
                <h5>Monto total($):<span class="text-danger">*</h5>
                <input type="number" id="monto_pago" name="monto_pago" class="form-control" placeholder="Monto total de pago " step="0.01">
                <div class="help-block"></div>
            </div>
          </div>

          <div class="row" id='tipo_pago'>
            <div id="fhpago" class="form-group col-lg-5 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Fecha y hora de pago: <span class="text-danger">*</span></h5>
              <div class="controls">
                <input type="datetime-local" class="form-control" id="fecha_pago" nombre="fecha_pago">
              </div>
            </div>

            <div id="p_pago" class="form-group col-lg-5" style="height: 50px;">
                <h5>Monto de pago($):<span class="text-danger">*</h5>
                <input type="number" id="primer_pago" name="primer_pago" class="form-control" placeholder="Monto del pago" step="0.01">
                <div class="help-block"></div>
            </div>

            <div  id="boton_agregar" class="form-group col-lg-2 col-sm-2 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Agregar:</h5>
              <?php
              $param = array();
              array_push($param,"");
              echo generar_boton($param,"agregar","btn-info","fa fa-plus","Agregar");
               ?>
            </div>

          </div>

          <div id="nuevo" class="row">

          </div>

          <div align="right" id="btnadd1">
            <button type="submit" class="btn waves-effect waves-light btn-success2">Aceptar</button>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

$(function(){
    $("#formajax11").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax11"));
        formData.append("fecha_pago", $("#fecha_pago").val());
        formData.append("tipo_conciliacion", $("#tipo_conciliacion").val());
        alert($("#tipo_conciliacion").val())
        j = ($('#nuevo').find('input').length)/2;
        i=1;
        while (i!=(j+1)) {
          formData.append("fecha_pago"+i, $("#fecha_pago"+i).val());
          i++;
        }
        $('#modal_pagos').modal('hide');
        $('#modal_resolucion').modal('show');

        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/pagos/gestionar_pagos_modal",
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
            if(res != "fracaso"){
                $.toast({ heading: 'Registro exitoso', text: 'Fechas de pagos ingresados correctamente', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
             }//else{
            //     swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            // }
        });
      // $('#modal_resolucion').remove();
      // $('.modal-backdrop').remove();
      // $('body').removeClass('modal-open');
      // tablasolicitudes();
    });
});

$(function(){
    $(document).ready(function(){
      // $("#tipo_pago").hide(0);
    	// var date = new Date(); var currentMonth = date.getMonth(); var currentDate = date.getDate(); var currentYear = date.getFullYear();
      //   $('#fecha_resultado').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true}).datepicker("setDate", new Date());
    });
  });

</script>
