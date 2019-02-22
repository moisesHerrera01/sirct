<h5>Municipio: <span class="text-danger">*</span></h5>
<select id="municipio_directivo" name="municipio_directivo" class="select2" required onchange="" style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($municipio)){
            foreach ($municipio->result() as $fila) {
        ?>
            <option  value="<?php echo intval($fila->id_municipio); ?>" <?php if($fila->id_municipio==$id){?> selected  <?php }?>>
        <?php
            echo $fila->municipio;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
