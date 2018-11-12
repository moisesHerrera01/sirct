
<div class="card">
    <div class="card-header">
        <div class="card-actions">

        </div>
        <h4 class="card-title m-b-0">Listado de solicitantes</h4>
    </div>
    <div class="card-body b-t"  style="padding-top: 7px;">
    	<div class="pull-right">
          <?php if(true){ ?>
            <button type="button" onclick="cambiar_nuevo_solicitante();" class="btn waves-effect waves-light btn-success2"><span class="mdi mdi-plus"></span> Nuevo solicitante</button>
          <?php } ?>
        </div>
        <div class="table-responsive">

            <table id="myTable" class="table table-bordered product-overview">
                <thead class="bg-info text-white">
                    <tr>
                        <th>Id</th>
                        <th>Nombre del Solicitante</th>
                        <th>Sexo</th>
                        <th>Estudios</th>
                        <th>Estado</th>
                        <th style="min-width: 150px;">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $contador=1;
                    if($solicitantes) {
                        if($solicitantes->num_rows() > 0){
                            foreach ($solicitantes->result() as $fila) {
                                echo "<tr>";
                                echo "<td>".$contador."</td>";
                                echo "<td>".$fila->nombre_personaci. " " .$fila->apellido_personaci. "</td>";
                                echo "<td>".$fila->sexo_personaci. "</td>";
                                echo "<td>".$fila->estudios_personaci. "</td>";
                                if ($fila->estado_persona == 1) {
                                    echo "<td>Activo</td>";
                                } else {
                                    echo "<td>Inactivo</td>";
                                }
                                echo "<td>";
                                $array = array($fila->id_personaci);

                                if(true){
                                    array_push($array, "edit");
                                    echo generar_boton($array,"cambiar_editar_solicitante","btn-info","fa fa-wrench","Editar");
                                }

                                array_pop($array);

                                echo ($fila->estado_audiencia == 1) ? generar_boton($array,"resultado","btn-info","mdi mdi-checkbox-marked","Registrar Resultado") : '' ;

                                echo ($fila->estado_audiencia == 2) ? generar_boton($array,"pagos","btn-info","mdi mdi-square-inc-cash","Gestionar Pagos") : '';

                                if($fila->estado_persona == "1"){
                                    echo generar_boton($array,"desactivar","btn-danger","fa fa-chevron-down","Dar de baja");
                                }else{
                                    echo generar_boton($array,"activar","btn-success","fa fa-chevron-up","Activar");
                                }


                                echo "</td>";
                                echo "</tr>";

                                $contador++;
                            }
                        }
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
