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
                        /*********** Si hay registros consulta los permisos **********************/
                        $puede_editar = tiene_permiso($segmentos=2,$permiso=4);
                        $puede_consultar = tiene_permiso($segmentos=2,$permiso=1);
                        /*********** Fin de consulta de permisos *********************************/
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
                          }elseif ($fila->estado_audiencia == 2) {
                              echo '<td><span class="label label-success">Con resultado</span></td>';
                          }else{
                              echo '<td><span class="label label-success">Activa</span></td>';
                          }

                          echo "<td>";
                          $array = array($fila->id_fechasaudienciasci, $fila->fecha_fechasaudienciasci,
                          $fila->hora_fechasaudienciasci, $fila->id_expedienteci, $fila->estado_audiencia, $fila->numero_fechasaudienciasci,
                          $fila->id_defensorlegal,$fila->id_representaci,$fila->id_delegado);

                          $resultado = array($fila->id_expedienteci, $fila->id_fechasaudienciasci, ((isset($id_sindicato)) ? $id_sindicato:false) );

                          $actas = array($fila->id_expedienteci,$fila->cuenta,$fila->tipo_pago,$fila->asistieron,$fila->estado,$fila->id_fechasaudienciasci,$fila->resultado,$fila->id_representaci,$fila->numero_fechasaudienciasci);

                          switch ($fila->estado_audiencia) {
                            case '1':
                              if($puede_editar){
                                array_push($array, "edit");
                                echo generar_boton($array,"cambiar_editar5","btn-info","fa fa-wrench","Editar");
                              }
                              if($puede_consultar){
                                unset($array[endKey($array)]); //eliminar el ultimo elemento de un array
                                array_push($array, "delete");
                                  echo generar_boton($array,"cambiar_editar5","btn-danger","fa fa-times","Eliminar");
                              }
                              if($puede_editar){
                                array_push($resultado,"");
                                echo generar_boton($resultado,"resolucion","btn-success2","fa fa-check","Resultado");
                              }
                            break;
                            case '2':
                              // if ($tipo==1) {
                                if($puede_editar){
                                  array_push($actas,"");
                                  echo generar_boton($actas,"modal_actas_tipo","btn-success2","fa fa-file","Generar actas");
                                }
                              // }
                              break;
                            default:
                              break;
                          }
                    }
                  }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
