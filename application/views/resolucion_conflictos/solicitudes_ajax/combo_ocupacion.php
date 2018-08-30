<h5>Ocupación: <span class="text-danger">*</span></h5>
<select id="ocupacion" name="ocupacion" required class="select2" onchange=""  style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($ocupacion)){
            foreach ($ocupacion->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_catalogociuo ?>" <?php if($fila->id_catalogociuo==$id){?> selected  <?php }?>>
        <?php
            echo $fila->primarios_catalogociuo;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
