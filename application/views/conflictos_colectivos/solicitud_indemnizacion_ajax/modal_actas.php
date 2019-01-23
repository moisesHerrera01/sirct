<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<!--INICIO MODAL GENERAR ACTA -->
<div class="modal fade" id="modal_actas_tipo" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Emitir acta:</h4>
      </div>

      <div class="modal-body" id="">
          <input type="hidden" id="id_expedienteci_copia2" name="id_expedienteci_copia2" value="">
          <input type="hidden" id="id_audiencia" name="id_audiencia" value="">
          <input type="hidden" id="cuenta_audiencias" name="cuenta_audiencias" value="">
          <div class="row">
            <div class="form-group col-lg-12 col-sm-12 <?php if($navegatorless){ echo " pull-left"; } ?>">
                <h5>Seleccione el tipo de acta: <span class="text-danger">*</span></h5>
                <div class="controls">
                  <!-- Tipo 1: Engloba 4 posibilidades primera y segunda fecha, con presencia del trabajador y sin el trabajador -->
                  <select id="tipo_acta" name="tipo_acta" class="custom-select col-4" onchange="nav(this.value)" required>
                    <option value="">[Seleccione]</option>
                    <option id="inasistencia" style="display: none;" value="<?=base_url('index.php/conflictos_colectivos/acta_colectivos/acta_pc_pendiente/')?>">Pendiente segunda audiencia</option>
                    <option id="pc_sin_conciliar" style="display: none;" value="<?=base_url('index.php/conflictos_colectivos/acta_colectivos/acta_pc_noconciliada_ct/')?>">No conciliada</option>
                    <option id="sc_conciliada_pago" style="display: none;" value="<?=base_url('index.php/conflictos_colectivos/acta_colectivos/acta_sc_conciliada_pago/')?>">Conciliada pago diferido</option>
                    <?php foreach ($pagos->result() as $p) {?>
                      <option id="acta_pago" value="<?=base_url('index.php/conflictos_colectivos/acta_colectivos/acta_pago/'.$p->numero_pago.'/')?>">Acta de pago <?=$p->numero_pago ?></option>
                    <?php } ?>

                    <option id="inasistencia_scto" value="<?=base_url('index.php/conflictos_colectivos/acta_colectivos/acta_pc_pendiente_scto/')?>">Pendiente segunda audiencia</option>

                    <!-- <option id="pf_st" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/1/')?>">Acta de audiencia</option>
                    <option id="multa" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/2/')?>">Acta de audiencia: multa</option>
                    <option id="desistimiento" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/4/')?>">Acta de desistimiento</option> -->
                    <!-- <option id="diferido_con" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/3/')?>">Conciliada pago diferido con defensor/a público</option>
                     -->
                    <!-- <option id="solicitud_pn_pj" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/5/')?>">Acta de solicitud</option>
                    <option id="esquela" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/6/')?>">Acta de esquela</option> -->
                    <!-- <option value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta/')?>">Ficha de persona natural a persona juridica</option>
                    <option id="segunda_con" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/6/')?>">Segunda cita PN-PJ con defensor/a</option>
                    <option id="segunda_sin" style="display: none;" value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/7/')?>">Segunda cita PN-PJ sin defensor/a</option> -->
                    <!-- <option value="<?=base_url('index.php/resolucion_conflictos/acta/generar_acta_tipo/8/')?>">Desistimiento de persona natural a persona juridica</option> -->
                  </select>
                </div>
            </div>
          </div>
          <div align="right">
            <button type="button" class="btn waves-effect waves-light btn-danger" data-dismiss="modal">Cerrar</button>
          <!--  <button type="button" onclick="generar_actas_tipo();" class="btn waves-effect waves-light btn-success2"> Generar
          </button> !-->
          </div>
      </div>
    </div>
  </div>
</div>
<!--FIN MODAL GENERAR ACTA -->
