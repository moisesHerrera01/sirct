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
        <?php if(isset($tipo_solicitud)){ ?>
            $("#motivo_expedienteci").val('<?=$tipo_solicitud?>');
            <?php if($band_mantto == "save"){ ?>
                nuevo_reg_post();
            <?php }else{ ?>
                cambiar_editar_post('<?=$id_empresa?>','save');
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

    function nuevo_reg_post(){
        $("#cnt_tabla").hide(0);
        $("#cnt_form_main").show(0);
        combo_establecimiento('<?=$id_empresa?>');
    }

    function convert_lim_text(lim){
        var tlim = "-"+lim+"d";
        return tlim;
    }

    var estado_pestana = "";
    function cambiar_pestana(tipo){
        estado_pestana = tipo;
        tablasolicitudes();
    }

    function combo_ocupacion(seleccion, seleccion2){
        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica/combo_ocupacion",
            type: "post",
            dataType: "html",
            data: {id : seleccion}
        })
        .done(function(res){
            $('#div_combo_ocupacion').html(res);
            //$("#id_catalogociuo").select2();
            combo_establecimiento(seleccion2);
        });
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

    function combo_resultados(seleccion){
      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/expediente/combo_resultados",
        type: "post",
        dataType: "html",
        data: {id : seleccion}
      })
      .done(function(res){
        $('#div_combo_resultados').html(res);
        $("#resolucion").select2();
      });
    }

    function open_form(num){
        $(".cnt_form").hide(0);
        $("#cnt_form"+num).show(0);

        if($("#band").val() == "save"){
            $("#btnadd"+num).show(0);
            $("#btnedit"+num).hide(0);
        }else{
            $("#btnadd"+num).hide(0);
            $("#btnedit"+num).show(0);
        }
    }

    function cerrar_mantenimiento(){
        $("#cnt_tabla").show(0);
        $("#cnt_actions").hide(0);
        $("#cnt_form_main").hide(0);
        open_form(1);
        tablasolicitudes();
    }

    function objetoAjax(){
        var xmlhttp = false;
        try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) { try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { xmlhttp = false; } }
        if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp = new XMLHttpRequest(); }
        return xmlhttp;
    }

    function tablasolicitudes(){
      var nr_empleado = $("#nr_search").val();
        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttpB=new XMLHttpRequest();
        }else{// code for IE6, IE5
            xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
        }
        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                document.getElementById("cnt_tabla_solicitudes").innerHTML=xmlhttpB.responseText;
                $('[data-toggle="tooltip"]').tooltip();
                $('#myTable').DataTable();
            }
        }
        xmlhttpB.open("GET","<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica/tabla_solicitud_juridica?nr="+nr_empleado+"&tipo="+estado_pestana,true);
        xmlhttpB.send();
    }

    function tabla_representantes(){
        var id_empresa = $("#establecimiento").val();
        var id_representanteci = $("#id_representanteci").val();
        if(window.XMLHttpRequest){ xmlhttpB=new XMLHttpRequest();
        }else{ xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB"); }
        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                document.getElementById("cnt_tabla_representantes").innerHTML=xmlhttpB.responseText;
                $('[data-toggle="tooltip"]').tooltip();
                $('#myTable2').DataTable();
            }
        }
        xmlhttpB.open("GET","<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/tabla_representantes?id_empresa="+id_empresa+"&id_representanteci="+id_representanteci,true);
        xmlhttpB.send();
    }

    function visualizar(id_expedienteci,id_empresaci, id_personaci) {
        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica/ver_expediente",
            type: "post",
            dataType: "html",
            data: {id : id_expedienteci, id_emp : id_empresaci, id_per : id_personaci}
        })
        .done(function(res){
            $('#cnt_actions').html(res);
            $("#cnt_actions").show(0);
            $("#cnt_tabla").hide(0);
            $("#cnt_form_main").hide(0);
        });
    }

    function alertFunc() {
        $('[data-toggle="tooltip"]').tooltip()
    }

    function cambiar_nuevo(){
        /*Inicio Solicitante*/
        //combo_establecimiento('');
        $("#id_expedienteci").val('');
        $("#id_representanteci").val('');
        $("#nr").val($("#nr_search").val()).trigger('change.select2');
        $("#nombres").val('');
        $("#apellidos").val('');
        $("#dui").val('');
        $("#telefono").val('');
        $("#municipio").val('').trigger('change.select2');
        $("#causa_expedienteci").val('').trigger('change.select2');
        $("#direccion").val('');
        $("#fecha_nacimiento").val('');
        document.getElementById('masculino').checked = 1;
        document.getElementById('femenino').checked = 0;
        document.getElementById('si').checked = 0;
        document.getElementById('no').checked = 1;
        $("#estudios").val('');
        $("#nacionalidad").val('');
        $("#discapacidad").val('');
        /*Fin Solicitante*/

        /*Inicio Solicitado*/

        //
        /*Fin Solicitado*/

        $("#band3").val('save');
        $("#bandx").val('save');

        $("#ttl_form").addClass("bg-success");
        $("#ttl_form").removeClass("bg-info");

        open_form(1);

        $("#cnt_tabla").hide(0);
        $("#cnt_form_main").show(0);

        $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> Nueva Solicitud");
        combo_ocupacion('');
    }

    function cambiar_nuevo4(){
        /*Inicio Solicitante*/
        //$("#motivo_expedienteci").val('');
        $("#descripmotivo_expedienteci").val('');
        $("#id_personal").val('').trigger('change.select2');
        /*Fin Solicitante*/

        /*Inicio Solicitado*/

        //
        /*Fin Solicitado*/
        open_form(3);
        $("#band3").val("edit");
        $("#band4").val('save');
    }

    function cambiar_editar_post(id_expedienteci) {
        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica/obtener_expediente_juridico",
            type: "POST",
            data: {
                id: id_expedienteci
            }
        })
        .done(function (res) {
            result = JSON.parse(res)[0];

            combo_ocupacion(result.id_catalogociuo, result.id_empresaci);
            $("#id_catalogociuo").val(result.id_catalogociuo);
            $("#id_personaci").val(result.id_personaci);
            $("#id_representanteci").val(result.id_representanteci);
            $("#nombre_personaci").val(result.nombre_personaci);
            $("#apellido_personaci").val(result.apellido_personaci);
            if(result.sexo_personaci == 'M'){
                document.getElementById('masculino').checked = 1;
                document.getElementById('femenino').checked = 0;
            }else{
                document.getElementById('masculino').checked = 0;
                document.getElementById('femenino').checked = 1;
            }
            if(result.discapacidad_personaci == '1'){
                document.getElementById('si').checked = 1;
                document.getElementById('no').checked = 0;
            }else{
                document.getElementById('si').checked = 0;
                document.getElementById('no').checked = 1;
            }
            $("#direccion_personaci").val(result.direccion_personaci);
            $("#discapacidad_personaci").val(result.discapacidad_personaci);
            $("#telefono_personaci").val(result.telefono_personaci);
            $("#municipio").val(result.id_municipio.padStart(5,"00000")).trigger('change.select2');
            $("#causa_expedienteci").val(result.causa_expedienteci).trigger('change.select2');
            $("#salario_personaci").val(result.salario_personaci);
            $("#horarios_personaci").val(result.horarios_personaci);
            $("#id_expedienteci").val(result.id_expedienteci);
            $("#motivo_expedienteci").val(result.motivo_expedienteci);
            $("#descripmotivo_expedienteci").val(result.descripmotivo_expedienteci);
            $("#id_personal").val(result.id_personal).trigger('change.select2');
            $("#band3").val("edit");
            $("#bandx").val('edit');

            open_form(1)

            $("#ttl_form").removeClass("bg-success");
            $("#ttl_form").addClass("bg-info");
            $("#cnt_tabla").hide(0);
            $("#cnt_form_main").show(0);
            $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Editar Solicitud");
        });
    }

    function cambiar_editar(id_empresaci, id_personaci, nombre_personaci, apellido_personaci, sexo_personaci, direccion_personaci, discapacidad_personaci, telefono_personaci, id_municipio, id_catalogociuo, salario_personaci, horarios_personaci, id_expedienteci, motivo_expedienteci, descripmotivo_expedienteci, id_personal, id_representanteci, causa_expedienteci,band){
        combo_ocupacion(id_catalogociuo, id_empresaci);
        $("#id_catalogociuo").val(id_catalogociuo);
        $("#id_personaci").val(id_personaci);
        $("#id_representanteci").val(id_representanteci);
        $("#nombre_personaci").val(nombre_personaci);
        $("#apellido_personaci").val(apellido_personaci);
        if(sexo_personaci == 'M'){
            document.getElementById('masculino').checked = 1;
            document.getElementById('femenino').checked = 0;
        }else{
            document.getElementById('masculino').checked = 0;
            document.getElementById('femenino').checked = 1;
        }
        if(discapacidad_personaci == '1'){
            document.getElementById('si').checked = 1;
            document.getElementById('no').checked = 0;
        }else{
            document.getElementById('si').checked = 0;
            document.getElementById('no').checked = 1;
        }
        $("#direccion_personaci").val(direccion_personaci);
        $("#discapacidad_personaci").val(discapacidad_personaci);
        $("#telefono_personaci").val(telefono_personaci);
        $("#municipio").val(id_municipio.padStart(5,"00000")).trigger('change.select2');
        $("#causa_expedienteci").val(causa_expedienteci).trigger('change.select2');
        $("#salario_personaci").val(salario_personaci);
        $("#horarios_personaci").val(horarios_personaci);
        $("#id_expedienteci").val(id_expedienteci);
        $("#motivo_expedienteci").val(motivo_expedienteci);
        $("#descripmotivo_expedienteci").val(descripmotivo_expedienteci);
        $("#id_personal").val(id_personal).trigger('change.select2');
        $("#band3").val(band);
        $("#bandx").val('edit');

        open_form(1)

        if(band == "edit"){
            $("#ttl_form").removeClass("bg-success");
            $("#ttl_form").addClass("bg-info");
            //$("#btnadd").hide(0);
            //$("#btnedit").show(0);
            $("#cnt_tabla").hide(0);
            $("#cnt_form_main").show(0);
            $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Editar Solicitud");
        }else{
            eliminar_horario(estado);
        }
    }

    function cerrar_combo_establecimiento() {
        var select2 = $('.select2-search__field').val();
        $("#nombre_empresa").val(select2);
        $("#establecimiento").select2('close');
    }

    function combo_establecimiento(seleccion){
        $.ajax({
            async: true,
          url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica/combo_establecimiento",
          type: "post",
          dataType: "html",
          data: {id : seleccion}
        })
        .done(function(res){
            $.when($('#div_combo_establecimiento').html(res) ).then(function( data, textStatus, jqXHR ) {
                $("#establecimiento").select2({

                    'language': {
                        noResults: function () {
                            return '<div align="right"><a href="javascript:;" data-toggle="modal" data-target="#modal_establecimiento" title="Agregar nuevos establecimientos" class="btn btn-success2" onClick="cerrar_combo_establecimiento()"><span class="mdi mdi-plus"></span>Agregar nuevo establecimiento</a></div>';
                        }
                    }, 'escapeMarkup': function (markup) { return markup; }
                });
                tabla_representantes()
            });
        });
    }

    function cambiar_nuevo2(){
        if($("#establecimiento").val() == ''){
            swal({ title: "Seleccione un establecimiento", type: "warning", showConfirmButton: true });
        }else{
            $("#id_representante").val('');
            $("#nombres_representante").val('');
            $("#dui_representante").val('');
            $("#acreditacion_representante").val('');
            $("#tipo_representante").val('');
            $("#estado_representante").val('1');
            combo_estados_civiles('');
            combo_profesiones('');
            combo_municipio2('');
            $("#band2").val('save');
            $("#modal_representante").modal('show');
        }

    }

    function cambiar_editar2(id_representante, dui_representante, nombres_representante, acreditacion_representante,
  tipo_representante, estado_representante,id_estado_civil,id_titulo_academico,id_municipio,f_nacimiento_representante, band){
  $("#id_representante").val(id_representante);
  $("#dui_representante").val(dui_representante);
  $("#nombres_representante").val(nombres_representante);
  $("#acreditacion_representante").val(acreditacion_representante);
  $("#tipo_representante").val(tipo_representante);
  $("#estado_representante").val(estado_representante);
  $("#f_nacimiento_representante").val(f_nacimiento_representante);
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

    function cambiar_eliminar3(estado){
        if(estado == 1){
            var text = "Desea desactivar el registro";
            var title = "¿Dar de baja?";
        }else{
            var text = "Desea activar el registro";
            var title = "¿Activar?";
        }
        swal({
            title: title,
            text: text,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#fc4b6c",
            confirmButtonText: "Sí, continuar"
        }, function(){
            if(estado == 1){
                $.when( $("#estado_representante").val('0') ).then( $("#submit2").click() );
            }else{
                $.when( $("#estado_representante").val('1') ).then( $("#submit2").click() );
            }
        });
    }

    function open_form(num){
        $(".cnt_form").hide(0);
        $("#cnt_form"+num).show(0);

        if($("#band3").val() == "save"){
            $("#btnadd"+num).show(0);
            $("#btnedit"+num).hide(0);
        }else{
            $("#btnadd"+num).hide(0);
            $("#btnedit"+num).show(0);
        }
    }

    function validar_establecimiento(){
        var establecimiento = $("#establecimiento").val();
        var registros = $("#tabla_representante tbody tr.table-active");

        if(establecimiento == "" || registros.length == 0){
            if(establecimiento == ""){
                swal({ title: "Seleccione establecimiento", text: "No se ha seleccionado ningún establecimiento.", type: "warning", showConfirmButton: true });
            }else{
                swal({ title: "Seleccione una persona representante", text: "Agregue o seleccione una persona representante de la lista.", type: "warning", showConfirmButton: true });
            }
        }else{
            open_form(2);
        }
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
            if(res == "exito"){
              tablasolicitudes();
              swal({ title: "¡Expediente inhabilitado exitosamente!", type: "success", showConfirmButton: true });
            }else{
                  swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
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
              if(res == "exito"){
                tablasolicitudes();
                swal({ title: "¡Expediente habilitado exitosamente!", type: "success", showConfirmButton: true });
              }else{
                  swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
              }
            });
        });
    }

    function modal_estado(id_expedienteci, id_estadosci) {
        $("#id_expedienteci_copia").val(id_expedienteci);
        $("#id_estado_copia").val(id_estadosci).trigger('change.select2');
        $("#modal_estado").modal("show");
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
            tablasolicitudes();
            swal({ title: "¡Delegado/a modificado exitosamente!", type: "success", showConfirmButton: true });
          }else{
              swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
          }
        });
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
            //$("#cnt_tabla_solicitudes").hide(0);
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
            //$("#paso4").show(0);
            tabla_audiencias(id_expedienteci);
            $("#div_finalizar").show(0);
        }else {
          tabla_audiencias(id_expedienteci);
        }

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

