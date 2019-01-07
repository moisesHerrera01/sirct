<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<script type="text/javascript">
	function iniciar(){

	}
</script>

<div class="page-wrapper">
   <div class="container-fluid">
       <div class="row page-titles">
           <div class="align-self-center" align="center">
               <h3 class="text-themecolor m-b-0 m-t-0">Reportes de mediación individual</h3>
           </div>
       </div>
       <div class="row">
       		<div class="col-lg-2"></div>
       		<div class="col-lg-8 col-md-12">
              <div class="card">
                  <div class="card-body bg-info">
                      <h4 class="text-white card-title">Opciones del menú</h4>
                      <h6 class="card-subtitle text-white m-b-0 op-5">Seleccione uno de los siguientes reportes</h6> </div>
                  <div class="card-body">
                      <div class="message-box contact-box">
                          <h2 class="add-ct-btn"><button type="button" class="btn btn-circle btn-lg btn-secondary waves-effect waves-dark"><i class="mdi mdi-file-document"></i></button></h2>
                          <div class="message-widget contact-widget">
                              <!-- Message -->
                              <a href="<?=site_url()?>/reportes/reportes_individuales/relaciones_individuales">
                                  <div class="user-img"> <span class="round"><i class="mdi mdi-file-document text-white"></i></span> <span class="profile-status online pull-right"></span> </div>
                                  <div class="mail-contnet">
                                      <h5>Reporte de relaciones individuales</h5>
                                      <span class="mail-desc">Presenta el resumen de expedientes</span></div>
                              </a>
                              <!-- Message -->
                              <a href="<?=site_url()?>/reportes/reportes_individuales/renuncia_voluntaria">
                                  <div class="user-img"> <span class="round"><i class="mdi mdi-file-document text-white"></i></span> <span class="profile-status online pull-right"></span> </div>
                                  <div class="mail-contnet">
                                      <h5>Reporte de renuncias voluntarias</h5>
                                      <span class="mail-desc">Presenta el resumen de expedientes de renuncias voluntarias</span></div>
                              </a>
                              <!-- Message -->
                              <a href="<?=site_url()?>/reportes/reportes_individuales/consolidado">
                                  <div class="user-img"> <span class="round"><i class="mdi mdi-file-document text-white"></i></span> <span class="profile-status online pull-right"></span> </div>
                                  <div class="mail-contnet">
                                      <h5>Consolidado de solicitudes</h5>
                                      <span class="mail-desc">Presenta el consolidado de solicitudes individuales</span></div>
                              </a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="col-lg-2"></div>
       </div>
   </div>
</div>

<script>

$(function(){
   $("#formajax").on("submit", function(e){
       e.preventDefault();
       var f = $(this);
   });
});

</script>
