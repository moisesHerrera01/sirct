<div class="table-responsive">
            <table id="myTable" class="table table-hover product-overview" width="100%">
                <thead class="bg-info text-white">
                    <tr>
                      <th width="130px">Número de expediente</th>
                      <th>Nombre solicitante</th>
                      <th>Nombre solicitado</th>
                      <th>Nombre encargado(a)</th>
                      <th>Resultado de mediación</th>
                      <th>Estado actual</th>
                        <th width="200px">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if($sindicatos!=NULL){
                        /*********** Si hay registros consulta los permisos **********************/
                        $puede_editar = tiene_permiso($segmentos=2,$permiso=4);
                        $puede_consultar = tiene_permiso($segmentos=2,$permiso=1);
                        /*********** Fin de consulta de permisos *********************************/
                        foreach ($sindicatos->result() as $fila) {
                          echo "<tr>";
                            echo "<td>".$fila->numerocaso_expedienteci."</td>";
                            echo "<td>".$fila->nombre_sindicato."</td>";
                            echo "<td>".$fila->nombre_empresa."</td>";
                            echo "<td>".$fila->nombre_delegado_actual."</td>";
                            if ($fila->resultado_expedienteci==NULL) {
                              $fila->resultado_expedienteci="Sin Intervenir";
                            }
                            echo "<td>".$fila->resultado_expedienteci."</td>";

                            if($fila->id_estadosci == 0){
                                echo '<td><span class="label label-danger">INCOMPLETA</span></td>';
                            }else if($fila->id_estadosci == 1){
                                echo '<td><span class="label label-success">ESPERANDO AUDIENCIA</span></td>';
                            }else if($fila->id_estadosci == 2){
                                echo '<td><span class="label label-info">CON RESULTADO</span></td>';
                            }else if($fila->id_estadosci == 3){
                                echo '<td><span class="label label-danger">ARCHIVADO</span></td>';
                            }else if($fila->id_estadosci == 4){
                                echo '<td><span class="label label-danger">INHABILITADO</span></td>';
                            }

                            echo "<td>";

                            $array = array($fila->id_expedienteci);
                            $array2 = array($fila->id_sindicato);
                            if($puede_editar){
                                array_push($array, "edit");
                                echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                                array_push($array2, "directivos");
                                echo generar_boton($array2,"gestionar_directivos","btn-info","mdi mdi-account-plus","Gestionar");
                            }
                            if($puede_consultar){
                                ?>
                              <div class="btn-group">
                                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                      aria-expanded="false">
                                      <i class="ti-settings"></i>
                                  </button>
                                  <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                      <a class="dropdown-item" href="javascript:;" onClick="visualizar(<?=$fila->id_expedienteci.','.$fila->id_empresa?>)">Visualizar</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="audiencias(<?=$fila->id_empresa.','.$fila->id_expedienteci.',2,'.$fila->id_sindicato?>)">Gestionar audiencias</a>
                                      <!-- <a class="dropdown-item" href="javascript:;" onClick="pagos(<?=$fila->id_expedienteci?>)">Gestionar pagos</a> -->
                                      <a class="dropdown-item" href="javascript:;" onClick="modal_delegado(<?=$fila->id_expedienteci.','.$fila->delegado_actual?>)">Cambiar delegado/a</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="modal_bitacora_delegados(<?=$fila->id_expedienteci?>)">Bitacora de cambios</a>
                                      <a class="dropdown-item" href="<?=base_url('index.php/conflictos_colectivos/acta_colectivos/generar_acta/'.$fila->id_expedienteci.'/')?>" >Emitir Ficha</a>
                                      <?php if (((integer)$fila->cuenta)>=2) {?>
                                      <a class="dropdown-item" href="<?=base_url('index.php/conflictos_colectivos/acta_colectivos/generar_acta_solicitud/'.$fila->id_expedienteci.'/')?>" >Emitir Acta</a>
                                      <?php } ?>
                                      <!-- <a class="dropdown-item" href="javascript:;" onClick="resolucion(<?=$fila->id_expedienteci?>)">Registrar resolución</a> -->
                                      <a class="dropdown-item" href="javascript:;" onClick="modal_estado(<?=$fila->id_expedienteci.','.$fila->id_estadosci?>)">Cambiar estado</a>
                                      <!-- <a class="dropdown-item" href="javascript:;" onClick="adjuntar_actas(<?=$fila->id_expedienteci?>)">Gestionar actas</a> -->
                                      <?php
                                          if ($fila->id_estadosci == "4") {
                                      ?>

                                            <a class="dropdown-item" href="javascript:;" onClick="habilitar(<?=$fila->id_expedienteci?>)">Habilitar Expediente</a>
                                      <?php
                                          } else {

                                      ?>
                                            <a class="dropdown-item" href="javascript:;" onClick="inhabilitar(<?=$fila->id_expedienteci?>)">Inhabilitar Expediente</a>
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
