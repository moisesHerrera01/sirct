<h5>Asignar delegado: <span class="text-danger">*</span></h5>
<select id="delegado" name="delegado" class="select2" onchange="" style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($colaborador)){
            foreach ($colaborador->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_empleado ?>" <?php if($fila->id_empleado==$id){?> selected  <?php }?>>
        <?php
            echo $fila->nombre_completo;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
