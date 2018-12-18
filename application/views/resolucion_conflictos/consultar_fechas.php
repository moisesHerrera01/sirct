<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<script>
function iniciar(){
    <?php if(tiene_permiso($segmentos=2,$permiso=1)){ ?>
    // tabla_calendario();
    combo_delegado_tabla();
    <?php }else{ ?>
        $("#cnt_calendario").html("Usted no tiene permiso para este formulario.");
    <?php } ?>
}

function tabla_calendario(){
  var id_delegado = $("#nr_search").val();
  // alert(id_delegado)
    if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttpB=new XMLHttpRequest();
    }else{// code for IE6, IE5
        xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
    }
    $('#calendar').fullCalendar("destroy");
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
    $('#calendar').fullCalendar({
        // put your options and callbacks here
        eventClick:  function(event, jsEvent, view) {
              $('#numero_caso_exp').html(event.title);
              $('#tipo_sol').html(event.tipo);
              $('#delegado').html(event.delegado);
              $('#persona').html(event.persona);
              $('#inicio').html(event.inicio);
              $('#fin').html(event.fin);
              $('#modalBody').html(event.description);
              $('#eventUrl').attr('href',event.url);
              $('#calendarModal').modal();
          },dayClick: function(date, jsEvent, view) {
            imprimir_citas_del_dia(date, "html")
        },
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listMonth'
        },
        events: {
        url: "<?php echo site_url(); ?>/resolucion_conflictos/Consultar_fechas/calendario?nr="+id_delegado,
        cache: true
    },
  timeFormat: 'h:mm a',
        defaultView: 'month',
        defaultDate: date,
        selectable: true,
        editable:true
    })
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

