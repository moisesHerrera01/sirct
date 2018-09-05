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
                        <th style="min-width: 85px;">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                  $contador=0;
                    if($audiencia!=FALSE){
                        foreach ($audiencia->result() as $fila) {
                          $contador++;
                          echo "<tr>";
                          echo "<td>".$contador."</td>";
                          echo "<td>".date("d-M-Y", strtotime($fila->fecha_fechasaudienciasci))."</td>";
                          echo "<td>".date("g:i:s A", strtotime($fila->hora_fechasaudienciasci))."</td>";
                          echo "<td>";
                          /*$array = array($fila->id_representante, $fila->dui_representante, $fila->nombres_representante, $fila->acreditacion_representante, $fila->tipo_representante, $fila->estado_representante);

                          if(tiene_permiso($segmentos=2,$permiso=4)){
                            array_push($array, "edit");
                            echo generar_boton($array,"cambiar_editar2","btn-info","fa fa-wrench","Editar");
                          }

                          if(tiene_permiso($segmentos=2,$permiso=3)){
                            unset($array[endKey($array)]); //eliminar el ultimo elemento de un array
                            array_push($array, "delete");
                            if($fila->estado_representante == "1"){
                                echo generar_boton($array,"cambiar_editar2","btn-danger","fa fa-chevron-down","Dar de baja");
                            }else{
                                echo generar_boton($array,"cambiar_editar2","btn-success","fa fa-chevron-up","Activar");
                            }
                          }
                          echo "</td>";
                          echo "</tr>";
                        */}
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
