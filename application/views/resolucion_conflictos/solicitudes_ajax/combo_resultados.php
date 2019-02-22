<h5>Resultado: <span class="text-danger">*</span></h5>
<select id="resolucion" name="resolucion" required class="select2" onchange="mostrar()"  style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($resultados)){
            foreach ($resultados->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_resultadoci ?>" <?php if($fila->id_resultadoci==$id){?> selected  <?php }?>>
        <?php
            echo $fila->resultadoci;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
