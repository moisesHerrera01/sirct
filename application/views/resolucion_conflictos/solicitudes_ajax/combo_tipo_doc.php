<h5>Tipo documento identidad: <span class="text-danger">*</span></h5>
<select onchange="ocultar()" id="id_doc_identidad" name="id_doc_identidad" required class="select2" style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
            if(!empty($doc_identidad)){
            foreach ($doc_identidad->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_doc_identidad ?>" <?php if($fila->id_doc_identidad==$id){?> selected  <?php }?>>
        <?php
            echo $fila->doc_identidad;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
