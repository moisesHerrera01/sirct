<div class="table-responsive">
  <!-- <?php var_dump($abreviatura->pre) ?> -->
            <table id="myTable" class="table table-hover product-overview" width="100%">
                <thead class="bg-info text-white">
                    <tr>
                      <th width="130px">Número de expediente</th>
                      <th>Nombre solicitante</th>
                      <th>Nombre solicitado</th>
                      <th>Tipo de solicitud</th>
                      <th>Resultado de mediación</th>
                      <th>Fecha de registro</th>
                      <th>Estado actual</th>
                        <th width="150px">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $nr = $_GET["nr"];
                    $id_estadoci = $_GET["tipo"];

                    $add = "";

                    if(!empty($nr)){
                        $add .= "AND l.nr/*l.nr_empleado*/ = '".$nr."'";
                    }

                    if(!empty($id_estadoci)){
                        $add .= "AND e.id_estadosci = '".$id_estadoci."'";
                    }

                    $solicitudes = $this->db->query("SELECT e.*,
                                              e.numerocaso_expedienteci AS numero,
                                              ep.nombre_empresa,
                                              e.id_empresaci,
                                              CASE
                                                WHEN e.tiposolicitud_expedienteci=1 THEN 'Persona natural'
                                                ELSE e.tiposolicitud_expedienteci END AS tipo,
                                              e.resultado_expedienteci AS resultado,
                                              e.fechacrea_expedienteci AS fecha,
                                              e.tipocociliacion_expedienteci AS tipo_conciliacion,
                                              p.nombre_personaci,
                                              p.apellido_personaci,
                                              p.id_personaci,
                                              p.posee_representante,
                                              e.id_expedienteci,
                                              es.id_estadosci AS estado,
                                              (select count(*) from sct_fechasaudienciasci f where f.id_expedienteci=e.id_expedienteci) AS cuenta,
                                              d.delegado_actual,
                                              (SELECT r.resultadoci
              																 FROM sct_fechasaudienciasci fea
              																 JOIN sct_resultadosci r ON r.id_resultadoci=fea.resultado
              																 WHERE estado_audiencia=2
                                               AND fea.id_expedienteci = e.id_expedienteci
              																 AND fea.id_fechasaudienciasci = (SELECT MAX(fa.id_fechasaudienciasci)
              																																	FROM sct_fechasaudienciasci fa
              																																	WHERE fa.id_expedienteci=fea.id_expedienteci
              																																	AND fa.estado_audiencia=2)) AS resultado
                                              FROM sct_estadosci AS es
                                              JOIN sct_expedienteci AS e ON es.id_estadosci = e.id_estadosci
                                              JOIN sct_personaci p ON p.id_personaci=e.id_personaci
                                              JOIN sge_empresa ep ON ep.id_empresa=e.id_empresaci
                                              JOIN (
                                                    SELECT de.id_expedienteci,de.id_personal delegado_actual
                                                    FROM sct_delegado_exp de
                                                    WHERE de.id_delegado_exp = (SELECT MAX(de2.id_delegado_exp)
                                                                                FROM sct_delegado_exp de2
                                                                                WHERE de2.id_expedienteci=de.id_expedienteci
                                                                               )
                                                  ) d ON d.id_expedienteci=e.id_expedienteci
                                              JOIN sir_empleado l on l.id_empleado=d.delegado_actual
                                              ".$add." WHERE tiposolicitud_expedienteci=1
                                              AND RIGHT(e.numerocaso_expedienteci,2)='".$abreviatura->pre."'
                                              ORDER BY e.id_expedienteci DESC");
                    if($solicitudes->num_rows() > 0){
                        foreach ($solicitudes->result() as $fila) {
                          echo "<tr>";
                            echo "<td>".$fila->numero."</td>";
                            echo "<td>".$fila->nombre_personaci.' '.$fila->apellido_personaci."</td>";
                            echo "<td>".$fila->nombre_empresa."</td>";
                            echo "<td>".$fila->tipo/*.$fila->id_personal.$fila->delegado_actual*/."</td>";
                            if ($fila->resultado==NULL) {
                              $fila->resultado="Sin Intervenir";
                            }
                            echo "<td>".$fila->resultado."</td>";
                            echo "<td>".$fila->fecha."</td>";

                            if($fila->estado == 0){
                                echo '<td><span class="label label-danger">Incompleta</span></td>';
                            }else if($fila->estado == 1){
                                echo '<td><span class="label label-success">ESPERANDO AUDIENCIA</span></td>';
                            }else if($fila->estado == 2){
                                echo '<td><span class="label label-info">CON RESULTADO</span></td>';
                            }else if($fila->estado == 3){
                                echo '<td><span class="label label-danger">ARCHIVADO</span></td>';
                            }else if($fila->estado == 4){
                                echo '<td><span class="label label-danger">INHABILITADO</span></td>';
                            }

                            echo "<td>";

                            $array = array($fila->id_expedienteci);
                            if(tiene_permiso($segmentos=2,$permiso=4)){
                                array_push($array, "edit");
                                echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                            }
                            if(tiene_permiso($segmentos=2,$permiso=1)){
                                ?>
                              <div class="btn-group">
                                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                      aria-expanded="false">
                                      <i class="ti-settings"></i>
                                  </button>
                                  <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                      <a class="dropdown-item" href="javascript:;" onClick="visualizar(<?=$fila->id_expedienteci.','.$fila->id_empresaci?>)">Visualizar</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="audiencias(<?=$fila->id_empresaci.','.$fila->id_expedienteci.',2'?>)">Gestionar audiencias</a>
                                      <!-- <?php if ($fila->id_estadosci=="2") {?>
                                        <a class="dropdown-item" href="javascript:;" onClick="pagos(<?=$fila->id_expedienteci?>)">Gestionar pagos</a>
                                      <?php } ?> -->
                                      <?php if (obtener_rango($segmentos=2, $permiso=1) > 1) { ?>
                                        <a class="dropdown-item" href="javascript:;" onClick="modal_delegado(<?=$fila->id_expedienteci.','.$fila->delegado_actual?>)">Cambiar delegado/a</a>
                                        <?php  } ?>
                                      <a class="dropdown-item" href="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/5/'.$fila->id_expedienteci)?>">Acta de solicitud</a>
                                      <a class="dropdown-item" href="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/6/'.$fila->id_expedienteci)?>">Acta de esquela</a>
                                      <!-- <a class="dropdown-item" href="javascript:;" onClick="resolucion(<?=$fila->id_expedienteci?>)">Registrar resolución</a> -->
                                      <a class="dropdown-item" href="javascript:;" onClick="modal_estado(<?=$fila->id_expedienteci.','.$fila->id_estadosci?>)">Cambiar estado</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="adjuntar_actas(<?=$fila->id_expedienteci?>)">Subir actas escaneadas</a>
                                      <?php
                                          if ($fila->id_estadosci == "1") {
                                      ?>
                                          <a class="dropdown-item" href="javascript:;" onClick="inhabilitar(<?=$fila->id_expedienteci?>)">Inhabilitar Expediente</a>
                                      <?php
                                    } elseif($fila->id_estadosci == "4"){
                                      ?>
                                          <a class="dropdown-item" href="javascript:;" onClick="habilitar(<?=$fila->id_expedienteci?>)">Habilitar Expediente</a>
                                      <?php
                                          }
                                      ?>
                                  </div>
                              </div>
                              <?php
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                ?>
                </tbody>
            </table>
        </div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
