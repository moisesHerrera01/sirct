<div class="table-responsive">
    <table id="myTable2" class="table table-bordered product-overview">
        <thead class="bg-info text-white">
            <tr>
                <th>#</th>
                <th>Nombre de la empresa</th>
                <th>Abreviatura</th>
                <th>Telefono</th>
                <th>Estado</th>
                <th style="min-width: 120px;">(*)</th>
            </tr>
        </thead>
        <tbody>
        <?php
        	$actividad = $this->db->query("SELECT * FROM sge_empresa ORDER BY fechacrea_empresa");
          $contador=0;
            if($actividad->num_rows() > 0){
                foreach ($actividad->result() as $fila) {
                  $contador++;
                  echo "<tr>";
                  echo "<td>".$contador."</td>";
                  echo "<td>".$fila->nombre_empresa."</td>";
                  echo "<td>".$fila->abreviatura_empresa."</td>";
                  echo "<td>".$fila->telefono_empresa."</td>";
                  echo ($fila->estado_empresa == "1") ? '<td><span class="label label-success">Activo</span></td>' : '<td><span class="label label-danger">Inactivo</span></td>';
                  echo "<td>";
                  $array = array($fila->id_empresa);
                   
                  if(tiene_permiso($segmentos=2,$permiso=4)){
                    array_push($array, "edit");
                    echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                    unset($array[endKey($array)]); //eliminar el ultimo elemento de un array
                  }

                  if(tiene_permiso($segmentos=2,$permiso=1)){
                        echo generar_boton($array,"visualizar_empresa","btn-secondary","mdi mdi-magnify","Visualizar");
                    }
                   
                  echo "</td>";
                  echo "</tr>";
                }
            }
        ?>
        </tbody>
    </table>
</div>