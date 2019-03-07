<h5>Estado familiar: </h5>
<select id="ecivil" name="ecivil" class="select2" onchange=""  style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($estados)){
            foreach ($estados->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_estado_civil ?>" <?php if($fila->id_estado_civil==$id){?> selected  <?php }?>>
        <?php
            echo $fila->estado_civil;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
