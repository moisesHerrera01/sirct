<h5>Persona defensora pública: </h5>
<select id="procurador" name="procurador" class="select2" onchange=""  style="width: 100%">
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
