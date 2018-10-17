<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<script>

  function iniciar() {
    $(".checked").each(function(){
      $(this).prop('checked', true);
    });
  }

</script>

<div class="page-wrapper">
  <div class="container-fluid">

    <div class="row page-titles">
      <div class="align-self-center" align="center">
        <h3 class="text-themecolor m-b-0 m-t-0">Cambio de roles</h3>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-1"></div>
      <div class="col-lg-10" id="cnt_form_main">
        <div class="card">
          <div class="card-header bg-success2">
            <h4 class="card-title m-b-0 text-white"> Listado de Colaboradores</h4>
          </div>
          <div class="card-body b-t">

            <div class="cnt_form">
              <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

              <span class="etiqueta">Rol de Filtro</span>

              <blockquote class="m-t-0">
                <div class="row">
                  <div class="form-group col-lg-8 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">

                    <?php if ($colaboradores) {
                      foreach ($colaboradores->result() as $fila) {
                        ?>
                        <div class="row">
                          <div class="col-8">
                            <?= $fila->nombre_rol?>
                            <h5><?= $fila->nombre_completo?></h5>
                          </div>
                          <div class="col-4">
                            <div class="switch">
                              <label>
                                Delegado(a)
                                <input type="checkbox" <?= ($fila->nombre_rol == 'FILTRO CCIT') ? "class='checked pred'" : "class='check'" ?> name="<?= $fila->id_empleado?>" value="1">
                                <span class="lever switch-col-blue"></span>Filtro
                              </label>
                            </div>
                          </div>
                        </div>
                    <?php
                      }
                    } ?>
                  </div>
                </div>
              </blockquote>

              <div align="right" id="btnadd1">
                <button type="submit" class="btn waves-effect waves-light btn-success2">
                  Guadar </button>
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
    $("#formajax").on("submit", function(e){
      e.preventDefault();
      var f = $(this);
      var formData = new FormData(document.getElementById("formajax"));

      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/roles/gestionar_roles",
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
          swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
        }
      });
    });
  });

</script>
