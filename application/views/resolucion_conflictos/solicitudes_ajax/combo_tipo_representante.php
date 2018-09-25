<h5>Tipo representante: <span class="text-danger">*</span></h5>
<select id="tipo_representante_persona" name="tipo_representante_persona" required class="select2" onchange=""  style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($tipo_representante)){
            foreach ($tipo_representante->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_tipo_representante ?>" <?php if($fila->id_tipo_representante==$id){?> selected  <?php }?>>
        <?php
            echo $fila->tipo_representante;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
