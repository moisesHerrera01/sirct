<!-- <label for="directivos" >Personas directivas: <span class="text-danger">*</span></label> -->
<h5>Personas directivas: <span class="text-danger">*</span></h5>
<select id="directivo" name="directivo" required class="select2 m-b-10 select2-multiple select2-hidden-accessible" onchange="" multiple style="width: 100%">
    <option value="">[Seleccione personas directivas]</option>
    <?php
        if(!empty($directivos)){ foreach ($directivos->result() as $fila) {
    ?>
        <option  value="<?php echo $fila->id_directivo ?>" <?php if($fila->id_directivo==$id){?> selected  <?php }?>>
    <?php
        echo $fila->nombre_directivo;
    ?>
        </option>;
    <?php
            }
        }
    ?>
</select>
