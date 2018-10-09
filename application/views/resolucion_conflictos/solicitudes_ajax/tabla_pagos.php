<div class="card">
    <div class="card-header">
        <div class="card-actions">
        </div>
        <h4 class="card-title m-b-0">Listado de audiencias</h4>
    </div>
    <div class="card-body b-t"  style="padding-top: 7px;">

        <div class="table-responsive">
              <table id="myTable3" class="table table-hover product-overview" width="100%">
                <thead class="bg-info text-white">
                    <tr>
                        <th>N&uacute;mero</th>
                        <th>Fecha de pago</th>
                        <th>indemnizaci&oacute;n restante</th>
                        <th>Monto de pago</th>
                        <th style="min-width: 85px;">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                  $contador=0;
                    if($pago!=FALSE){
                        foreach ($pago->result() as $fila) {
                          $contador++;
                          echo "<tr>";
                          echo "<td>".$contador."</td>";
                          echo "<td>".date("d-M-Y g:i a", strtotime($fila->fechapago_fechaspagosci))."</td>";
                          echo "<td>".'$'.number_format($fila->indemnizacion_fechaspagosci,2)."</td>";
                          echo "<td>".'$'.number_format($fila->montopago_fechaspagosci,2)."</td>";
                          echo "<td>";
                          $array = array($fila->id_fechaspagosci, date("Y-m-d\TH:i", strtotime($fila->fechapago_fechaspagosci)),
                          $fila->montopago_fechaspagosci, $fila->id_expedienteci);

                          if(tiene_permiso($segmentos=2,$permiso=4)){
                            array_push($array, "edit");
                            echo generar_boton($array,"cambiar_editar6","btn-info","fa fa-wrench","Editar");
                          }

                          if(tiene_permiso($segmentos=2,$permiso=1)){
                            unset($array[endKey($array)]); //eliminar el ultimo elemento de un array
                            array_push($array, "delete");
                              echo generar_boton($array,"cambiar_editar6","btn-danger","fa fa-times","Eliminar");
                          }
                          echo "</td>";
                          echo "</tr>";
                        }
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
