<div class="card">
    <div class="card-header">
        <div class="card-actions">

        </div>
        <h4 class="card-title m-b-0">Listado de representantes</h4>
    </div>
    <div class="card-body b-t"  style="padding-top: 7px;">
    	<div class="pull-right">
          <?php if(tiene_permiso($segmentos=2,$permiso=2)){ ?>
            <button type="button" onclick="cambiar_nuevo2();" class="btn waves-effect waves-light btn-success2"><span class="mdi mdi-plus"></span> Nuevo representante</button>
          <?php } ?>
        </div>
        <div class="table-responsive">
          
            <table id="myTable2" class="table table-bordered product-overview">
                <thead class="bg-info text-white">
                    <tr>
                        <th>Id</th>
                        <th>DUI</th>
                        <th>Nombre del representante</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th style="min-width: 85px;">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                	$id_empresa = $_GET["id_empresa"];
                	$representantes = $this->db->query("SELECT * FROM sge_representante WHERE id_empresa = '".$id_empresa."'");
                  $contador=0;
                    if($representantes->num_rows() > 0){
                        foreach ($representantes->result() as $fila) {
                          $contador++;
                          echo "<tr>";
                          echo "<td>".$contador."</td>";
                          echo "<td>".$fila->dui_representante."</td>";
                          echo "<td>".$fila->nombres_representante."</td>";
                          echo "<td>".$fila->tipo_representante."</td>";
                          echo ($fila->estado_representante == "1") ? '<td><span class="label label-success">Activo</span></td>' : '<td><span class="label label-danger">Inactivo</span></td>';
                          echo "<td>";
                          $array = array($fila->id_representante, $fila->dui_representante, $fila->nombres_representante, $fila->acreditacion_representante, $fila->tipo_representante, $fila->estado_representante);
                           
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
                        }
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
