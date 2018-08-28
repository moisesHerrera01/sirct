<div class="card">
    <div class="card-header">
        <h4 class="card-title m-b-0">Listado de estados</h4>
    </div>
    <div class="card-body b-t" style="padding-top: 7px;">
        <div class="pull-right">
            <?php
            if(tiene_permiso($segmentos=2,$permiso=2)){
            ?>
            <button type="button" onclick="cambiar_nuevo();" class="btn waves-effect waves-light btn-success2" data-toggle="tooltip" title="Clic para agregar un nuevo registro"><span class="mdi mdi-plus"></span> Nuevo registro</button>
            <?php } ?>
        </div>
        <div class="table-responsive">
            <table id="myTable" class="table table-hover product-overview">
                <thead class="bg-info text-white">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php

                    $estados = $this->db->query("SELECT * FROM sct_estadosci ORDER BY estado DESC");
                    $correlativo = 0;

                    if($estados->num_rows() > 0){
                        foreach ($estados->result() as $fila) {
                            $correlativo++;
                          echo "<tr>";
                            echo "<td>".$correlativo."</td>";
                            echo "<td>".$fila->nombre_estadosci."</td>";
                            echo "<td>".$fila->descripcion_estadosci."</td>";
                            echo ($fila->estado == "1") ? '<td><span class="label label-success">Activo</span></td>' : '<td><span class="label label-danger">Inactivo</span></td>';
                            echo "<td>";
                            $array = array($fila->id_estadosci,$fila->nombre_estadosci, $fila->descripcion_estadosci, $fila->estado);
                                if(tiene_permiso($segmentos=2,$permiso=4)){
                                    array_push($array, "edit");
                                    echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                                }
                                if(tiene_permiso($segmentos=2,$permiso=3)){
                                    unset($array[endKey($array)]); //eliminar el ultimo elemento de un array
                                    array_push($array, "delete");
                                    if($fila->estado == "1"){
                                        echo generar_boton($array,"cambiar_editar","btn-danger","fa fa-chevron-down","Dar de baja");
                                    }else{
                                        echo generar_boton($array,"cambiar_editar","btn-success","fa fa-chevron-up","Activar");
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
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
