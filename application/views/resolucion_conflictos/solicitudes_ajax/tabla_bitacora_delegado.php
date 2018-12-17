<div class="table-responsive">
<table id="tabla_delegados" class="table table-hover product-overview" width="100%">
    <thead class="bg-info text-white">
        <tr>
          <th width="130px">Rol</th>
          <th>Nombre de la persona usuaria</th>
          <th>Acción</th>
          <th>Nombre de persona delegada</th>
          <th>Fecha</th>
        </tr>
                </thead>
                <tbody>
                <?php
                    if($delegados!=NULL){
                        foreach ($delegados->result() as $fila) {
                          echo "<tr>";
                            echo "<td>".$fila->nombre_rol."</td>";
                            echo "<td>".$fila->nombre_completo."</td>";
                            echo "<td>Asignación o reasignación de expediente</td>";
                            echo "<td>".$fila->nombre_delegado_actual."</td>";
                            echo "<td>".$fila->fecha_cambio_delegado."</td>";

                            echo "</tr>";
                        }
                    }
                ?>
                </tbody>
            </table>
        </div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#tabla_delegados').DataTable();
    });
</script>
