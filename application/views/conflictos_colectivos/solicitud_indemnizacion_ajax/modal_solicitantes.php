
<script>
    function combo_doc_identidad(seleccion){

      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/combo_tipo_doc",
        type: "post",
        dataType: "html",
        data: {id : seleccion}
      })
      .done(function(res){
        $('#div_combo_tipo_doc').html(res);
        $(".select2").select2();
      });
    }

    function combo_nacionalidades(seleccion){

      $.ajax({
        url: "<?php echo site_url(); ?>/resolucion_conflictos/solicitudes/combo_nacionalidades",
        type: "post",
        dataType: "html",
        data: {id : seleccion}
      })
      .done(function(res){
        $('#div_combo_nacionalidad').html(res);
        $(".select2").select2();
      });
    }
</script>

<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<div class="modal fade" id="modal_solicitante" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Solicitante</h4>
            </div>

            <div class="modal-body" id="">
                <?php echo form_open('', array('id' => 'formajax4', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                <div class="row">
                    <input type="hidden" id="id_expediente3" name="id_expediente" value="<?=$id?>">
                    <input type="hidden" name="id_persona" id="id_persona1">
                    <input type="hidden" id="band4" name="band4" value="save">

                    <span class="etiqueta">Datos del Solicitante</span>
                    <blockquote class="m-t-0">

                        <div class="row">

                            <div class="form-group col-lg-6" style="height: 83px;">
                                <h5>Nombres: <span class="text-danger">*</span></h5>
                                <input type="text" id="nombre_solicitante" name="nombre_solicitante" class="form-control"
                                    placeholder="Nombre Persona del Solicitante" required>
                                <div class="help-block"></div>
                            </div>

                            <div class="form-group col-lg-6" style="height: 83px;">
                                <h5>Apellidos: <span class="text-danger">*</span></h5>
                                <input type="text" id="apellido_solicitante" name="apellido_solicitante" class="form-control"
                                    placeholder="Apellido Persona del Solicitante" required>
                                <div class="help-block"></div>
                            </div>

                        </div>

                        <div class="row">
                          <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                              <h5>Conocido por: </h5>
                              <input type="text" id="conocido_por" name="conocido_por" class="form-control" placeholder="Conocido por">
                              <div class="help-block"></div>
                          </div>

                            <div class="form-group col-lg-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <h5>Fecha de nacimiento: <span class="text-danger">*</span></h5>
                                <input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="dd/mm/yyyy" readonly="">
                                <div class="help-block"></div>
                            </div>

                            <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_tipo_doc"></div>

                        </div>

                        <div id="partida_div" style="display: none;">
                          <div class="row">
                            <div class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <h5>Número:</h5>
                                <div class="controls">
                                    <input type="text" placeholder="N° partida nacimiento" id="numero_partida" name="numero_partida" class="form-control">
                                </div>
                            </div>

                            <div class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <h5>Folio:</h5>
                                <div class="controls">
                                    <input type="text" placeholder="Folio partida nacimiento" id="folio_partida" name="folio_partida" class="form-control">
                                </div>
                            </div>

                            <div class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <h5>Libro: <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" placeholder="Libro partida nacimiento" id="libro_partida" name="libro_partida" class="form-control">
                                </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <h5>Asiento: <span class="text-danger">*</span></h5>
                                <div class="controls">
                                    <input type="text" placeholder="Asiento partida nacimiento" id="asiento_partida" name="asiento_partida" class="form-control">
                                </div>
                            </div>

                            <div class="form-group col-lg-4 col-sm-4 <?php if($navegatorless){ echo " pull-left"; } ?>">
                                <h5>Año: </h5>
                                <div class="controls">
                                    <input type="text" placeholder="Año partida nacimiento" id="anio_partida" name="anio_partida" class="form-control">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div id="div_numero_doc_identidad" class="form-group col-lg-4" style="height: 83px;">
                              <h5>N° documento identidad: <span class="text-danger">*</span></h5>
                              <input data-mask="99999999-9" data-mask-reverse="true" type="text" id="dui" name="dui" class="form-control" placeholder="Documento de identidad" required="">
                              <div class="help-block"></div>
                          </div>

                          <div class="form-group col-lg-4" style="height: 83px;">
                              <h5>Teléfono 1: </h5>
                              <input data-mask="9999-9999" type="text" id="telefono" name="telefono" class="form-control" placeholder="Número de Telefóno">
                              <div class="help-block"></div>
                          </div>

                          <div class="form-group col-lg-4" style="height: 83px;">
                              <h5>Teléfono 2: </h5>
                              <input data-mask="9999-9999" type="text" id="telefono2" name="telefono2" class="form-control" placeholder="Número de Telefóno casa">
                              <div class="help-block"></div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group col-lg-8" style="height: 83px;">
                              <h5>Dirección:</h5>
                              <textarea type="text" id="direccion" name="direccion" class="form-control" placeholder="Dirección completa"></textarea>
                              <div class="help-block"></div>
                          </div>

                            <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                <h5>Municipio: <span class="text-danger">*</span></h5>
                                <select id="municipio_solicitante" name="municipio_solicitante" class="select2" style="width: 100%" required>
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

                            <div class="form-group col-lg-2" style="height: 83px;">
                                <h5>Sexo:</h5>
                                <input name="sexo_solicitante" type="radio" id="masculino" checked="" value="M">
                                <label for="masculino">Masculino</label>
                                <input name="sexo_solicitante" type="radio" id="femenino" value="F">
                                <label for="femenino">Femenino</label>
                                <div class="help-block"></div>
                          </div>

                         <div class="form-group col-lg-2" style="height: 83px;">
                             <h5>LGTBI:</h5>
                             <input name="pertenece_lgbt" type="radio" id="si_lgbt" value='1'>
                             <label for="si_lgbt">Si </label><Br>
                             <input name="pertenece_lgbt" type="radio" id="no_lgbt" checked="" value='0'>
                             <label for="no_lgbt">No</label>
                        <div class="help-block"></div>
                      </div>

                      <div class="form-group col-lg-2" style="height: 83px;">
                          <h5>Discapacidad:</h5>
                          <input name="discapacidad_solicitante" type="radio" id="si" value='1'>
                          <label for="si">Si </label><Br>
                          <input name="discapacidad_solicitante" type="radio" id="no" checked="" value='0'>
                          <label for="no">No</label>
                     <div class="help-block"></div>
                   </div>

                   <div id="ocultar_div" class="form-group col-lg-6" style="height: 83px;">
                       <Br>
                       <textarea type="text" id="discapacidad_desc" name="discapacidad_desc" class="form-control" placeholder="Ingrese la discapacidad"></textarea>
                       <div class="help-block"></div>
                   </div>

                </div>

               <div class="row">
                 <div class="col-lg-4 form-group <?php if($navegatorless){ echo " pull-left "; } ?>" id="div_combo_nacionalidad"></div>

                 <div class="form-group col-lg-4 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                     <h5>Estudios realizados: <span class="text-danger">*</span></h5>
                     <div class="controls">
                       <select id="estudios" name="estudios" class="custom-select col-4" onchange="" required>
                         <option value="">[Seleccione]</option>
                         <option value="Sin estudio">Sin estudio</option>
                         <option value="Educacion Básica">Educacion Básica</option>
                         <option value="Bachillerato">Bachillerato</option>
                         <option value="Universidad">Universidad</option>
                       </select>
                     </div>
                 </div>
                </blockquote>
               </div>

                <div align="right">
                    <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="submit" id="submit2" class="btn btn-info waves-effect text-white">Siguiente</button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_representante_motivo" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Solicitante</h4>
            </div>
            <div class="modal-body" id="">
                <?php echo form_open('', array('id' => 'formajax5', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                <div class="row">
                    <div class="col-lg-12">
                        <input type="hidden" name="band5" id="band5" value="save">
                        <input type="hidden" name="id_persona" id="id_persona2">
                        <input type="hidden" name="id_representante" id="id_representante_solicitante">

                        <span class="etiqueta">Informaci&oacute;n del Solicitante</span>
                        <blockquote class="m-t-0">
                            <div class="row">
                                <div class="form-group col-lg-6" style="height: 83px;">
                                    <h5>Nombres de Representante: <span class="text-danger">*</span></h5>
                                    <input type="text" id="nombre_representacion_solicitante" name="nombre_representacion_solicitante"
                                        class="form-control" placeholder="Nombre de Representante" required>
                                    <div class="help-block"></div>
                                </div>
                                <div class="form-group col-lg-6" style="height: 83px;">
                                    <h5>Apellidos de Representante: <span class="text-danger">*</span></h5>
                                    <input type="text" id="apellido_representacion_solicitante" name="apellido_representacion_solicitante"
                                        class="form-control" placeholder="Apellidos de Representante" required>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6" style="height: 83px;">
                                    <h5>Tipo de Representación: <span class="text-danger">*</span></h5>
                                    <input type="text" id="tipo_representacion_solicitante" name="tipo_representacion_solicitante"
                                        class="form-control" placeholder="Tipo de Representación" required>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </blockquote>

                        <span class="etiqueta">Motivo de la solicitud</span>
                        <blockquote class="m-t-0">
                            <div class="row">
                                <div class="form-group col-lg-6" style="height: 83px;">
                                    <h5>Motivo de la solicitud: <span class="text-danger">*</span></h5>
                                    <div class="controls">
                                        <select id="motivo" name="motivo" class="custom-select col-4" onchange="" required>
                                            <option value="">[Seleccione]</option>
                                            <option value="1">Indemnización</option>
                                            <option value="2">Inasistencia Laboral</option>
                                            <option value="3">Despido Injustificado</option>
                                            <option value="4">Exige indeminización</option>
                                            <option value="5">Insubordinación</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-lg-6" style="height: 83px;">
                                    <?= $ocupacion ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-12" style="height: 83px;">
                                    <h5>Funciones: <span class="text-danger">*</span></h5>
                                    <textarea type="text" id="funciones" name="funciones" class="form-control" placeholder="Funciones"></textarea>
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6" style="height: 83px;">
                                    <h5>Salario($):<span class="text-danger">*</h5>
                                    <input type="number" id="salario" name="salario" class="form-control" placeholder="Salario"
                                        step="0.01">
                                    <div class="help-block"></div>
                                </div>

                                <div class="form-group col-lg-6" style="height: 83px;">
                                    <h5>Forma de pago:<span class="text-danger">*</h5>
                                    <input type="text" id="forma_pago" name="forma_pago" class="form-control" placeholder="Forma de pago">
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-12" style="height: 83px;">
                                    <h5>Horario laboral:<span class="text-danger">*</h5>
                                    <textarea type="text" id="horario" name="horario" class="form-control" placeholder="Horario laboral"></textarea>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </blockquote>
                    </div>
                </div>
                <div align="right">
                    <button type="button" class="btn waves-effect waves-light" onClick="volver_modal()">Volver</button>
                    <button type="submit" id="submit3" class="btn btn-info waves-effect text-white">Guardar</button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_resolucion" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Registrar Resultado del Expediente</h4>
      </div>

      <div class="modal-body" id="">
        <div id="cnt_form6" class="cnt_form">
          <?php echo form_open('', array('id' => 'formajax6', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>

          <input type="hidden" id="id_persona3" name="id_persona" value="">

          <div class="row">
            <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Resoluci&oacute;n de intervenci&oacute;n: <span class="text-danger">*</span></h5>
              <div class="controls">
                <select id="resolucion" name="resolucion" class="form-control" required>
                  <option value="">[Seleccione]</option>
                  <option value="">[Seleccione]</option>
                  <option value="Conciliado">Conciliado</option>
                  <option value="Sin conciliar">Sin conciliar</option>
                  <option value="Inasistencia">Inasistencia</option>
                  <option value="Desistida">Desistida</option>
                  <option value="A multas">A multas</option>
                  <option value="No notificada">No notificada</option>
                  <option value="Reinstalo">Reinstalo</option>
                </select>
              </div>
            </div>
            <div class="form-group col-lg-6 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
              <h5>Tipo de Conciliaci&oacute;n: </h5>
              <div class="controls">
                <select id="tipo_conciliacion" name="tipo_conciliacion" class="form-control">
                  <option value="">[Seleccione]</option>
                  <option value="Pago en el momento">Pago en el momento</option>
                  <option value="Pago diferido">Pago diferido</option>
                </select>
              </div>
            </div>
          </div>

          <div align="right" id="btnadd1">
            <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn waves-effect waves-light btn-success2">
              Guardar <i class="mdi mdi-chevron-right"></i>
            </button>
          </div>
          <?php echo form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    $(function () {
        $("#formajax4").on("submit", function (e) {
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formajax4"));

            $.ajax({
                url: "<?php echo site_url(); ?>/conflictos_colectivos/solicitud_indemnizacion/gestionar_solicitante",
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
                    $('#id_persona1').val(res);
                    $('#id_persona2').val(res);
                    $('#modal_solicitante').modal('hide');
                    $('#modal_representante_motivo').modal('show');
                }
            });
        });
    });

    $(function () {
        $("#formajax5").on("submit", function (e) {
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formajax5"));

            $.ajax({
                url: "<?php echo site_url(); ?>/conflictos_colectivos/solicitud_indemnizacion/gestionar_representante",
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
                    swal({ title: "¡El solicitante se ingreso con exito!", type: "success", showConfirmButton: true });
                    tabla_solicitantes();
                    $('#modal_representante_motivo').modal('hide');
                    $('.modal-backdrop').remove();
                }
            });
        });
    });

    $(function () {
        $("#formajax6").on("submit", function (e) {
            e.preventDefault();
            var f = $(this);
            var formData = new FormData(document.getElementById("formajax6"));

            $.ajax({
                url: "<?php echo site_url(); ?>/conflictos_colectivos/detalle_solicitante/registrar_resultado",
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
                    swal({ title: "¡Registro de resultado con exito!", type: "success", showConfirmButton: true });
                    tabla_solicitantes();
                    $('#modal_resolucion').modal('hide');
                    $('.modal-backdrop').remove();
                }
            });
        });
    });

    function cambiar_nuevo_solicitante() {

        $('#band4').val('save');
        $('#band5').val('save');

        $('#id_persona1').val('');
        $('#id_persona2').val('');
        $('#id_representante_solicitante').val('');

        combo_doc_identidad();
        combo_nacionalidades();
        $('#nombre_solicitante').val('');
        $('#apellido_solicitante').val('');
        $('#dui').val('');
        $('#fecha_nacimiento').val('');
        $('#telefono').val('');
        $('#municipio_solicitante').val('').trigger('change.select2');
        $('#direccion').val('');
        $('#discapacidad_solicitante').val('');
        $('#sexo_solicitante').val('');

        $('#nombre_representacion_solicitante').val('');
        $('#apellido_representacion_solicitante').val('');
        $('#tipo_representacion_solicitante').val('');
        $('#motivo').val('').trigger('change.select2');
        $('#ocupacion').val('').trigger('change.select2');
        $('#funciones').val('');
        $('#salario').val('');
        $('#forma_pago').val('');
        $('#horario').val('');

        $("#modal_solicitante").modal("show");
        $("#ocultar_div").hide();
    }

    function cambiar_editar_solicitante(id_solicitante) {

        $.ajax({
            url: "<?php echo site_url(); ?>/conflictos_colectivos/detalle_solicitante/obtener_solicitantes_json",
            type: "POST",
            data: {
                id: id_solicitante
            }
        })
        .done(function (res) {
            result = JSON.parse(res)[0];

            var fecha = new Date(result.fnacimiento_personaci);

            $('#band4').val('edit');
            $('#band5').val('edit');

            $('#id_expediente3').val(result.id_expedienteci);
            $('#id_persona1').val(result.id_personaci);
            $('#id_persona2').val(result.id_personaci);
            $('#id_representante_solicitante').val(result.id_representantepersonaci);

            $('#nombre_solicitante').val(result.nombre_personaci);
            $('#apellido_solicitante').val(result.apellido_personaci);
            $('#dui').val(result.dui_personaci);
            $('#fecha_nacimiento').val(`${fecha.getDate()}-${fecha.getMonth() + 1}-${fecha.getFullYear()}`);
            $('#telefono').val(result.telefono_personaci);
            $("#municipio_solicitante").val(result.id_municipio.padStart(5,"00000")).trigger('change.select2');
            $('#direccion').val(result.direccion_personaci);
            $('#discapacidad_solicitante').val(result.discapacidad_personaci);
            $('#sexo_solicitante').val(result.sexo_personaci);

            $('#nombre_representacion_solicitante').val(result.nombre_representantepersonaci);
            $('#apellido_representacion_solicitante').val(result.apellido_representantepersonaci);
            $('#tipo_representacion_solicitante').val(result.tipo_representantepersonaci);
            $('#motivo').val(result.tipopeticion_personaci).trigger('change.select2');
            $('#ocupacion').val(result.id_catalogociuo).trigger('change.select2');
            $('#funciones').val(result.funciones_personaci);
            $('#salario').val(result.salario_personaci);
            $('#forma_pago').val(result.formapago_personaci);
            $('#horario').val(result.horarios_personaci);

            $(".modal-header").children("h4").html("Editar Solicitante");
            $("#modal_solicitante").modal("show");

        });

    }

    $(function () {
        $(document).ready(function () {
              $("input[name=discapacidad_solicitante]").click(function(evento){
                    var valor = $(this).val();
                    if(valor == 0){
                        $("#ocultar_div").hide(500);
                    }else{
                        $("#ocultar_div").show(500);
                    }
            });

            $('#fecha_nacimiento').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                endDate: moment().format("DD-MM-YYYY")
            }).datepicker("setDate", new Date());
        });
    });

    function volver_modal() {
        $('#modal_representante_motivo').modal('hide');
        $('#modal_solicitante').modal('show');
        $('#band4').val('edit');
    }

    function desactivar(id_solicitante) {
        swal({
            title: "Confirmar Dar de Baja",
            text: "¿Está seguro que desea dar de baja al solicitante?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-success2",
            confirmButtonText: "Si",
            closeOnConfirm: false
        },
        function () {
            $.ajax({
                url: "<?php echo site_url(); ?>/conflictos_colectivos/detalle_solicitante/bajar_solicitante",
                type: "post",
                dataType: "html",
                data: {
                    id: id_solicitante,
                }
            })
            .done(function (res) {
                if (res == "exito") {
                    tabla_solicitantes();
                    swal({
                        title: "¡Solicitante desactivado exitosamente!",
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

    function activar(id_solicitante) {
        swal({
            title: "Confirmar Activación",
            text: "¿Está seguro que desea activar al solicitante?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-success2",
            confirmButtonText: "Si",
            closeOnConfirm: false
        },
        function () {
            $.ajax({
                url: "<?php echo site_url(); ?>/conflictos_colectivos/detalle_solicitante/activar_solicitante",
                type: "post",
                dataType: "html",
                data: {
                    id: id_solicitante,
                }
            })
            .done(function (res) {
                if (res == "exito") {
                    tabla_solicitantes();
                    swal({
                        title: "¡Solicitante activado exitosamente!",
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

    function resultado(id_solicitante) {
        $('#id_persona3').val(id_solicitante);
        $('#modal_resolucion').modal('show');
    }

    function ocultar(){
      var value = $("#id_doc_identidad").val();
      if (value!=1) {
        $('#dui').mask('', {reverse: true});
        $('#dui').unmask();
        if (value==4) {
          $('#partida_div').show(500);
          $('#div_numero_doc_identidad').hide(500);
          $("#dui").removeAttr("required");
        }else {
          $('#partida_div').hide(500);
          $('#div_numero_doc_identidad').show(500);
          $("#dui").attr("required",'required');
        }
      }else {
         $('#dui').mask('99999999-9', {reverse: true});
         $('#partida_div').hide(500);
         $('#div_numero_doc_identidad').show(500);
         $("#dui").attr("required",'required');
      }
    }
</script>