function tablasolicitudes(){
  tabla_calendario();
}

  function imprimir_citas_del_dia(end, tipo){
    var id_delegado = $("#nr_search").val();
    var fecha_seleccionada = getDateEnd(end);
    $("#fecha").val(fecha_seleccionada);
    $("#titulo_vista_previa").text("Citas para la fecha: "+castDate(fecha_seleccionada));

    var param = {id_delegado: id_delegado, fecha: fecha_seleccionada, report_type: tipo};

    if(tipo == "html"){
      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/Consultar_fechas/imprimir_citas_del_dia_pdf",
        type: "post",
        dataType: "html",
        data: param
      })
      .done(function(res){
        $("#modal_vista_previa").modal('show');
        $("#cnt_vista_previa").html(res);
      });
    }else{
      OpenWindowWithPost("<?php echo site_url(); ?>/resolucion_conflictos/Consultar_fechas/imprimir_citas_del_dia_pdf", param, "_blank");
    }

    
  }

  function castDate(date){
    var fecha = date.split("-");
    return fecha[2]+"/"+fecha[1]+"/"+fecha[0];
  }

  function getDateEnd(date){
    return moment(date).format('YYYY-MM-DD');
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
<style type="text/css">
  .fc-button {
      background: #ffffff;
      border: 1px solid rgba(120, 130, 140, 0.13);
      color: #67757c;
      text-transform: capitalize;
  }
  .fc-event {
      border-radius: 0px;
      border: none;
      cursor: move;
      color: #ffffff !important;
      font-size: 13px;
      margin: 1px -1px 0 -1px;
      padding: 5px 5px;
      margin: 0px 1px;
      text-align: center;
      background: #1e88e5;
  }

  a.fc-event.fc-draggable:hover { 
    width: 135px;
    position: absolute;
    z-index: 3;
  }

  .bg-success:hover {
    background-color: #26c6da !important;
  }

</style>
<input type="hidden" id="address" name="">
<div class="page-wrapper">
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- TITULO de la página de sección -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="align-self-center" align="center">
                <h3 class="text-themecolor m-b-0 m-t-0">Consulta de fechas de audiencias y pagos</h3>
            </div>
        </div>

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
                    </div>
                </div>
            </div>
            <div class="col-lg-12" id="cnt_actions" style="display:none;"></div>
            <div class="col-lg-1"></div>
            <div class="col-lg-12" id="cnt_tabla">
              <?php if (obtener_rango($segmentos=2, $permiso=1) > 1) { ?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title m-b-0 ">Filtrar por delegado/a</h4>
                    </div>
                    <div class="card-body b-t" style="padding-top: 7px;">
                    <div>
                        <div class="pull-left">
                            <div class="form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_delegado_tabla" style="width: 400px;"></div>
                        </div>
                    <div class="row" style="width: 100%"></div>
                    </div>
                </div>
              <?php }else{ ?>
                <input type="hidden" id="nr_search" name="nr_search" value="<?= $this->session->userdata('nr')?>">
              <?php } ?>
            </div>
                <div id="cnt_calendario">
                <div class="row">
                  <div class="col-md-2"></div>
                  <div class="col-md-8">
                    <div class="card">
                    <div class="card-body">
                      <div id="calendar"></div>
                        </div>
                    </div>
                  </div>
                </div>
                </div>
                  <!--Pruebas -->
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
    <button  id="submit_ubi" name="submit_ubi" type="button"  >clicks</button>
</div>

<!-- ============================================================== -->
<!--INICIO MODAL DE EVENTO CALENDARIO -->
<!-- ============================================================== -->
<div id="calendarModal" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Detalles de la audiencia</h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">Cerrar</span></button>
        </div>
        <div id="modalBody" class="modal-body">
          <div class="row">
            <div class="form-group col-lg-6" style="height: 20px;">
              N&uacute;mero de caso:
            </div>
            <div class="form-group col-lg-6" style="height: 20px;">
                  <h5 id="numero_caso_exp"></h5>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-6" style="height: 20px;">
              Tipo de solicitud:
            </div>
            <div class="form-group col-lg-6" style="height: 20px;">
                  <h5 id="tipo_sol"></h5>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-6" style="height: 20px;">
              Nombre delegado/a:
            </div>
            <div class="form-group col-lg-6" style="height: 20px;">
                  <h5 id="delegado"></h5>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-6" style="height: 20px;">
              Nombre de solicitante:
            </div>
            <div class="form-group col-lg-6" style="height: 20px;">
                  <h5 id="persona"></h5>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-6" style="height: 20px;">
              Fecha y hora de inicio:
            </div>
            <div class="form-group col-lg-6" style="height: 20px;">
                  <h5 id="inicio"></h5>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-lg-6" style="height: 20px;">
              Fecha y hora de fin:
            </div>
            <div class="form-group col-lg-6" style="height: 20px;">
                  <h5 id="fin"></h5>
            </div>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>
</div>
<!-- ============================================================== -->
<!--FIN MODAL DE EVENTO CALENDARIO -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!--INICIO MODAL DE EVENTO CALENDARIO -->
<!-- ============================================================== -->
<div id="modal_vista_previa" class="modal fade">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="titulo_vista_previa"></h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">Cerrar</span></button>
        </div>
        <div id="modalBody" class="modal-body">
          <input type="hidden" id="fecha" name="fecha">
          <div class="row">
            <div class="col-lg-12" id="cnt_vista_previa">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info" onclick="imprimir_citas_del_dia($('#fecha').val(),'pdf')"><span class="mdi mdi-file-pdf"></span> Imprimir</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>
</div>
<!-- ============================================================== -->
<!--FIN MODAL DE EVENTO CALENDARIO -->
<!-- ============================================================== -->

<script>
$(document).ready(function () {

  var calendar = $('#calendar').fullCalendar('getCalendar');
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    // page is now ready, initialize the calendar...
    $('#calendar').fullCalendar({
        // put your options and callbacks here
        selectable: true,
        eventClick:  function(event, jsEvent, view) {
              $('#numero_caso_exp').html(event.title);
              $('#tipo_sol').html(event.tipo);
              $('#delegado').html(event.delegado);
              $('#persona').html(event.persona);
              $('#inicio').html(event.inicio);
              $('#fin').html(event.fin);
              $('#modalBody').html(event.description);
              $('#eventUrl').attr('href',event.url);
              $('#calendarModal').modal();
          },
          
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay,listMonth'
        },
        events: {
        url: "<?php echo site_url(); ?>/resolucion_conflictos/Consultar_fechas/calendario",
        cache: true
    },
        defaultView: 'month',
        defaultDate: date,
        editable:true
    })
});
</script>
