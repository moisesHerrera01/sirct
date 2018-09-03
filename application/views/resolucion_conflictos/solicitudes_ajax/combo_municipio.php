<label for="municipio" class="font-weight-bold">Municipio: <span class="text-danger">*</span></label>
<select id="municipio2" name="municipio2" class="select2" required onchange="" style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($municipio)){
            foreach ($municipio->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_municipio ?>" <?php if($fila->id_municipio==$id){?> selected  <?php }?>>
        <?php
            echo $fila->municipio;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
