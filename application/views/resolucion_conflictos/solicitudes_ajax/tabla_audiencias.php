<div class="card">
    <div class="card-header">
        <div class="card-actions">
        </div>
        <h4 class="card-title m-b-0">Listado de audiencias</h4>
    </div>
    <div class="card-body b-t"  style="padding-top: 7px;">

        <div class="table-responsive">
              <table id="myTable2" class="table table-hover product-overview" width="100%">
                <thead class="bg-info text-white">
                    <tr>
                        <th>N&uacute;mero</th>
                        <th>Fecha de audiencia</th>
                        <th>Hora de audiencia</th>
                        <th>Orden</th>
                        <th>Estado Actual</th>
                        <th style="min-width: 150px;">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                  $contador=0;
                    if($audiencia!=FALSE){
                        foreach ($audiencia->result() as $fila) {
                          $fila->fecha_fechasaudienciasci = date("d-m-Y",strtotime($fila->fecha_fechasaudienciasci));
                          $contador++;
                          echo "<tr>";
                          echo "<td>".$contador."</td>";
                          echo "<td>".date("d-M-Y", strtotime($fila->fecha_fechasaudienciasci))."</td>";
                          echo "<td>".date("g:i:s A", strtotime($fila->hora_fechasaudienciasci))."</td>";
                          if($fila->numero_fechasaudienciasci == 1){
                              echo '<td><span>Primera</span></td>';
                          }else{
                              echo '<td><span>Segunda</span></td>';
                          }
                          if($fila->estado_audiencia == 0){
                              echo '<td><span class="label label-danger">Inactiva</span></td>';
                          }else{
                              echo '<td><span class="label label-success">Activa</span></td>';
                          }

                          echo "<td>";
                          $array = array($fila->id_fechasaudienciasci, $fila->fecha_fechasaudienciasci,
                          $fila->hora_fechasaudienciasci, $fila->id_expedienteci, $fila->estado_audiencia, $fila->numero_fechasaudienciasci,
                          $fila->id_defensorlegal,$fila->id_representaci,$fila->id_delegado);

                          $resultado = array($fila->id_expedienteci, $fila->id_fechasaudienciasci);

                          if ($fila->estado_audiencia) {
                            if(tiene_permiso($segmentos=2,$permiso=4)){
                              array_push($array, "edit");
                              echo generar_boton($array,"cambiar_editar5","btn-info","fa fa-wrench","Editar");
                            }

                            if(tiene_permiso($segmentos=2,$permiso=1)){
                              unset($array[endKey($array)]); //eliminar el ultimo elemento de un array
                              array_push($array, "delete");
                                echo generar_boton($array,"cambiar_editar5","btn-danger","fa fa-times","Eliminar");
                            }
                          }
                          if ($tipo==1) {
                          ?>
                          <div class="btn-group">
                              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                  aria-expanded="false">
                                  <i class="ti-settings"></i>
                              </button>
                              <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                  <a class="dropdown-item" href="javascript:;" onClick="modal_actas_tipo(<?=$fila->id_expedienteci.','.$fila->cuenta.',\''.$fila->tipo_pago.'\','.$fila->asistieron.','.$fila->estado.','.$fila->id_fechasaudienciasci.','.$fila->resultado?>)">Generar acta por tipo</a>
                                  <a class="dropdown-item" href="javascript:;" onClick="resolucion(<?=$fila->id_expedienteci.','.$fila->id_fechasaudienciasci?>)">Registrar resolución</a>
                              </div>
                          </div>
                          <?php
                        }else {
                          if ($fila->estado_audiencia) {
                            if(tiene_permiso($segmentos=2,$permiso=4)){
                              array_push($resultado,"");
                              echo generar_boton($resultado,"resolucion","btn-success2","fa fa-check","Resultado");
                            }
                        }
                          echo "</td>";
                          echo "</tr>";
                        }
                    }
                  }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
