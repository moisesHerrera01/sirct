<?php $color = array('#26c6da', '#1e88e5', '#7460ee', '#ffb22b', '#fc4b6c', '#99abb4'); ?>
<script type="text/javascript">

    function iniciar(){
        toogle_Options2(0);
	}

    function toogle_Options(inteval){
        $("#cnt_options").hide(inteval);
        $("#cnt_indicadores").show(inteval);
        toogle_buttons2();
        chart2.update();
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
        var param = { 'tipo_solicitud' : '1' };
        OpenWindowWithPost("<?php echo site_url(); ?>/resolucion_conflictos/solicitudes", param);
    }else{
        var param = { 'tipo_solicitud' : '1' };
        OpenWindowWithPost("<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica", param);  
    }
}

function redireccionar_diferencia_laboral(tipo){
    if(tipo == "1"){
        var param = { 'tipo_solicitud' : '2' };
        OpenWindowWithPost("<?php echo site_url(); ?>/resolucion_conflictos/solicitudes", param);
    }else{
        var param = { 'tipo_solicitud' : '2' };
        OpenWindowWithPost("<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica", param);  
    }
}

function redireccionar_retiro_voluntario(tipo){
    var param = { 'tipo_solicitud' : '3' };
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
                        <h3 class="text-themecolor pull-left">Dashboard</h3>

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
                <div class="col-lg-4 col-xlg-3 col-md-5">
                    <div class="card blog-widget">
                        <div class="card-body text-center">
                            <div class="blog-image"><img src="<?=base_url().'/assets/images/portadas/despido_injustificado.jpg'?>" alt="img" class="img-responsive"></div>
                            <h3>Despido de Hecho o injustificado</h3>
                            <a href="javascript:void(0)" onclick="redireccionar_despido_hecho(1)" class="m-t-10 waves-effect waves-dark btn btn-info btn-md btn-rounded">Persona natural</a>
                            <a href="javascript:void(0)" onclick="redireccionar_despido_hecho(2)" class="m-t-10 waves-effect waves-dark btn btn-warning btn-md btn-rounded">Persona jurídica</a>
                            <div class="d-flex">
                                <div class="read"><a href="javascript:void(0)" class="link font-medium">Read More</a></div>
                                <div class="ml-auto">
                                    <a href="javascript:void(0)" class="link m-r-10 " data-toggle="tooltip" title="" data-original-title="Like"><i class="mdi mdi-heart-outline"></i></a> <a href="javascript:void(0)" class="link" data-toggle="tooltip" title="" data-original-title="Share"><i class="mdi mdi-share-variant"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xlg-3 col-md-5">
                    <div class="card blog-widget">
                        <div class="card-body text-center">
                            <div class="blog-image"><img src="<?=base_url().'/assets/images/portadas/diferencia_laboral.jpg'?>" alt="img" class="img-responsive"></div>
                            <h3>Diferencia laboral</h3>
                            <a href="javascript:void(0)" onclick="redireccionar_diferencia_laboral(1)" class="m-t-10 waves-effect waves-dark btn btn-info btn-md btn-rounded">Persona natural</a>
                            <a href="javascript:void(0)" onclick="redireccionar_diferencia_laboral(2)" class="m-t-10 waves-effect waves-dark btn btn-warning btn-md btn-rounded">Persona jurídica</a>
                            <div class="d-flex">
                                <div class="read"><a href="javascript:void(0)" class="link font-medium">Read More</a></div>
                                <div class="ml-auto">
                                    <a href="javascript:void(0)" class="link m-r-10 " data-toggle="tooltip" title="" data-original-title="Like"><i class="mdi mdi-heart-outline"></i></a> <a href="javascript:void(0)" class="link" data-toggle="tooltip" title="" data-original-title="Share"><i class="mdi mdi-share-variant"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xlg-3 col-md-5">
                    <div class="card blog-widget">
                        <div class="card-body text-center">
                            <div class="blog-image"><img src="<?=base_url().'/assets/images/portadas/renuncia_voluntaria.png'?>" alt="img" class="img-responsive"></div>
                            <h3>Retiro voluntario</h3>
                            <div>
                            <br>
                            <a href="javascript:void(0)" onclick="redireccionar_retiro_voluntario(3)" class="m-t-10 waves-effect waves-dark btn btn-info btn-md btn-rounded">Persona natural</a>
                            <br><br>
                            </div>
                            <div class="d-flex">
                                <div class="read"><a href="javascript:void(0)" class="link font-medium">Read More</a></div>
                                <div class="ml-auto">
                                    <a href="javascript:void(0)" class="link m-r-10 " data-toggle="tooltip" title="" data-original-title="Like"><i class="mdi mdi-heart-outline"></i></a> <a href="javascript:void(0)" class="link" data-toggle="tooltip" title="" data-original-title="Share"><i class="mdi mdi-share-variant"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <div id="cnt_indicadores" >
                <div class="row">

	                <div class="col-lg-12">
	                    <div class="card">
	                        <div class="card-body">
	                            <h4 align="center">ESTADÍSTICAS POR TIPO DE SOLICITUD</h4>
	                            <div class="d-flex flex-row" align="center">
	                            <?php
	                            	$total = 0;
	                            	if($clase_asociacion->num_rows() > 0){
	                                    foreach ($clase_asociacion->result() as $fila_ca) { $total += $fila_ca->cantidad; }
	                                }
	                                if($total == 0){
	                                	$total = 1;
	                                }
	                                if($clase_asociacion->num_rows() > 0){
	                                    foreach ($clase_asociacion->result() as $fila_ca) {
	                            ?>
	                                <div class="p-0 b-r" align="center" style="width: 50%;">
	                                    <h6 class="font-light"><?=$fila_ca->nombre?></h6><h6><?=$fila_ca->cantidad?></h6><h6><?php echo number_format((($fila_ca->cantidad/$total)*100),2); ?>%</h6>
	                                </div>

	                            <?php } }?>
	                            </div>
	                        </div>
	                    </div>
	                </div>

					<div class="col-lg-12">
						<div class="row">
		                    <div class="col-md-12 col-lg-12">
		                        <div class="card">
		                            <div class="card-body" style="position: relative;">
		                                <h3 class="card-title">Estadísticas por estado </h3>
		                                <h3 align="center" class="text-muted" style="z-index: 0; left:50%; top: 60%; position: absolute; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%);">Estados</h3>
	                                    <div style="margin-left: 10px; margin-right: 10px;">
	                                        <canvas id="myChart2" style="min-height: 200px;"></canvas>
	                                    </div>
		                            </div>
		                            <div>
		                                <hr class="m-t-0 m-b-0">
		                            </div>
		                            <div class="card-body text-center ">
		                                <ul class="list-inline m-b-0">
		                                	<?php
		                                	$cont = 0;
                                            $arrayName = array('Esperando audiencia', 'Con resultado', 'Archivado', 'Inactivo');
		                                	for($i =0; $i<4; $i++){
        									?>
        										<li>
			                                        <h6 style="color: <?=$color[$cont]?>;">
			                                        	<i class="fa fa-circle font-10 m-r-10 "></i><?=$arrayName[$cont]?>
			                                        </h6> 
		                                        </li>
        									<?php $cont++;}  ?>
		                                </ul>
		                            </div>
		                        </div>
		                    </div>

						

						</div>
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

var ctx2;
var chart2;

$( document ).ready(function() {

ctx2 = document.getElementById('myChart2').getContext('2d');
chart2 = new Chart(ctx2, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: [<?php
	$contador = 0;
	if($tipo_asociacion->num_rows() > 0){
        foreach ($tipo_asociacion->result() as $fila_sa) { 
        	$contador++;
        	if($tipo_asociacion->num_rows() == $contador){echo '"'.mb_substr($fila_sa->nombre, 0, 25).'.."';}else{echo '"'.mb_substr($fila_sa->nombre, 0, 25).'..",';} }
    }
?>],
        datasets: [
        {
            label: "Esp. Audiencia",
            backgroundColor: "<?php echo $color[0]; ?>",
            data: [
                <?php if($tipo_asociacion->num_rows() > 0){
                    foreach ($tipo_asociacion->result() as $fila_sa) { 
                        echo $fila_sa->estado1.", ";
                        }
                    }
            ?>
            ]
        },
        {
            label: "Con resultado",
            backgroundColor: "<?php echo $color[1]; ?>",
            data: [
                <?php if($tipo_asociacion->num_rows() > 0){
                    foreach ($tipo_asociacion->result() as $fila_sa) { 
                        echo $fila_sa->estado2.", ";
                        }
                    }
            ?>
            ]
        },
        {
            label: "Archivado",
            backgroundColor: "<?php echo $color[2]; ?>",
            data: [
                <?php if($tipo_asociacion->num_rows() > 0){
                    foreach ($tipo_asociacion->result() as $fila_sa) { 
                        echo $fila_sa->estado3.", ";
                        }
                    }
            ?>
            ]
        },
        {
            label: "Inactivo",
            backgroundColor: "<?php echo $color[3]; ?>",
            data: [
                <?php if($tipo_asociacion->num_rows() > 0){
                    foreach ($tipo_asociacion->result() as $fila_sa) { 
                        echo $fila_sa->estado4.", ";
                        }
                    }
            ?>
            ]
        }
    ]
    },

    // Configuration options go here
    options: { maintainAspectRatio: false, cutoutPercentage: 75, legend: { display: false}, scales: {
        yAxes: [{
            ticks: {
                beginAtZero: true
            }
        }]
    } }

});

});


</script>