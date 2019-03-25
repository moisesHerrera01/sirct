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
        indicadores();
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

    function indicadores() {

        $.ajax({
          url: "<?php echo site_url(); ?>/inicio/indicadores",
          type: "post",
          dataType: "html",
          data: {
            id_delegado: $("#id_delegado").val(),
            mes: $("#mes").val(),
            anio: $("#anio_actual").val()
          }
        })
        .done(function (res) {
          $("#cnt_indicadores2").html(res);
        });
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

                        <div class="row">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <h5>Persona delegada: <span class="text-danger"></span></h5>
                                    <select id="id_delegado" name="id_delegado" class="select2" style="width: 100%"onchange="indicadores();">
                                        <?php
                                            $ids_delegados = array();
                                            $content = "";
                                            if($colaborador->num_rows() > 0){
                                                foreach ($colaborador->result() as $fila) {
                                                    array_push($ids_delegados, $fila->id_empleado);
                                                    if($fila->id_empleado==$id){ 
                                                        $content .= "<option value='".$fila->id_empleado."' selected>".$fila->nombre_completo."</option>";
                                                    }else{
                                                        $content .= "<option value='".$fila->id_empleado."'>".$fila->nombre_completo."</option>";
                                                    }
                                                }
                                            }
                                            $ids_delegados = implode(",", $ids_delegados);
                                            echo "<option value='".$ids_delegados."'>[Seleccione]</option>".$content;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <div class="form-group" id="input_anio">
                                    <h5>Año: <span class="text-danger">*</span></h5>
                                    <input type="text" value="<?php echo date('Y'); ?>" class="date-own form-control" id="anio_actual" name="anio_actual" placeholder="yyyy"onchange="indicadores();">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group" id="input_mes">
                                    <h5>Mes: <span class="text-danger"></span></h5>
                                    <select id="mes" name="mes" class="select2" style="width: 100%" onchange="indicadores();">
                                        <option value="0">[Seleccione]</option>
                                        <option class="m-l-50" value="1">Enero</option>
                                        <option class="m-l-50" value="2">Febrero</option>
                                        <option class="m-l-50" value="3">Marzo</option>
                                        <option class="m-l-50" value="4">Abril</option>
                                        <option class="m-l-50" value="5">Mayo</option>
                                        <option class="m-l-50" value="6">Junio</option>
                                        <option class="m-l-50" value="7">Julio</option>
                                        <option class="m-l-50" value="8">Agosto</option>
                                        <option class="m-l-50" value="9">Septiembre</option>
                                        <option class="m-l-50" value="10">Octubre</option>
                                        <option class="m-l-50" value="11">Noviembre</option>
                                        <option class="m-l-50" value="12">Diciembre</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="cnt_indicadores2"></div>
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
