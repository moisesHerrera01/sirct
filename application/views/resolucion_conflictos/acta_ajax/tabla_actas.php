<div class="card">
    <div class="card-header">
        <h4 class="card-title m-b-0">Listado de estados</h4>
    </div>
    <div class="card-body b-t" style="padding-top: 7px;">
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
                                echo "<td>";
                                    echo '<a class="btn waves-effect waves-light btn-rounded btn-sm btn-info" href="'.base_url('index.php/resolucion_conflictos/acta/descargar_acta/'.$fila->id_actasci).'"
                                        data-toggle="tooltip" data-original-title="Descargar"><span class="fa fa-download" style="color:#fff;"></span></a>';
                                    echo "&nbsp;";
                                    echo generar_boton(
                                            array($fila->id_actasci, $fila->id_expedienteci),
                                            "eliminar_acta",
                                            "btn-danger",
                                            "fa fa-chevron-down",
                                            "Eliminar"
                                        );
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
