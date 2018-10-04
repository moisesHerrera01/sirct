<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_colectivos_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}

	function obtener_centros(){
        $centros = $this->db->query("SELECT * FROM `cdr_centro` ORDER BY nombre ASC");
        return $centros;
    }

    function obtener_centro($data){
        $centros = $this->db->query("SELECT * FROM `cdr_centro` WHERE id_centro = '".$data['id_centro']."' ORDER BY nombre ASC");
        return $centros;
    }

    function obtener_convenios(){
        $centros = $this->db->query("SELECT c.id_convenio, CONCAT(c.nombre, ' (', ce.nickname, ')') AS nombre, ce.id_centro FROM cdr_convenio AS c JOIN cdr_detalle_reserva AS dr ON dr.id_convenio = c.id_convenio JOIN cdr_reserva AS r ON r.id_reserva = dr.id_reserva JOIN cdr_centro AS ce ON ce.id_centro = r.id_centro GROUP BY c.id_convenio, ce.id_centro ORDER BY ce.nickname, c.nombre");
        return $centros;
    }

    function obtener_categoria_visitantes($tipo){
    	if($tipo == "pagado"){
    		$centros = $this->db->query("SELECT * FROM `cdr_categoria` WHERE id_tipo_categoria = '2' AND id_categoria IN (SELECT t.id_categoria_cdr_tarifas FROM cdr_tarifas AS t WHERE t.precio_cdr_tarifas > 0)");
    	}elseif ($tipo == "gratis") {
    		$centros = $this->db->query("SELECT * FROM `cdr_categoria` WHERE id_tipo_categoria = '2' AND id_categoria NOT IN (SELECT t.id_categoria_cdr_tarifas FROM cdr_tarifas AS t WHERE t.precio_cdr_tarifas > 0)");
    	}
        return $centros;
    }

    function obtener_categoria_espacios($tipo){
    	if($tipo == "espacios_fisicos"){
    		$centros = $this->db->query("SELECT * FROM `cdr_categoria` WHERE id_tipo_categoria = '1' ");
    	}elseif ($tipo == "cafeterias") {
    		$centros = $this->db->query("SELECT * FROM `cdr_categoria` WHERE id_tipo_categoria = '5' ");
    	}elseif ($tipo == "estacionamientos") {
    		$centros = $this->db->query("SELECT * FROM `cdr_categoria` WHERE id_tipo_categoria = '3' ");
    	}
        return $centros;
    }

    function obtener_historial_ingresos(){
    	$centro = $this->obtener_centros();
    	$add=""; $contador = 0;
		if($centro->num_rows()>0){
			foreach ($centro->result() as $filas) {
				$contador++;
				$add .= ", SUM(CASE WHEN r.id_centro = '".$filas->id_centro."' THEN dr.monto ELSE 0 END) AS column".$contador;
			}
		}

        $centros = $this->db->query("SELECT MONTH(r.fecha_inicio) AS mes, YEAR(r.fecha_inicio) AS anio$add FROM cdr_detalle_reserva AS dr JOIN cdr_reserva AS r ON r.id_reserva = dr.id_reserva JOIN cdr_centro AS ce ON ce.id_centro = r.id_centro GROUP BY MONTH(r.fecha_inicio),  YEAR(r.fecha_inicio)");
        return $centros;
    }

    function obtener_ingresos_diarios(){
    	$centro = $this->obtener_centros();
    	$add=""; $contador = 0;
		if($centro->num_rows()>0){
			foreach ($centro->result() as $filas) {
				$contador++;
				$add .= ", SUM(CASE WHEN r.id_centro = '".$filas->id_centro."' THEN dr.monto ELSE 0 END) AS column".$contador;
			}
		}

        $centros = $this->db->query("SELECT r.fecha_inicio AS fecha$add FROM cdr_detalle_reserva AS dr JOIN cdr_reserva AS r ON r.id_reserva = dr.id_reserva JOIN cdr_centro AS ce ON ce.id_centro = r.id_centro GROUP BY r.fecha_inicio");
        return $centros;
    }

    function obtener_ingresos_diarios_UFI($data){
		$add = ", SUM(CASE WHEN r.id_seccion_usuario IN (5,6,8,9) THEN dr.monto ELSE 0 END) AS centro, SUM(CASE WHEN r.id_seccion_usuario NOT IN (5,6,8,9) THEN dr.monto ELSE 0 END) AS ufi, SUM(dr.monto)  AS total";

        $centros = $this->db->query("SELECT r.fecha_inicio AS fecha$add FROM cdr_detalle_reserva AS dr JOIN cdr_reserva AS r ON r.id_reserva = dr.id_reserva JOIN cdr_centro AS ce ON ce.id_centro = r.id_centro AND r.id_centro = '".$data['id_centro']."' GROUP BY r.fecha_inicio");
        return $centros;
    }

	function obtener_ingresos_periodo($data, $tipo){
		if($tipo == "normal"){
			$cnt_fem = "dr.cant_femenino";
			$cnt_mas = "dr.cant_masculino";
			$add = '';
			$select = "dr.monto";
			$data["id_centro"] = "AND r.id_centro = '".$data["id_centro"]."'";
		}else if($tipo == "convenios"){
			$select = "0.00";
			$cnt_fem = "dr.cant_femenino_exo_ministra";
			$cnt_mas = "dr.cant_masculino_exo_ministra";
			$add = "AND dr.	id_exoneracion_tipo = '2'";
			$data["id_centro"] = "";
		}else if($tipo == "despacho"){
			$select = "0.00";
			$cnt_fem = "dr.cant_femenino_exo_ministra";
			$cnt_mas = "dr.cant_masculino_exo_ministra";
			$add = "AND dr.	id_exoneracion_tipo = '1'";
			$data["id_centro"] = "";
		}

		if($data["tipo"] == "mensual"){
	 		$centros = $this->db->query("SELECT SUM($select) AS monto, SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$add." ".$data["id_centro"]." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) = '".$data["value"]."'");
	 	}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);
 			$tminicio = $tmfin-2;
	 		$centros = $this->db->query("SELECT SUM($select) AS monto, SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$add." ".$data["id_centro"]." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$tminicio."' AND '".$tmfin."'");

	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);
 			$sminicio = $smfin-5;
	 		$centros = $this->db->query("SELECT SUM($select) AS monto, SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$add." ".$data["id_centro"]." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$sminicio."' AND '".$smfin."'");
	 	}else{
	 		$centros = $this->db->query("SELECT SUM($select) AS monto, SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$add." ".$data["id_centro"]." AND YEAR(r.fecha_inicio) = '".$data["anio"]."'");
	 	}
        
        return $centros;
    }

    function obtener_cantidad_visitante($data, $tipo, $id_categ_visi){
		if($tipo == "normal"){ /* TODOS LOS INGRESOS QUE NO ESTEN EXONERADOS */
			$cnt_fem = "dr.cant_femenino";
			$cnt_mas = "dr.cant_masculino";
			$data['id_centro'] = "AND r.id_centro = '".$data["id_centro"]."'";
		}else if($tipo == "despacho"){ /* EXONERADOS EN CATEGORIAS EXTRAS */
			$cnt_fem = "dr.cant_femenino_exo_ministra";
			$cnt_mas = "dr.cant_masculino_exo_ministra";
			$data['id_centro'] = "AND dr.id_exoneracion_tipo = '1'";
		}else if($tipo == "convenios"){ /* SOLO CONVENIOS */
			$cnt_fem = "dr.cant_femenino_exo_ministra";
			$cnt_mas = "dr.cant_masculino_exo_ministra";
			$data['id_centro'] = "AND dr.id_exoneracion_tipo = '2'";
		}

		$id_categ_visi = "AND dr.id_categoria_espacio IN (".$id_categ_visi.")";

		if($data["tipo"] == "mensual"){
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) = '".$data["value"]."' ".$id_categ_visi);
	 	}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);
 			$tminicio = $tmfin-2;
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$tminicio."' AND '".$tmfin."' ".$id_categ_visi);

	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);
 			$sminicio = $smfin-5;
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$sminicio."' AND '".$smfin."' ".$id_categ_visi);
	 	}else{
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' ".$id_categ_visi);
	 	}
        
        return $centros;
    }

    function obtener_cantidad_visitas_totales($data, $tipo){
		if($tipo == "normal"){ /* TODOS LOS INGRESOS QUE NO ESTEN EXONERADOS */
			$cnt_fem = "dr.cant_femenino";
			$cnt_mas = "dr.cant_masculino";
			$data['id_centro'] = "AND r.id_centro = '".$data["id_centro"]."'";
		}else if($tipo == "despacho"){ /* EXONERADOS EN CATEGORIAS EXTRAS */
			$cnt_fem = "dr.cant_femenino_exo_ministra";
			$cnt_mas = "dr.cant_masculino_exo_ministra";
			$data['id_centro'] = "AND dr.id_exoneracion_tipo = '1'";
		}else if($tipo == "convenios"){ /* SOLO CONVENIOS */
			$cnt_fem = "dr.cant_femenino_exo_ministra";
			$cnt_mas = "dr.cant_masculino_exo_ministra";
			$data['id_centro'] = "AND dr.id_exoneracion_tipo = '2'";
		}

		if($data["tipo"] == "mensual"){
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) = '".$data["value"]."'");
	 	}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);
 			$tminicio = $tmfin-2;
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$tminicio."' AND '".$tmfin."'");

	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);
 			$sminicio = $smfin-5;
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$sminicio."' AND '".$smfin."'");
	 	}else{
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."'");
	 	}
        
        return $centros;
    }

    function obtener_ingresos_actuales($data){
	 	$centros = $this->db->query("SELECT SUM(dr.monto) AS monto FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva AND YEAR(r.fecha_ingreso_reserva) >= '".$data["anio"]."'");
        
        return $centros;
    }

    function obtener_cantidad_visitante_convenio($data, $id_convenio, $id_categ_visi){
		
		$cnt_fem = "dr.cant_femenino_exo_ministra";
		$cnt_mas = "dr.cant_masculino_exo_ministra";
		$data['id_centro'] = "AND r.id_centro = '".$data["id_centro"]."'";

		if($id_categ_visi == "exonerados"){
			$id_categ_visi = "AND dr.id_categoria_espacio IN (SELECT r.id_categoria FROM cdr_reporte_exonerado_titular AS r)";
		}else{
			$id_categ_visi = "AND dr.id_categoria_espacio IN (".$id_categ_visi.")";
		}

		if($data["tipo"] == "mensual"){
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND dr.id_convenio = '".$id_convenio."' AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) = '".$data["value"]."' ".$id_categ_visi);
	 	}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);
 			$tminicio = $tmfin-2;
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND dr.id_convenio = '".$id_convenio."' AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$tminicio."' AND '".$tmfin."' ".$id_categ_visi);

	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);
 			$sminicio = $smfin-5;
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND dr.id_convenio = '".$id_convenio."' AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$sminicio."' AND '".$smfin."' ".$id_categ_visi);
	 	}else{
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND dr.id_convenio = '".$id_convenio."' AND YEAR(r.fecha_inicio) = '".$data["anio"]."' ".$id_categ_visi);
	 	}
        
        return $centros;
    }

    function obtener_cantidad_visitante_despacho($data, $id_categ_visi){
		
		$cnt_fem = "dr.cant_femenino_exo_ministra";
		$cnt_mas = "dr.cant_masculino_exo_ministra";
		$data['id_centro'] = "AND r.id_centro = '".$data["id_centro"]."'";

		if($id_categ_visi == "exonerados"){
			$id_categ_visi = "AND dr.id_categoria_espacio IN (SELECT r.id_categoria FROM cdr_reporte_exonerado_titular AS r)";
		}else{
			$id_categ_visi = "AND dr.id_categoria_espacio IN (".$id_categ_visi.")";
		}

		if($data["tipo"] == "mensual"){
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND dr.id_exoneracion_tipo = '1' AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) = '".$data["value"]."' ".$id_categ_visi);
	 	}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);
 			$tminicio = $tmfin-2;
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND dr.id_exoneracion_tipo = '1' AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$tminicio."' AND '".$tmfin."' ".$id_categ_visi);

	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);
 			$sminicio = $smfin-5;
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND dr.id_exoneracion_tipo = '1' AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$sminicio."' AND '".$smfin."' ".$id_categ_visi);
	 	}else{
	 		$centros = $this->db->query("SELECT SUM($cnt_mas) AS cant_masculino, SUM($cnt_fem) AS cant_femenino FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND dr.id_exoneracion_tipo = '1' AND YEAR(r.fecha_inicio) = '".$data["anio"]."' ".$id_categ_visi);
	 	}
        
        return $centros;
    }


    function obtener_instalacion_uso($data, $tipo, $id_categoria){
		if($tipo == "normal"){ /* TODOS LOS INGRESOS QUE NO ESTEN EXONERADOS */
			$data['id_centro'] = "AND r.id_centro = '".$data["id_centro"]."' AND dr.monto > 0";
			$monto = 'SUM(dr.monto) AS monto';
		}else if($tipo == "despacho"){ /* EXONERADOS EN CATEGORIAS EXTRAS */
			$monto = 'SUM(dr.monto_exonerado) AS monto';
			$data['id_centro'] = "AND dr.id_exoneracion_tipo = '1'";
		}else if($tipo == "convenios"){ /* SOLO CONVENIOS */
			$monto = 'SUM(dr.monto_exonerado) AS monto';
			$data['id_centro'] = "AND dr.id_exoneracion_tipo = '2'";
		}

		if($data["tipo"] == "mensual"){
	 		$centros = $this->db->query("SELECT count(*) AS cantidad, $monto FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) = '".$data["value"]."' AND id_categoria_espacio = '".$id_categoria."'");
	 	}else if($data["tipo"] == "trimestral"){
 			$tmfin = (intval($data["value"])*3);
 			$tminicio = $tmfin-2;
	 		$centros = $this->db->query("SELECT count(*) AS cantidad, $monto FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$tminicio."' AND '".$tmfin."' AND id_categoria_espacio = '".$id_categoria."'");

	 	}else if($data["tipo"] == "semestral"){
 			$smfin = (intval($data["value"])*6);
 			$sminicio = $smfin-5;
	 		$centros = $this->db->query("SELECT count(*) AS cantidad, $monto FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND MONTH(r.fecha_inicio) BETWEEN '".$sminicio."' AND '".$smfin."' AND id_categoria_espacio = '".$id_categoria."'");
	 	}else{
	 		$centros = $this->db->query("SELECT count(*) AS cantidad, $monto FROM `cdr_detalle_reserva` AS dr JOIN `cdr_reserva` AS r ON dr.id_reserva = r.id_reserva ".$data['id_centro']." AND YEAR(r.fecha_inicio) = '".$data["anio"]."' AND id_categoria_espacio = '".$id_categoria."'");
	 	}
        
        return $centros;
    }


}