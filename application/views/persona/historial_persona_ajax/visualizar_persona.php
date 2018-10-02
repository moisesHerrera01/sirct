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

                          <div align="center">
                        <a href="javascript:void(0)" onclick="redireccionar_despido_hecho(1,'<?=$row->id_personaci?>','save');" class="m-t-10 waves-effect waves-dark btn btn-success btn-rounded">Concialiatorio por despido <br>de hecho o injustificado</a>
                            <a href="javascript:void(0)" onclick="redireccionar_diferencia_laboral(1,'<?=$row->id_personaci?>','save');" class="m-t-10 waves-effect waves-dark btn btn-info btn-rounded">Conciliatorio por <br>diferencia laboral</a>
                            <a href="javascript:void(0)" onclick="redireccionar_retiro_voluntario(1,'<?=$row->id_personaci?>','save');" class="m-t-10 waves-effect waves-dark btn btn-secondary btn-rounded">Noticicación de <br>renuncia voluntaria</a>
                        </div><br>

                        	<?php
                        	$this->db->select('')
                            ->from('sct_expedienteci e')
                            ->join('sct_personaci p ', ' p.id_personaci = e.id_personaci')
                            ->join('org_municipio m','m.id_municipio=p.id_municipio')
                            ->join('sge_empresa em','em.id_empresa = e.id_empresaci')
                            ->join('sir_empleado ep','ep.id_empleado=e.id_personal')
                            ->where('p.id_personaci', $row->id_personaci);
                              	
                            $solicitudes = $this->db->get();
	                        	if($solicitudes->num_rows() > 0){
	                        		foreach ($solicitudes->result() as $fila) {
                            ?>
                            	<div class="sl-item">
	                                <div class="sl-left"> <span class="round">PN</span> </div>
	                                <div class="sl-right">
	                                    <div><a href="#" class="link"><?=$fila->numerocaso_expedienteci?></a> <span class="sl-date"><?=$fila->fechacrea_expedienteci?></span>
	                                        <p>assign a new task <a href="#"> Design weblayout</a></p>
	                                        <div class="like-comm"> <a href="javascript:void(0)" class="link m-r-10">2 comment</a> <a href="javascript:void(0)" class="link m-r-10"><i class="fa fa-heart text-danger"></i> 5 Love</a> </div>
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