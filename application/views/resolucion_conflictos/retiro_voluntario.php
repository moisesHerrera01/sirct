<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<script type="text/javascript">
    function iniciar() {
        <?php if(tiene_permiso($segmentos=2,$permiso=1)){ ?>
        tablasolicitudes();
        <?php }else{ ?>
        $("#cnt_tabla").html("Usted no tiene permiso para este formulario.");
        <?php } ?>
    }

    function convert_lim_text(lim) {
        var tlim = "-" + lim + "d";
        return tlim;
    }

    var estado_pestana = "";

    function cambiar_pestana(tipo) {
        estado_pestana = tipo;
        tablasolicitudes();
    }

    function combo_establecimiento(seleccion) {

        $.ajax({
                url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/combo_establecimiento",
                type: "post",
                dataType: "html",
                data: {
                    id: seleccion
                }
            })
            .done(function (res) {
                $('#div_combo_establecimiento').html(res);
                $(".est").select2({
                    'minimumInputLength': 3,
                    'language': {
                        noResults: function () {
                            return '<a href="javascript:;" data-toggle="modal" data-target="#modal_establecimiento" title="Agregar nuevos establecimientos" onClick="cerrar_combo_establecimiento()">Agregar uno nuevo</a>';
                        }
                    },
                    'escapeMarkup': function (markup) {
                        return markup;
                    }
                });
            });

    }

    function combo_actividad_economica() {

        $.ajax({
                url: "<?php echo site_url(); ?>/resolucion_conflictos/establecimiento/combo_actividad_economica",
                type: "post",
                dataType: "html"
            })
            .done(function (res) {
                $('#div_combo_actividad_economica').html(res);
                $(".select2").select2();
            });

    }

    function combo_municipio() {

        $.ajax({
                url: "<?php echo site_url(); ?>/resolucion_conflictos/establecimiento/combo_municipio",
                type: "post",
                dataType: "html"
            })
            .done(function (res) {
                $('#div_combo_municipio').html(res);
                $(".select2").select2();
            });

    }

    function combo_delegado(seleccion) {

        $.ajax({
                url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/combo_delegado",
                type: "post",
                dataType: "html",
                data: {
                    id: seleccion
                }
            })
            .done(function (res) {
                $('#div_combo_delegado').html(res);
                $(".select2").select2();
            });

    }

    function open_form(num) {
        $(".cnt_form").hide(0);
        $("#cnt_form" + num).show(0);

        if ($("#band").val() == "save") {
            $("#btnadd" + num).show(0);
            $("#btnedit" + num).hide(0);
        } else {
            $("#btnadd" + num).hide(0);
            $("#btnedit" + num).show(0);
        }
    }

    function cerrar_mantenimiento() {
        $("#cnt_tabla").show(0);
        $("#cnt_tabla_solicitudes").show(0);
        $("#cnt_form_main").hide(0);
        $("#cnt_actions").hide(0);
        $("#cnt_actions").remove('.card');
        open_form(1);
        tablasolicitudes();
    }

    function objetoAjax() {
        var xmlhttp = false;
        try {
            xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (E) {
                xmlhttp = false;
            }
        }
        if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
            xmlhttp = new XMLHttpRequest();
        }
        return xmlhttp;
    }

    function tablasolicitudes() {
        var nr_empleado = $("#nr_search").val();
        if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttpB = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttpB = new ActiveXObject("Microsoft.XMLHTTPB");
        }
        xmlhttpB.onreadystatechange = function () {
            if (xmlhttpB.readyState == 4 && xmlhttpB.status == 200) {
                document.getElementById("cnt_tabla_solicitudes").innerHTML = xmlhttpB.responseText;
                $('[data-toggle="tooltip"]').tooltip();
                $('#myTable').DataTable();
            }
        }
        xmlhttpB.open("GET", "<?php echo site_url(); ?>/resolucion_conflictos/retiro_voluntario/tabla_solicitudes?nr=" +
            nr_empleado + "&tipo=" + estado_pestana, true);
        xmlhttpB.send();
    }

    function alertFunc() {
        $('[data-toggle="tooltip"]').tooltip()
    }

    function cambiar_nuevo() {
        /*Inicio Solicitante*/
        $("#id_personaci").val('');
        $("#nr").val($("#nr_search").val()).trigger('change.select2');
        $("#nombres").val('');
        $("#apellidos").val('');
        $("#dui").val('');
        $("#telefono").val('');
        $("#municipio").val('').trigger('change.select2');
        $("#direccion").val('');
        $("#fecha_nacimiento").val('');
        $("#sexo").val('');
        $("#estudios").val('');
        $("#nacionalidad").val('');
        $("#discapacidad").val('');
        /*Fin Solicitante*/

        /*Inicio Expediente*/
        combo_delegado('');
        combo_actividad_economica();
        combo_municipio();
        $("#id_personal").val('');
        $("#establecimiento").val('');
        /*Fin expediente*/

        $("#band").val("save");
        $("#band1").val("save");
        $("#band2").val("save");

        $("#ttl_form").addClass("bg-success");
        $("#ttl_form").removeClass("bg-info");

        $("#btnadd").show(0);
        $("#btnedit").hide(0);

        $("#cnt_tabla").hide(0);
        $("#cnt_form_main").show(0);

        $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> Nueva Solicitud");
        combo_establecimiento('');
    }

    function cambiar_editar(id_personaci, bandera) {
        $("#id_persona").val(id_personaci);

        if (bandera == "edit") {

            $.ajax({
                    url: "<?php echo site_url(); ?>/resolucion_conflictos/retiro_voluntario/registro_expediente",
                    type: "POST",
                    data: {
                        id: id_personaci
                    }
                })
                .done(function (res) {
                    result = JSON.parse(res)[0];

                    $("#id_personaci").val(result.id_personaci);
                    $("#id_persona").val(result.id_persona);
                    $("#id_expedienteci").val(result.id_expedienteci);
                    $("#nr").val($("#nr_search").val()).trigger('change.select2');
                    $("#nombres").val(result.nombre_personaci);
                    $("#apellidos").val(result.apellido_personaci);
                    $("#dui").val(result.dui_personaci);
                    $("#telefono").val(result.telefono_personaci);
                    $("#municipio").val(result.id_municipio.padStart(5, "00000")).trigger('change.select2');
                    $("#direccion").val(result.direccion_personaci);
                    $("#fecha_nacimiento").val(result.fnacimiento_personaci);
                    $("#sexo").val(result.sexo_personaci);
                    $("#estudios").val(result.estudios_personaci);
                    $("#nacionalidad").val(result.nacionalidad_personaci);
                    $("#discapacidad").val(result.discapacidad_personaci);

                    /*Inicio Expediente*/
                    combo_delegado(result.id_personal);
                    combo_actividad_economica(result.id_catalogociiu);
                    combo_municipio(result.id_municipio1);
                    combo_establecimiento(result.id_empresaci);


                    /*Fin expediente*/
                    $("#band").val("edit");
                    $("#band1").val("edit");
                    $("#band2").val("edit");
                });

            $("#ttl_form").removeClass("bg-success");
            $("#ttl_form").addClass("bg-info");
            $("#btnadd1").hide(0);
            $("#btnedit1").show(0);
            $("#btnadd2").hide(0);
            $("#btnedit2").show(0);
            $("#cnt_tabla_solicitudes").hide(0);
            $("#cnt_tabla").hide(0);
            $("#cnt_form_main").show(0);
            $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Editar Expediente");
        } else {
            //eliminar_reglamento();
        }
    }

    function volver(num) {
        open_form(num);
        $("#band" + num).val("edit")
    }

    function editar_solicitud() {
        $("#band").val("edit");
        enviarDatos();
    }

    function visualizar(id_personaci, id_empresaci) {
        $.ajax({
                url: "<?php echo site_url(); ?>/resolucion_conflictos/retiro_voluntario/ver_expediente",
                type: "post",
                dataType: "html",
                data: {
                    id: id_personaci,
                    id_emp: id_empresaci
                }
            })
            .done(function (res) {
                $('#cnt_actions').html(res);
                $("#cnt_actions").show(0);
                $("#cnt_tabla").hide(0);
                $("#cnt_tabla_solicitudes").hide(0);
                $("#cnt_form_main").hide(0);
            });
    }

    function adjuntar_actas(id_expediente) {
        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/acta",
            type: "post",
            dataType: "html",
            data: {
                id: id_expediente
            }
        })
        .done(function (res) {
            $('#cnt_actions').html(res);
            $("#cnt_actions").show(0);
            $("#cnt_tabla").hide(0);
            $("#cnt_tabla_solicitudes").hide(0);
            $("#cnt_form_main").hide(0);

            $("#myAwesomeDropzone").dropzone({
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 10,
                successmultiple: function (data, response) {
                    $("#uploaded_files").val(response);
                },
                init: function () {
                    var submitButton = document.querySelector("#submit_dropzone_form");
                    myDropzone = this;
                    submitButton.addEventListener("click", function () {
                        myDropzone.processQueue();
                    });
                }
            });
        });
    }

