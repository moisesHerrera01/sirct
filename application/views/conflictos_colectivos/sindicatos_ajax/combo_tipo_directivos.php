<h5>Tipo directivo: <span class="text-danger">*</span></h5>
<select id="tipo_directivo" name="tipo_directivo" required class="select2" onchange=""  style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($resultados)){
            foreach ($resultados->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_tipo_directivo ?>" <?php if($fila->id_tipo_directivo==$id){?> selected  <?php }?>>
        <?php
            echo $fila->tipo_directivo;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
