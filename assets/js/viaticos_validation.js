function TEXTO(id,mini,maxi){
	var data = $("#"+id).val();
	var band = false;
	var l = data.length;
	
	if(l < mini){
		band = false;
		CLASE_ERROR(id,"Debe ingresar al menos "+mini+" caracteres");
	}else{
		band = true;
		CLASE_SUCCESS(id);
	}
	if(l > maxi){
		band = false;
		CLASE_ERROR(id,"Excedió el máximo de "+maxi+" caracteres");
	}
	return band;	
}

function COMBO(id){
	var data = $("#"+id).val();
	var band = false;
	if(data == 0 || data == ""){
		CLASE_ERROR(id,"Debes seleccionar una opción");
	}else{
		band = true;
		CLASE_SUCCESS(id);
	}
	return band;	
}

function FECHA(id){
	var data = $("#"+id).val();
	var band = false;
	if(VALIDARFORMATOFECHA(data)){
	      if(EXISTEFECHA(data)){
	            band = true;
				CLASE_SUCCESS(id);
	      }else{
	            band = false;
				CLASE_ERROR(id,"Fecha demasiado antigua");
	      }
	}else{
	      band = false;
			CLASE_ERROR(id,"Formato de fecha incorreto");
	}
}


function EXISTEFECHA(fecha){
    var fechaf = fecha.split("-");
    var day = fechaf[0];
    var month = fechaf[1];
    var year = fechaf[2];
    var date = new Date(year,month,'0');
    if((day-0)>(date.getDate()-0)){
        return false;
    }
    if(year < 2017){
    	return false;
    }
    return true;
}
 
function VALIDARFORMATOFECHA(campo) {
      var RegExPattern = /^\d{2}\-\d{2}\-\d{4}$/;
      if ((campo.match(RegExPattern)) && (campo!='')) {
            return true;
      } else {
            return false;
      }
}

function CLASE_ERROR(id,texto){
	$("#"+id).parent("div").removeClass("t_success");
	$("#"+id).siblings(".text_validate").removeClass("t_successtext");
	$("#"+id).parent("div").addClass("t_error");
	$("#"+id).siblings(".text_validate").addClass("t_errortext");
	$("#"+id).siblings(".text_validate").html("<span class='fa fa-warning'></span> "+texto);
}

function CLASE_SUCCESS(id){
	$("#"+id).parent("div").removeClass("t_error");
	$("#"+id).siblings(".text_validate").removeClass("t_errortext");
	$("#"+id).parent("div").addClass("t_success");
	$("#"+id).siblings(".text_validate").addClass("t_successtext");
	$("#"+id).siblings(".text_validate").html("¡¡¡ Tiene buena pinta !!! <span class='fa fa-thumbs-up'></span>");
}