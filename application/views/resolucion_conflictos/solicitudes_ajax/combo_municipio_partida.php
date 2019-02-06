<h5>Municipio expedición: <span class="text-danger">*</span></h5>
<select id="municipio_partida" name="municipio_partida" class="select2" required onchange="" style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($municipios)){
            foreach ($municipios->result() as $fila) {
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
