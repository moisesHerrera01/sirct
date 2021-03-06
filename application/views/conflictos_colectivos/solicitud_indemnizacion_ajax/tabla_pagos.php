<div class="card">
    <div class="card-header">
        <div class="card-actions">
        </div>
        <h4 class="card-title m-b-0">Listado de pagos</h4>
    </div>
    <div class="card-body b-t"  style="padding-top: 7px;">

        <div class="table-responsive">
              <table id="myTable3" class="table table-hover product-overview" width="100%">
                <thead class="bg-info text-white">
                    <tr>
                        <th>N&uacute;mero</th>
                        <th>Fecha de pago</th>
                        <th>Monto de pago</th>
                        <th style="min-width: 85px;">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                  $contador=0;
                    if($pago!=FALSE){
                        /*********** Si hay registros consulta los permisos **********************/
                        $puede_editar = tiene_permiso($segmentos=2,$permiso=4);
                        $puede_eliminar = tiene_permiso($segmentos=2,$permiso=3);
                        /*********** Fin de consulta de permisos *********************************/
                        foreach ($pago->result() as $fila) {
                            $contador++;
                            echo "<tr>";
                            echo "<td>".$contador."</td>";
                            echo "<td>".date("d-M-Y g:i a", strtotime($fila->fechapago_fechaspagosci))."</td>";
                            echo "<td>".'$'.number_format($fila->montopago_fechaspagosci,2)."</td>";
                            echo "<td>";
                            $array = array($fila->id_fechaspagosci, date("Y-m-d\TH:i", strtotime($fila->fechapago_fechaspagosci)),
                            $fila->montopago_fechaspagosci, $fila->id_persona);

                            if($puede_editar){
                                array_push($array, "edit");
                                echo generar_boton($array,"cambiar_editar6","btn-info","fa fa-wrench","Editar");
                            }

                            if($puede_eliminar){
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
