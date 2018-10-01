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
                                              e.tiposolicitud_expedienteci AS tipo,
                                              es.nombre_estadosci AS nombre_estado,
                                              e.resultado_expedienteci AS resultado,
                                              e.fechacrea_expedienteci AS fecha,
                                              p.nombre_personaci,
                                              p.apellido_personaci,
                                              p.dui_personaci AS dui,
                                              p.id_personaci,
                                              p.id_municipio,
                                              p.telefono_personaci,
                                              p.direccion_personaci,
                                              p.fnacimiento_personaci AS nacimiento,
                                              p.sexo_personaci,
                                              p.estudios_personaci AS estudios,
                                              p.nacionalidad_personaci AS nacionalidad,
                                              p.discapacidad_personaci,
                                              em.nombre_empresa,
                                              e.id_expedienteci,
                                              es.id_estadosci AS estado
                                              FROM sct_estadosci AS es
                                              JOIN sct_expedienteci AS e ON es.id_estadosci = e.id_estadosci
                                              JOIN sct_personaci p ON p.id_personaci=e.id_personaci
                                              JOIN sge_empresa em ON em.id_empresa=e.id_empresaci
                                              /*JOIN lista_empleados_estado l on l.id_empleado=e.id_personal*/
                                              JOIN sir_empleado l on l.id_empleado=e.id_personal
                                              ".$add." AND tiposolicitud_expedienteci = 'conciliacion juridica' ORDER BY e.id_expedienteci DESC");

                    if($solicitudes->num_rows() > 0){
                        foreach ($solicitudes->result() as $fila) {
                          echo "<tr>";
                            echo "<td>".$fila->numero."</td>";
                            echo "<td>".$fila->nombre_empresa."</td>";
                            echo "<td>".$fila->nombre_personaci."</td>";
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
                            $array = array($fila->id_empresaci, $fila->id_personaci, $fila->nombre_personaci, $fila->apellido_personaci, $fila->sexo_personaci, $fila->direccion_personaci, $fila->discapacidad_personaci, $fila->telefono_personaci, $fila->id_municipio, $fila->ocupacion, $fila->salario_personaci, $fila->horarios_personaci, $fila->id_expedienteci, $fila->motivo_expedienteci, $fila->descripmotivo_expedienteci, $fila->id_personal);
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
                                      <a class="dropdown-item" href="javascript:;" onClick="visualizar(<?=$fila->id_expedienteci.','.$fila->id_empresaci.','.$fila->id_personaci?>)">Visualizar</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="modal_delegado(<?=$fila->id_expedienteci.','.$fila->id_personal?>)"">Cambiar Delegado</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="audiencias(<?=$fila->id_expedienteci?>)">Gestionar audiencias</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="modal_estado(<?=$fila->id_expedienteci.','.$fila->id_estadosci?>)">Cambiar estado</a>
                                      <a class="dropdown-item" href="javascript:;" onClick="adjuntar_actas(<?=$fila->id_expedienteci?>)">Gestionar actas</a>
                                      <a class="dropdown-item" href="<?=base_url('index.php/resolucion_conflictos/solicitud_juridica/emitir_ficha/'.$fila->id_expedienteci.'/')?>">Emitir Ficha</a>
                                      <?php if ($fila->id_estadosci == "1") { ?>
                                            <a class="dropdown-item" href="javascript:;" onClick="inhabilitar(<?=$fila->id_expedienteci?>)">Inhabilitar Expediente</a>
                                      <?php } else { ?>
                                          	<a class="dropdown-item" href="javascript:;" onClick="habilitar(<?=$fila->id_expedienteci?>)">Habilitar Expediente</a>
                                      <?php } ?>
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
