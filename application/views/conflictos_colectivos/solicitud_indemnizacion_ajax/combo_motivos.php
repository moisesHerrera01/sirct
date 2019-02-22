<h5>Motivo de la solicitud: <span class="text-danger">*</span></h5>
<select  id="motivo" name="motivo" required class="select2" style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($motivos)){
            foreach ($motivos->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_motivo_solicitud ?>" <?php if($fila->id_motivo_solicitud==$id){?> selected  <?php }?>>
        <?php
            echo $fila->nombre_motivo;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
