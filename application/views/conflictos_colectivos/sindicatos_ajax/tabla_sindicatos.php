<div class="table-responsive">
            <table id="myTable" class="table table-hover product-overview" width="100%">
                <thead class="bg-info text-white">
                    <tr>
                      <th width="130px">Número de Expediente</th>
                      <th>Nombre Solicitante</th>
                      <th>Nombre Solicitado</th>
                      <th>Nombre Encargado(a)</th>
                      <th>Resulato de Intervención</th>
                      <th>Estado Actual</th>
                        <th width="150px">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if($sindicatos!=NULL){
                        foreach ($sindicatos->result() as $fila) {
                          echo "<tr>";
                            echo "<td>".$fila->numerocaso_expedienteci."</td>";
                            echo "<td>".$fila->nombre_sindicato."</td>";
                            echo "<td>".$fila->nombre_empresa."</td>";
                            echo "<td>".$fila->delegado."</td>";
                            if ($fila->resultado_expedienteci==NULL) {
                              $fila->resultado_expedienteci="Sin Intervenir";
                            }
                            echo "<td>".$fila->resultado_expedienteci."</td>";

                            if($fila->id_estadosci == 0){
                                echo '<td><span class="label label-danger">INCOMPLETA</span></td>';
                            }else if($fila->id_estadosci == 1){
                                echo '<td><span class="label label-success">ESPERANDO AUDIENCIA</span></td>';
                            }else if($fila->id_estadosci == 2){
                                echo '<td><span class="label label-success">CON RESULTADO</span></td>';
                            }else if($fila->id_estadosci == 3){
                                echo '<td><span class="label label-success">ARCHIVADO</span></td>';
                            }else if($fila->id_estadosci == 4){
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
                                      <a class="dropdown-item" href="javascript:;" onClick="visualizar(<?=$fila->id_expedienteci.','.$fila->id_empresa?>)">Visualizar</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="audiencias(<?=$fila->id_expedienteci?>)">Gestionar audiencias</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="pagos(<?=$fila->id_expedienteci?>)">Gestionar pagos</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="modal_delegado(<?=$fila->id_expedienteci.','.$fila->id_personal?>)">Cambiar delegado</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="modal_actas_tipo(<?=$fila->id_expedienteci?>)">Generar acta por tipo</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="resolucion(<?=$fila->id_expedienteci?>)">Registrar resolución</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="modal_estado(<?=$fila->id_expedienteci.','.$fila->id_estadosci?>)">Cambiar estado</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="adjuntar_actas(<?=$fila->id_expedienteci?>)">Gestionar actas</a>
                                      <?php
                                          if ($fila->id_estadosci == "1") {
                                      ?>
                                              <a class="dropdown-item" href="javascript:;" onClick="inhabilitar(<?=$fila->id_expedienteci?>)">Inhabilitar Expediente</a>
                                      <?php
                                          } else {
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
