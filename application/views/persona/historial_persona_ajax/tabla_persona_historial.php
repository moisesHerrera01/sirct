<div class="table-responsive">
    <table id="myTable" class="table table-hover product-overview" width="100%">
        <thead class="bg-info text-white">
            <tr>
              <th>#</th>
              <th>Documento identidad</th>
              <th>Nombre solicitante</th>
              <th>Teléfono</th>
              <th>Edad (años)</th>
              <th>Sexo</th>
              <th>Estado Actual</th>
                <th width="150px">(*)</th>
            </tr>
        </thead>
        <tbody>
        <?php

            $this->db->select('')
             ->from('sct_personaci p')
             ->join('sct_doc_identidad di', 'di.id_doc_identidad = p.id_doc_identidad', 'left')
             ->join('org_municipio m','m.id_municipio=p.id_municipio', 'left')
             ->join('sct_nacionalidad n','n.id_nacionalidad=p.nacionalidad_personaci', 'left')
             ->order_by('p.nombre_personaci, p.apellido_personaci');
            $solicitudes=$this->db->get();
           
            $correlativo = 0;
            if($solicitudes->num_rows() > 0){

                /*********** Si hay registros consulta los permisos **********************/
                $puede_editar = tiene_permiso($segmentos=2,$permiso=4);
                $puede_consultar = tiene_permiso($segmentos=2,$permiso=1);
                /*********** Fin de consulta de permisos *********************************/

                foreach ($solicitudes->result() as $fila) {
                  $correlativo++;
                  echo "<tr>";
                    echo "<td>".$correlativo."</td>";
                    echo "<td>".(!empty($fila->dui_personaci) ? $fila->dui_personaci : "N/A")."</td>";
                    echo "<td>".$fila->nombre_personaci." ".$fila->apellido_personaci."</td>";
                    echo "<td>".(!empty($fila->telefono_personaci) ? $fila->telefono_personaci : "N/A")."</td>";
                    echo "<td>".calcular_edad(date("Y-m-d", strtotime($fila->fnacimiento_personaci)))."</td>";
                    echo "<td>".$fila->sexo_personaci."</td>";

                    if($fila->estado_persona == 0){
                        echo '<td><span class="label label-success">Activo</span></td>';
                    }else if($fila->estado_persona == 1){
                        echo '<td><span class="label label-danger">Inactivo</span></td>';
                    }

                    echo "<td>";
                    $array = array($fila->id_personaci);
                    if($puede_editar){
                        array_push($array, "edit");
                        echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                    }

                    if($puede_consultar){
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