</script>
<input type="hidden" id="id_empresaci" name="id_empresaci">
<input type="hidden" id="address" name="">
<input type="hidden" id="bandx" name="bandx">
<div class="page-wrapper">
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- TITULO de la página de sección -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="align-self-center" align="center">
                <h3 class="text-themecolor m-b-0 m-t-0">Solicitud de mediación de Conflictos persona jurídica </h3>
            </div>
        </div>

        <div id="cnt_actions" style="display: block;"></div>
        <!-- ============================================================== -->
        <!-- Fin TITULO de la página de sección -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Inicio del CUERPO DE LA SECCIÓN -->
        <!-- ============================================================== -->
        <div class="row" <?php if($navegatorless){ echo "style='margin-right: 80px;'"; } ?>>
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
                        <div id="cnt_form1" class="cnt_form">
                          <h3 class="box-title" style="margin: 0px;">
                              <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 1</button>&emsp;
                              Información del solicitante
                            </h3><hr class="m-t-0 m-b-30">
                            <span class="etiqueta">Datos del establecimiento solicitante</span>
                            <blockquote class="m-t-0">
                                <div class="row">
                                    <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_establecimiento">
                                    </div>
                                </div>
                            </blockquote>

                            <span class="etiqueta">Persona representante</span>
                            <blockquote class="m-t-0">
                                <div id="cnt_tabla_representantes"></div>
                            </blockquote>
                            <div class="pull-left">
                                <button type="button" class="btn waves-effect waves-light btn-default" onclick="cerrar_mantenimiento();"><i class="mdi mdi-chevron-left"></i> Salir</button>
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
                        <!-- Fin del FORMULARIO INFORMACIÓN DEL SOLICITANTE -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Inicio del FORMULARIO INFORMACIÓN DEL SOLICITADO -->
                        <!-- ============================================================== -->
                        <?php echo form_open('', array('id' => 'formajax3', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

                        <input type="hidden" id="band3" name="band3" value="save">
                        <input type="hidden" id="id_personaci" name="id_personaci" value="">
                        <input type="hidden" id="estado" name="estado" value="1">

                        <div id="cnt_form2" class="cnt_form" style="display: none;">
                            <h3 class="box-title" style="margin: 0px;">
                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 2</button>&emsp;
                                Información del solicitado
                            </h3><hr class="m-t-0 m-b-30">
                            <span class="etiqueta">Datos del solicitado</span>
                            <blockquote class="m-t-0">
                            <div class="row">
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Nombres: <span class="text-danger">*</span></h5>
                                    <input type="text" id="nombre_personaci" name="nombre_personaci" class="form-control" placeholder="Nombres de la persona" required="">
                                </div>
                                <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Apellidos: <span class="text-danger">*</span></h5>
                                    <input type="text" id="apellido_personaci" name="apellido_personaci" class="form-control" placeholder="Apellidos de la persona" required="">
                                </div>
                                <div class="form-group col-lg-4">
                                    <center>
                                    <h5>Seleccione el sexo:</h5>
                                    <input name="sexo_personaci" type="radio" id="masculino" checked="" value="M">
                                    <label for="masculino">Hombre</label><br>
                                    <input name="sexo_personaci" type="radio" id="femenino" value="F">
                                    <label for="femenino">Mujer</label>
                                    </center>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-8">
                                    <h5>Dirección: <span class="text-danger">*</span></h5>
                                    <textarea type="text" id="direccion_personaci" name="direccion_personaci" class="form-control" placeholder="Dirección completa" required=""></textarea>
                                </div>
                                <div class="form-group col-lg-4">
                                    <center>
                                    <h5>Posee discapacidad:</h5>
                                    <input name="discapacidad_personaci" type="radio" id="si" value="1">
                                    <label for="si">Si</label><br>
                                    <input name="discapacidad_personaci" type="radio" id="no" checked="" value="0">
                                    <label for="no">No</label>
                                    </center>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-4">
                                    <h5>Teléfono: </h5>
                                    <input data-mask="9999-9999" type="text" id="telefono_personaci" name="telefono_personaci" class="form-control" placeholder="Número de Telefóno">
                                </div>
                                <div class="form-group col-lg-8 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                    <h5>Municipio: <span class="text-danger">*</span></h5>
                                    <select id="municipio" name="municipio" class="select2" style="width: 100%" required>
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
                            </div>
                            </blockquote>
                            <div class="pull-left">
                                <button type="button" class="btn waves-effect waves-light btn-default" onclick="open_form(1)"><i class="mdi mdi-chevron-left"></i> Volver</button>
                            </div>
                            <div class="row">
                                <div class="col-lg-12" align="center">
                                    <div align="right" id="btnadd2" class="pull-right">
                                        <button type="submit" class="btn waves-effect waves-light btn-success2">Siguiente <i class="mdi mdi-chevron-right"></i></button>
                                    </div>
                                    <div align="right" id="btnedit2" style="display: none;" class="pull-right">
                                        <button type="submit" class="btn waves-effect waves-light btn-info">Siguiente <i class="mdi mdi-chevron-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <!-- ============================================================== -->
                        <!-- Fin del FORMULARIO INFORMACIÓN DEL SOLICITADO -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- Inicio del FORMULARIO INFORMACIÓN DEL SOLICITADO -->
                        <!-- ============================================================== -->
                        <?php echo form_open('', array('id' => 'formajax4', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                        <input type="hidden" id="id_expedienteci" name="id_expedienteci" value="">
                        <input type="hidden" id="id_representanteci" name="id_representanteci" value="">

                        <div id="cnt_form3" class="cnt_form" style="display: none;">
                            <h3 class="box-title" style="margin: 0px;">
                                <button type="button" class="btn waves-effect waves-light btn-lg btn-danger" style="padding: 1px 10px 1px 10px;">Paso 3</button>&emsp;
                                Información de la solicitud
                            </h3><hr class="m-t-0 m-b-30">
                            <span class="etiqueta">Motivo de la solicitud</span>
                            <blockquote class="m-t-0">
                                <div class="row">
                                    <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                                        <h5>Motivo de la solicitud: <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <select id="motivo_expedienteci" name="motivo_expedienteci" class="custom-select" required style="width: 100%">
                                                <option value="">[Seleccione el motivo]</option>
                                                <option value="1">Despido de hecho o injustificado</option>
                                                <option value="2">Conflicto laboral</option>
                                            </select>
                                        </div>
                                    </div>
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
                                    <div class="form-group col-lg-12">
                                        <h5>Descripción del motivo:<span class="text-danger">*</h5>
                                        <textarea type="text" id="descripmotivo_expedienteci" name="descripmotivo_expedienteci" class="form-control" placeholder="Descipción del motivo"></textarea>
                                    </div>
                                </div>
                            </blockquote>
                            <span class="etiqueta">Asignación de delegado/a</span>
                            <blockquote class="m-t-0">
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <h5>Delegado/a:<span class="text-danger">*</h5>
                                        <select id="id_personal" name="id_personal" class="select2" style="width: 100%" required="">
                                        <option value="">[Todos los empleados]</option>
                                        <?php
                                            $otro_empleado = $this->db->query("SELECT e.id_empleado, e.nr, UPPER(CONCAT_WS(' ', e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada)) AS nombre_completo FROM sir_empleado AS e WHERE e.id_estado = '00001' ORDER BY e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada");
                                            if($otro_empleado->num_rows() > 0){
                                                foreach ($otro_empleado->result() as $fila) {
                                                    echo '<option class="m-l-50" value="'.$fila->id_empleado.'">'.preg_replace ('/[ ]+/', ' ', $fila->nombre_completo.' - '.$fila->nr).'</option>';
                                                }
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                            </blockquote>
                            <div class="pull-left">
                                <button type="button" class="btn waves-effect waves-light btn-default" onclick="open_form(2)"><i class="mdi mdi-chevron-left"></i> Volver</button>
                            </div>
                            <div class="row">
                                <div class="col-lg-12" align="center">
                                    <div align="right" id="btnadd2" class="pull-right">
                                        <button type="submit" class="btn waves-effect waves-light btn-success2">Siguiente <i class="mdi mdi-chevron-right"></i></button>
                                    </div>
                                    <div align="right" id="btnedit2" style="display: none;" class="pull-right">
                                        <button type="submit" class="btn waves-effect waves-light btn-info">Siguiente <i class="mdi mdi-chevron-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <!-- ============================================================== -->
                        <!-- Fin del FORMULARIO INFORMACIÓN DEL SOLICITADO -->
                        <!-- ============================================================== -->

                    </div>
                </div>
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-12" id="cnt_tabla">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-b-0">Listado de solicitudes</h4>
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
                            <button type="button" onclick="cambiar_nuevo();" class="btn waves-effect waves-light btn-success2" ><span class="mdi mdi-plus"></span> Nuevo registro</button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row" style="width: 100%"></div>
                    <div class="row col-lg-12">
                        <ul class="nav nav-tabs customtab2 <?php if($navegatorless){ echo "pull-left"; } ?>" role="tablist" style='width: 100%;'>
                            <li class="nav-item <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <a class="nav-link active" onclick="cambiar_pestana('');" data-toggle="tab" href="#">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">Todas</span></a>
                            </li>
                            <li class="nav-item <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <a class="nav-link" onclick="cambiar_pestana('1');" data-toggle="tab" href="#">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">Esperando Audiencia</span></a>
                            </li>
                            <li class="nav-item <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <a class="nav-link" onclick="cambiar_pestana('2');" data-toggle="tab" href="#">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">Con Resultado</span></a>
                            </li>
                            <li class="nav-item <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <a class="nav-link" onclick="cambiar_pestana('3');" data-toggle="tab" href="#">
                                    <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                    <span class="hidden-xs-down">Archivado</span></a>
                            </li>
                            <li class="nav-item <?php if($navegatorless){ echo "pull-left"; } ?>">
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


<div id="cnt_modal_acciones"></div>

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
                      <h5>Nombre de la persona: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" id="nombres_representante" name="nombres_representante" class="form-control" required="">
                      </div>
                  </div>

                <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                    <h5>Fecha de nacimiento: <span class="text-danger">*</span></h5>
                    <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="f_nacimiento_representante" name="f_nacimiento_representante" placeholder="dd/mm/yyyy" readonly="">
                    <div class="help-block"></div>
                </div>

                </div>
                <div class="row">
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>DUI: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" id="dui_representante" name="dui_representante" class="form-control" data-mask="99999999-9">
                      </div>
                  </div>

                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Tipo: <span class="text-danger">*</span></h5>
                      <select id="tipo_representante" name="tipo_representante" class="form-control custom-select"  style="width: 100%" required="">
                          <option value=''>[Seleccione el tipo]</option>
                          <option class="m-l-50" value="1">Legal</option>
                          <option class="m-l-50" value="2">Designado</option>
                          <option class="m-l-50" value="3">Apoderado</option>
                      </select>
                  </div>

                  <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_estados_civiles"></div>
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

<div class="modal fade" id="modal_establecimiento" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
          <input type="hidden" id="band" name="band" value="save">
          <input type="hidden" id="id_empresa" name="id_empresa" value="">
            <div class="modal-header">
                <h4 class="modal-title">Gestión de empresas</h4>
            </div>
            <div class="modal-body" id="">
                <div class="row">
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                        <h5>Tipo de inscripción: <span class="text-danger">*</span></h5>
                        <select id="tiposolicitud_empresa" name="tiposolicitud_empresa" class="form-control custom-select"  style="width: 100%" required="">
                            <option class="m-l-50" value="1">INSCRIPCIÓN PERSONA NATURAL</option>
                            <option class="m-l-50" value="2">INSCRIPCIÓN PERSONA JURÍDICA</option>
                        </select>
                    </div>
                  <div class="form-group col-lg-8 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                      <h5>Razón social o denominación: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" placeholder="Razón social" id="razon_social" name="razon_social" class="form-control" required="">
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-8 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Nombre del establecimiento: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" placeholder="Nombre" id="nombre_empresa" name="nombre_empresa" class="form-control" required="">
                      </div>
                  </div>
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Abreviatura: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <input type="text" placeholder="Abreviatura" id="abreviatura_empresa" name="abreviatura_empresa" class="form-control" required="">
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                      <h5>Direcci&oacute;n: <span class="text-danger">*</span></h5>
                      <div class="controls">
                          <textarea type="text" id="direccion_empresa" name="direccion_empresa" class="form-control" required=""></textarea>
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                      <h5>Telefono: </h5>
                      <div class="controls">
                          <input type="text" placeholder="Telefono" id="telefono_empresa" name="telefono_empresa" class="form-control" data-mask="9999-9999">

                      </div>
                  </div>
                  <div class="form-group col-lg-8 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                        <h5>Municipio: <span class="text-danger">*</span></h5>
                        <select id="id_municipio" name="id_municipio" class="select2" style="width: 100%" required>
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
                </div>
                <div class="row">
                    <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                        <h5>Actividad económica: <span class="text-danger">*</span></h5>
                        <select id="id_catalogociiu" name="id_catalogociiu" class="select2" style="width: 100%" required>
                            <option value=''>[Seleccione la actividad]</option>
                            <?php
                                $catalogociiu = $this->db->query("SELECT * FROM sge_catalogociiu ORDER BY actividad_catalogociiu");
                                if($catalogociiu->num_rows() > 0){
                                    foreach ($catalogociiu->result() as $fila2) {
                                       echo '<option class="m-l-50" value="'.$fila2->id_catalogociiu.'">'.$fila2->actividad_catalogociiu.'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-white" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-info waves-effect text-white">Aceptar</button>
            </div>
          <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_delegado" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Registrar Resultado del Expediente</h4>
      </div>

      <div class="modal-body" id="">
          <input type="hidden" id="id_expedienteci_copia" name="id_expedienteci_copia" value="">
          <div class="row">
            <div class="form-group col-lg-12 col-sm-12">
                <div class="form-group">
                    <h5>Delegado/a:<span class="text-danger">*</h5>
                    <select id="id_personal_copia" name="id_personal_copia" class="select2" style="width: 100%" required="">
                    <option value="">[Todos los empleados]</option>
                    <?php
                        $otro_empleado = $this->db->query("SELECT e.id_empleado, e.nr, UPPER(CONCAT_WS(' ', e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada)) AS nombre_completo FROM sir_empleado AS e WHERE e.id_estado = '00001' ORDER BY e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada");
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
            <button type="button" onclick="cambiar_delegado();" class="btn waves-effect waves-light btn-success2"> Guardar
            </button>
          </div>
      </div>
    </div>
  </div>
</div>

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
                        <select id="id_estado_copia" name="id_estado_copia" class="select2" style="width: 100%" required="">
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
                <button type="button" onclick="cambiar_estado();" class="btn waves-effect waves-light btn-success2"> Guardar
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
                <h4 class="modal-title">Gestión de personas defensoras legales</h4>
            </div>
            <div class="modal-body" id="">
              <div class="row">
                <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                    <h5>Nombres de la persona: <span class="text-danger">*</span></h5>
                    <div class="controls">
                        <input type="text" id="nombre_representante_persona" name="nombre_representante_persona" class="form-control" placeholder="Nombres de la persona representante" required>
                    </div>
                </div>

                <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                    <h5>Apellidos de la persona: <span class="text-danger">*</span></h5>
                    <div class="controls">
                        <input type="text" id="apellido_representante_persona" name="apellido_representante_persona" class="form-control" placeholder="Apellidos de la persona representante" required>
                    </div>
                </div>
              </div>

              <div class="row">
                <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                    <h5>DUI de la persona: <span class="text-danger">*</span></h5>
                    <div class="controls">
                        <input data-mask="99999999-9" type="text" id="dui_representante_persona" name="dui_representante_persona" class="form-control" placeholder="Dui de la persona representante" required>
                    </div>
                </div>

                <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                    <h5>Tel&eacute;fono de la persona: <span class="text-danger">*</span></h5>
                    <div class="controls">
                        <input data-mask="9999-9999" type="text" id="telefono_representante_persona" name="telefono_representante_persona" class="form-control" placeholder="telefono de la persona representante" required>
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

<script>
$(function(){

    $("#acreditacion_representante").keypress(function (e) {
        if (e.keyCode != 13) return;
        var msg = $("#acreditacion_representante").val().replace(/\n/g, "");
        $("#acreditacion_representante").val(msg)
        return false;
    });

    $("#direccion_empresa").keypress(function (e) {
        if (e.keyCode != 13) return;
        var msg = $("#direccion_empresa").val().replace(/\n/g, "");
        $("#direccion_empresa").val(msg)
        return false;
    });

    $("#direccion").keypress(function (e) {
        if (e.keyCode != 13) return;
        var msg = $("#direccion").val().replace(/\n/g, "");
        $("#direccion").val(msg)
        return false;
    });

    $("#formajax").on("submit", function(e){
        e.preventDefault();

        var act_representante = $("#tabla_representante tbody tr.table-active");

        var f = $(this);
        var formData = new FormData(document.getElementById("formajax"));
        formData.append("dato", "valor");
        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica/gestionar_establecimiento",
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
                if($("#band").val() == "save"){
                    //$("#id_empresa").val(res[1])
                    $("#modal_establecimiento").modal('hide');
                    $.toast({ heading: 'Registro exitoso', text: 'Registro de establecimiento exitoso', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
                    combo_establecimiento(res[1]);
                }else if($("#band").val() == "edit"){
                    swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                    tabla_representantes();
                }
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
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

    $("#formajax3").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax3"));
        formData.append("id_empresaci", $('#establecimiento').val());
        formData.append("sexo", $('input:radio[name=sexo_personaci]:checked').val());
        formData.append("discapacidad", $('input:radio[name=discapacidad_personaci]:checked').val());

        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica/gestionar_solicitado",
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
                    $("#id_personaci").val(res[1])
                    cambiar_nuevo4();
                    $.toast({ heading: 'Registro exitoso', text: 'Registro de información de solicitado exitoso', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
                }else if($("#band3").val() == "edit"){
                    $.toast({ heading: 'Modificación exitosa', text: 'Modificación de información de solicitado exitosa', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
                    open_form(3);
                }
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });
    });

    $("#formajax4").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax4"));
        formData.append("id_empresaci", $('#establecimiento').val());
        formData.append("id_representanteci", $('#id_representanteci').val());
        formData.append("id_personaci", $('#id_personaci').val());
        formData.append("band4", $('#bandx').val());

        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitud_juridica/gestionar_expediente",
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
                if($("#bandx").val() == "save"){
                    $.toast({ heading: 'Registro exitoso', text: 'Registro de expediente exitoso', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
                    audiencias($('#establecimiento').val(), res[1], 1);
                }else if($("#bandx").val() == "edit"){
                    $.toast({ heading: 'Modificación exitosa', text: 'Modificación de expediente exitosa', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
                    audiencias($('#establecimiento').val(), res[1], 1)
                }else{
                    if($("#estado_empresa").val() == '1'){
                        swal({ title: "¡Activado exitosamente!", type: "success", showConfirmButton: true });
                    }else{
                        swal({ title: "¡Desactivado exitosamente!", type: "success", showConfirmButton: true });
                    }
                }
                //cerrar_mantenimiento()
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });
    });

    $("#formajax8").on("submit", function(e){
        e.preventDefault();

        //var act_representante = $("#tabla_representante tbody tr.table-active");

        var f = $(this);
        var formData = new FormData(document.getElementById("formajax8"));
        formData.append("dato", "valor");
        $.ajax({
            url: "<?php echo site_url(); ?>/resolucion_conflictos/representante_persona/gestionar_representantes",
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
                if($("#band6").val() == "save"){
                    //$("#id_empresa").val(res[1])
                    $("#modal_defensores").modal('hide');
                    $.toast({ heading: 'Registro exitoso', text: 'Registro de persona defensora exitoso', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
                    combo_defensores(res[1]);
                }else if($("#band6").val() == "edit"){
                    swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                    // tabla_representantes();
                }
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });

    });


});
</script>
