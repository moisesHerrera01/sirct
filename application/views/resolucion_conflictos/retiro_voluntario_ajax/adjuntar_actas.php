<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<div class="card">
    <div class="card-header bg-success2" id="ttl_form">
        <div class="card-actions text-white">
            <a style="font-size: 16px;" onclick="cerrar_mantenimiento();">
                <i class="mdi mdi-window-close"></i>
            </a>
        </div>
        <h4 class="card-title m-b-0 text-white">Adjuntar Acta</h4>
    </div>
    <div class="card-body b-t">

        <div id="cnt_form4" class="cnt_form">
            <form method="post" action="<?php echo site_url(); ?>/resolucion_conflictos/acta/gestionar_adjuntar_actas" enctype="multipart/form-data" class="dropzone" id="myAwesomeDropzone">
                <input type="hidden" id="id_expediente" name="id_expediente" value="<?= $id?>">
            </form>
            <div align="right" id="btnadd1">
              <button type="reset" class="btn waves-effect waves-light btn-success">
                <i class="mdi mdi-recycle"></i> Limpiar</button>
              <button type="button" id="submit_dropzone_form" class="btn waves-effect waves-light btn-success2">
                Guardar <i class="mdi mdi-chevron-right"></i>
              </button>
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
            url: "<?php echo site_url(); ?>/resolucion_conflictos/acta/gestionar_adjuntar_actas",
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