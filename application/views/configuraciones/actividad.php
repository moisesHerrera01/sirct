<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<script type="text/javascript">
  function cambiar_editar(id_vyp_actividades,nombre_vyp_actividades,depende_vyp_actividades,bandera){
    $("#id_vyp_actividades").val(id_vyp_actividades);
    $("#nombre_vyp_actividades").val(nombre_vyp_actividades);
    $("#depende_vyp_actividades").val(depende_vyp_actividades).trigger('change.select2');
    if(bandera == "edit"){
      $("#ttl_form").removeClass("bg-success");
      $("#ttl_form").addClass("bg-info");
      $("#btnadd").hide(0);
      $("#btnedit").show(0);
      $("#cnt-tabla").hide(0);
      $("#cnt_form").show(0);
      combo_actividades(depende_vyp_actividades);
      $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Editar Actividad");
    }else{           
      eliminar_actividad();
    }
  }

  function editar_actividad(){ $("#band").val("edit"); enviarDatos(); }
   
  function eliminar_actividad(){
    $("#band").val("delete");
    swal({
      title: "¿Está seguro?", text: "¡Desea eliminar el registro!", type: "warning", showCancelButton: true, confirmButtonColor: "#fc4b6c", confirmButtonText: "Sí, deseo eliminar!", closeOnConfirm: false
    }, function(){ enviarDatos(); });
   }

  function cambiar_nuevo(){
    $("#nombre_vyp_actividades").val("");
    $("#depende_vyp_actividades").val("0").trigger('change.select2');
    $("#id_vyp_actividades").val("");
    $("#band").val("save");
    combo_actividades('0');
    $("#ttl_form").addClass("bg-success");
    $("#ttl_form").removeClass("bg-info");

    $("#btnadd").show(0);
    $("#btnedit").hide(0);
    $("#cnt-tabla").hide(0);
    $("#cnt_form").show(0);
    $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> Nueva Actividad");
  }

  function cerrar_mantenimiento(){
    $("#nombre_vyp_actividades").val("");
    $("#depende_vyp_actividades").val("0").trigger('change.select2');
    $("#id_vyp_actividades").val("");
    $("#band").val("save");
    $("#cnt-tabla").show(0);
    $("#cnt_form").hide(0);
  }

  function iniciar(){
    <?php if(tiene_permiso($segmentos=2,$permiso=1)){ ?>
      tablaActividades();
    <?php }else{ ?>
      $("#cnt-tabla").html("Usted no tiene permiso para este formulario.");     
    <?php } ?>
  }

  function objetoAjax(){
    var xmlhttp = false;
    try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) { try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { xmlhttp = false; } }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp = new XMLHttpRequest(); }
    return xmlhttp;
  }

  function tablaActividades(){
    $( "#cnt-tabla" ).load("<?php echo site_url(); ?>/configuraciones/actividad/tabla_actividad/", function() {
      $('#myTable').DataTable();
      $('[data-toggle="tooltip"]').tooltip();
    });
  }

  function enviarDatos(){
    var formData = new FormData(document.getElementById("formajax"));
    formData.append("dato", "valor");

    if($("#id_vyp_actividades").val()==$("#depende_vyp_actividades").val() && $("#band").val()=="edit"){
      swal({ title: "¡Ups! Error", text: "Una actividad no puede depender de sí misma.", type: "error", showConfirmButton: true });
      return;
    }else if($("#nombre_vyp_actividades").val()==""){
      swal({ title: "¡Ups! Error", text: "Una actividad debe tener un nombre.", type: "error", showConfirmButton: true });
      return;
    }else{
      $.ajax({
        url: "<?php echo site_url(); ?>/configuraciones/actividad/gestionar_actividad", type: "post", dataType: "html", data: formData, cache: false, contentType: false, processData: false
      })
      .done(function(res){
        console.log(res);
        if(res == "exito"){
          cerrar_mantenimiento();
          if($("#band").val() == "save"){
            swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
          }else if($("#band").val() == "edit"){
            swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
          }else{
            swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
          }
          tablaActividades();$("#band").val('save');
        }else if(res=="depende"){
          swal({ title: "¡Ups! Error", text: "Actividad tiene dependencias.", type: "error", showConfirmButton: true });
        }else if(res=="duplicado"){
          swal({ title: "¡Ups! Error", text: "Actividad ya existe.", type: "error", showConfirmButton: true });
        }else{
          swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
        }
      });
    }
   }

  function combo_actividades(seleccion){
    id=seleccion;
    if(window.XMLHttpRequest){ xmlhttp=new XMLHttpRequest();
    }else{ xmlhttp=new ActiveXObject("Microsoft.XMLHTTPB"); }
    
    xmlhttp.onreadystatechange=function(){
      if (xmlhttp.readyState==4 && xmlhttp.status==200){
        document.getElementById("div_combo_actividades").innerHTML=xmlhttp.responseText;
        $(".select2").select2();
      }
    }
    xmlhttp.open("GET","<?php echo site_url(); ?>/configuraciones/actividad/mostrarActividad/"+id,true);
    xmlhttp.send();
  }
