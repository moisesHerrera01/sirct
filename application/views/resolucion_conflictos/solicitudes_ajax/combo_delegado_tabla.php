<!-- <h5>Asignar delegado/a: <span class="text-danger">*</span></h5> -->
<select id="nr_search" name="nr_search" class="select2" onchange="tablasolicitudes();" style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($colaborador)){
            foreach ($colaborador->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->nr ?>" <?php if($fila->nr==$id){?> selected  <?php }?>>
        <?php
            echo $fila->nombre_completo.' - '.$fila->nr;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
