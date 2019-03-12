<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<?php $color = array('#26c6da', '#1e88e5', '#7460ee', '#ffb22b', '#fc4b6c', '#99abb4');
$color2 = array('#26c6da', 'rgb(255, 99, 132)', '#7460ee', '#ffb22b', '#fc4b6c', '#99abb4'); ?>
<script type="text/javascript">

    function iniciar(){
        toogle_Options2(0);
	}

    function toogle_Options(inteval){
        $("#cnt_options").hide(inteval);
        $("#cnt_indicadores").show(inteval);
        toogle_buttons2();
    }

    function toogle_Options2(interval){
        $("#cnt_options").show(interval);
        $("#cnt_indicadores").hide(interval);
        toogle_buttons();
    }

    function toogle_buttons(){
        $("#btn_indicador").show(300);
        $("#btn_menu").hide(300);
    }

    function toogle_buttons2(){
        $("#btn_indicador").hide(300);
        $("#btn_menu").show(300);
    }

    function OpenWindowWithPost(url, params){
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", url);
    form.setAttribute("target", "_SELF");

    for (var i in params) {
        if (params.hasOwnProperty(i)) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = i;
            input.value = params[i];
            form.appendChild(input);
        }
    }
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}


function redireccionar_despido_hecho(tipo){
    if(tipo == "1"){
        var param = { 'tipo_solicitud' : '1', 'band_mantto' : 'save' };
        OpenWindowWithPost("<?php echo site_url(); ?>/resolucion_conflictos/solicitudes", param);
    }else{
        var param = { 'tipo_solicitud' : '1', 'band_mantto' : 'save' };
        OpenWindowWithPost("<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica", param);
    }
}

function redireccionar_colectivo(tipo){
        var param = { 'band_mantto' : 'save' };
        OpenWindowWithPost("<?php echo site_url(); ?>/conflictos_colectivos/"+tipo, param);
}

function redireccionar_diferencia_laboral(tipo){
    if(tipo == "1"){
        var param = { 'tipo_solicitud' : '2', 'band_mantto' : 'save' };
        OpenWindowWithPost("<?php echo site_url(); ?>/resolucion_conflictos/solicitudes", param);
    }else{
        var param = { 'tipo_solicitud' : '2', 'band_mantto' : 'save' };
        OpenWindowWithPost("<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica", param);
    }
}

function redireccionar_retiro_voluntario(tipo){
    var param = { 'tipo_solicitud' : '3', 'band_mantto' : 'save' };
    OpenWindowWithPost("<?php echo site_url(); ?>/resolucion_conflictos/retiro_voluntario", param);
}

