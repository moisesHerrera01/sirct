<h5>Procurador: <span class="text-danger">*</span></h5>
<select id="procurador" name="procurador" required class="select2" onchange=""  style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($procuradores)){
            foreach ($procuradores->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_procuradorci ?>" <?php if($fila->id_procuradorci==$id){?> selected  <?php }?>>
        <?php
            echo $fila->nombre_procurador;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
