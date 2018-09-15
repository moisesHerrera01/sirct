
<div class="card">
    <div class="card-header">
        <div class="card-actions">

        </div>
        <h4 class="card-title m-b-0">Listado de representantes</h4>
    </div>
    <div class="card-body b-t"  style="padding-top: 7px;">
    	<div class="pull-right">
          <?php if(tiene_permiso($segmentos=2,$permiso=2)){ ?>
            <button type="button" onclick="cambiar_nuevo2();" class="btn waves-effect waves-light btn-success2"><span class="mdi mdi-plus"></span> Nuevo directivo</button>
          <?php } ?>
        </div>
          <div class="table-responsive">
            <table id="myTable2" class="table table-hover product-overview" width="100%">
                <thead class="bg-info text-white">
                    <tr>
                      <th>Nombre directivo</th>
                      <th>Apellido directivo</th>
                      <th>Tipo directivo</th>
                      <th>Acreditación directivo</th>
                        <th width="150px">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if($directivos!=NULL){
                        foreach ($directivos->result() as $fila) {
                          echo "<tr>";
                            echo "<td>".$fila->nombre_directivo."</td>";
                            echo "<td>".$fila->apellido_directivo."</td>";
                            echo "<td>".$fila->tipo_directivo."</td>";
                            echo "<td>".$fila->acreditacion_directivo."</td>";
                            echo "<td>";

                            $array = array($fila->id_directivo);
                            if(tiene_permiso($segmentos=2,$permiso=4)){
                                array_push($array, "edit");
                                echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
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