</script>

<div class="page-wrapper">
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- TITULO de la página de sección -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="align-self-center" align="center">
                <h3 class="text-themecolor m-b-0 m-t-0">Renuncia Voluntaria</h3>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Fin TITULO de la página de sección -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Inicio del CUERPO DE LA SECCIÓN -->
        <!-- ============================================================== -->
        <div class="row" <?php if($navegatorless){ echo "style='margin-right: 80px;'" ; } ?>>
            <!-- ============================================================== -->
            <!-- Inicio del FORMULARIO INFORMACIÓN DEL SOLICITANTE -->
            <!-- ============================================================== -->
            <div class="col-lg-1"></div>
            <div class="col-lg-10" id="cnt_form_main" style="display: none;">
                <div class="card">
                    <div class="card-header bg-success2" id="ttl_form">
                        <div class="card-actions text-white">
                            <a style="font-size: 16px;" onclick="cerrar_mantenimiento();"><i class="mdi mdi-window-close"></i></a>
                        </div>
                        <h4 class="card-title m-b-0 text-white">Listado de Solicitudes</h4>
                    </div>
                    <div class="card-body b-t">

                        <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                        <div id="cnt_form1" class="cnt_form">
                            <h3 class="box-title" style="margin: 0px;">
                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso
                                    1</button>&emsp;
                                Información del solicitante
                            </h3>
                            <hr class="m-t-0 m-b-30">
                            <input type="hidden" id="band" name="band" value="save">
                            <input type="hidden" id="band1" name="band1" value="save">
                            <input type="hidden" id="estado" name="estado" value="1">
                            <input type="hidden" id="id_personaci" name="id_personaci" value="">


                            <span class="etiqueta">Expediente</span>
                            <blockquote class="m-t-0">

                                <div class="row">
                                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "
                                        pull-left"; } ?>">
                                        <h5>Nombres: <span class="text-danger">*</span></h5>
                                        <input type="text" id="nombres" name="nombres" class="form-control" placeholder="Nombres de la persona"
                                            required="">
                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "
                                        pull-left"; } ?>">
                                        <h5>Apellidos: <span class="text-danger">*</span></h5>
                                        <input type="text" id="apellidos" name="apellidos" class="form-control"
                                            placeholder="Apellidos de la persona" required="">
                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group col-lg-4" style="height: 83px;">
                                        <h5>Número de DUI: <span class="text-danger">*</span></h5>
                                        <input data-mask="99999999-9" type="text" id="dui" name="dui" class="form-control"
                                            placeholder="Documento Unico de Identidad" required="">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4" style="height: 83px;">
                                        <h5>Teléfono: </h5>
                                        <input data-mask="9999-9999" type="text" id="telefono" name="telefono" class="form-control"
                                            placeholder="Número de Telefóno">
                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "
                                        pull-left"; } ?>">
                                        <h5>Municipio: <span class="text-danger">*</span></h5>
                                        <select id="municipio" name="municipio" class="select2" style="width: 100%"
                                            required>
                                            <option value=''>[Seleccione el municipio]</option>
                                            <?php
                                            $municipio = $this->db->query("SELECT * FROM org_municipio ORDER BY municipio");
                                            if($municipio->num_rows() > 0){
                                                foreach ($municipio->result() as $fila2) {
                                                   echo '<option class="m-l-50" value="'.$fila2->id_municipio.'">'.$fila2->municipio.'</option>';
                                                }
                                            }
                                        ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-4 <?php if($navegatorless){ echo " pull-left"; } ?>">
                                        <h5>Fecha de nacimiento: <span class="text-danger">*</span></h5>
                                        <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control"
                                            id="fecha_nacimiento" name="fecha_nacimiento" placeholder="dd/mm/yyyy"
                                            readonly="">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4" style="height: 83px;">
                                        <h5>Estudios realizados:</h5>
                                        <input type="text" id="estudios" name="estudios" class="form-control"
                                            placeholder="Estudios realizados">
                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group col-lg-4" style="height: 83px;">
                                        <h5>Nacionalidad:</h5>
                                        <input type="text" id="nacionalidad" name="nacionalidad" class="form-control"
                                            placeholder="Nacionalidad">
                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group col-lg-2" style="height: 83px;">
                                        <h5>Sexo:</h5>
                                        <input name="sexo" type="radio" id="masculino" checked="" value="M">
                                        <label for="masculino">Masculino</label>
                                        <input name="sexo" type="radio" id="femenino" value="F">
                                        <label for="femenino">Femenino</label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-8" style="height: 83px;">
                                        <h5>Dirección:</h5>
                                        <textarea type="text" id="direccion" name="direccion" class="form-control"
                                            placeholder="Dirección completa"></textarea>
                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group col-lg-2" style="height: 83px;">
                                        <h5>Discapacidad:</h5>
                                        <input name="discapacidad" type="radio" id="si" value="SI">
                                        <label for="si">Si </label><Br>
                                        <input name="discapacidad" type="radio" id="no" checked="" value="NO">
                                        <label for="no">No</label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </blockquote>

                            <div align="right" id="btnadd1">
                                <button type="reset" class="btn waves-effect waves-light btn-success">
                                    <i class="mdi mdi-recycle"></i> Limpiar</button>
                                <button type="submit" class="btn waves-effect waves-light btn-success2">
                                    Siguiente <i class="mdi mdi-chevron-right"></i>
                                </button>
                            </div>
                            <div align="right" id="btnedit1" style="display: none;">
                                <button type="reset" class="btn waves-effect waves-light btn-success">
                                    <i class="mdi mdi-recycle"></i> Limpiar</button>
                                <button type="submit" class="btn waves-effect waves-light btn-info">
                                    Siguiente <i class="mdi mdi-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <!-- ============================================================== -->
                        <!-- Fin del FORMULARIO INFORMACIÓN DEL SOLICITANTE -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Inicio del FORMULARIO INFORMACIÓN DE LA SOLICITUD -->
                        <!-- ============================================================== -->
                        <?php echo form_open('', array('id' => 'formajax2', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                        <div id="cnt_form2" class="cnt_form" style="display: none;">
                            <h3 class="box-title" style="margin: 0px;">
                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso
                                    2</button>&emsp;
                                Información de la solicitud
                                <input type="hidden" id="band2" name="band2" value="save">
                                <input type="hidden" id="id_persona" name="id_persona" value="">
                                <input type="hidden" id="id_expedienteci" name="id_expedienteci" value="">

                            </h3>
                            <hr class="m-t-0 m-b-30">
                            <span class="etiqueta">Expediente</span>
                            <blockquote class="m-t-0">

                                <div class="row">
                                    <div class="col-lg-8 form-group <?php if($navegatorless){ echo " pull-left "; } ?>"
                                        id="div_combo_establecimiento"></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-8 form-group <?php if($navegatorless){ echo " pull-left "; } ?>"
                                        id="div_combo_delegado"></div>
                                </div>

                            </blockquote>
                            <div align="right" id="btnadd2">
                                <button type="reset" class="btn waves-effect waves-light btn-success">
                                    <i class="mdi mdi-recycle"></i> Limpiar
                                </button>
                                <button type="submit" class="btn waves-effect waves-light btn-success2">Finalizar
                                    <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                            <div align="right" id="btnedit2" style="display: none;">
                                <button type="reset" class="btn waves-effect waves-light btn-success">
                                    <i class="mdi mdi-recycle"></i> Limpiar</button>
                                <button type="submit" class="btn waves-effect waves-light btn-info">Finalizar
                                    <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <!-- ============================================================== -->
                        <!-- Fin del FORMULARIO INFORMACIÓN DE LA SOLICITUD -->
                        <!-- ============================================================== -->
                    </div>
                </div>
            </div>
            <div class="col-lg-10" id="cnt_actions" style="display:none;"></div>
            <div class="col-lg-1"></div>
            <div class="col-lg-12" id="cnt_tabla">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-b-0">Listado de Solicitudes</h4>
                    </div>
                    <div class="card-body b-t" style="padding-top: 7px;">
                        <div>
                            <div class="pull-left">
                                <div class="form-group" style="width: 400px;">
                                    <select id="nr_search" name="nr_search" class="select2" style="width: 100%"
                                        required="" onchange="tablasolicitudes();">
                                        <option value="">[Todos los empleados]</option>
                                        <?php
                                    $otro_empleado = $this->db->query("SELECT e.id_empleado, e.nr, UPPER(CONCAT_WS(' ', e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada)) AS nombre_completo FROM sir_empleado AS e WHERE e.id_estado = '00001' ORDER BY e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada");
                                    if($otro_empleado->num_rows() > 0){
                                        foreach ($otro_empleado->result() as $fila) {
                                            if($nr_usuario == $fila->nr){
                                               echo '<option class="m-l-50" value="'.$fila->nr.'" selected>'.preg_replace ('/[ ]+/', ' ', $fila->nombre_completo.' - '.$fila->nr).'</option>';
                                            }else{
                                                echo '<option class="m-l-50" value="'.$fila->nr.'">'.preg_replace ('/[ ]+/', ' ', $fila->nombre_completo.' - '.$fila->nr).'</option>';
                                            }
                                        }
                                    }
                                ?>
                                    </select>
                                </div>
                            </div>
                            <div class="pull-right">
                                <?php if(tiene_permiso($segmentos=2,$permiso=2)){ ?>
                                <button type="button" onclick="cambiar_nuevo();" class="btn waves-effect waves-light btn-success2"
                                    data-toggle="tooltip" title="Clic para agregar un nuevo registro"><span class="mdi mdi-plus"></span>
                                    Nuevo registro</button>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row" style="width: 100%"></div>
                        <div class="row col-lg-12">
                            <ul class="nav nav-tabs customtab2 <?php if($navegatorless){ echo " pull-left"; } ?>"
                                role="tablist" style='width: 100%;'>
                                <li class="nav-item <?php if($navegatorless){ echo " pull-left"; } ?>">
                                    <a class="nav-link active" onclick="cambiar_pestana('');" data-toggle="tab" href="#">
                                        <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                        <span class="hidden-xs-down">Todas</span></a>
                                </li>
                                <li class="nav-item <?php if($navegatorless){ echo " pull-left"; } ?>">
                                    <a class="nav-link" onclick="cambiar_pestana('1');" data-toggle="tab" href="#">
                                        <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                        <span class="hidden-xs-down">Esperando Audiencia</span></a>
                                </li>
                                <li class="nav-item <?php if($navegatorless){ echo " pull-left"; } ?>">
                                    <a class="nav-link" onclick="cambiar_pestana('2');" data-toggle="tab" href="#">
                                        <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                        <span class="hidden-xs-down">Con Resultado</span></a>
                                </li>
                                <li class="nav-item <?php if($navegatorless){ echo " pull-left"; } ?>">
                                    <a class="nav-link" onclick="cambiar_pestana('3');" data-toggle="tab" href="#">
                                        <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                        <span class="hidden-xs-down">Archivado</span></a>
                                </li>
                                <li class="nav-item <?php if($navegatorless){ echo " pull-left"; } ?>">
                                    <a class="nav-link" onclick="cambiar_pestana('4');" data-toggle="tab" href="#">
                                        <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                        <span class="hidden-xs-down">Inhabilitado</span></a>
                                </li>
                            </ul>
                        </div>
                        <div id="cnt_tabla_solicitudes"></div>
                    </div>
                </div>
            </div>

        </div>
        <!-- ============================================================== -->
        <!-- Fin CUERPO DE LA SECCIÓN -->
        <!-- ============================================================== -->
    </div>
</div>
<!-- ============================================================== -->
<!-- Fin de DIV de inicio (ENVOLTURA) -->
<!-- ============================================================== -->

<div style="display:none;">
    <button id="submit_ubi" name="submit_ubi" type="button">clicks</button>
</div>


<!--INICIA MODAL DE ESTABLECIMIENTOS -->
<div class="modal fade" id="modal_establecimiento" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open('', array('id' => 'formajax3', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
            <input type="hidden" id="band3" name="band3" value="save">
            <input type="hidden" id="id_representante" name="id_representante" value="">
            <div class="modal-header">
                <h4 class="modal-title">Gestión de representantes</h4>
            </div>
            <div class="modal-body" id="">

                <div class="row">
                    <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                        <h5>Nombre del establecimiento: <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" placeholder="Nombre" id="nombre_establecimiento" name="nombre_establecimiento"
                                class="form-control" required="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                        <h5>Abreviatura del establecimiento: <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" placeholder="Abreviatura" id="abre_establecimiento" name="abre_establecimiento"
                                class="form-control" required="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                        <h5>Direcci&oacute;n: <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <textarea type="text" id="dir_establecimiento" name="dir_establecimiento" class="form-control"
                                required=""></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                        <h5>Telefono: </h5>
                        <div class="controls">
                            <input type="text" placeholder="Telefono" id="telefono_establecimiento" name="telefono_establecimiento"
                                class="form-control" data-mask="9999-9999">
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_actividad_economica"></div>
                </div>

                <div class="row">
                    <div class="col-lg-12 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_municipio"></div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                        <h5>Nombre del representante: <span class="text-danger">*</span></h5>
                        <div class="controls">
                            <input type="text" id="nombre_representante" name="nombre_representante" class="form-control"
                                required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-white" data-dismiss="modal">Cerrar</button>
                <button type="submit" id="submit2" class="btn btn-info waves-effect text-white">Aceptar</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!--FIN MODAL DE ESTABLECIMIENTOS -->
<script>
    $(function () {
        $("#formajax").on("submit", function (e) {
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formajax"));

            $.ajax({
                url: "<?php echo site_url(); ?>/resolucion_conflictos/retiro_voluntario/gestionar_solicitante",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
                processData: false
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
                    open_form(2);
                    $("#id_personaci").val(res);
                    $("#id_persona").val(res);
                    $("#band1").val($("#band").val());
                    $("#band2").val($("#band").val());
                }
            });
        });
    });

    $(function () {
        $("#formajax2").on("submit", function (e) {
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formajax2"));

            $.ajax({
                    url: "<?php echo site_url(); ?>/resolucion_conflictos/retiro_voluntario/gestionar_expediente",
                    type: "post",
                    dataType: "html",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
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
                        cerrar_mantenimiento();
                        if ($("#band2").val() == "save") {
                            swal({
                                title: "¡Registro exitoso!",
                                type: "success",
                                showConfirmButton: true
                            });
                        } else if ($("#band2").val() == "edit") {
                            swal({
                                title: "¡Modificación exitosa!",
                                type: "success",
                                showConfirmButton: true
                            });
                        } else {
                            swal({
                                title: "¡Borrado exitoso!",
                                type: "success",
                                showConfirmButton: true
                            });
                        }
                        tablasolicitudes();
                    }
                });

        });
    });

    $(function () {
        $("#formajax3").on("submit", function (e) {
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formajax3"));

            $.ajax({
                    url: "<?php echo site_url(); ?>/resolucion_conflictos/establecimiento/gestionar_establecimiento",
                    type: "post",
                    dataType: "html",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
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
                            title: "¡Registro exitoso!",
                            type: "success",
                            showConfirmButton: true
                        });

                        var data = {
                            id: res,
                            text: $("#nombre_establecimiento").val()
                        };

                        var newOption = new Option(data.text, data.id, false, false);
                        $('#establecimiento').append(newOption).trigger('change');
                        $('#establecimiento').val(data.id).trigger("change");
                        $('#modal_establecimiento').modal('toggle');
                    }
                });

        });
    });

    $(function () {
        $(document).ready(function () {
            var date = new Date();
            var currentMonth = date.getMonth();
            var currentDate = date.getDate();
            var currentYear = date.getFullYear();
            $('#fecha_nacimiento').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: moment().format("DD-MM-YYYY")
            }).datepicker("setDate", new Date());
        });
    });
</script>