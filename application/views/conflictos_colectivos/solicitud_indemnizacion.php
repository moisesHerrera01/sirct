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
        <?php if(isset($band_mantto)){ ?>
          cambiar_nuevo();
        <?php } ?>
        <?php if(tiene_permiso($segmentos=2,$permiso=1)){ ?>
        combo_delegado_tabla();
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

    function cerrar_combo_establecimiento() {
        var select2 = $('.select2-search__field').val();
        $("#nombre_establecimiento").val(select2);
        $("#establecimiento").select2('close');
    }

    function combo_delegado_tabla(seleccion){

      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/combo_delegado_tabla",
        type: "post",
        dataType: "html",
        data: {id : seleccion}
      })
      .done(function(res){
        $('#div_combo_delegado_tabla').html(res);
        <?php if(obtener_rango($segmentos=2, $permiso=1)>1){?>
                $("#nr_search").select2();
          <?php } ?>
        tablasolicitudes();
      });
    }

    function combo_establecimiento(seleccion){
      $.ajax({
        async: true,
        url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/combo_establecimiento",
        type: "post",
        dataType: "html",
        data: {id : seleccion}
      })
      .done(function(res){
        $('#div_combo_establecimiento').html(res);
        $("#establecimiento").select2({

          'language': {
            noResults: function () {
              return '<div align="right"><a href="javascript:;" data-toggle="modal" data-target="#modal_establecimiento" title="Agregar nuevos establecimientos" class="btn btn-success2" onClick="cerrar_combo_establecimiento()"><span class="mdi mdi-plus"></span>Agregar nuevo establecimiento</a></div>';
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

    function combo_motivos(seleccion){

      $.ajax({
        url: "<?php echo site_url(); ?>/conflictos_colectivos/solicitud_indemnizacion/combo_motivo_solicitud",
        type: "post",
        dataType: "html",
        data: {id : seleccion}
      })
      .done(function(res){
        $('#div_combo_motivos').html(res);
        $("#motivo").select2();
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
        $("#cnt_tabla_solicitantes").remove('.card')
        $("#modal_delegado").modal('hide');
        $("#modal_estado").modal('hide');
        $('#title_paso3').show();
        $('#btn_volver').show();
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
        xmlhttpB.open("GET", "<?php echo site_url(); ?>/conflictos_colectivos/solicitud_indemnizacion/tabla_solicitudes?nr=" +
            nr_empleado + "&tipo=" + estado_pestana, true);
        xmlhttpB.send();
    }

    function tabla_solicitantes(a = 0) {
        open_form(3);
        var id_expediente = $("#id_expediente").val();
        if(window.XMLHttpRequest){ xmlhttpB=new XMLHttpRequest();
        }else{ xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB"); }
        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                document.getElementById("cnt_tabla_solicitantes").innerHTML=xmlhttpB.responseText;
                $('[data-toggle="tooltip"]').tooltip();
                $('#myTable').DataTable();
            }
        }
        xmlhttpB.open(
            "GET",
            "<?php echo site_url(); ?>/conflictos_colectivos/detalle_solicitante/tabla_solicitante?expediente="+id_expediente+"&resultado="+a,
            true);
        xmlhttpB.send();

        $.ajax({
            url: "<?php echo site_url(); ?>/conflictos_colectivos/solicitud_indemnizacion/modal_solicitantes",
            type: "POST",
            data: {
                id: id_expediente
            }
        })
        .done(function (res) {
            $('#cnt_modal_acciones').html(res);
            $('.select2').select2();
        });

    }

    function alertFunc() {
        $('[data-toggle="tooltip"]').tooltip()
    }

    function cambiar_nuevo() {
        /*Inicio Expediente*/
        $("#fecha_conflicto").val('');
        $("#nombre_persona").val('');
        $("#apellido_persona").val('');
        $("#cago_persona").val('');
        $("#id_persona").val('');
        $("#id_expediente2").val('');
        /*Fin Expediente*/

        /*Inicio establecimiento*/
        combo_actividad_economica();
        combo_municipio();
        combo_motivos();
        $("#id_expediente").val('');
        /*Fin establecimiento*/

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

        /*Inicio Solicitado */
        $("#id_expediente").val('');
        combo_delegado('');
        combo_establecimiento('');
        /*Fin Solicitado */

    }

    function cambiar_editar(id_expediente, bandera) {
        $("#id_persona").val(id_expediente);

        if (bandera == "edit") {

            $.ajax({
                    url: "<?php echo site_url(); ?>/conflictos_colectivos/solicitud_indemnizacion/obtener_expediente_json",
                    type: "POST",
                    data: {
                        id: id_expediente
                    }
                })
                .done(function (res) {
                    result = JSON.parse(res)[0];

                    var fecha = new Date(result.fechaconflicto_personaci);

                    /*Inicio Expediente*/
                    $("#fecha_conflicto").val( `${fecha.getDate()}-${fecha.getMonth() + 1}-${fecha.getFullYear()}` );
                    $("#nombre_persona").val(result.nombre_personaci);
                    $("#apellido_persona").val(result.apellido_personaci);
                    $("#cago_persona").val(result.funciones_personaci);
                    $("#id_persona").val(result.id_personaci);
                    $("#id_expediente2").val(id_expediente);
                    /*Fin Expediente*/

                    /*Inicio establecimiento*/
                    combo_actividad_economica();
                    combo_municipio();
                    combo_motivos(result.causa_expedienteci);
                    $("#id_expediente").val(id_expediente);
                    /*Fin establecimiento*/

                    /*Inicio Solicitado */
                    $("#id_expediente").val(id_expediente);
                    combo_delegado(result.id_personal);
                    combo_establecimiento(result.id_empresaci);
                    /*Fin Solicitado */

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

    function visualizar(id_expediente) {
        $.ajax({
            url: "<?php echo site_url(); ?>/conflictos_colectivos/solicitud_indemnizacion/ver_expediente",
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

            tabla_acta(id_expediente);

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
                },
                success: function () {
                    swal({
                        title: "¡Registro exitoso!",
                        type: "success",
                        showConfirmButton: true
                    });
                    tabla_acta(id_expediente);
                }
            });
        });
    }

    function audiencias(id_expedienteci,id_empresa,codigo) {

        var codigo = codigo || false;
        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/audiencias/programar_audiencias",
            type: "post",
            dataType: "html",
            data: {id : id_expedienteci}
        }).done(function(res){
            $('#cnt_actions').html(res);
            $("#cnt_actions").show(0);
            $("#cnt_tabla").hide(0);
            $("#cnt_tabla_solicitudes").hide(0);
            $("#cnt_form_main").hide(0);
            combo_defensores();
            combo_representante_empresa(null, id_empresa);
            combo_establecimiento(id_empresa);
            combo_delega2();
            if (codigo) {
                $("#paso4").show();
                $("#div_finalizar").show(0);
            }
            tabla_audiencias(id_expedienteci);
        });
    }

    function tabla_audiencias(id_expedienteci){
        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttpB=new XMLHttpRequest();
        }else{// code for IE6, IE5
            xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
        }
        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                document.getElementById("cnt_tabla_audiencias").innerHTML=xmlhttpB.responseText;
                $('[data-toggle="tooltip"]').tooltip();
                $('#myTable2').DataTable();
            }
        }
        xmlhttpB.open("GET","<?php echo site_url(); ?>/resolucion_conflictos/audiencias/tabla_audiencias?id_expedienteci="+id_expedienteci,true);
        xmlhttpB.send();
    }

    function abrir_audiencia_paso() {
        audiencias( $("#id_expediente").val(), $("#establecimiento").val(), true );
    }

    function modal_delegado(id_expedienteci, id_personal) {
        $("#id_expedienteci_copia").val(id_expedienteci);
        $("#id_personal_copia").val(id_personal).trigger('change.select2');
        $("#modal_delegado").modal("show");
    }

    function cambiar_delegado() {
        var id_expedienteci = $("#id_expedienteci_copia").val();
        var id_personal = $("#id_personal_copia").val();
        $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/cambiar_delegado",
        type: "post",
        dataType: "html",
        data: {
            id_expedienteci: id_expedienteci,
            id_personal: id_personal,
        }
        })
        .done(function (res) {
        if(res == "exito"){
            cerrar_mantenimiento()
            tablasolicitudes();
            swal({ title: "¡Delegado modificado exitosamente!", type: "success", showConfirmButton: true });
        }else{
            swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
        }
        });
    }

    function modal_estado(id_expedienteci, id_estadosci) {
        $("#id_expedienteci_copia").val(id_expedienteci);
        $("#id_estado_copia").val(id_estadosci).trigger('change.select2');
        $("#modal_estado").modal("show");
    }

    function cambiar_estado() {
        var id_expedienteci = $("#id_expedienteci_copia").val();
        var id_estadosci = $("#id_estado_copia").val();
        $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/cambiar_estado",
        type: "post",
        dataType: "html",
        data: {
            id_expedienteci: id_expedienteci,
            id_estadosci: id_estadosci,
        }
        })
        .done(function (res) {
        if(res == "exito"){
            cerrar_mantenimiento()
            tablasolicitudes();
            swal({ title: "¡Estado modificado exitosamente!", type: "success", showConfirmButton: true });
        }else{
            swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
        }
        });
    }

    function inhabilitar(id_expedienteci) {
        swal({
            title: "Inhabilitar Expediente",
            text: "Motivo de Inhabilitar Expediente: *",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            inputPlaceholder: "Motivo para inhabilitar"
        }, function (inputValue) {
            if (inputValue === false) return false;
            if (inputValue === "") {
                swal.showInputError("Se necesita un motivo para inhabilitar.");
                return false
            }
            $.ajax({
                url: "<?php echo site_url(); ?>/conflictos_colectivos/solicitud_indemnizacion/gestionar_inhabilitar_expediente",
                type: "post",
                dataType: "html",
                data: {
                    id_exp: id_expedienteci,
                    mov_inhabilitar: inputValue
                }
            })
            .done(function (res) {
                if (res == "exito") {
                    tablasolicitudes();
                    swal({
                        title: "¡Expediente inhabilitado exitosamente!",
                        type: "success",
                        showConfirmButton: true
                    });
                } else {
                    swal({
                        title: "¡Ups! Error",
                        text: "Intentalo nuevamente.",
                        type: "error",
                        showConfirmButton: true
                    });
                }
            });
        });
    }

    function habilitar(id_expedienteci) {
        swal({
            title: "Confirmar Habilitación",
            text: "¿Está seguro que desea habilitar el expediente?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-success2",
            confirmButtonText: "Si",
            closeOnConfirm: false
        },
        function () {
            $.ajax({
                    url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/gestionar_habilitar_expediente",
                    type: "post",
                    dataType: "html",
                    data: {
                        id_exp: id_expedienteci,
                    }
                })
                .done(function (res) {
                    if (res == "exito") {
                        tablasolicitudes();
                        swal({
                            title: "¡Expediente habilitado exitosamente!",
                            type: "success",
                            showConfirmButton: true
                        });
                    } else {
                        swal({
                            title: "¡Ups! Error",
                            text: "Intentalo nuevamente.",
                            type: "error",
                            showConfirmButton: true
                        });
                    }
                });
        });
    }

    function gestionar_solicitantes(id_expediente) {
        $("#cnt_tabla").hide(0);
        $("#cnt_form_main").show(0);
        $('#title_paso3').hide();
        $('#btn_volver').hide();
        $('#id_expediente3').val(id_expediente);
        $('#id_expediente').val(id_expediente);
        tabla_solicitantes( 1 );
    }

    function pagos(id_persona) {
        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/pagos/programar_pagos_indemnizacion",
            type: "post",
            dataType: "html",
            data: {id : id_persona}
        })
        .done(function(res){
            console.log(res)
            $('#cnt_actions').html(res);
            $("#cnt_actions").show(0);
            $("#cnt_tabla").hide(0);
            $("#cnt_tabla_solicitudes").hide(0);
            $("#cnt_form_main").hide(0);
            tabla_pagos(id_persona);
        });
    }

    function tabla_pagos(id_persona){
        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttpB=new XMLHttpRequest();
        }else{// code for IE6, IE5
            xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
        }
        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                document.getElementById("cnt_tabla_pagos").innerHTML=xmlhttpB.responseText;
                $('[data-toggle="tooltip"]').tooltip();
                $('#myTable3').DataTable();
            }
        }
        xmlhttpB.open("GET","<?php echo site_url(); ?>/resolucion_conflictos/pagos/tabla_pagos_indemnizacion?id_persona="+id_persona,true);
        xmlhttpB.send();
    }

    function generar_acta(id_expedienteci) {
        // swal({
        //     title: "Información Adicional para Generar Acta",
        //     text: " Especifique el período que se detalla en las correspondientes hojas de calculos que se agregan a las presentes diligencias: *",
        //     type: "input",
        //     showCancelButton: true,
        //     closeOnConfirm: false,
        //     inputPlaceholder: "Agregar información adicional para generar acta"
        // },
        // function (inputValue) {
            // if (inputValue === false) return false;
            // if (inputValue === "") {
            //     swal.showInputError("Se necesita información adicional para generar el acta.");
            //     return false
            // }
            location.href="<?=base_url('index.php/conflictos_colectivos/acta_colectivos/generar_acta_indemnizacion/')?>" + id_expedienteci/* + "/" + inputValue*/;

            swal({
                title: "¡Acta generada exitosmente!",
                type: "success",
                showConfirmButton: true
            });
        // });
    }

    function combo_representante_empresa(seleccion,id_emp){
        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/establecimiento/combo_representante_empresa?id_empresaci="+id_emp,
            type: "post",
            dataType: "html",
            data: {id : seleccion}
        })
        .done(function(res){
            $.when($('#div_combo_representante_empresa').html(res)).then(function( data, textStatus, jqXHR ) {
                $("#representante_empresa").select2({

                    'language': {
                        noResults: function () {
                            return '<div align="right"><a href="javascript:;" data-toggle="modal" title="Agregar nuevo representante" class="btn btn-success2" onClick="cerrar_combo_representante()"><span class="mdi mdi-plus"></span>Agregar nuevo representante</a></div>';
                        }
                    }, 'escapeMarkup': function (markup) { return markup; }
                });
            });
        });
    }

    function combo_defensores(seleccion){
        $.ajax({
            async: true,
            url: "<?php echo site_url(); ?>/resolucion_conflictos/representante_persona/combo_defensores",
            type: "post",
            dataType: "html",
            data: {id : seleccion}
        })
        .done(function(res){
            $.when($('#div_combo_defensores').html(res) ).then(function( data, textStatus, jqXHR ) {
                $("#defensor").select2({

                    'language': {
                        noResults: function () {
                            return '<div align="right"><a href="javascript:;" data-toggle="modal" data-target="#modal_defensores" title="Agregar nuevo defensor" class="btn btn-success2" onClick="cerrar_combo_defensores()"><span class="mdi mdi-plus"></span>Agregar nuevo defensor</a></div>';
                        }
                    }, 'escapeMarkup': function (markup) { return markup; }
                });
            });
        });
    }

    function combo_delega2(seleccion){

        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/combo_delega2",
            type: "post",
            dataType: "html",
            data: {id : seleccion}
        }) .done(function(res){
            $('#div_combo_delegado2').html(res);
            $("#delegado").select2();
            // $('#dui_representante').inputmask('99999999-9');
        });
    }

    function cerrar_combo_defensores() {
        $("#defensor").select2('close');
    }

    function cerrar_combo_representante() {

        $.ajax({
            url: "<?php echo site_url(); ?>/conflictos_colectivos/solicitud_indemnizacion/modal_representante",
            type: "post",
            dataType: "html"
        }) .done(function(res){
            $('#cnt_modal_acciones').html(res);
            combo_profesiones();
            combo_municipio2();
            combo_estados_civiles();
            $('#modal_representante').modal('show');
        });

        $("#representante_empresa").select2('close');
    }

    function combo_resultados(seleccion){
        $.ajax({
            url: "<?php echo site_url(); ?>/conflictos_colectivos/solicitud_indemnizacion/combo_resultados",
            type: "post",
            dataType: "html",
            data: {id : seleccion}
        })
        .done(function(res){
            $('#div_combo_resultados').html(res);
            $("#resolucion").select2();
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
                <h3 class="text-themecolor m-b-0 m-t-0">Solicitudes por Indemnizaci&oacute;n</h3>
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
            <!-- Inicio del FORMULARIO INFORMACIÓN DEL SOLICITADO Y ASIGNACION -->
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
                                Información del solicitado y Asignaci&oacute;n de expediente
                            </h3>
                            <hr class="m-t-0 m-b-30">
                            <input type="hidden" id="band" name="band" value="save">
                            <input type="hidden" id="band1" name="band1" value="save">
                            <input type="hidden" id="estado" name="estado" value="1">
                            <input type="hidden" id="id_expediente" name="id_expediente" value="">

                            <span class="etiqueta">Información del solicitado</span>
                            <blockquote class="m-t-0">

                                <div class="row">
                                    <div class="col-lg-8 form-group <?php if($navegatorless){ echo " pull-left "; } ?>"
                                        id="div_combo_establecimiento"></div>
                                </div>

                            </blockquote>

                            <span class="etiqueta">Asignaci&oacute;n de expediente</span>
                            <blockquote class="m-t-0">

                                <div class="row">
                                    <div class="col-lg-8 form-group <?php if($navegatorless){ echo " pull-left "; } ?>"
                                        id="div_combo_delegado"></div>
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
                        <!-- Fin del FORMULARIO INFORMACIÓN DEL SOLICITADO Y ASIGNADO -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Inicio del FORMULARIO DETALLE EXPEDIENTE -->
                        <!-- ============================================================== -->
                        <?php echo form_open('', array('id' => 'formajax2', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                        <div id="cnt_form2" class="cnt_form" style="display: none;">
                            <h3 class="box-title" style="margin: 0px;">
                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso
                                    2</button>&emsp;
                                Detalle del Expediente
                            </h3>

                            <input type="hidden" id="band2" name="band2" value="save">
                            <input type="hidden" id="id_persona" name="id_persona" value="">
                            <input type="hidden" id="id_expediente2" name="id_expediente" value="">

                            <hr class="m-t-0 m-b-30">

                            <span class="etiqueta">Expediente</span>
                            <blockquote class="m-t-0">

                                <div class="row">

                                    <div class="form-group col-lg-4 <?php if($navegatorless){ echo " pull-left"; } ?>">
                                        <h5>Fecha del Conflicto: <span class="text-danger">*</span></h5>
                                        <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control"
                                            id="fecha_conflicto" name="fecha_conflicto" placeholder="dd/mm/yyyy" autocomplete="off">
                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group col-lg-4" style="height: 83px;">
                                        <h5>Nombre Persona del Conficto: </h5>
                                        <input type="text" id="nombre_persona" name="nombre_persona" class="form-control"
                                            placeholder="Nombre Persona del Conficto">
                                        <div class="help-block"></div>
                                    </div>

                                    <div class="form-group col-lg-4" style="height: 83px;">
                                        <h5>Apellido Persona del Conficto: </h5>
                                        <input type="text" id="apellido_persona" name="apellido_persona" class="form-control"
                                            placeholder="Apellido Persona del Conficto">
                                        <div class="help-block"></div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-8" style="height: 83px;">
                                        <h5>Cargo de la Persona:</h5>
                                        <textarea type="text" id="cago_persona" name="cago_persona" class="form-control"
                                            placeholder="Cargo de la Persona"></textarea>
                                        <div class="help-block"></div>
                                    </div>
                                    <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_motivos"></div>
                                </div>
                            </blockquote>

                            <div class="pull-left">
                                <button type="button" class="btn waves-effect waves-light btn-default" onclick="volver(1)"><i class="mdi mdi-chevron-left"></i> Volver</button>
                            </div>

                            <div align="right" id="btnadd2">
                                <button type="reset" class="btn waves-effect waves-light btn-success">
                                    <i class="mdi mdi-recycle"></i> Limpiar
                                </button>
                                <button type="submit" class="btn waves-effect waves-light btn-success2">Siguiente
                                    <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                            <div align="right" id="btnedit2" style="display: none;">
                                <button type="reset" class="btn waves-effect waves-light btn-success">
                                    <i class="mdi mdi-recycle"></i> Limpiar</button>
                                <button type="submit" class="btn waves-effect waves-light btn-info">Siguiente
                                    <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <!-- ============================================================== -->
                        <!-- Fin del FORMULARIO DETALLE EXPEDIENTE -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- Inicio del FORMULARIO DETALLE SOLICITANTES -->
                        <!-- ============================================================== -->
                        <div id="cnt_form3" class="cnt_form" style="display: none;">
                            <h3 class="box-title" style="margin: 0px;">
                                <button type="button" id="title_paso3" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 3</button>&emsp;
                                Gestionar Solicitantes:
                            </h3><hr class="m-t-0 m-b-30">

                            <div id="cnt_tabla_solicitantes"></div>

                            <div class="pull-left">
                                <button id="btn_volver" type="button" class="btn waves-effect waves-light btn-default" onclick="volver(2)"><i class="mdi mdi-chevron-left"></i> Volver</button>
                            </div>
                            <div align="right" id="btnadd2" class="pull-right">
                                <button type="button" onclick="abrir_audiencia_paso();" class="btn waves-effect waves-light btn-success2">Siguiente <i class="mdi mdi-chevron-right"></i></button>
                            </div>
                            <div align="right" id="btnedit2" style="display: none;" class="pull-right">
                                <button type="button" onclick="abrir_audiencia_paso();" class="btn waves-effect waves-light btn-info">Siguiente <i class="mdi mdi-chevron-right"></i></button>
                            </div>

                        </div>
                        <!-- ============================================================== -->
                        <!-- Fin del FORMULARIO DETALLE SOLICITANTES -->
                        <!-- ============================================================== -->

                    </div>
                </div>
            </div>

            <div class="col-lg-12" id="cnt_actions" style="display:none;"></div>
            <div class="col-lg-1"></div>
            <div class="col-lg-12" id="cnt_tabla">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-b-0">Listado de Solicitudes</h4>
                    </div>
                    <div class="card-body b-t" style="padding-top: 7px;">
                        <div>
                          <?php if (obtener_rango($segmentos=2, $permiso=1) > 1) { ?>
                            <div class="pull-left">
                                <div class="form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_delegado_tabla" style="width: 400px;"></div>
                            </div>
                            <?php }else{ ?>
                              <input type="hidden" id="nr_search" name="nr_search" value="<?= $this->session->userdata('nr')?>">
                            <?php } ?>
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
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <?php echo form_open('', array('id' => 'formajax3', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
          <input type="hidden" id="band3" name="band3" value="save">
          <!-- <input type="hidden" id="id_representante" name="id_representante" value=""> -->
          <input type="hidden" id="id_empresaci" name="id_empresaci" value="">
            <div class="modal-header">
                <h4 class="modal-title">Gestión de establecimiento</h4>
            </div>
            <div class="modal-body" id="">

              <div class="row">
                <div class="form-group col-lg-6 col-sm-6 <?php if($navegatorless){ echo " pull-left"; } ?>">
                    <h5>Tipo: <span class="text-danger">*</span></h5>
                    <div class="controls">
                      <select id="tipo_establecimiento" name="tipo_establecimiento" class="custom-select col-4" onchange="ocultar_pn()" required>
                        <option value="">[Seleccione]</option>
                        <option value="1">Persona natural</option>
                        <option value="2">Persona jurídica</option>
                      </select>
                    </div>
                </div>

                <div class="form-group col-lg-16 col-sm-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                    <h5>Nombre del establecimiento:</h5>
                    <div class="controls">
                        <input type="text" placeholder="Nombre" id="nombre_establecimiento" name="nombre_establecimiento" class="form-control">
                    </div>
                </div>
              </div>

                <div class="row" id="ocultar_pn">
                  <div class="form-group col-lg-6 col-sm-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Razon social del establecimiento:</h5>
                      <div class="controls">
                          <input type="text" placeholder="Nombre" id="razon_social" name="razon_social" class="form-control" required="">
                      </div>
                  </div>

                  <div class="form-group col-lg-6 col-sm-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Abreviatura del establecimiento: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" placeholder="Abreviatura" id="abre_establecimiento" name="abre_establecimiento" class="form-control" required>
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Direcci&oacute;n: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <textarea type="text" id="dir_establecimiento" name="dir_establecimiento" class="form-control" required=""></textarea>
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_municipio"></div>

                  <div class="form-group col-lg-6 col-sm-6 <?php if($navegatorless){ echo " pull-left"; } ?>">
                      <h5>Telefono: </h5>
                      <div class="controls">
                          <input type="text" placeholder="Telefono" id="telefono_establecimiento" name="telefono_establecimiento" class="form-control" data-mask="9999-9999" required>
                          <div class="help-block"></div>
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_actividad_economica"></div>
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

<!--INICIO MODAL DE DELEGADO -->
<div class="modal fade" id="modal_delegado" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cambiar asignación de delegado:</h4>
            </div>

            <div class="modal-body" id="">
                <input type="hidden" id="id_expedienteci_copia" name="id_expedienteci_copia" value="">
                <div class="row">
                    <div class="form-group col-lg-12 col-sm-12">
                        <div class="form-group">
                            <h5>Delegado/a:<span class="text-danger">*</h5>
                            <select id="id_personal_copia" name="id_personal_copia" class="select2" style="width: 100%"
                                required="">
                                <option value="">[Todos los empleados]</option>
                                <?php
                            $otro_empleado = $this->db->query("SELECT e.id_empleado, e.nr, UPPER(CONCAT_WS(' ', e.primer_nombre,
                                                                      e.segundo_nombre, e.tercer_nombre, e.primer_apellido,
                                                                      e.segundo_apellido, e.apellido_casada)) AS nombre_completo
                                                              FROM sir_empleado AS e WHERE e.id_estado = '00001'
                                                              ORDER BY e.primer_nombre, e.segundo_nombre, e.tercer_nombre,
                                                              e.primer_apellido, e.segundo_apellido, e.apellido_casada");
                            if($otro_empleado->num_rows() > 0){
                                foreach ($otro_empleado->result() as $fila) {
                                    echo '<option class="m-l-50" value="'.$fila->id_empleado.'">'.preg_replace ('/[ ]+/', ' ', $fila->nombre_completo.' - '.$fila->nr).'</option>';
                                }
                            }
                        ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div align="right">
                    <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" onclick="cambiar_delegado();" class="btn waves-effect waves-light btn-success2">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--FIN MODAL DE DELEGADO -->

<!--INICIO MODAL DE ESTADO -->
<div class="modal fade" id="modal_estado" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cambiar estado del expediente:</h4>
            </div>

            <div class="modal-body" id="">
                <input type="hidden" id="id_expedienteci_copia" name="id_expedienteci_copia" value="">
                <div class="row">
                    <div class="form-group col-lg-12 col-sm-12">
                        <div class="form-group">
                            <h5>Estado:<span class="text-danger">*</h5>
                            <select id="id_estado_copia" name="id_estado_copia" class="select2" style="width: 100%"
                                required="">
                                <option value="">[Todos los estados]</option>
                                <?php
                            $otro_estado = $this->db->query("SELECT e.id_estadosci, e.nombre_estadosci FROM sct_estadosci AS e ");
                            if($otro_estado->num_rows() > 0){
                                foreach ($otro_estado->result() as $fila) {
                                    echo '<option class="m-l-50" value="'.$fila->id_estadosci.'">'.preg_replace ('/[ ]+/', ' ', $fila->nombre_estadosci).'</option>';
                                }
                            }
                        ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div align="right">
                    <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" onclick="cambiar_estado();" class="btn waves-effect waves-light btn-success2">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--FIN MODAL DE ESTADO -->

<!--INICIA MODAL DE PROCURADOR -->
<div class="modal fade" id="modal_defensores" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <?php echo form_open('', array('id' => 'formajax8', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
          <input type="hidden" id="band6" name="band6" value="save">
          <input type="hidden" id="id_procuradorci" name="id_procuradorci" value="">
          <!-- <input type="hidden" id="id_representante" name="id_representante" value=""> -->
            <div class="modal-header">
                <h4 class="modal-title">Defensores legales</h4>
            </div>
            <div class="modal-body" id="">
              <div class="row">
                <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                    <h5>Nombres del representante: <span class="text-danger">*</span></h5>
                    <div class="controls">
                        <input type="text" id="nombre_representante_persona" name="nombre_representante_persona" class="form-control" placeholder="Nombres del representante" required>
                    </div>
                </div>

                <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                    <h5>Apellidos del representante: <span class="text-danger">*</span></h5>
                    <div class="controls">
                        <input type="text" id="apellido_representante_persona" name="apellido_representante_persona" class="form-control" placeholder="Apellidos del representante" required>
                    </div>
                </div>
              </div>

              <div class="row">
                <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                    <h5>DUI de representante: <span class="text-danger">*</span></h5>
                    <div class="controls">
                        <input data-mask="99999999-9" type="text" id="dui_representante_persona" name="dui_representante_persona" class="form-control" placeholder="Dui del representante" required>
                    </div>
                </div>

                <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                    <h5>Tel&eacute;fono representante: <span class="text-danger">*</span></h5>
                    <div class="controls">
                        <input data-mask="9999-9999" type="text" id="telefono_representante_persona" name="telefono_representante_persona" class="form-control" placeholder="telefono del representante" required>
                    </div>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_tipo_representante"></div>

                <div class="form-group col-lg-8 <?php if($navegatorless){ echo "pull-left"; } ?>">
                    <h5>Acreditaci&oacute;n: <span class="text-danger">*</span></h5>
                    <div class="controls">
                        <textarea type="text" id="acreditacion_representante_persona" name="acreditacion_representante_persona" class="form-control" required></textarea>
                    </div>
                </div>
              </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-white" data-dismiss="modal">Cerrar</button>
                <button type="submit" id="submit4" class="btn btn-info waves-effect text-white">Aceptar</button>
            </div>
          <?php echo form_close(); ?>
    </div>
  </div>
</div>
<!--FIN MODAL DE PROCURADOR -->

<div id="cnt_modal_acciones"></div>

<script>

function ocultar_pn(){
  var value = $("#tipo_establecimiento").val();
  if (value==1) {
    $("#razon_social").removeAttr("required");
    $("#abre_establecimiento").removeAttr("required");
    $('#ocultar_pn').hide(500);
  }else {
     $('#ocultar_pn').show(500);
     $("#razon_social").attr("required",'required');
     $("#abre_establecimiento").attr("required",'required');
  }
}
    $(function () {
        $("#formajax").on("submit", function (e) {
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formajax"));

            $.ajax({
                url: "<?php echo site_url(); ?>/conflictos_colectivos/solicitud_indemnizacion/gestionar_solicitud",
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
                    $("#id_expediente").val(res);
                    $("#id_expediente2").val(res);
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
                    url: "<?php echo site_url(); ?>/conflictos_colectivos/solicitud_indemnizacion/gestionar_solicitud_persona",
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
                        tabla_solicitantes();
                        $("#id_persona").val(res);
                        $("#band1").val($("#band").val());
                        $("#band2").val($("#band").val());
                    }
                });

        });
    });

    $(function(){
        $("#formajax3").on("submit", function(e){
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
            .done(function(res){
              console.log(res)
              res = res.split(",");
                if(res[0] == "exito"){
                    if($("#band3").val() == "save"){
                        //$("#id_empresa").val(res[1])
                        $("#modal_establecimiento").modal('hide');
                        $.toast({ heading: 'Registro exitoso', text: 'Registro de establecimiento exitoso', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
                        combo_establecimiento(res[1]);
                    }else if($("#band3").val() == "edit"){
                        swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                        //tabla_representantes();
                    }
                }else{
                    swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
                }
            });

        });
    });

    $(function () {
        $(document).ready(function () {
            $('#fecha_conflicto').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: moment().format("DD-MM-YYYY")
            }).datepicker("setDate", new Date());
        });
    });
</script>
