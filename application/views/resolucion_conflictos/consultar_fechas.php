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
    tabla_calendario();
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
        defaultView: 'month',
        defaultDate: date,
        editable:true
    })
}
</script>

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
                        <h4 class="card-title m-b-0 ">Filtrar por delegado</h4>
                    </div>
                    <div class="card-body b-t" style="padding-top: 7px;">
                    <div>
                        <div class="pull-left">
                            <div class="form-group" style="width: 400px;">
                                <select id="nr_search" name="nr_search" class="select2" style="width: 100%" required="" onchange="tabla_calendario();">
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
              Nombre delegado(a):
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
