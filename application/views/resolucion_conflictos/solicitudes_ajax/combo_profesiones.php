<h5>Profesión: <span class="text-danger">*</span></h5>
<select id="profesion" name="profesion" required class="select2" onchange=""  style="width: 100%">
    <option value="">[Seleccione]</option>
        <?php
              if(!empty($profesiones)){
              foreach ($profesiones->result() as $fila) {
          ?>
              <option  value="<?php echo $fila->id_titulo_academico ?>" <?php if($fila->id_titulo_academico==$id){?> selected  <?php }?>>
          <?php
              echo $fila->titulo_academico;
          ?>
              </option>;
          <?php
              }
            }
        ?>
</select>
