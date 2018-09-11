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
        <h4 class="card-title m-b-0 text-white">Gestionar Acta</h4>
    </div>
    <div class="card-body b-t">

        <div id="cnt_form4" class="cnt_form">
            <form method="post" action="<?php echo site_url(); ?>/resolucion_conflictos/acta/gestionar_adjuntar_actas" enctype="multipart/form-data" class="dropzone" id="myAwesomeDropzone">
                <input type="hidden" id="id_expediente" name="id_expediente" value="<?= $id ?>">
            </form>
            <br>
            <div align="right" id="btnadd1">
              <button type="reset" class="btn waves-effect waves-light btn-success">
                <i class="mdi mdi-recycle"></i> Limpiar</button>
              <button type="button" id="submit_dropzone_form" class="btn waves-effect waves-light btn-success2">
                Guardar <i class="mdi mdi-chevron-right"></i>
              </button>
            </div>
        </div>
        <br><br>
        <div id="cnt_tabla_actas"></div>
    </div>
</div>


<script>

function tabla_acta(id_expediente) {
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttpB = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttpB = new ActiveXObject("Microsoft.XMLHTTPB");
    }
    xmlhttpB.onreadystatechange = function () {
        if (xmlhttpB.readyState == 4 && xmlhttpB.status == 200) {
            document.getElementById("cnt_tabla_actas").innerHTML = xmlhttpB.responseText;
            $('[data-toggle="tooltip"]').tooltip();
            $('#myTable').DataTable();
        }
    }
    xmlhttpB.open("GET", "<?php echo site_url(); ?>/resolucion_conflictos/acta/tabla_acta?id_expediente="+id_expediente);
    xmlhttpB.send();
}

function eliminar_acta(id_acta, id_expediente) {
    $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/acta/eliminar_acta",
        type: "post",
        dataType: "html",
        data: {id_acta: id_acta}
    })
    .done(function (res) {
        if (res == "fracaso") {
            swal({
                title: "¡Ups! Error",
                text: "Intentalo nuevamente.",
                type: "error",
                showConfirmButton: true
            });
        } else {
            swal({
                title: "¡Archivo eliminado exitosamente!",
                type: "success",
                showConfirmButton: true
            });

            tabla_acta(id_expediente);
        }
    });

}

</script>