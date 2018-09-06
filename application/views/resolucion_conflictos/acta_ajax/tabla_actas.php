<div class="table-responsive">
    <table id="myTable" class="table table-hover product-overview" width="100%">
        <thead class="bg-info text-white">
            <tr>
                <th>Correlativo</th>
                <th>Nombre</th>
                <th>Fecha de Subida</th>
                <th width="150px">(*)</th>
            </tr>
        </thead>
        <tbody>
            <?php

                $actas = $this->db->query("SELECT *
                                            FROM sct_actasci
                                            WHERE id_expedienteci = ".$this->input->get('id_expediente'));

                if($actas->num_rows() > 0){
                    foreach ($actas->result() as $fila) {
                        echo "<tr>";
                        echo "<td>".$fila->id_actasci."</td>";
                        echo "<td>".$fila->nombre_actasci."</td>";
                        echo "<td>".date("d-M-Y g:i:s A", strtotime($fila->fechacrea_actasci))."</td>";

                        $array = array($fila->id_actasci);
                        //if(tiene_permiso($segmentos=2,$permiso=4)){
                            array_push($array, "edit");
                            echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                        //}
                        echo "</td>";
                        echo "</tr>";
                    }
                }
            ?>
        </tbody>
    </table>
</div>

