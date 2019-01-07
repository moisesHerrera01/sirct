<script type="text/javascript">
	function mostrarReportePorPeriodo(tipo){
		var type = "anual";
		var value = "";
		var value2 = "";
       	anio = $("#anio_actual").val();
        id_delegado = $("#id_delegado").val().toString();
	    if(document.getElementById('radio_mensual').checked==true){
	    	value = $("#mes").val();
	    	type = "mensual";
	    }
       	if(document.getElementById('radio_trimestral').checked==true){
       		value = $("#trimestre").val();
       		type = "trimestral";
       	}
       	if(document.getElementById('radio_semestral').checked==true){
       		value = $("#semestre").val();
       		type = "semestral";
       	}

       	if(document.getElementById('radio_periodo').checked==true){
       		value = $("#fecha_inicio").val();
       		value2 = $("#fecha_fin").val();
       		type = "periodo";
       	}
        
        if(anio!="" &&  (($("#mes").val()!="0" && document.getElementById('radio_mensual').checked==true)	
        	|| ($("#trimestre").val()!="0" && document.getElementById('radio_trimestral').checked==true)	
        	|| ($("#semestre").val()!="0" && document.getElementById('radio_semestral').checked==true)	
        	|| document.getElementById('radio_anual').checked==true
        	|| ($("#fecha_inicio").val()!="" && $("#fecha_fin").val()!="" && document.getElementById('radio_periodo').checked==true)
        )){
          
          	var url = "<?php echo site_url()?>"+"/reportes/reportes_individuales/relaciones_individuales_report";

          	if(document.getElementById('radio_pdf').checked==true && tipo==""){
          		var param = { 'anio' : anio, 'tipo' : type, 'value' : value, 'value2' : value2, 'id_delegado' : id_delegado,'report_type' : 'pdf' };
        		OpenWindowWithPost(url, param, "_blank");
          	}else if(document.getElementById('radio_excel').checked==true && tipo==""){
          		var param = { 'anio' : anio, 'tipo' : type, 'value' : value, 'value2' : value2, 'id_delegado' : id_delegado,'report_type' : 'excel' };
        		OpenWindowWithPost(url, param, "_blank");
          	}else{
          		var param = { 'anio' : anio, 'tipo' : type, 'value' : value, 'value2' : value2, 'id_delegado' : id_delegado,'report_type' : 'html' };
        		embed_html(url, param);
          	}
        }else{
          	swal({ title: "¡Ups! Error", text: "Completa los campos.", type: "error", showConfirmButton: true });
        }
    }

    function embed_html(url, params, id_recibe = "informe_vista"){
	    $.ajax({ url: url, type: "POST", data: params })
	    .done(function(res){ $("#"+id_recibe).html(res); });
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

    function mostrar_ocultar_selects(){
     	if(document.getElementById('radio_mensual').checked==true){
     		document.getElementById("input_anio").style.display="block";
     		document.getElementById("input_mes").style.display="block";
     		document.getElementById("input_semestre").style.display="none";
     		document.getElementById("input_trimestre").style.display="none";
     		document.getElementById("input_periodo").style.display="none";
     	}else if(document.getElementById('radio_trimestral').checked==true){
     		document.getElementById("input_anio").style.display="block";
     		document.getElementById("input_mes").style.display="none";
     		document.getElementById("input_semestre").style.display="none";
     		document.getElementById("input_trimestre").style.display="block";
     		document.getElementById("input_periodo").style.display="none";
     	}else if(document.getElementById('radio_semestral').checked==true){
     		document.getElementById("input_anio").style.display="block";
     		document.getElementById("input_mes").style.display="none";
     		document.getElementById("input_semestre").style.display="block";
     		document.getElementById("input_trimestre").style.display="none";
     		document.getElementById("input_periodo").style.display="none";
     	}else if(document.getElementById('radio_anual').checked==true){
     		document.getElementById("input_anio").style.display="block";
     		document.getElementById("input_mes").style.display="none";
     		document.getElementById("input_semestre").style.display="none";
     		document.getElementById("input_trimestre").style.display="none";
     		document.getElementById("input_periodo").style.display="none";
     	}else if(document.getElementById('radio_periodo').checked==true){
     		document.getElementById("input_anio").style.display="none";
     		document.getElementById("input_mes").style.display="none";
     		document.getElementById("input_semestre").style.display="none";
     		document.getElementById("input_trimestre").style.display="none";
     		document.getElementById("input_periodo").style.display="block";
     	}
    }
     
    function iniciar() {
     	$("#mes").val(moment().format("M")).trigger('change.select2');
    }

    function maximizar(){
    	$("#maximizar").hide(0);
    	$("#minimizar").show(0);

    	$("#cnt_form").hide(0);
    	
    	var preview = $("#cnt_preview");
    	$(preview).removeClass("col-lg-8");
    	$(preview).addClass("col-lg-12");
    }

    function minimizar(){
    	var preview = $("#cnt_preview");
    	$(preview).removeClass("col-lg-12");
    	$(preview).addClass("col-lg-8");

    	$("#maximizar").show(0);
    	$("#minimizar").hide(0);

    	$("#cnt_form").show(0);
    }
</script>
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="align-self-center" align="left" style="width: 100%">
                <h3 class="text-themecolor m-b-0 m-t-0">Reporte de relaciones individuales
                    <div class="pull-right"><small><a href="<?=site_url()?>/reportes/reportes_individuales"><span class="mdi mdi-arrow-left"></span> Volver</a></small></div></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4" style="display: block;" id="cnt_form">
                <div class="card">
                    <div class="card-header bg-success2" id="">
                        <h4 class="card-title m-b-0 text-white">Datos</h4>
                    </div>
                    <div class="card-body b-t">
                        <div class="form-group">
                            <h5>Persona delegada: <span class="text-danger"></span></h5>
                            <select id="id_delegado" name="id_delegado" class="select2" onchange="" style="width: 100%">
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
                        <div class="demo-radio-button">
                        	<h5>Periodo: <span class="text-danger"></span></h5>
                        	<div class="d-flex flex-row">
                                <div class="p-0" style="width: 50%;">
                                    <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_mensual" checked="">
		                            <label for="radio_mensual">Mensual</label>
		                            <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_trimestral">
		                            <label for="radio_trimestral">Trimestral</label>
		                            <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_semestral">
		                            <label for="radio_semestral">Semestral</label>
                                </div>
                                <div class="p-0" style="width: 50%;">
                                	<input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_anual">
		                            <label for="radio_anual">Anual</label>
		                            <input name="group1" type="radio" onchange="mostrar_ocultar_selects()" class="with-gap" id="radio_periodo">
		                            <label for="radio_periodo">Perido</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="input_anio">
                            <h5>Año: <span class="text-danger">*</span></h5>
                            <input type="text" value="<?php echo date('Y'); ?>" class="date-own form-control" id="anio_actual" name="anio_actual" placeholder="yyyy">
                        </div>
                        <div class="form-group" id="input_mes">
                            <h5>Mes: <span class="text-danger"></span></h5>
                            <select id="mes" name="mes" class="select2" onchange="" style="width: 100%" >
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
                        <div class="form-group" id="input_trimestre" style="display:none">
                            <h5>Trimestre: <span class="text-danger"></span></h5>
                            <select id="trimestre" name="trimestre" class="select2" onchange="" style="width: 100%" >
                                <option value="0">[Seleccione]</option>
                                <option class="m-l-50" value="1">1er Trimestre</option>
                                <option class="m-l-50" value="2">2do Trimestre</option>
                                <option class="m-l-50" value="3">3er Trimestre</option>
                                <option class="m-l-50" value="4">4ta Trimestre</option>
                            </select>
                        </div>
                        <div class="form-group" id="input_semestre" style="display:none">
                            <h5>Semestre: <span class="text-danger"></span></h5>
                            <select id="semestre" name="semestre" class="select2" onchange="" style="width: 100%" >
                                <option value="0">[Seleccione]</option>
                                <option class="m-l-50" value="1">1er Semestre</option>
                                <option class="m-l-50" value="2">2do Semestre</option>
                            </select>
                        </div>
                        <div id="input_periodo" style="display:none">
                        	<div class="form-group">
	                            <h5>Inicio periodo: <span class="text-danger">*</span></h5>
	                            <input type="text" class="form-control" readonly id="fecha_inicio" name="fecha_inicio" placeholder="yyyy-mm-dd">
	                        </div>
	                        <div class="form-group">
	                            <h5>Fin periodo: <span class="text-danger">*</span></h5>
	                            <input type="text" class="form-control" readonly id="fecha_fin" name="fecha_fin" placeholder="yyyy-mm-dd">
	                        </div>
                        </div>
                        <div class="form-group" align="right">
                        	<button type="button" onclick="mostrarReportePorPeriodo('vista')" class="btn waves-effect waves-light btn-success2"><i class="mdi mdi-view-dashboard"></i> Vista previa</button>
                    	</div>
                    	<br>
                        <div class="card-body b-t">
                        	<div class="demo-radio-button">
                                <input name="group2" type="radio" id="radio_pdf" checked="">
                                <label for="radio_pdf">PDF</label>
                                <input name="group2" type="radio" id="radio_excel">
                                <label for="radio_excel">EXCEL</label>
                            </div>
                        </div>
                        <div align="right">
                            <button type="button" onclick="mostrarReportePorPeriodo('')" class="btn waves-effect waves-light btn-success2"><i class="mdi mdi-file-pdf"></i> Exportar Reporte</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8" id="cnt_preview" style="display: block;">
                <div class="card">
                    <div class="card-header bg-success2">
                    	<div class="card-actions text-white">
                            <a style="font-size: 16px;" id="maximizar" data-toggle="tooltip" title="Maximizar vista previa" onclick="maximizar();"><i class="mdi mdi-window-maximize"></i></a>
                			<a style="font-size: 16px; display: none;" id="minimizar" data-toggle="tooltip" title="Restaurar vista previa" onclick="minimizar();"><i class="mdi mdi-window-restore"></i></a>
                        </div>
                        <h4 class="card-title m-b-0 text-white">Vista previa</h4>
                    </div>
                    <div class="card-body b-t">
						<div id="informe_vista" style="width: 100%;">
							No se ha solicitado ninguna vista previa...
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	$(document).ready(function(){
        $('.date-own').datepicker({
            minViewMode: 2,
            format: 'yyyy',
            autoclose: true,
            todayHighlight: true
        });

        $('#fecha_inicio').datepicker({
            minViewMode: 0,
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

        $('#fecha_fin').datepicker({
            minViewMode: 0,
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

    });
</script>