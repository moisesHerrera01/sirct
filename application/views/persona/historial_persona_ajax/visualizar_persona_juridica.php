<?php $row = $empresa->result()[0]; ?>
<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<div class="row">
    <!-- Column -->
    <div class="col-lg-4 col-xlg-3 col-md-5">
        <div class="card">
            <div class="card-body">
                <center class="m-t-30"><img src="<?=base_url().'assets/images/silueta/juridica.png'?>" class="img-circle" width="150">
                    <h4 class="card-title m-t-10"><?=$row->nombre_empresa?></h4>
                    <h6 class="card-subtitle">Expediente: <?php echo $row->numinscripcion_empresa; ?></h6>
                    <?php echo ($row->estado_empresa == 1) ? '<span class="label label-info">Cuenta activa</span>' : '<span class="label label-danger">Cuenta inactiva</span>'; ?>
                </center>
            </div>
            <div><hr></div>
            <div class="card-body"> 
            	<small class="text-muted">NIT: </small>
                <h6><?=$row->nit_empresa?></h6>
                <small class="text-muted">Abreviatura: </small>
                <h6><?=$row->abreviatura_empresa?></h6> 
                <small class="text-muted db">Municipio</small>
                <h6><?=$row->municipio?></h6>
                <small class="text-muted db">Direccion</small>
                <h6 align="justify"><?=$row->direccion_empresa?></h6>
                <small class="text-muted db">Correo electrónico</small>
                <h6><?=$row->correoelectronico_empresa?></h6>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- Column -->
    <div class="col-lg-8 col-xlg-9 col-md-7">
        <div class="card">
            <!-- Nav tabs -->
            
            <ul class="nav nav-tabs profile-tab" role="tablist">
            	<li class="nav-item bg-secondary"> <a class="nav-link show text-white" onclick="cerrar_mantenimiento();" data-toggle="tab" href="#!" role="tab" aria-selected="true"><span class="mdi mdi-keyboard-backspace"></span> Volver</a> </li>
                <li class="nav-item"> <a class="nav-link show" data-toggle="tab" href="#home" role="tab" aria-selected="true">Lista de expedientes</a> </li>
            </ul>
           <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active show" id="home" role="tabpanel">
                    <div class="card-body">


                        <div class="profiletimeline">
                        	<div align="center">
	                        	<a href="javascript:void(0)" onclick="redireccionar_despido_hecho(2,'<?=$row->id_empresa?>','save');" class="m-t-10 m-r-20 waves-effect waves-dark btn btn-success btn-rounded">Concialiatorio por despido <br>de hecho o injustificado</a>
	                            <a href="javascript:void(0)" onclick="redireccionar_diferencia_laboral(2,'<?=$row->id_empresa?>','save');" class="m-t-10 waves-effect waves-dark btn btn-info btn-rounded">Conciliatorio por <br>diferencia laboral</a>
	                        </div>
                            <br><br>
                        	<?php

								$this->db->select('e.id_expedienteci AS id_expedienteciem, e.*, ep.*, p.*, es.*, em.*')
								->from('sge_empresa em')
								->join('sct_expedienteci e','em.id_empresa = e.id_empresaci')
								->join('sir_empleado ep','ep.id_empleado=e.id_personal')
								->join('sct_personaci p','p.id_personaci=e.id_personaci')
								->join('sct_estadosci es','e.id_estadosci=es.id_estadosci')
								->where('em.id_empresa', $row->id_empresa);

								$solicitudes = $this->db->get();

	                        	if($solicitudes->num_rows() > 0){
	                        		foreach ($solicitudes->result() as $fila) {
                            ?>
                            	<div class="sl-item m-b-10">
                                    <?php if($fila->motivo_expedienteci == '1'){ ?>
                                      <div class="sl-left"> <span class="round bg-success">DHI</span> </div>
                                    <?php }else{ ?>
                                        <div class="sl-left"> <span class="round bg-info">DL</span> </div>
                                    <?php } ?>
	                                <div class="sl-right">
	                                    <div>
	                                    	<div style="margin-bottom: 10px;">
		                                    	<a href="#" class="link"><?=$fila->numerocaso_expedienteci?></a> <span class="sl-date"><?=$fila->fechacrea_expedienteci?></span>
		                                    </div>
		                                    <div class="row">
		                                    	<div class="col-lg-6"><p class="m-b-0"><b>Solicitado:</b> <?php echo implode(" ", array($fila->nombre_personaci, $fila->apellido_personaci)); ?></p></div>
		                                    	<div class="col-lg-6"><p class="m-b-0"><b>Delegado/a:</b> <?php echo implode(" ", array($fila->primer_nombre, $fila->segundo_nombre, $fila->tercer_nombre, $fila->primer_apellido, $fila->segundo_apellido, $fila->apellido_casada)); ?></p></div>
		                                    </div>
	                                        <div class="row">
		                                    	<div class="col-lg-6"><p class="m-b-0"><b>Motivo:</b> <?=$fila->motivo_expedienteci?></p></div>
		                                    	<div class="col-lg-6"><p class="m-b-0"><b>Resultado:</b> <?=$fila->resultado_expedienteci?></p></div>
		                                    </div>
	                                        <br>
	                                        <div class="like-comm">
                                                <?php if($fila->motivo_expedienteci == '1'){ ?>
	                                        	  <a href="javascript:void(0)" onclick="redireccionar_despido_hecho(2,'<?=$fila->id_expedienteciem?>','edit');" class="m-r-10 btn btn-info text-white"> <span class="mdi mdi-wrench"></span> Editar expediente</a>
                                                <?php }else{ ?>
                                                    <a href="javascript:void(0)" onclick="redireccionar_diferencia_laboral(2,'<?=$fila->id_expedienteciem?>','edit');" class="m-r-10 btn btn-info text-white"> <span class="mdi mdi-wrench"></span> Editar expediente</a>
                                                <?php } ?>
	                                        	<a href="javascript:void(0)" class="btn btn-secondary m-r-10"><?=$fila->nombre_estadosci?></a>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <hr>
                            <?php
	                        		}
	                        	}else{
	                        		echo "<blockquote>No posee registro de expediente</blockquote>";
	                        	}
                            ?>
                        </div>
                    </div>
                </div>
              

            </div>
        </div>
    </div>
    <!-- Column -->
</div>