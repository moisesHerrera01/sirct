<div class="table-responsive">
            <table id="myTable" class="table table-hover product-overview" width="100%">
                <thead class="bg-info text-white">
                    <tr>
                      <th width="130px">Número de Expediente</th>
                      <th>Nombre Solicitante</th>
                      <th>Nombre Solicitado</th>
                      <th>Tipo de Solicitud</th>
                      <th>Estado Actual</th>
                      <th>Resulato de Intervención</th>
                      <th>Fecha de Registro</th>
                        <th width="150px">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    /*$id_estadoci = $_GET["id_estadoci"];

                    $add = "";

                    if(!empty($estado)){
                        $add .= "AND m.id_estadosci = '".$id_estadosci."'";
                    }*/

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

                    /*$solicitudes = $this->db->query("SELECT e.*,
                                              e.numerocaso_expedienteci AS numero,
                                              e.tiposolicitud_expedienteci AS tipo,
                                              es.nombre_estadosci AS nombre_estado,
                                              e.resultado_expedienteci AS resultado,
                                              e.fechacrea_expedienteci AS fecha,
                                              p.nombre_personaci,
                                              em.nombre_empleador,
                                              e.id_expedienteci
                                              FROM sct_estadosci AS es
                                              JOIN sct_expedienteci AS e ON es.id_estadosci = e.id_estadosci
                                              JOIN sct_personaci p ON p.id_personaci=e.id_personaci
                                              JOIN sge_empleador em ON em.id_empleador=p.id_empleador
                                              ".$add." ORDER BY e.id_expedienteci DESC");*/

                    $solicitudes = $this->db->query("SELECT
                                              e.numerocaso_expedienteci AS numero,
                                              e.tiposolicitud_expedienteci AS tipo,
                                              es.nombre_estadosci AS nombre_estado,
                                              e.resultado_expedienteci AS resultado,
                                              e.fechacrea_expedienteci AS fecha,
                                              p.nombre_personaci,
                                              em.nombre_empleador,
                                              e.id_expedienteci
                                              FROM sct_estadosci AS es
                                              JOIN sct_expedienteci AS e ON es.id_estadosci = e.id_estadosci
                                              JOIN sct_personaci p ON p.id_personaci=e.id_personaci
                                              JOIN sge_empleador em ON em.id_empleador=p.id_empleador
                                              ORDER BY e.id_expedienteci DESC");
                    if($solicitudes->num_rows() > 0){
                        foreach ($solicitudes->result() as $fila) {
                          echo "<tr>";
                            echo "<td>".$fila->numero."</td>";
                            echo "<td>".$fila->nombre_personaci."</td>";
                            echo "<td>".$fila->nombre_empleador."</td>";
                            echo "<td>".$fila->tipo."</td>";
                            echo "<td>".$fila->nombre_estado."</td>";
                            echo "<td>".$fila->resultado."</td>";
                            echo "<td>".$fila->fecha."</td>";

                            /*if($fila->estado == 0){
                                echo '<td><span class="label label-danger">Incompleta</span></td>';
                            }else if($fila->estado == 1){
                                echo '<td><span class="label label-success">ESPERANDO AUDIENCIA</span></td>';
                            }else if($fila->estado == 2){
                                echo '<td><span class="label label-danger">CON RESULTADO</span></td>';
                            }else if($fila->estado == 3){
                                echo '<td><span class="label label-success">ARCHIVADO</span></td>';
                            }else if($fila->estado == 4){
                                echo '<td><span class="label label-danger">INHABILITADO</span></td>';
                            }*/

                            echo "<td>";


                            $array = array($fila->id_expedienteci, $fila->numero, date("d-m-Y",strtotime($fila->fecha)),  $fila->nombre_personaci);
                            if(tiene_permiso($segmentos=2,$permiso=4)){
                                array_push($array, "edit");
                                echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                            }
                            if(tiene_permiso($segmentos=2,$permiso=3)){
                                unset($array[endKey($array)]); //eliminar el ultimo elemento de un array
                                array_push($array, "delete");
                                echo generar_boton($array,"cambiar_editar","btn-danger","fa fa-close","Eliminar");
                            }
                            echo generar_boton(array($fila->id_expedienteci),"imprimir_solicitud","btn-default","fa fa-print","Imprimir");
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
