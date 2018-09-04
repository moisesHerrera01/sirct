<label for="establecimiento" >Nombre del establecimiento: <span class="text-danger">*</span></label>
<select id="establecimiento" name="establecimiento" required class="select2" onchange="tabla_representantes();"  style="width: 100%">
    <option value="">[Seleccione un establecimiento]</option>
    <?php
        if(!empty($establecimiento)){ foreach ($establecimiento->result() as $fila) {
    ?>
        <option  value="<?php echo $fila->id_empresa ?>" <?php if($fila->id_empresa==$id){?> selected  <?php }?>>
    <?php
        echo $fila->nombre_empresa;
    ?>
        </option>;
    <?php
            }
        }
    ?>
</select>