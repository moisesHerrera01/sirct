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
        <?php if(isset($tipo_solicitud)){ ?>
            $("#motivo_expedienteci").val('<?=$tipo_solicitud?>');
            <?php if($band_mantto == "save"){ ?>
                cambiar_nuevo();
            <?php }else{ ?>
                cambiar_editar('<?=$id_personaci?>','edit');
            <?php } ?>
        <?php }else{ ?>
            $("#motivo_expedienteci").val('');
        <?php } ?>

        <?php if(tiene_permiso($segmentos=2,$permiso=1)){ ?>
        combo_delegado_tabla();
        <?php }else{ ?>
        $("#cnt_tabla").html("Usted no tiene permiso para este formulario.");
        <?php } ?>
    }

    // function nuevo_reg_post(){
    //     $("#cnt_tabla").hide(0);
    //     $("#cnt_form_main").show(0);
    // }

    function convert_lim_text(lim) {
        var tlim = "-" + lim + "d";
        return tlim;
    }

    var estado_pestana = "";

    function cambiar_pestana(tipo) {
        estado_pestana = tipo;
        tablasolicitudes();
    }

    function nav(value) {
      var id_expedienteci = $("#id_expedienteci_copia2").val();
      var id_audiencia = $("#id_audiencia").val();
      if (value != "") { location.href = value+id_expedienteci+'/'+id_audiencia; }
      swal({ title: "¡Acta generada éxitosamente!", type: "success", showConfirmButton: true });
      $("#modal_actas_tipo").modal("hide");
      tabla_audiencias($("#id_expedienteci_copia2").val());
      //cerrar_mantenimiento();
    }

    function combo_resultados(seleccion){
      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/retiro_voluntario/combo_resultados",
        type: "post",
        dataType: "html",
        data: {id : seleccion}
      })
      .done(function(res){
        $('#div_combo_resultados').html(res);
        $("#resolucion").select2();
      });
    }

    function modal_actas_tipo(id_expedienteci, cuenta_audiencias,tipo_conciliacion,posee_trabajador,estado,id_audiencia,resultado,id_representaci,numero_audiencia) {
          $("#solicitud_pn_pj").hide();
          $("#pf_st").hide();
          $("#multa").hide();
          $("#inasistencia").hide();
          $("#rv_ambas_partes").hide();
          $("#rv_solo_trabajador").hide();
          $("#desistimiento").hide();
          $("#rv_ncnp").hide()

        if (cuenta_audiencias>1) {
          $("#solicitud_pn_pj").show();
          $("#esquela").show();
        }

        if (estado=="2") {
          if (resultado=="1" || resultado=="2" || resultado=="7") {
            $("#pf_st").show();
          }else if (resultado=="5") {
            $("#multa").show();
          }else if (resultado=="3") {
            $("#inasistencia").show();
          }else if (resultado=="4"){
            $("#desistimiento").show();
          }else if (resultado=="6" || resultado=="8") {
            if (id_representaci>0) {
              $("#rv_ambas_partes").show();
            }else if(posee_trabajador>0){
              $("#rv_solo_trabajador").show();
            }else {
              $("#rv_ncnp").show();
            }
          }
        }
        $("#id_expedienteci_copia2").val(id_expedienteci);
        $("#id_audiencia").val(id_audiencia);
        $("#tipo_acta").val('').trigger('');
        $("#modal_actas_tipo").modal("show");
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

    function tabla_delegados(id_expedienteci){
        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttpB=new XMLHttpRequest();
        }else{// code for IE6, IE5
            xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
        }
        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                document.getElementById("cnt_tabla_delegados").innerHTML=xmlhttpB.responseText;
                //$('[data-toggle="tooltip"]').tooltip();
                $('#myTable').DataTable();
            }
        }
        xmlhttpB.open("GET","<?php echo site_url(); ?>/resolucion_conflictos/expediente/tabla_delegados?id="+id_expedienteci,true);
        xmlhttpB.send();
    }

    function modal_bitacora_delegados(id_expedienteci) {
      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/bitacora_delegados",
        type: "post",
        dataType: "html",
        data: {id : id_expedienteci}
      })
      .done(function(res){
        $('#cnt_modal_bitacora_delegado').html(res);
        $('#modal_bitacora_delegados').modal('show');
        tabla_delegados(id_expedienteci);
      });
    }

    function combo_cambiar_delegado(seleccion){

      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/combo_cambiar_delegado",
        type: "post",
        dataType: "html",
        data: {id : seleccion}
      })
      .done(function(res){
        $('#div_cambiar_delegado').html(res);
        $("#id_personal_copia").select2();
      });
    }

    function combo_establecimiento(seleccion){
        $.ajax({
          async: true,
          url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica/combo_establecimiento",
          type: "post",
          dataType: "json",
          data: {id : seleccion}
        })
        .done(function(res){
            $.when($('#div_combo_establecimiento').html(res.modal) ).then(function( data, textStatus, jqXHR ) {
                $("#establecimiento").select2({
                  ajax: {
                    url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica/combo_empresa",
                    dataType: 'json',
                    data: function (params) {
                      return {
                        s: params.term,
                        page: params.page
                      };
                    },
                    processResults: function (data, params) {
                      if ((data.items)==false) {
                        data.items = [{
                          id:0,
                          text:function () {
                                  return '<div align="right"><a href="javascript:;" title="Agregar nuevo registro" class="btn btn-success2"><span class="mdi mdi-plus"></span>Agregar nuevo registro</a></div>';
                              }
                        }];
                      }
                      params.page = params.page || 1;
                      return {
                        results: data.items,
                        pagination: {
                          more: (params.page * 30) < data.total_count
                        }
                      };
                    },
                     cache: true
                  },
                  'language': 'es',
                  'escapeMarkup': function (markup) { return markup; }
                });
                $('#establecimiento').on('select2:select', function (e) {
                  var data = e.params.data;
                  if (data.id == 0) {
                    cerrar_combo_establecimiento();
                    $('#modal_establecimiento').modal('show');
                    tabla_representantes();
                  } else {
                    tabla_representantes();
                    verificar_empresa_completa(data.id);
                  }
                });
                if (seleccion != '') {
                  var newOption = new Option(res.establecimiento.nombre_empresa,res.establecimiento.id_empresa, true, true);
                  $('#establecimiento').append(newOption).trigger('change');
                  tabla_representantes()
                }
            });
        });
    }

    function cerrar_combo_establecimiento() {
        //var select2 = $('.select2-search__field').val();
        //$("#nombre_establecimiento").val(select2);

        $("#id_empresaci").val('');
        $("#tipo_establecimiento").val('');
        $("#razon_social").val('');
        $("#nombre_establecimiento").val('');
        $("#abre_establecimiento").val('');
        $("#dir_establecimiento").val('');
        $("#telefono_establecimiento").val('');
        $("#municipio2").val('').trigger('change.select2');
        $("#act_economica").val('').trigger('change.select2');
        $("#band3").val('save');
        $("#alert_empresa").html('');
        $("#establecimiento").select2('close');
    }

    function limpiar_modal_empresa(){
        $("#modal_establecimiento").modal('hide');
        $("#establecimiento").val('').trigger('change.select2');
    }

    function verificar_empresa_completa(id_empresa) {
        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/verificar_empresa_completa",
            type: "POST",
            data: {
                id_empresa: id_empresa
            }
        })
        .done(function (res) {
            result = JSON.parse(res);
            $("#id_empresaci").val(result.id_empresa);
            $("#tipo_establecimiento").val(result.tiposolicitud_empresa);
            $("#razon_social").val(result.razon_social);
            $("#nombre_establecimiento").val(result.nombre_empresa);
            $("#abre_establecimiento").val(result.abreviatura_empresa);
            $("#dir_establecimiento").val(result.direccion_empresa);
            $("#telefono_establecimiento").val(result.telefono_empresa);
            $("#municipio2").val(result.id_municipio).trigger('change.select2');
            $("#act_economica").val(result.id_catalogociiu).trigger('change.select2');
            $("#band3").val('edit');

            $("#modal_establecimiento").modal('show');
            $("#alert_empresa").html('<div class="alert alert-danger"><i class="mdi mdi-alert"></i> <b>Por favor, verifique y/o complete la información de la parte empleadora</b><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>');
        });
    }

    function ocultar_pn(){
      var value = $("#tipo_establecimiento").val();
      if (value==1) {
        //$("#razon_social").removeAttr("required");
        $("#abre_establecimiento").removeAttr("required");
        $('#ocultar_pn').hide(500);
      }else {
         $('#ocultar_pn').show(500);
         //$("#razon_social").attr("required",'required');
         $("#abre_establecimiento").attr("required",'required');
      }
    }

    function ocultar_campos_rep(){
      var value = $("#tipo_representante").val();
      $('#ocultar_campos').show(500);
      $("#rep_tipo_doc").attr("required",'required');
      $("#f_nacimiento_representante").attr("required",'required');
      $("#dui_representante").attr("required",'required');
      $("#acreditacion_representante").attr("required",'required');
      $("#profesion").attr("required",'required');
      $("#municipio_representante").attr("required",'required');
      $("#estado_civil").attr("required",'required');
      if (value==1) {
        $('#ocultar_campos').hide(500);
        $("#rep_tipo_doc").removeAttr("required");
        $("#f_nacimiento_representante").removeAttr("required");
        $("#dui_representante").removeAttr("required");
        $("#acreditacion_representante").removeAttr("required");
        $("#profesion").removeAttr("required");
        $("#municipio_representante").removeAttr("required");
        $("#estado_civil").removeAttr("required");
      }else {
        $('#ocultar_campos').show(500);
        $("#rep_tipo_doc").attr("required",'required');
        $("#f_nacimiento_representante").attr("required",'required');
        $("#dui_representante").attr("required",'required');
        $("#acreditacion_representante").attr("required",'required');
        $("#profesion").attr("required",'required');
        $("#municipio_representante").attr("required",'required');
        $("#estado_civil").attr("required",'required');
      }
    }

    function combo_actividad_economica() {

        $.ajax({
                url: "<?php echo site_url(); ?>/resolucion_conflictos/establecimiento/combo_actividad_economica",
                type: "post",
                dataType: "html"
            })
            .done(function (res) {
                $('#div_combo_actividad_economica').html(res);
                $("#act_economica").select2();
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
                $("#municipio2").select2();
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

    function combo_nacionalidades(seleccion){

        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/combo_nacionalidades",
            type: "post",
            dataType: "html",
            data: {
                id: seleccion
            }
        })
        .done(function (res) {
            $('#div_combo_nacionalidad').html(res);
            $("#nacionalidad").select2();
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

    function seleccionar_representante(obj, id_representanteci){
        $("#id_representanteci").val(id_representanteci)
        $(obj).parent().addClass('table-active active');
        $(obj).parent().siblings('tr').removeClass('table-active active');
        var tds = $(obj).parent().children('td');

        var trs = $("#tabla_representante tbody tr");
        for (var i = 0; i < trs.length; i+=1) {
            var td = $(trs[i]).children('td');
            $(td[0]).html('');
        }


        $(tds[0]).html('<span class="round round-primary">R</span>');
    }

    function cerrar_mantenimiento() {
        $("#cnt_tabla").show(0);
        $("#cnt_tabla_solicitudes").show(0);
        $("#cnt_form_main").hide(0);
        $("#cnt_actions").hide(0);
        $("#cnt_actions").remove('.card');
        $("#modal_delegado").modal('hide');
        $("#modal_estado").modal('hide');
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
        $("#fecha_nacimiento").datepicker("setDate", '');
        $("#sexo").val('');
        $("#estudios").val('');
        $("#nacionalidad").val('');
        $("#discapacidad").val('');
        $("#fecha_preaviso").datepicker("setDate", '');
        $("#fecha_conflicto").datepicker("setDate", '');
        $("#descripcion_motivo").val('');
        $("#id_representanteci").val('');
        $("#causa_expedienteci").val('').trigger('change.select2');
        /*Fin Solicitante*/

        /*Inicio Expediente*/
        combo_delegado('');
        combo_actividad_economica();
        combo_municipio();
        combo_nacionalidades();
        $("#id_personal").val('');
        $("#establecimiento").val('');
        /*Fin expediente*/

        $("#band").val("save");
        $("#bandx").val("save");
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
        tabla_representantes();
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
                    $("#fecha_nacimiento").datepicker("setDate", moment(result.fnacimiento_personaci).format("DD-MM-YYYY"));
                    $("#sexo").val(result.sexo_personaci);
                    $("#estudios").val(result.estudios_personaci);
                    $("#discapacidad").val(result.discapacidad_personaci);
                    $("#fecha_preaviso").datepicker("setDate", moment(result.fechaconflicto_personaci).format("DD-MM-YYYY"));
                    $("#fecha_renuncia").datepicker("setDate", moment(result.fecha_renuncia).format("DD-MM-YYYY"));
                    $("#descripcion_motivo").val(result.descripmotivo_expedienteci);
                    $("#id_representanteci").val(result.id_representanteci);
                    $("#causa_expedienteci").val(result.causa_expedienteci).trigger('change.select2');

                    /*Inicio Expediente*/
                    combo_delegado(result.id_personal);
                    combo_actividad_economica(result.id_catalogociiu);
                    combo_municipio(result.id_municipio1);
                    combo_establecimiento(result.id_empresaci);
                    tabla_representantes();
                    combo_nacionalidades(result.nacionalidad_personaci);

                    /*Fin expediente*/
                    $("#band").val("edit");
                    $("#bandx").val("edit");
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

    function audiencias(id_empresaci, id_expedienteci, origen) {
      $("#id_empresaci").val(id_empresaci);
      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/audiencias/programar_audiencias",
        type: "post",
        dataType: "html",
        data: {id : id_expedienteci}
      })
      .done(function(res){
        //console.log(res)
        $('#cnt_actions').html(res);
        $("#cnt_actions").show(0);
        $("#cnt_tabla").hide(0);
        //$("#cnt_tabla_solicitudes").hide(0);
        $("#cnt_form_main").hide(0);
        combo_defensores();
        combo_representante_empresa();
        combo_delega2();
        if (origen==1) {
            $("#paso3").show(0);
            tabla_audiencias(id_expedienteci);
            $("#div_finalizar").show(0);
        }else {
          tabla_audiencias(id_expedienteci);
        }

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
                if ($("#myTable2 tbody tr").length>=1) {
                  $('#div_orden').hide(0);
                }
            }
        }
        xmlhttpB.open("GET","<?php echo site_url(); ?>/resolucion_conflictos/audiencias/tabla_audiencias?id_expedienteci="+id_expedienteci,true);
        xmlhttpB.send();
    }

    function modal_delegado(id_expedienteci, id_personal) {
        $("#id_expedienteci_copia").val(id_expedienteci);
        $("#id_personal_copia").val(id_personal).trigger('change.select2');
        $("#modal_delegado").modal("show");
        combo_cambiar_delegado(id_personal);
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
        if(res != "fracaso"){
            cerrar_mantenimiento()
            tablasolicitudes();
            swal({ title: "¡Persona delegada modificada exitosamente!", type: "success", showConfirmButton: true });
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
                url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/gestionar_inhabilitar_expediente",
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

    function combo_procuradores(seleccion){
      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/audiencias/combo_procuradores",
        type: "post",
        dataType: "html",
        data: {id : seleccion}
      })
      .done(function(res){
        $('#div_combo_procurador').html(res);
        $(".select2").select2();
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
                            return '<div align="right"><a href="javascript:;" data-toggle="modal" data-target="#modal_defensores" title="Agregar nuevo registro" class="btn btn-success2" onClick="cerrar_combo_defensores()"><span class="mdi mdi-plus"></span>Agregar nuevo registro</a></div>';
                        }
                    }, 'escapeMarkup': function (markup) { return markup; }
                });
                //tabla_representantes()
            });
        });
    }

    function combo_representante_empresa(seleccion){
      var id_emp = $("#id_empresaci").val();
      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/establecimiento/combo_representante_empresa?id_empresaci="+id_emp,
        type: "post",
        dataType: "html",
        data: {id : seleccion}
      })
      .done(function(res){
        $('#div_combo_representante_empresa').html(res);
        $("#representante_empresa").select2();
      });
    }

    function combo_delega2(seleccion){

      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/combo_delega2",
        type: "post",
        dataType: "html",
        data: {id : seleccion}
      })
      .done(function(res){
        $('#div_combo_delegado2').html(res);
        $("#delegado").select2();
      });
    }

    function cerrar_combo_defensores() {
        $("#defensor").select2('close');
        combo_tipo_representante();
    }

    function combo_tipo_representante(seleccion){
      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/combo_tipo_representante",
        type: "post",
        dataType: "html",
        data: {id : seleccion}
      })
      .done(function(res){
        $('#div_combo_tipo_representante').html(res);
        $("#tipo_representante_persona").select2();
      });
    }

    function resolucion(id_expedienteci) {
      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/resolucion_expediente",
        type: "post",
        dataType: "html",
        data: {id : id_expedienteci}
      })
      .done(function(res){
        $('#cnt_modal_acciones').html(res);
        $('#modal_resolucion').modal('show');
      });
    }

    function tabla_representantes(){
        var id_empresa = $("#establecimiento").val();
        var id_representanteci = $("#id_representanteci").val();

        $.ajax({
          url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/tabla_representantes",
          type: "get",
          dataType: "html",
          data: {id_empresa: id_empresa,id_representanteci : id_representanteci}
        })
        .done(function(res){
          $('#cnt_tabla_representantes').html(res);
          $('[data-toggle="tooltip"]').tooltip();
          $('#myTable2').DataTable();
        });
    }


    function validar_establecimiento(){
        var establecimiento = $("#establecimiento").val();
        var registros = $("#tabla_representante tbody tr.table-active");

        if(establecimiento == "" || registros.length == 0){
            if(establecimiento == ""){
                swal({ title: "Seleccione la parte empleadora", text: "No se ha seleccionado la parte empleadora.", type: "warning", showConfirmButton: true });
            }else{
                swal({ title: "Seleccione la persona representante", text: "Agregue o seleccione una persona representante de la lista.", type: "warning", showConfirmButton: true });
            }
        }else{
            open_form(3);
        }
    }

    function cambiar_nuevo2(){
        if($("#establecimiento").val() == ''){
            swal({ title: "Seleccione la parte empleadora", type: "warning", showConfirmButton: true });
        }else{
            $("#id_representante").val('');
            $("#nombres_representante").val('');
            $("#dui_representante").val('');
            $("#acreditacion_representante").val('');
            $("#tipo_representante").val('');
            $("#estado_representante").val('1');
            $("#f_nacimiento_representante").datepicker("setDate", '');
            combo_estados_civiles('');
            combo_profesiones('');
            combo_municipio2('');
            $("#band4").val('save');
            $("#modal_representante").modal('show');
        }
    }

    function combo_estados_civiles(seleccion){
      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/combo_estados_civiles",
        type: "post",
        dataType: "html",
        data: {id : seleccion}
      })
      .done(function(res){
        $('#div_combo_estados_civiles').html(res);
        $("#estado_civil").select2();
      });
    }

    function combo_profesiones(seleccion){
      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/combo_profesiones",
        type: "post",
        dataType: "html",
        data: {id : seleccion}
      })
      .done(function(res){
        $('#div_combo_profesiones').html(res);
        $("#profesion").select2();
      });
    }

    function combo_municipio2(seleccion){

      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/establecimiento/combo_municipio2",
        type: "post",
        dataType: "html",
        data: {id : seleccion}
      })
      .done(function(res){
        $('#div_combo_municipio2').html(res);
        $("#municipio_representante").select2();
      });

    }

    function cambiar_editar2(id_representante, dui_representante, nombres_representante, acreditacion_representante,
  tipo_representante, estado_representante,id_estado_civil,id_titulo_academico,id_municipio,f_nacimiento_representante,id_tipo_doc, band){
      $("#id_representante").val(id_representante);
      $("#dui_representante").val(dui_representante);
      $("#nombres_representante").val(nombres_representante);
      $("#acreditacion_representante").val(acreditacion_representante);
      $("#tipo_representante").val(tipo_representante);
      $("#estado_representante").val(estado_representante);
      $("#fecha_nacimiento").datepicker("setDate", moment(f_nacimiento_representante).format("DD-MM-YYYY"));
      combo_estados_civiles(id_estado_civil);
      combo_profesiones(id_titulo_academico);
      combo_municipio2(id_municipio);
      // alert(id_municipio)
      $("#band4").val(band);

      if(band == "edit"){
            $("#modal_representante").modal('show');
        }else{
            cambiar_eliminar3(estado_representante);
        }
    }

    function imprimir_ficha_pdf(id_expediente, id_empresa){
        var params = { 'id_expediente': id_expediente, 'id_empresa': id_empresa };
        var url = "<?php echo site_url(); ?>/resolucion_conflictos/expediente/imprimir_ficha_pdf"
        OpenWindowWithPost(url, params, "_BLANK")
    }

    function OpenWindowWithPost(url, params, target){
        var form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", url);
        form.setAttribute("target", target);
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


</script>
<input type="hidden" id="bandx" name="bandx">
<div class="page-wrapper">
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- TITULO de la página de sección -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="align-self-center" align="center">
                <h3 class="text-themecolor m-b-0 m-t-0">Notificación de renuncia voluntaria</h3>
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
                        <h4 class="card-title m-b-0 text-white">Listado de las personas solicitudes</h4>
                    </div>
                    <div class="card-body b-t">

                        <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                        <div id="cnt_form1" class="cnt_form">
                            <h3 class="box-title" style="margin: 0px;">
                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso
                                    1</button>&emsp;
                                Información de la persona solicitante
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
                                            id="fecha_nacimiento" name="fecha_nacimiento" placeholder="dd/mm/yyyy">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4" style="height: 83px;">
                                      <h5>Estudios realizados:</h5>
                                      <div class="controls">
                                        <select id="estudios" name="estudios" class="custom-select col-4" onchange="">
                                          <option value="">[Seleccione]</option>
                                          <option value="Sin estudio">Sin estudio</option>
                                          <option value="Educacion Básica">Educacion Básica</option>
                                          <option value="Bachillerato">Bachillerato</option>
                                          <option value="Universidad">Universidad</option>
                                        </select>
                                    </div>
                                  </div>

                                    <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_nacionalidad"></div>

                                    <div class="form-group col-lg-2" style="height: 83px;">
                                        <h5>Sexo:</h5>
                                        <input name="sexo" type="radio" id="masculino" checked="" value="M">
                                        <label for="masculino">Hombre</label>
                                        <input name="sexo" type="radio" id="femenino" value="F">
                                        <label for="femenino">Mujer</label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-8" style="height: 83px;">
                                        <h5>Dirección:<span class="text-danger">*</span></h5>
                                        <textarea type="text" id="direccion" name="direccion" class="form-control"
                                            placeholder="Dirección completa" required></textarea>
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
                            <div class="pull-left">
                                <button type="button" class="btn waves-effect waves-light btn-default" onclick="cerrar_mantenimiento();"><i class="mdi mdi-chevron-left"></i> Salir</button>
                            </div>
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

                        <div id="cnt_form2" class="cnt_form" style="display: none;">
                          <h3 class="box-title" style="margin: 0px;">
                              <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 2</button>&emsp;
                              Información de la persona solicitada
                            </h3><hr class="m-t-0 m-b-30">
                            <span class="etiqueta">Datos de la persona solicitada</span>
                            <blockquote class="m-t-0">
                                <div class="row">
                                    <div class="col-lg-8 form-group <?php if($navegatorless){ echo " pull-left "; } ?>"
                                        id="div_combo_establecimiento"></div>
                                </div>
                            </blockquote>

                            <span class="etiqueta">Persona representante</span>
                            <blockquote class="m-t-0">
                                <div id="cnt_tabla_representantes"></div>
                            </blockquote>
                            <div class="pull-left">
                                <button type="button" class="btn waves-effect waves-light btn-default" onclick="open_form(1)"><i class="mdi mdi-chevron-left"></i> Volver</button>
                            </div>
                            <div class="row">
                                <div class="col-lg-12" align="center">
                                    <div align="right" id="btnadd1" class="pull-right">
                                        <button type="submit" onclick="validar_establecimiento();" class="btn waves-effect waves-light btn-success2">Siguiente <i class="mdi mdi-chevron-right"></i></button>
                                    </div>
                                    <div align="right" id="btnedit1" style="display: none;" class="pull-right">
                                        <button type="submit" onclick="validar_establecimiento();" class="btn waves-effect waves-light btn-info">Siguiente <i class="mdi mdi-chevron-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ============================================================== -->
                        <!-- Inicio del FORMULARIO INFORMACIÓN DE LA SOLICITUD -->
                        <!-- ============================================================== -->
                        <?php echo form_open('', array('id' => 'formajax2', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                        <div id="cnt_form3" class="cnt_form" style="display: none;">
                            <h3 class="box-title" style="margin: 0px;">
                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 3</button>&emsp;
                                Información de la solicitud
                                <input type="hidden" id="band2" name="band2" value="save">
                                <input type="hidden" id="id_persona" name="id_persona" value="">
                                <input type="hidden" id="id_expedienteci" name="id_expedienteci" value="">
                                <input type="hidden" id="id_representanteci" name="id_representanteci" value="">

                            </h3>
                            <hr class="m-t-0 m-b-30">
                            <span class="etiqueta">Expediente</span>
                            <blockquote class="m-t-0">

                                <div class="row">
                                    <div class="form-group col-lg-3 <?php if($navegatorless){ echo " pull-left"; } ?>">
                                        <h5>Fecha de preaviso: <span class="text-danger">*</span></h5>
                                        <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control"
                                            id="fecha_preaviso" name="fecha_preaviso" placeholder="dd/mm/yyyy">
                                        <div class="help-block"></div>
                                    </div>
                                    <div class="form-group col-lg-3 <?php if($navegatorless){ echo " pull-left"; } ?>">
                                        <h5>Fecha de renuncia: <span class="text-danger">*</span></h5>
                                        <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control"
                                            id="fecha_renuncia" name="fecha_renuncia" placeholder="dd/mm/yyyy">
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 form-group <?php if($navegatorless){ echo " pull-left "; } ?>"
                                        id="div_combo_delegado"></div>
                                    <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                        <h5>Causa: <span class="text-danger">*</span></h5>
                                        <select id="causa_expedienteci" name="causa_expedienteci" class="select2" style="width: 100%" required>
                                            <option value=''>[Seleccione la causa]</option>
                                            <?php
                                                $causa = $this->db->query("SELECT * FROM sct_motivo_solicitud WHERE id_tipo_solicitud < 4");
                                                if($causa->num_rows() > 0){
                                                    foreach ($causa->result() as $fila2) {
                                                       echo '<option class="m-l-50" value="'.$fila2->id_motivo_solicitud.'">'.$fila2->nombre_motivo.'</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                  <div class="form-group col-lg-12" style="height: 83px;">
                                      <h5>Descripción del motivo:<span class="text-danger">*</h5>
                                      <textarea type="text" id="descripcion_motivo" name="descripcion_motivo" class="form-control" placeholder="Descipción del motivo"></textarea>
                                      <div class="help-block"></div>
                                  </div>
                                </div>

                            </blockquote>
                            <div class="pull-left">
                                <button type="button" class="btn waves-effect waves-light btn-default" onclick="open_form(2)"><i class="mdi mdi-chevron-left"></i> Volver</button>
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
                        <!-- Fin del FORMULARIO INFORMACIÓN DE LA SOLICITUD -->
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
    <div id="cnt_modal_bitacora_delegado"></div>
</div>
<!-- ============================================================== -->
<!-- Fin de DIV de inicio (ENVOLTURA) -->
<!-- ============================================================== -->

<div style="display:none;">
    <button id="submit_ubi" name="submit_ubi" type="button">clicks</button>
</div>

<!--INICIO MODAL GENERAR ACTA -->
<div class="modal fade" id="modal_actas_tipo" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Emitir acta:</h4>
      </div>

      <div class="modal-body" id="">
          <input type="hidden" id="id_expedienteci_copia2" name="id_expedienteci_copia2" value="">
          <input type="hidden" id="id_audiencia" name="id_audiencia" value="">
          <input type="hidden" id="cuenta_audiencias" name="cuenta_audiencias" value="">
          <div class="row">
            <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                <h5>Seleccione el tipo de acta: <span class="text-danger">*</span></h5>
                <div class="controls">
                  <!-- Tipo 1: Engloba 4 posibilidades primera y segunda fecha, con presencia del trabajador y sin el trabajador -->
                  <select id="tipo_acta" name="tipo_acta" class="custom-select col-4" onchange="nav(this.value)" required>
                    <option value="">[Seleccione]</option>
                    <option id="pf_st" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/1/')?>">Acta de audiencia</option>
                    <option id="multa" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/2/')?>">Acta de audiencia: multa</option>
                    <option id="inasistencia" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/3/')?>">Acta segunda audiencia</option>
                    <option id="desistimiento" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/4/')?>">Acta de desistimiento</option>
                    <!-- <option id="diferido_con" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/3/')?>">Conciliada pago diferido con defensor/a público</option>
                     -->
                    <option id="solicitud_pn_pj" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/5/')?>">Acta de solicitud</option>
                    <option id="rv_ambas_partes" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/7/')?>">Acta renuncia volunataria ambas partes</option>
                    <option id="rv_solo_trabajador" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/8/')?>">Acta renuncia volunataria solo trabajador</option>
                    <option id="rv_ncnp" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/9/')?>">Acta renuncia volunataria Ninguna de las partes</option>
                    <option id="esquela" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/6/')?>">Acta de esquela</option>
                    <!-- <option value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta/')?>">Ficha de persona natural a persona juridica</option>
                    <option id="segunda_con" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/6/')?>">Segunda cita PN-PJ con defensor/a</option>
                    <option id="segunda_sin" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/7/')?>">Segunda cita PN-PJ sin defensor/a</option> -->
                    <!-- <option value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/8/')?>">Desistimiento de persona natural a persona juridica</option> -->
                  </select>
                </div>
            </div>
          </div>
          <div align="right">
            <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal">Cerrar</button>
          <!--  <button type="button" onclick="generar_actas_tipo();" class="btn waves-effect waves-light btn-success2"> Generar
          </button> !-->
          </div>
      </div>
    </div>
  </div>
</div>
<!--FIN MODAL GENERAR ACTA -->

<!--INICIO MODAL DE REPRESENTANTE EMPRESA -->
    <div id="modal_representante" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

              <?php echo form_open('', array('id' => 'formajax5', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
              <input type="hidden" id="band4" name="band4" value="save">
              <input type="hidden" id="id_representante" name="id_representante" value="">
                <div class="modal-header">
                    <h4 class="modal-title">Gestión de personas representantes</h4>
                </div>
                <div class="modal-body" id="">
                    <div class="row">
                      <div class="form-group col-lg-6 col-sm-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                          <h5>Nombre del representante: <span class="text-danger">*</span></h5>
                          <div class="controls">
                              <input type="text" id="nombres_representante" name="nombres_representante" class="form-control" required="">
                          </div>
                      </div>

                    <!-- <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                        <h5>Fecha de nacimiento: <span class="text-danger">*</span></h5>
                        <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="f_nacimiento_representante" name="f_nacimiento_representante" placeholder="dd/mm/yyyy">
                        <div class="help-block"></div>
                    </div> -->

                    <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                        <h5>Tipo: <span class="text-danger">*</span></h5>
                        <select onchange="ocultar_campos_rep()" id="tipo_representante" name="tipo_representante" class="form-control custom-select"  style="width: 100%" required="">
                            <option value=''>[Seleccione el tipo]</option>
                            <option class="m-l-50" value="1">Legal</option>
                            <option class="m-l-50" value="2">Designado</option>
                            <option class="m-l-50" value="3">Apoderado</option>
                        </select>
                    </div>

                    </div>
                    <div id="ocultar_campos">
                    <div class="row">
                      <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                          <h5>DUI: <span class="text-danger">*</span></h5>
                          <div class="controls">
                              <input type="text" id="dui_representante" name="dui_representante" class="form-control" data-mask="99999999-9">
                          </div>
                      </div>

                      <!-- <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                          <h5>Tipo: <span class="text-danger">*</span></h5>
                          <select id="tipo_representante" name="tipo_representante" class="form-control custom-select"  style="width: 100%" required="">
                              <option value=''>[Seleccione el tipo]</option>
                              <option class="m-l-50" value="1">Legal</option>
                              <option class="m-l-50" value="2">Designado</option>
                              <option class="m-l-50" value="3">Apoderado</option>
                          </select>
                      </div> -->

                      <div class="col-lg-6 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_estados_civiles"></div>
                    </div>
                    <div class="row">


                      <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                          <h5>Acreditación: <span class="text-danger">*</span></h5>
                          <div class="controls">
                              <textarea id="acreditacion_representante" name="acreditacion_representante" class="form-control"></textarea>
                          </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_profesiones"></div>

                      <div class="col-lg-6 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_municipio2"></div>
                    </div>

                    <div style="display: none;"> class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                        <h5>Estado: <span class="text-danger">*</span></h5>
                        <select id="estado_representante" name="estado_representante" class="form-control custom-select"  style="width: 100%" required="">
                            <option class="m-l-50" value="1">Activo</option>
                            <option class="m-l-50" value="0">Inactivo</option>
                        </select>
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect text-white" data-dismiss="modal">Cerrar</button>
                    <button type="submit" id="submit3" class="btn btn-info waves-effect text-white">Aceptar</button>
                </div>
              <?php echo form_close(); ?>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
  <!--FIN MODAL REPRESENTANTE EMPRESA -->


<!--INICIA MODAL DE ESTABLECIMIENTOS -->
  <div class="modal fade" id="modal_establecimiento" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
    <?php echo form_open('', array('id' => 'formajax3', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
          <input type="hidden" id="band3" name="band3" value="save">
          <input type="hidden" id="id_empresaci" name="id_empresaci" value="">
          <!-- <input type="hidden" id="id_representante" name="id_representante" value=""> -->
            <div class="modal-header">
                <h4 class="modal-title">Gestión de parte empleadora</h4>
            </div>
            <div class="modal-body" id="">
              <div id="alert_empresa"></div>
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
              </div>

                <div class="row">
                  <!-- <div class="form-group col-lg-6 col-sm-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Raz&oacute;n social de la parte empleadora:</h5>
                      <div class="controls">
                          <input type="text" placeholder="Nombre" id="razon_social" name="razon_social" class="form-control">
                      </div>
                  </div> -->

                  <div class="form-group col-lg-16 col-sm-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Nombre de la parte empleadora: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" placeholder="Nombre" id="nombre_establecimiento" name="nombre_establecimiento" class="form-control" required>
                      </div>
                  </div>

                  <div class="form-group col-lg-6 col-sm-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Abreviatura de la parte empleadora:</h5>
                      <div class="controls">
                          <input type="text" placeholder="Abreviatura" id="abre_establecimiento" name="abre_establecimiento" class="form-control">
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
                      <h5>Tel&eacute;fono: </h5>
                      <div class="controls">
                          <input type="text" placeholder="Telefono" id="telefono_establecimiento" name="telefono_establecimiento" class="form-control" data-mask="9999-9999">
                          <div class="help-block"></div>
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-12 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_actividad_economica"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-white" onclick="limpiar_modal_empresa();">Cerrar</button>
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
                <h4 class="modal-title">Cambiar asignación de persona delegada:</h4>
            </div>

            <div class="modal-body" id="">
                <input type="hidden" id="id_expedienteci_copia" name="id_expedienteci_copia" value="">
                <div class="row">
                  <div class="col-lg-12 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_cambiar_delegado"></div>
                    <!-- <div class="form-group col-lg-12 col-sm-12">
                        <div class="form-group">
                            <h5>Delegado/a:<span class="text-danger">*</h5>
                            <select id="id_personal_copia" name="id_personal_copia" class="select2" style="width: 100%"
                                required="">
                                <option value="">[Todas las personas empleadas]</option>
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
                    </div> -->
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
                    $("#band1").val('edit');
                    $("#band2").val($("#bandx").val());
                }
            });
        });
    });

    $(function () {
        $("#formajax2").on("submit", function (e) {
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formajax2"));
            formData.append("id_representanteci", $('#id_representanteci').val());
            formData.append("establecimiento", $('#establecimiento').val());

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
                    console.log(res)
                    if (res == "fracaso") {
                        swal({
                            title: "¡Ups! Error",
                            text: "Intentalo nuevamente.",
                            type: "error",
                            showConfirmButton: true
                        });
                    } else {
                        //
                        cerrar_mantenimiento();
                        audiencias($('#establecimiento').val(), res, 1);
                        /*if ($("#band2").val() == "save") {
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
                        tablasolicitudes();*/
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
                    console.log(res)
                      res = res.split(",");
                        if(res[0] == "exito"){
                            if($("#band3").val() == "save"){
                                //$("#id_empresa").val(res[1])
                                $("#modal_establecimiento").modal('hide');
                                $.toast({ heading: 'Registro exitoso', text: 'Registro de parte empleadora exitoso', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
                                combo_establecimiento(res[1]);
                            }else if($("#band3").val() == "edit"){
                                swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                                $("#modal_establecimiento").modal('hide');
                                combo_establecimiento($("#establecimiento").val());
                            }
                        }else{
                            swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
                        }
                });

        });
    });

    $("#formajax5").on("submit", function(e){
    e.preventDefault();
    var f = $(this);
    var formData = new FormData(document.getElementById("formajax5"));
    formData.append("id_empresa", $('#establecimiento').val());

    $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/gestionar_representante",
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(res){
      console.log(res)
        if(res == "exito"){
            if($("#band4").val() == "save"){
                swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
            }else if($("#band4").val() == "edit"){
                swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
            }else{
                if($("#estado_representante").val() == '1'){
                    swal({ title: "¡Activado exitosamente!", type: "success", showConfirmButton: true });
                }else{
                    swal({ title: "¡Desactivado exitosamente!", type: "success", showConfirmButton: true });
                }
            }
            $("#modal_representante").modal('hide');
            tabla_representantes();
        }else{
            swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
        }
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

            $('#fecha_preaviso').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: moment().format("DD-MM-YYYY")
            }).datepicker("setDate", new Date());

            $('#f_nacimiento_representante').datepicker({ format: 'dd-mm-yyyy', autoclose: true, todayHighlight: true, endDate: moment().format("DD-MM-YYYY")}).datepicker("setDate", new Date());

            $('#fecha_renuncia').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: moment().format("DD-MM-YYYY")
            }).datepicker("setDate", new Date());
        });
    });
</script>
