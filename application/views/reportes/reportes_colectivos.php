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
               <h3 class="text-themecolor m-b-0 m-t-0">Reportes conciliación indivial</h3>
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
                                        <a href="<?=site_url()?>/reportes/reportes_colectivos/relaciones_colectivas">
                                            <div class="user-img"> <span class="round"><i class="ti-server text-white"></i></span> <span class="profile-status online pull-right"></span> </div>
                                            <div class="mail-contnet">
                                                <h5>Reporte de relaciones colectivas</h5>
                                                <span class="mail-desc">Muestra todos registros de expediente con sus respectivos datos</span></div>
                                        </a>
                                        <!-- Message -->
                                        <a href="<?=site_url()?>/reportes/reportes_colectivos/registro_edades">
                                            <div class="user-img"> <span class="round"><i class="ti-server text-white"></i></span> <span class="profile-status online pull-right"></span> </div>
                                            <div class="mail-contnet">
                                                <h5>Reporte de registro de edades</h5>
                                                <span class="mail-desc">Muestra los expedientes con el conteo de edades segregados por sexo</span></div>
                                        </a>
                                        <a href="<?=site_url()?>/reportes/reportes_colectivos/tipo_pago">
                                            <div class="user-img"> <span class="round"><i class="ti-server text-white"></i></span> <span class="profile-status online pull-right"></span> </div>
                                            <div class="mail-contnet">
                                                <h5>Reporte por tipo de pago</h5>
                                                <span class="mail-desc">Reporte por tipo de pago</span></div>
                                        </a>
                                        <!-- Message -->
                                        <a href="#">
                                            <div class="user-img"> <span class="round"><i class="ti-server text-white"></i></span> <span class="profile-status away pull-right"></span> </div>
                                            <div class="mail-contnet">
                                                <h5>Arijit Sinh</h5> <span class="mail-desc">cruise1298.fiplip@gmail.com</span></div>
                                        </a>
                                        <!-- Message -->
                                        <a href="#">
                                            <div class="user-img"> <span class="round"><i class="ti-server text-white"></i></span> <span class="profile-status offline pull-right"></span> </div>
                                            <div class="mail-contnet">
                                                <h5>Pavan kumar</h5> <span class="mail-desc">kat@gmail.com</span></div>
                                        </a>
                                    </div>
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
   });
});

</script>
