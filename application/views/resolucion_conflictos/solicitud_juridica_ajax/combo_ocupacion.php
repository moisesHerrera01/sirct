<h5>Ocupación: <span class="text-danger">*</span></h5>
<select id="id_catalogociuo" name="id_catalogociuo" required class="select2" onchange=""  style="width: 100%">
    <option value="">[Seleccione la ocupación]</option>
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
