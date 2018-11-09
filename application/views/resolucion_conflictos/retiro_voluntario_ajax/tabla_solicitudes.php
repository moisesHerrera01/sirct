<div class="table-responsive">
            <table id="myTable" class="table table-hover product-overview" width="100%">
                <thead class="bg-info text-white">
                    <tr>
                      <th width="130px">Número de Expediente</th>
                      <th>Nombre Solicitante</th>
                      <th>Nombre Solicitado</th>
                      <th>Tipo de Solicitud</th>
                      <th>Resulato de Intervención</th>
                      <th>Fecha de Registro</th>
                      <th>Estado Actual</th>
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
                                                WHEN e.tiposolicitud_expedienteci=2 THEN 'Renuncia Voluntaria'
                                                ELSE e.tiposolicitud_expedienteci END AS tipo,
                                              e.resultado_expedienteci AS resultado,
                                              e.fechacrea_expedienteci AS fecha,
                                              p.nombre_personaci,
                                              p.apellido_personaci,
                                              p.id_personaci,
                                              e.id_expedienteci,
                                              es.id_estadosci AS estado
                                              FROM sct_estadosci AS es
                                              JOIN sct_expedienteci AS e ON es.id_estadosci = e.id_estadosci
                                              JOIN sct_personaci p ON p.id_personaci=e.id_personaci
                                              JOIN sge_empresa ep ON ep.id_empresa=e.id_empresaci
                                              JOIN sir_empleado l ON l.id_empleado=e.id_personal
                                              ".$add." AND e.tiposolicitud_expedienteci = '2' ORDER BY e.id_expedienteci DESC");

                    if($solicitudes->num_rows() > 0){
                        foreach ($solicitudes->result() as $fila) {
                          echo "<tr>";
                            echo "<td>".$fila->numero."</td>";
                            echo "<td>".$fila->nombre_personaci.' '.$fila->apellido_personaci."</td>";
                            echo "<td>".$fila->nombre_empresa."</td>";
                            echo "<td>".$fila->tipo."</td>";
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
                                echo '<td><span class="label label-success">CON RESULTADO</span></td>';
                            }else if($fila->estado == 3){
                                echo '<td><span class="label label-success">ARCHIVADO</span></td>';
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
                                if ($fila->estado != "4") {
                                    ?>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="ti-settings"></i>
                                        </button>
                                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                            <a class="dropdown-item" href="javascript:;" onClick="visualizar(<?=$fila->id_expedienteci.','.$fila->id_empresaci?>)">Visualizar</a>
                                            <a class="dropdown-item" href="javascript:;" onClick="audiencias(<?=$fila->id_empresaci.','.$fila->id_expedienteci.',2'?>)">Gestionar audiencias</a>
                                            <a class="dropdown-item" href="javascript:;" onClick="modal_delegado(<?=$fila->id_personaci.','.$fila->id_personal?>)">Cambiar delegado</a>
                                            <a class="dropdown-item" href="javascript:;" onClick="modal_estado(<?=$fila->id_expedienteci.','.$fila->id_estadosci?>)">Cambiar estado</a>
                                            <a class="dropdown-item" href="javascript:;" onClick="adjuntar_actas(<?=$fila->id_expedienteci?>)">Gestionar Actas</a>
                                            <a class="dropdown-item" href="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta/'.$fila->id_expedienteci.'/')?>" >Emitir Ficha</a>
                                            <a class="dropdown-item" href="javascript:;" onClick="inhabilitar(<?=$fila->id_expedienteci?>)">Inhabilitar Expediente</a>
                                        </div>
                                    </div>
                                <?php
                                } else {
                                ?>
                                    <button type = "button" class = "btn waves-effect waves-light btn-rounded btn-sm btn-info"
                                    onclick = "habilitar(<?=$fila->id_expedienteci?>)" data-toggle = "tooltip" title = ""
                                    data-original-title = "Habilitar"> <span class = "fa fa-check"></span></button >
                                    &nbsp;
                                    <button type = "button" class = "btn waves-effect waves-light btn-rounded btn-sm btn-info"
                                    onclick = "visualizar(<?=$fila->id_personaci.','.$fila->id_personal?>)" data-toggle = "tooltip" title = ""
                                    data-original-title = "Visualizar"> <span class = "fa fa-file"></span></button >
                            <?php
                                }
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                ?>
                </tbody>
            </table>
        </div>
