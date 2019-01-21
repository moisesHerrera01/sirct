
<div class="card">
    <div class="card-header">
        <div class="card-actions">

        </div>
        <h4 class="card-title m-b-0">Listado de personas directivas</h4>
    </div>
    <div class="card-body b-t"  style="padding-top: 7px;">
    	<div class="pull-right">
          <?php if(tiene_permiso($segmentos=2,$permiso=2)){ ?>
            <button type="button" onclick="cambiar_nuevo2(<?= $sindicato ?>);" class="btn waves-effect waves-light btn-success2"><span class="mdi mdi-plus"></span> Nuevo registro</button>
          <?php } ?>
        </div>
          <div class="table-responsive">
            <table id="myTable2" class="table table-hover product-overview" width="100%">
                <thead class="bg-info text-white">
                    <tr>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Tipo</th>
                      <th>Acreditación</th>
                        <th width="150px">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if($directivos!=NULL){
                        /*********** Si hay registros consulta los permisos **********************/
                        $puede_editar = tiene_permiso($segmentos=2,$permiso=4);
                        /*********** Fin de consulta de permisos *********************************/
                        foreach ($directivos->result() as $fila) {
                          echo "<tr>";
                            echo "<td>".$fila->nombre_directivo."</td>";
                            echo "<td>".$fila->apellido_directivo."</td>";
                            echo "<td>".$fila->tipo_directivo."</td>";
                            echo "<td>".$fila->acreditacion_directivo."</td>";
                            echo "<td>";

                            $array = array($fila->id_directivo);
                            if($puede_editar){
                                array_push($array, "edit");
                                echo generar_boton($array,"cambiar_editar2","btn-info","fa fa-wrench","Editar");
                            }
                            if($fila->estado_directivo == "1"){
                                echo generar_boton($array,"desactivar","btn-danger","fa fa-chevron-down","Dar de baja");
                            }else{
                                echo generar_boton($array,"activar","btn-success","fa fa-chevron-up","Activar");
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
        $('#myTable2').DataTable();
    });
</script>
