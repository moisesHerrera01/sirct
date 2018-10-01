<div class="table-responsive">
    <table id="myTable" class="table table-hover product-overview" width="100%">
        <thead class="bg-info text-white">
            <tr>
              <th>#</th>
              <th>Documento identidad</th>
              <th>Nombre Solicitante</th>
              <th>Teléfono</th>
              <th>Edad (años)</th>
              <th>Sexo</th>
              <th>Repres.</th>
              <th>Estado Actual</th>
                <th width="150px">(*)</th>
            </tr>
        </thead>
        <tbody>
        <?php

            $this->db->select('')
             ->from('sct_personaci p')
             ->join('sct_doc_identidad di', 'di.id_doc_identidad = p.id_doc_identidad')
             ->join('org_municipio m','m.id_municipio=p.id_municipio')
             ->join('sct_nacionalidad n','n.id_nacionalidad=p.nacionalidad_personaci');
            $solicitudes=$this->db->get();
           
            $correlativo = 0;
            if($solicitudes->num_rows() > 0){
                foreach ($solicitudes->result() as $fila) {
                  $correlativo++;
                  echo "<tr>";
                    echo "<td>".$correlativo."</td>";
                    echo "<td>".$fila->dui_personaci."</td>";
                    echo "<td>".$fila->nombre_personaci."</td>";
                    echo "<td>".$fila->telefono_personaci."</td>";

                    echo "<td>".calcular_edad(date("Y-m-d", strtotime($fila->fnacimiento_personaci)))."</td>";
                    echo "<td>".$fila->sexo_personaci."</td>";
                    if($fila->posee_representante == 0){
                        echo '<td>NO</td>';
                    }else if($fila->posee_representante == 1){
                        echo '<td>SI</td>';
                    }

                    if($fila->estado_persona == 0){
                        echo '<td><span class="label label-success">Activo</span></td>';
                    }else if($fila->estado_persona == 1){
                        echo '<td><span class="label label-danger">Inactivo</span></td>';
                    }

                    echo "<td>";
                    $array = array($fila->id_personaci);
                    if(tiene_permiso($segmentos=2,$permiso=4)){
                        array_push($array, "edit");
                        echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                    }

                    if(tiene_permiso($segmentos=2,$permiso=4)){
                        unset($array[endKey($array)]); //eliminar el ultimo elemento de un array
                        echo generar_boton($array,"visualizar","btn-secondary","mdi mdi-magnify","Visualizar");
                    }

                
                    echo "</td>";
                  echo "</tr>";
                }
            }
        ?>
        </tbody>
    </table>
</div>
