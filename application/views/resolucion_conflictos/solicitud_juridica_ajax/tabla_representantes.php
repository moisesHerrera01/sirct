<div class="card">
    <div class="card-header">
        <div class="card-actions">

        </div>
        <h4 class="card-title m-b-0">Listado de personas representantes</h4>
    </div>
    <div class="card-body b-t"  style="padding-top: 7px;">
    	<div class="pull-right">
          <?php if(tiene_permiso($segmentos=2,$permiso=2)){ ?>
            <button type="button" onclick="cambiar_nuevo2();" class="btn waves-effect waves-light btn-success2"><span class="mdi mdi-plus"></span> Nuevo registro</button>
          <?php } ?>
        </div>
        <div class="table-responsive">

            <table id="tabla_representante" class="table stylish-table">
                <thead class="bg-info text-white">
                    <tr>
                        <th class="text-white" colspan="2">Persona representante</th>
                        <th class="text-white">DUI</th>
                        <th class="text-white">Estado</th>
                        <th style="min-width: 20px;" class="text-white">(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                	$id_empresa = $_GET["id_empresa"];
                  $id_representanteci = $_GET["id_representanteci"];
                	$representantes = $this->db->query("SELECT * FROM sge_representante WHERE id_empresa = '".$id_empresa."'");
                  $contador=0;
                    if($representantes->num_rows() > 0){

                        /*********** Si hay registros consulta los permisos **********************/
                        $puede_editar = tiene_permiso($segmentos=2,$permiso=4);
                        /*********** Fin de consulta de permisos *********************************/

                        foreach ($representantes->result() as $fila) {
                          $contador++;
                          if($fila->id_representante == $id_representanteci){
                            echo "<tr class='table-active active'>";
                          }else{
                            echo "<tr>";
                          }

                          if($fila->id_representante == $id_representanteci){
                            echo '<td style="cursor: pointer; min-width: 40px; max-width: 40px;" onclick="seleccionar_representante(this,'.$fila->id_representante.');"><span class="round round-primary">R</span></td>';
                          }else{
                            echo '<td style="cursor: pointer; min-width: 40px; max-width: 40px;" onclick="seleccionar_representante(this,'.$fila->id_representante.');"></td>';
                          }

                          if($fila->tipo_representante == "1"){ $tipo ='Legal'; }
                          else if($fila->tipo_representante == "2"){ $tipo ='designado'; }
                          else if($fila->tipo_representante == "3"){ $tipo ='apoderado'; }
                          echo "<td style='cursor: pointer;' onclick='seleccionar_representante(this,".$fila->id_representante.");'>"."<h6>".$fila->nombres_representante."</h6><small class='text-muted'>".$tipo."</small>"."</td>";
                          echo "<td style='cursor: pointer;' onclick='seleccionar_representante(this,".$fila->id_representante.");'>".$fila->dui_representante."</td>";
                          echo ($fila->estado_representante == "1") ? '<td style="cursor: pointer;" onclick="seleccionar_representante(this,'.$fila->id_representante.');"><span class="label label-success">Activo</span></td>' : '<td style="cursor: pointer;" onclick="seleccionar_representante(this, '.$fila->id_representante.');"><span class="label label-danger">Inactivo</span></td>';
                          echo "<td>";
                          $array = array($fila->id_representante, $fila->dui_representante, $fila->nombres_representante, $fila->acreditacion_representante, $fila->tipo_representante, $fila->estado_representante,$fila->id_doc_identidad);

                          if($puede_editar){
                            array_push($array, "edit");
                            echo generar_boton($array,"cambiar_editar2","btn-info","fa fa-wrench","Editar");
                          }

                          echo "</td>";
                          echo "</tr>";
                        }
                    }else{
                      echo "<td colspan='6'>No hay representates registrados</td>";
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
