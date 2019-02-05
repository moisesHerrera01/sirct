<h5>Estado civil: <span class="text-danger">*</span></h5>
<select id="ecivil" name="ecivil" required class="select2" onchange=""  style="width: 100%">
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
