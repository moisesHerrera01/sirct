<label for="depende_vyp_actividades" class="font-weight-bold">Depende de la Actividad: <span class="text-danger"></span></label>
<select id="depende_vyp_actividades" name="depende_vyp_actividades" class="select2" onchange=""  style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            //$this->db->where("id_departamento_pais",$id_departamento);
            $actividad = $this->db->get("vyp_actividades");
            if(!empty($actividad)){
            foreach ($actividad->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_vyp_actividades ?>" <?php if($fila->id_vyp_actividades==$depende_vyp_actividades){?> selected  <?php }?>>
        <?php
            echo $fila->nombre_vyp_actividades;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
