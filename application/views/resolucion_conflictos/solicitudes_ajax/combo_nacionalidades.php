<h5>Nacionalidad: <span class="text-danger">*</span></h5>
<select id="nacionalidad" name="nacionalidad" required class="select2" onchange=""  style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($nacionalidad)){
            foreach ($nacionalidad->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_nacionalidad ?>" <?php if($fila->id_nacionalidad==$id || ($fila->nacionalidad=="SALVADOREÑA" && empty($id))){?> selected  <?php }?>>
        <?php
            echo $fila->nacionalidad;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
