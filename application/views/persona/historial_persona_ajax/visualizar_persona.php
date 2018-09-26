<?php $row = $personaci->result()[0]; ?>
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
                <center class="m-t-30"><?php if($row->sexo_personaci == 'M'){ ?> <img src="<?=base_url().'assets/images/silueta/man.jpg'?>" class="img-circle" width="150"><?php }else{ ?> <img src="<?=base_url().'assets/images/silueta/women.jpg'?>" class="img-circle" width="150"> <?php } ?>
                    <h4 class="card-title m-t-10"><?=$row->nombre_personaci." ".$row->apellido_personaci?></h4>
                    <h6 class="card-subtitle"><?php echo (empty($row->conocido_por)) ? '<br>' : 'Persona conocida por: '.$row->conocido_por; ?></h6>
                    <?php echo (empty($row->estado_persona)) ? '<span class="label label-info">Cuenta activa</span>' : '<span class="label label-danger">Cuenta inactiva</span>'; ?>
                    
                    <div class="row" align="left">
                        <div class="col-6"><a href="javascript:void(0)" class="link"><i class="icon-people"></i> <font class="font-medium">Edad: <?=calcular_edad($row->fnacimiento_personaci)?> años<br>Sexo: <?php echo ($row->sexo_personaci == 'M') ? 'Masculino' : 'Femenino'; ?></font></a></div>
                        <div class="col-6"><a href="javascript:void(0)" class="link"><i class="icon-picture"></i> <font class="font-medium"><br>
                        	<?php if($row->pertenece_lgbt){ ?>LGTBI <img src="<?=base_url()."assets/images/silueta/lgtbi.png"?>" style="max-height: 15px;"><?php } ?></font></a></div>
                    </div>
                    <div class="row" align="left">
                        <div class="col-6"><a href="javascript:void(0)" class="link"><i class="icon-people"></i> <font class="font-medium"><?php echo ($row->discapacidad_personaci) ? 'Discapacitado <span class="fa fa-wheelchair"></span>' : ''; ?></font></a></div>
                    </div>
                </center>
            </div>
            <div><hr></div>
            <div class="card-body"> 
            	<small class="text-muted"><?=$row->doc_identidad?> </small>
                <h6><?=$row->dui_personaci?></h6> 
                <small class="text-muted db">Nacionalidad</small>
                <h6><?=$row->nacionalidad?></h6>
                <small class="text-muted db">Fecha de nacimiento</small>
                <h6><?=date("d/m/Y",strtotime($row->nacionalidad))?></h6>
                <small class="text-muted db">Municipio</small>
                <h6><?=$row->municipio?></h6>
                <small class="text-muted db">Direccion</small>
                <h6><?=$row->direccion_personaci?></h6>
                <small class="text-muted db">Teléfono(s)</small>
                <h6><?=implode(', ', array($row->telefono_personaci, $row->telefono2_personaci))?></h6>
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

                        	<?php
                        	$solicitudes = $this->db->query("SELECT e.*,
                              	e.numerocaso_expedienteci AS numero,
                              	ep.nombre_empresa,
                              	e.id_empresaci,
                              	e.tiposolicitud_expedienteci AS tipo,
                              	e.resultado_expedienteci AS resultado,
                              	e.fechacrea_expedienteci AS fecha,
                              	e.tipocociliacion_expedienteci AS tipo_conciliacion,
                              	p.nombre_personaci,
                              	p.apellido_personaci,
                              	p.id_personaci,
                              	p.posee_representante,
                              	e.id_expedienteci,
                              	es.id_estadosci AS estado,
                              	(select count(*) from sct_fechasaudienciasci f where f.id_expedienteci=e.id_expedienteci) AS cuenta
                              	FROM sct_estadosci AS es
                              	JOIN sct_expedienteci AS e ON es.id_estadosci = e.id_estadosci
                              	JOIN sct_personaci p ON p.id_personaci=e.id_personaci
                              	JOIN sge_empleador em ON em.id_empleador=p.id_empleador
                              	JOIN sge_empresa ep ON ep.id_empresa=e.id_empresaci
                              	JOIN sir_empleado l on l.id_empleado=e.id_personal
                              	ORDER BY e.id_expedienteci DESC");
	                        	if($solicitudes->num_rows() > 0){
	                        		foreach ($solicitudes->result() as $fila) {
                            ?>
                            	<div class="sl-item">
	                                <div class="sl-left"> <span class="round">PN</span> </div>
	                                <div class="sl-right">
	                                    <div><a href="#" class="link"><?=$fila->numero?></a> <span class="sl-date"><?=$fila->fecha?></span>
	                                        <p>assign a new task <a href="#"> Design weblayout</a></p>
	                                        <div class="like-comm"> <a href="javascript:void(0)" class="link m-r-10">2 comment</a> <a href="javascript:void(0)" class="link m-r-10"><i class="fa fa-heart text-danger"></i> 5 Love</a> </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <hr>
                            <?php
	                        		}
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