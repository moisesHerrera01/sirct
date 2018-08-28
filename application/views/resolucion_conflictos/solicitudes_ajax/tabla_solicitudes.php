<div class="table-responsive">
            <table id="myTable" class="table table-hover product-overview" width="100%">
                <thead class="bg-info text-white">
                    <tr>
                      <th style="display: none;">Fecha</th>
                      <th width="130px">Número de Expediente</th>
                      <th>Nombre Solicitante</th>
                      <th>Nombre Solicitado</th>
                      <th>Tipo de Solicitud</th>
                      <th>Estado Actual</th>
                      <th>Resulato de Intervención</th>
                      <th>Fecha de Registro</th>
                        <th>Estado</th>
                        <th width="150px">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $id_estadoci = $_GET["id_estadoci"];

                    $add = "";

                    if(!empty($estado)){
                        $add .= "AND m.id_estadosci = '".$id_estadosci."'";
                    }

                    /*if(!empty($tipo)){
                        if($tipo == "1"){
                            $add .= " AND m.estado = '0'";
                        }else if($tipo == "2"){
                            $add .= " AND (m.estado = '1' || m.estado = '3' || m.estado = '5')";
                        }else if($tipo == "3"){
                            $add .= " AND (m.estado = '2' || m.estado = '4' || m.estado = '6')";
                        }else if($tipo == "4"){
                            $add .= " AND m.estado = '7'";
                        }else{
                            $add .= " AND m.estado = '8'";
                        }
                    }*/

                    $mision = $this->db->query("SELECT e.*,
                                              e.numerocaso_expedienteci AS numero,
                                              e.tiposolicitud_expedienteci AS tipo,
                                              es.nombre_estadosci AS nombre_estado,
                                              e.resultado_expedienteci AS resultado,
                                              e.fechacrea_expedienteci AS fecha,

                                              FROM sct_estadosci AS es
                                              JOIN sct_expedienteci AS e ON es.id_estadosci = e.id_estadosci
                                              JOIN lista_empleados_estado l ON l.id_empleado=e.id_personal
                                              JOIN sct_personaci p ON p.id_personaci=
                                              JOIN sge_empleador em ON em.id_empleador=
                                              ".$add." ORDER BY e.id_expedienteci DESC");
                    if($mision->num_rows() > 0){
                        $contador = 0;
                        foreach ($mision->result() as $fila) {
                            $contador++;
                          echo "<tr>";
                            echo "<td style='display: none;'>".$contador."</td>";

                            if($fila->fecha_solicitud == "0000-00-00 00:00:00"){
                                echo "<td>PENDIENTE</td>";
                            }else{
                                echo "<td>".date("d/m/Y",strtotime($fila->fecha_solicitud))."</td>";
                            }

                            echo "<td>".$fila->nombre_actividad."</td>";
                            echo "<td>".$fila->nombre_completo."</td>";

                            if($fila->estado == 0){
                                echo '<td><span class="label label-danger">Incompleta</span></td>';
                            }else if($fila->estado == 1){
                                echo '<td><span class="label label-success">Revisión 1</span></td>';
                            }else if($fila->estado == 2){
                                echo '<td><span class="label label-danger">Observaciones 1</span></td>';
                            }else if($fila->estado == 3){
                                echo '<td><span class="label label-success">Revisión 2</span></td>';
                            }else if($fila->estado == 4){
                                echo '<td><span class="label label-danger">Observaciones 2</span></td>';
                            }else if($fila->estado == 5){
                                echo '<td><span class="label label-success">Revisión 3</span></td>';
                            }else if($fila->estado == 6){
                                echo '<td><span class="label label-danger">Observaciones 3</span></td>';
                            }else if($fila->estado == 7){
                                echo '<td><span class="label label-success">Aprobada</span></td>';
                            }else if($fila->estado == 8){
                                echo '<td><span class="label label-success">Pagada</span></td>';
                            }

                            echo "<td>";

                            if($fila->ultima_observacion == "0000-00-00 00:00:00"){
                                $fecha_observacion = "falta";
                            }else{
                                $fecha_observacion = date("Y-m-d",strtotime($fila->ultima_observacion));
                            }

                            $array = array($fila->id_mision_oficial, $fila->nr_empleado, date("d-m-Y",strtotime($fila->fecha_mision_inicio)), date("d-m-Y",strtotime($fila->fecha_mision_fin)), $fila->id_actividad_realizada, $fila->detalle_actividad, $fila->estado, $fila->ruta_justificacion, date("Y-m-d",strtotime($fila->fecha_solicitud)), $fecha_observacion, $fila->oficina_solicitante_motorista);
                            if(tiene_permiso($segmentos=2,$permiso=4)){
                                array_push($array, "edit");
                                echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                            }
                            if(tiene_permiso($segmentos=2,$permiso=3)){
                                unset($array[endKey($array)]); //eliminar el ultimo elemento de un array
                                array_push($array, "delete");
                                echo generar_boton($array,"cambiar_editar","btn-danger","fa fa-close","Eliminar");
                            }
                            echo generar_boton(array($fila->id_mision_oficial),"imprimir_solicitud","btn-default","fa fa-print","Imprimir");
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
