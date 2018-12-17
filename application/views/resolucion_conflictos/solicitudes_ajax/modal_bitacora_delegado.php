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
        <h4 class="modal-title">Bitacora de reasignación de personas delegadas</h4>
        <button type="button" class="close" data-dismiss="modal" ><span aria-hidden="true">×</span> <span class="sr-only">Cerrar</span></button>
      </div>
      <div class="modal-body" id="">
        <div id="cnt_tabla_delegados"></div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-danger waves-effect text-white" data-dismiss="modal">Cerrar</button>
      </div>      
    </div>
  </div>
</div>