</script>
<!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <div class="row page-titles">
                    <div class="col-lg-12 align-self-center">
                        <h3 class="text-themecolor pull-left">INICIO </h3>

                        <div class="pull-right">
                        <div id="btn_indicador" style="display: none;"><button class="btn btn-info" onclick="toogle_Options(500);"><span class="mdi mdi-chart-bar"></span> Indicadores</button></div>
                        <div id="btn_menu"><button class="btn btn-info" onclick="toogle_Options2(500);"><span class="mdi mdi-apps"></span> Menú opciones</button></div>
                    </div>

                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->


        <div id="cnt_options">
            <div class="row">
                <?php if($tipo_rol == 1){ //MEDIACIÓN INDIVIDUAL ?>

                <div class="col-lg-4 col-xlg-3 col-md-5">
                    <div class="card blog-widget">
                        <div class="card-body text-center">
                            <div class="blog-image"><img src="<?=base_url().'/assets/images/portadas/despido_injustificado.jpg'?>" alt="img" class="img-responsive"></div>
                            <h3>Despido de Hecho o injustificado</h3>
                            <a href="javascript:void(0)" onclick="redireccionar_despido_hecho(1)" class="m-t-10 waves-effect waves-dark btn btn-info btn-md btn-rounded">Persona natural</a>
                            <a href="javascript:void(0)" onclick="redireccionar_despido_hecho(2)" class="m-t-10 waves-effect waves-dark btn btn-warning btn-md btn-rounded">Persona jurídica</a>
                            <br><br>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xlg-3 col-md-5">
                    <div class="card blog-widget">
                        <div class="card-body text-center">
                            <div class="blog-image"><img src="<?=base_url().'/assets/images/portadas/diferencia_laboral.jpg'?>" alt="img" class="img-responsive"></div>
                            <h3>Conflicto laboral</h3>
                            <a href="javascript:void(0)" onclick="redireccionar_diferencia_laboral(1)" class="m-t-10 waves-effect waves-dark btn btn-info btn-md btn-rounded">Persona natural</a>
                            <a href="javascript:void(0)" onclick="redireccionar_diferencia_laboral(2)" class="m-t-10 waves-effect waves-dark btn btn-warning btn-md btn-rounded">Persona jurídica</a>
                            <br><br>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xlg-3 col-md-5">
                    <div class="card blog-widget">
                        <div class="card-body text-center">
                            <div class="blog-image"><img src="<?=base_url().'/assets/images/portadas/renuncia_voluntaria.png'?>" alt="img" class="img-responsive"></div>
                            <h3>Notificación de renuncia voluntaria</h3>
                            <div>
                            <br>
                            <a href="javascript:void(0)" onclick="redireccionar_retiro_voluntario(3)" class="m-t-10 waves-effect waves-dark btn btn-info btn-md btn-rounded">Persona natural</a>
                            <br>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
                <?php }else{ //MEDIACIÓN COLECTIVA ?>
                <div class="col-lg-2 col-md-1"></div>
                <div class="col-lg-4 col-xlg-3 col-md-5">
                    <div class="card blog-widget">
                        <div class="card-body text-center">
                            <div class="blog-image"><img src="<?=base_url().'/assets/images/portadas/sindicato_cc.jpg'?>" alt="img" class="img-responsive"></div>
                            <h3>Conflicto Laboral</h3>
                            <div>
                            <br>
                            <a href="javascript:void(0)" onclick="redireccionar_colectivo('sindicato')" class="m-t-10 waves-effect waves-dark btn btn-info btn-md btn-rounded">Nueva solicitud</a>
                            <br><br>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xlg-3 col-md-5">
                    <div class="card blog-widget">
                        <div class="card-body text-center">
                            <div class="blog-image"><img src="<?=base_url().'/assets/images/portadas/diferencia_laboral_cc.jpg'?>" alt="img" class="img-responsive"></div>
                            <h3>Indenmización y Prestaciones Laborales</h3>
                            <div>

                            <a href="javascript:void(0)" onclick="redireccionar_colectivo('solicitud_indemnizacion')" class="m-t-10 waves-effect waves-dark btn btn-info btn-md btn-rounded">Nueva solicitud</a>
                            <br><br>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-1"></div>
                <?php } ?>
            </div>
        </div>

            <div id="cnt_indicadores" >

                <div class="row col-lg-12">
                    <ul class="nav nav-tabs customtab2 <?php if($navegatorless){ echo "pull-left"; } ?>" role="tablist" style='width: 100%;'>
                        <li class="nav-item <?php if($navegatorless){ echo "pull-left"; } ?>">
                            <a class="nav-link active" data-toggle="tab" href="#tab_persona_natural">
                                <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                <span class="hidden-xs-down">MEDIACIÓN INDIVIDUAL</span></a>
                        </li>
                        <li class="nav-item <?php if($navegatorless){ echo "pull-left"; } ?>">
                            <a class="nav-link" data-toggle="tab" href="#tab_persona_juridica">
                                <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                <span class="hidden-xs-down">MEDIACIÓN COLECTIVA</span></a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_persona_natural" role="tabpanel">
                        <div class="p-20">
                            <?php
                            if($contadores->num_rows() > 0){
                                $row = $contadores->row();
                            ?>
                            <div class="row">
                                <div class="col-md-3 col-lg-3">
                                    <div class="card">
                                        <div class="card-body" style="position: relative;">
                                            <h3 class="card-title">Estadística por estado </h3>
                                            <h3 align="center" class="text-muted" style="z-index:0; left:50%; top: 60%; position: absolute; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%);">
                                                Activos <br> <?php 
                                                if($row->total != 0){
                                                    $porcentaje = number_format( (($row->discapacitado/$row->total)*100), 2, '.', ''); 
                                                }
                                                $split = explode('.',$porcentaje);
                                                if($split[1] == "00"){
                                                    echo $split[0]."%";
                                                }else{
                                                    echo $porcentaje."%";
                                                }?>
                                            </h3>
                                            <div style="margin-left: 10px; margin-right: 10px;">
                                                <canvas id="myChart3" style="height: 200px;"></canvas>
                                            </div>
                                        </div>
                                        <div>
                                            <hr class="m-t-0 m-b-0">
                                        </div>
                                        <div class="card-body text-center">
                                            <ul class="list-inline m-b-0">
                                                <li>
                                                    <h6 style="color: <?=$color2[0]?>;">
                                                        <i class="fa fa-circle font-10 m-r-10 ">Discapacitado</i>
                                                    </h6> 
                                                </li>
                                                <li>
                                                    <h6 style="color: <?=$color2[1]?>;">
                                                        <i class="fa fa-circle font-10 m-r-10 "></i>No Discapacitado
                                                    </h6> 
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="tab-pane  p-20" id="tab_persona_juridica" role="tabpanel">
                        <div id="cnt_tabla_persona_juridica">MEDIACIÓN COLECTIVA</div>
                    </div>
                </div>



                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
            </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->

        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->


<script src="<?php echo base_url(); ?>assets/js/Chart.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/echarts/echarts-all.js"></script>
<script type="text/javascript">

var ctx3;
var chart3;

$( document ).ready(function() {

ctx3 = document.getElementById('myChart3').getContext('2d');
chart3 = new Chart(ctx3, {
    // The type of chart we want to create
    type: 'doughnut',

    // The data for our dataset
    data: {
        labels: ['Hombres', 'Mujeres'],
        datasets: [{
            backgroundColor: [<?= $color[0].",".$color[1] ?>],
            data: [<?= $row->discapacitado.','.$row->nodiscapacitado ?>],
        }]
    },

    // Configuration options go here
    options: { maintainAspectRatio: false, cutoutPercentage: 75, legend: { display: false} }

});

});


</script>
