<h5>Seleccione discapacidad: <span class="text-danger">*</span></h5>
<select id="id_discapacidad" name="id_discapacidad" required class="select2" style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($discapacidad)){
            foreach ($discapacidad->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_discapacidad ?>" <?php if($fila->id_discapacidad==$id){?> selected  <?php }?>>
        <?php
            echo $fila->discapacidad;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
