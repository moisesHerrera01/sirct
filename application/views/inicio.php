<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<?php $color = array('#b5999c', '#8a1111', '#7460ee', '#ffb22b', '#fc4b6c', '#99abb4');
$color2 = array('#7f6fa0', '#d0d0d1', '#7460ee', '#ffb22b', '#fc4b6c', '#99abb4'); ?>
<script type="text/javascript">

    function iniciar(){
        toogle_Options2(0);
	}

    function toogle_Options(inteval){
        $("#cnt_options").hide(inteval);
        $("#cnt_indicadores").show(inteval);
        toogle_buttons2();
        document.getElementById("chart_discapacitados").style.height = "200px";
        document.getElementById("chart_lgbti").style.height = "200px";
        document.getElementById("chart_sexo").style.height = "200px";
    }

    function toogle_Options2(interval){
        $("#cnt_options").show(interval);
        $("#cnt_indicadores").hide(interval);
        toogle_buttons();
        document.getElementById("chart_discapacitados").style.height = "200px";
        document.getElementById("chart_lgbti").style.height = "200px";
        document.getElementById("chart_sexo").style.height = "200px";
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
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title">PERSONAS POR RANGO DE EDAD </h3>
                                        <div class="row">
                                            <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                                                <h4>TOTAL: <?= $row->anios16." de ".$row->total ?><br>
                                                    <?= cast_porcent($row->anios16,$row->total)."%" ?>
                                                </h4>
                                            </div>
                                            <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                                                <div class="round round-lg align-self-center round-info"><i class="mdi mdi-human-child"></i></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <?php 
                                                    $aniosm16 = cast_porcent($row->aniosm16,$row->totalm);
                                                    $aniosf16 = cast_porcent($row->aniosf16,$row->totalf); 
                                                ?>
                                                <label class="m-b-0">Hombres: <?= $row->aniosm16." de ".$row->anios16 ?></label>
                                                <div class="progress m-b-5">
                                                    <div class="progress-bar bg-success2" style="width: <?= intval($aniosm16) ?>%; height:15px;" role="progressbar"><?= ($aniosm16) ?>%</div>
                                                </div>
                                                <label class="m-b-0">Mujeres: <?= $row->aniosf16." de ".$row->anios16 ?></label>
                                                <div class="progress m-b-5">
                                                    <div class="progress-bar bg-success2" style="width: <?= intval($aniosf16) ?>%; height:15px;" role="progressbar"><?= ($aniosf16) ?>%</div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="divider">
                                        <div class="row">
                                            <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                                                <h4>TOTAL: <?= $row->anios30." de ".$row->total ?><br>
                                                    <?= cast_porcent($row->anios30,$row->total)."%" ?>
                                                </h4>
                                            </div>
                                            <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                                                <div class="round round-lg align-self-center round-info"><i class="mdi mdi-walk"></i></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <?php 
                                                    $aniosm30 = cast_porcent($row->aniosm30,$row->anios30);
                                                    $aniosf30 = cast_porcent($row->aniosf30,$row->anios30); 
                                                ?>
                                                <label class="m-b-0">Hombres: <?= $row->aniosm30." de ".$row->anios30 ?></label>
                                                <div class="progress m-b-5">
                                                    <div class="progress-bar bg-success2" style="width: <?= intval($aniosm30) ?>%; height:15px;" role="progressbar"><?= ($aniosm30) ?>%</div>
                                                </div>
                                                <label class="m-b-0">Mujeres: <?= $row->aniosf30." de ".$row->anios30 ?></label>
                                                <div class="progress m-b-5">
                                                    <div class="progress-bar bg-success2" style="width: <?= intval($aniosf30) ?>%; height:15px;" role="progressbar"><?= ($aniosf30) ?>%</div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="divider">
                                        <div class="row">
                                            <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                                                <h4>TOTAL: <?= $row->anios50." de ".$row->total ?><br>
                                                    <?= cast_porcent($row->anios50,$row->total)."%" ?>
                                                </h4>
                                            </div>
                                            <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                                                <div class="round round-lg align-self-center round-info"><i class="mdi mdi-incognito"></i></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <?php 
                                                    $aniosm50 = cast_porcent($row->aniosm50,$row->anios50);
                                                    $aniosf50 = cast_porcent($row->aniosf50,$row->anios50); 
                                                ?>
                                                <label class="m-b-0">Hombres: <?= $row->aniosm50." de ".$row->anios50 ?></label>
                                                <div class="progress m-b-5">
                                                    <div class="progress-bar bg-success2" style="width: <?= intval($aniosm50) ?>%; height:15px;" role="progressbar"><?= ($aniosm50) ?>%</div>
                                                </div>
                                                <label class="m-b-0">Mujeres: <?= $row->aniosf50." de ".$row->anios50 ?></label>
                                                <div class="progress m-b-5">
                                                    <div class="progress-bar bg-success2" style="width: <?= intval($aniosf50) ?>%; height:15px;" role="progressbar"><?= ($aniosf50) ?>%</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title">PERSONAS DISCAPACITADAS </h3>
                                        <div class="row">
                                            <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                                                <h4>TOTAL: <?= $row->discapacitado." de ".$row->total ?><br>
                                                    <?= number_format(($row->discapacitado/$row->total)*100,2)."%" ?>
                                                </h4>
                                            </div>
                                            <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                                                <div class="round round-lg align-self-center round-info"><i class="fa fa-wheelchair"></i></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <?php 
                                                    $discapmasc = cast_porcent($row->discapacitadom,$row->totalm);
                                                    $discapfeme = cast_porcent($row->discapacitadof,$row->totalf); 
                                                ?>
                                                <label class="m-b-0">Hombres: <?= $row->discapacitadom." de ".$row->totalm ?></label>
                                                <div class="progress m-b-5">
                                                    <div class="progress-bar bg-success2" style="width: <?= intval($discapmasc) ?>%; height:15px;" role="progressbar"><?= ($discapmasc) ?>%</div>
                                                </div>
                                                <label class="m-b-0">Mujeres: <?= $row->discapacitadof." de ".$row->totalf ?></label>
                                                <div class="progress m-b-5">
                                                    <div class="progress-bar bg-success2" style="width: <?= intval($discapfeme) ?>%; height:15px;" role="progressbar"><?= ($discapfeme) ?>%</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="card-title">PERSONAS LGBTI </h3>
                                        <div class="row">
                                            <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                                                <h4>TOTAL: <?= $row->lgbti." de ".$row->total ?><br>
                                                    <?= number_format(($row->lgbti/$row->total)*100,2)."%" ?>
                                                </h4>
                                            </div>
                                            <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                                                <div class="round round-lg align-self-center round-primary"><i class="mdi mdi-gender-transgender"></i></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <?php 
                                                    $lgbtimasc = cast_porcent($row->lgbtim,$row->totalm);
                                                    $lgbtifeme = cast_porcent($row->lgbtif,$row->totalf); 
                                                ?>
                                                <label class="m-b-0">Hombres: <?= $row->lgbtim." de ".$row->totalm ?></label>
                                                <div class="progress m-b-5">
                                                    <div class="progress-bar bg-success2" style="width: <?= intval($lgbtimasc) ?>%; height:15px;" role="progressbar"><?= ($lgbtimasc) ?>%</div>
                                                </div>
                                                <label class="m-b-0">Mujeres: <?= $row->lgbtif." de ".$row->totalf ?></label>
                                                <div class="progress m-b-5">
                                                    <div class="progress-bar bg-success2" style="width: <?= intval($lgbtifeme) ?>%; height:15px;" role="progressbar"><?= ($lgbtifeme) ?>%</div>
                                                </div>
                                            </div>
                                        </div>
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