</script>

<div class="page-wrapper">
   <div class="container-fluid">
       <div class="row page-titles">
           <div class="align-self-center" align="center">
               <h3 class="text-themecolor m-b-0 m-t-0">Gestión de Actividades del MTPS</h3>
           </div>
       </div>
       <div class="row">
           <div class="col-lg-1"></div>
           <div class="col-lg-10" id="cnt_form" style="display: none;">
               <div class="card">
                   <div class="card-header bg-success2" id="ttl_form">
                       <div class="card-actions text-white">
                           <a style="font-size: 16px;" onclick="cerrar_mantenimiento();"><i class="mdi mdi-window-close"></i></a>
                       </div>
                       <h4 class="card-title m-b-0 text-white">Listado de Actividades</h4>
                   </div>
                   <div class="card-body b-t">
                       <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40', 'novalidate' => '')); ?>
                           <input type="hidden" id="band" name="band" value="save">
                           <input type="hidden" id="id_vyp_actividades" name="id_vyp_actividades" >
                           <div class="row">
                               <div class="col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>">
                                   <div class="form-group">
                                       <label for="nombre_oficina" class="font-weight-bold">Nombre de la Actividad: <span class="text-danger">*</span></label>
                                       <input type="text" class="form-control" id="nombre_vyp_actividades" name="nombre_vyp_actividades" required="" placeholder="Nombre de la Actividad" data-validation-required-message="Este campo es requerido">
                                      <div class="help-block"></div>
                                   </div>
                               </div>
                               <div class="col-lg-6 form-group <?php if($navegatorless){ echo "pull-left"; } ?>" id="div_combo_actividades">
                                   <div >
                                       <label for="depende_vyp_actividades" class="font-weight-bold">Depende de la Actividad: <span class="text-danger"></span></label>
                                       <select id="depende_vyp_actividades" name="depende_vyp_actividades" class="form-control" onchange="">
                                           <option value="0">[Seleccione]</option>
                                       </select>
                                      <div class="help-block"></div>
                                   </div>
                               </div>
                           </div>
                           <button id="submit" type="submit" style="display: none;"></button>
                           <div align="right" id="btnadd">
                               <button type="reset" class="btn waves-effect waves-light btn-success"><i class="mdi mdi-recycle"></i> Limpiar</button>
                               <button type="" onclick="enviarDatos()" class="btn waves-effect waves-light btn-success2"><i class="mdi mdi-plus"></i> Guardar</button>
                           </div>
                           <div align="right" id="btnedit" style="display: none;">
                               <button type="reset" class="btn waves-effect waves-light btn-success"><i class="mdi mdi-recycle"></i> Limpiar</button>
                               <button type="button" onclick="editar_actividad()" class="btn waves-effect waves-light btn-info"><i class="mdi mdi-pencil"></i> Editar</button>
                           </div>

                       <?php echo form_close(); ?>
                   </div>

               </div>
           </div>
           <div class="col-lg-1"></div>
               <div class="col-lg-12" id="cnt-tabla">
                 
           </div>

       </div>
   </div>
</div>

<script>

$(function(){
   $("#formajax").on("submit", function(e){
       e.preventDefault();
       var f = $(this);
   });
});

</script>
