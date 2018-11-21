<h5>Defensor(a) público(a): </h5>
<select id="defensor" name="defensor" class="select2 def" onchange=""  style="width: 100%">
    <option value="">[Sin defensor]</option>
        <?php
            if(!empty($defensor)){
            foreach ($defensor->result() as $fila) {
        ?>
            <option  value="<?php echo $fila->id_representantepersonaci ?>" <?php if($fila->id_representantepersonaci==$id){?> selected  <?php }?>>
        <?php
            echo $fila->nombre_completo;
        ?>
            </option>;
        <?php
            }
            }
        ?>
</select>
