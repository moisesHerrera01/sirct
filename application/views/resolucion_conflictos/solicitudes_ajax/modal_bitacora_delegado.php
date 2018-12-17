<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<div class="modal fade" id="modal_bitacora_delegados" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Registrar fechas de pago</h4>
        <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">×</span> <span class="sr-only">Cerrar</span></button>
      </div>
      <div class="modal-body" id="">

      </div>
    </div>
  </div>
</div>

<script>

// $(function(){
//     $("#formajax11").on("submit", function(e){
//         e.preventDefault();
//         var f = $(this);
//         var formData = new FormData(document.getElementById("formajax11"));
//         formData.append("fecha_pago", $("#fecha_pago").val());
//         formData.append("tipo_conciliacion", $("#tipo_conciliacion").val());
//         alert($("#tipo_conciliacion").val())
//         j = ($('#nuevo').find('input').length)/2;
//         i=1;
//         while (i!=(j+1)) {
//           formData.append("fecha_pago"+i, $("#fecha_pago"+i).val());
//           i++;
//         }
//         $('#modal_pagos').modal('hide');
//         $('#modal_resolucion').modal('show');
//
//         $.ajax({
//             url: "<?php echo site_url(); ?>/resolucion_conflictos/pagos/gestionar_pagos_modal",
//             type: "post",
//             dataType: "html",
//             data: formData,
//             cache: false,
//             contentType: false,
//             processData: false
//         })
//         .done(function(res){
//             if(res != "fracaso"){
//                 $.toast({ heading: 'Registro exitoso', text: 'Fechas de pagos ingresados correctamente', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
//              }//else{
//             //     swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
//             // }
//         });
//       // $('#modal_resolucion').remove();
//       // $('.modal-backdrop').remove();
//       // $('body').removeClass('modal-open');
//       // tablasolicitudes();
//     });
// });

$(function(){
    $(document).ready(function(){
      // $("#tipo_pago").hide(0);
    	// var date = new Date(); var currentMonth = date.getMonth(); var currentDate = date.getDate(); var currentYear = date.getFullYear();
      //   $('#fecha_resultado').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true}).datepicker("setDate", new Date());
    });
  });

</script>
