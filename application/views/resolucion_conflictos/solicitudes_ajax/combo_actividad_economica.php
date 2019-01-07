<h5>Actividad Economica: <span class="text-danger">*</span></h5>
<select id="act_economica" name="act_economica" class="select2" required onchange="" style="width: 100%" required>
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($catalogo)){
            foreach ($catalogo->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_catalogociiu ?>" <?php if($fila->id_catalogociiu==$id){?> selected  <?php }?>>
        <?php
            echo $fila->actividad_catalogociiu;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
