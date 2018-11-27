<h5>Parte patronal : </h5>
<select id="representante_empresa" name="representante_empresa" class="select2" onchange=""  style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($rep_empresa)){
            foreach ($rep_empresa->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_representante ?>" <?php if($fila->id_representante==$id){?> selected  <?php }?>>
        <?php
            echo $fila->nombres_representante;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
