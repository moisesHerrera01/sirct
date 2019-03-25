<?php
if($contadores->num_rows() > 0){
    $row = $contadores->row();
?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">PERSONAS POR RANGO DE EDAD </h3>
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(15%);" align="center">
                        <h4><?= $row->anios16." de ".$row->total ?> <br>
                        <small style="font-size: 12px;">personas tienen entre 16 y 30 años</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                        <div class="round round-lg align-self-center round-info"><i class="mdi mdi-human-child"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $aniosm16 = cast_porcent($row->aniosm16,$row->anios16);
                            $aniosf16 = cast_porcent($row->aniosf16,$row->anios16); 
                        ?>
                        <label class="m-b-0">Hombres: <?= $row->aniosm16." de ".$row->anios16 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($aniosm16) ?>%; height:15px;" role="progressbar"><?= ($aniosm16) ?>%</div>
                        </div>
                        <label class="m-b-0">Mujeres: <?= $row->aniosf16." de ".$row->anios16 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($aniosf16) ?>%; height:15px;" role="progressbar"><?= ($aniosf16) ?>%</div>
                        </div>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(15%);" align="center">
                        <h4><?= $row->anios30." de ".$row->total ?><br> 
                        <small style="font-size: 12px;">personas tienen entre 30 y 50 años</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                        <div class="round round-lg align-self-center round-info"><i class="mdi mdi-walk"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $aniosm30 = cast_porcent($row->aniosm30,$row->anios30);
                            $aniosf30 = cast_porcent($row->aniosf30,$row->anios30); 
                        ?>
                        <label class="m-b-0">Hombres: <?= $row->aniosm30." de ".$row->anios30 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($aniosm30) ?>%; height:15px;" role="progressbar"><?= ($aniosm30) ?>%</div>
                        </div>
                        <label class="m-b-0">Mujeres: <?= $row->aniosf30." de ".$row->anios30 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($aniosf30) ?>%; height:15px;" role="progressbar"><?= ($aniosf30) ?>%</div>
                        </div>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(15%);" align="center">
                        <h4><?= $row->anios50." de ".$row->total ?> <br>
                        <small style="font-size: 12px;">personas tienen más de 50 años</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                        <div class="round round-lg align-self-center round-info"><i class="mdi mdi-incognito"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $aniosm50 = cast_porcent($row->aniosm50,$row->anios50);
                            $aniosf50 = cast_porcent($row->aniosf50,$row->anios50); 
                        ?>
                        <label class="m-b-0">Hombres: <?= $row->aniosm50." de ".$row->anios50 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($aniosm50) ?>%; height:15px;" role="progressbar"><?= ($aniosm50) ?>%</div>
                        </div>
                        <label class="m-b-0">Mujeres: <?= $row->aniosf50." de ".$row->anios50 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($aniosf50) ?>%; height:15px;" role="progressbar"><?= ($aniosf50) ?>%%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">EXPEDIENTES POR RESULTADO </h3>
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                        <?php 
                            $total_exp_13 = $row->pendientes + $row->conciliados + $row->noconciliados + $row->inasistencias + $row->desistidas + $row->amultados + $row->reinstalo + $row->segundacita;
                        ?>
                        <h4><?= $total_exp_13." de ".$row->total ?> <br>
                        <small style="font-size: 12px;">Casos de diferencia laboral y despidos de hecho o injustificados</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(30%);" align="center">
                        <div class="round round-lg align-self-center round-warning"><i class="mdi mdi-account-switch"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $pendientes = cast_porcent($row->pendientes,$total_exp_13);
                            $conciliados = cast_porcent($row->conciliados,$total_exp_13);
                            $noconciliados = cast_porcent($row->noconciliados,$total_exp_13);
                            $inasistencias = cast_porcent($row->inasistencias,$total_exp_13);
                            $desistidas = cast_porcent($row->desistidas,$total_exp_13);
                            $amultados = cast_porcent($row->amultados,$total_exp_13);
                            $reinstalo = cast_porcent($row->reinstalo,$total_exp_13);
                            $segundacita = cast_porcent($row->segundacita,$total_exp_13);
                        ?>
                        <label class="m-b-0">Pendientes: <?= $row->pendientes." de ".$total_exp_13 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($pendientes) ?>%; height:15px;" role="progressbar"><?= ($pendientes) ?>%</div>
                        </div>
                        <label class="m-b-0">Conciliados: <?= $row->conciliados." de ".$total_exp_13 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($conciliados) ?>%; height:15px;" role="progressbar"><?= ($conciliados) ?>%</div>
                        </div>
                        <label class="m-b-0">Sin Conciliar: <?= $row->noconciliados." de ".$total_exp_13 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($noconciliados) ?>%; height:15px;" role="progressbar"><?= ($noconciliados) ?>%</div>
                        </div>
                        <label class="m-b-0">Inacistencias: <?= $row->inasistencias." de ".$total_exp_13 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($inasistencias) ?>%; height:15px;" role="progressbar"><?= ($inasistencias) ?>%</div>
                        </div>
                        <label class="m-b-0">Desistidas: <?= $row->desistidas." de ".$total_exp_13 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($desistidas) ?>%; height:15px;" role="progressbar"><?= ($desistidas) ?>%</div>
                        </div>
                        <label class="m-b-0">A multados: <?= $row->amultados." de ".$total_exp_13 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($amultados) ?>%; height:15px;" role="progressbar"><?= ($amultados) ?>%</div>
                        </div>
                        <label class="m-b-0">Reinstalo: <?= $row->reinstalo." de ".$total_exp_13 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($reinstalo) ?>%; height:15px;" role="progressbar"><?= ($reinstalo) ?>%</div>
                        </div>
                        <label class="m-b-0">Pendiente p/ 2a cita: <?= $row->segundacita." de ".$total_exp_13 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($segundacita) ?>%; height:15px;" role="progressbar"><?= ($segundacita) ?>%</div>
                        </div>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(20%);" align="center">
                        <?php 
                            $total_exp_2 = $row->nonotificados + $row->notificado;
                        ?>
                        <h4><?= $total_exp_2." de ".$row->total ?><br> 
                        <small style="font-size: 12px;">Renuncia voluntaria</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(15%);" align="center">
                        <div class="round round-lg align-self-center bg-inverse"><i class="mdi mdi-contact-mail"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $notificado = cast_porcent($row->notificado,$total_exp_2);
                            $nonotificados = cast_porcent($row->nonotificados,$total_exp_2); 
                        ?>
                        <label class="m-b-0">Hombres: <?= $row->notificado." de ".$total_exp_2 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($notificado) ?>%; height:15px;" role="progressbar"><?= ($notificado) ?>%</div>
                        </div>
                        <label class="m-b-0">Mujeres: <?= $row->nonotificados." de ".$total_exp_2 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($nonotificados) ?>%; height:15px;" role="progressbar"><?= ($nonotificados) ?>%</div>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">PERSONAS DISCAPACITADAS </h3>
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(15%);" align="center">
                        <h4><?= $row->discapacitado." de ".$row->total ?><br>
                            <small style="font-size: 12px;">personas possen discapacidad</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                        <div class="round round-lg align-self-center round-info"><i class="fa fa-wheelchair"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $discapmasc = cast_porcent($row->discapacitadom,$row->discapacitado);
                            $discapfeme = cast_porcent($row->discapacitadof,$row->discapacitado); 
                        ?>
                        <label class="m-b-0">Hombres: <?= $row->discapacitadom." de ".$row->discapacitado ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($discapmasc) ?>%; height:15px;" role="progressbar"><?= ($discapmasc) ?>%</div>
                        </div>
                        <label class="m-b-0">Mujeres: <?= $row->discapacitadof." de ".$row->discapacitado ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($discapfeme) ?>%; height:15px;" role="progressbar"><?= ($discapfeme) ?>%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">PERSONAS LGBTI </h3>
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(15%);" align="center">
                        <h4><?= $row->lgbti." de ".$row->total ?><br>
                            <small style="font-size: 12px;">personas pertenecen a grupo LGBTI</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                        <div class="round round-lg align-self-center round-primary"><i class="mdi mdi-gender-transgender"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $lgbtimasc = cast_porcent($row->lgbtim,$row->lgbti);
                            $lgbtifeme = cast_porcent($row->lgbtif,$row->lgbti); 
                        ?>
                        <label class="m-b-0">Hombres: <?= $row->lgbtim." de ".$row->lgbti ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($lgbtimasc) ?>%; height:15px;" role="progressbar"><?= ($lgbtimasc) ?>%</div>
                        </div>
                        <label class="m-b-0">Mujeres: <?= $row->lgbtif." de ".$row->totalf ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($lgbtifeme) ?>%; height:15px;" role="progressbar"><?= ($lgbtifeme) ?>%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">MUJERES EMBARAZADAS </h3>
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(15%);" align="center">
                        <h4><?= $row->embarazada." de ".$row->totalf ?><br>
                            <small style="font-size: 12px;">mujeres embarazadas</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                        <div class="round round-lg align-self-center round-primary"><i class="mdi mdi-human-pregnant"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $embarazada = cast_porcent($row->embarazada,$row->totalf);
                            $noembarazada = cast_porcent($row->noembarazada,$row->totalf); 
                        ?>
                        <label class="m-b-0">Embarazadas: <?= $row->embarazada." de ".$row->totalf ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($embarazada) ?>%; height:15px;" role="progressbar"><?= ($embarazada) ?>%</div>
                        </div>
                        <label class="m-b-0">No embarazadas: <?= $row->noembarazada." de ".$row->totalf ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($noembarazada) ?>%; height:15px;" role="progressbar"><?= ($noembarazada) ?>%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<?php
}else{
?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">PERSONAS POR RANGO DE EDAD </h3>
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(15%);" align="center">
                        <h4><?= 0 ?> <br>
                        <small style="font-size: 12px;">personas tienen entre 16 y 30 años</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                        <div class="round round-lg align-self-center round-info"><i class="mdi mdi-human-child"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $aniosm16 = cast_porcent(0,0);
                            $aniosf16 = cast_porcent(0,0); 
                        ?>
                        <label class="m-b-0">Hombres: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($aniosm16) ?>%; height:15px;" role="progressbar"><?= ($aniosm16) ?>%</div>
                        </div>
                        <label class="m-b-0">Mujeres: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($aniosf16) ?>%; height:15px;" role="progressbar"><?= ($aniosf16) ?>%</div>
                        </div>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(15%);" align="center">
                        <h4><?= 0 ?><br> 
                        <small style="font-size: 12px;">personas tienen entre 30 y 50 años</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                        <div class="round round-lg align-self-center round-info"><i class="mdi mdi-walk"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $aniosm30 = cast_porcent(0,0);
                            $aniosf30 = cast_porcent(0,0); 
                        ?>
                        <label class="m-b-0">Hombres: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($aniosm30) ?>%; height:15px;" role="progressbar"><?= ($aniosm30) ?>%</div>
                        </div>
                        <label class="m-b-0">Mujeres: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($aniosf30) ?>%; height:15px;" role="progressbar"><?= ($aniosf30) ?>%</div>
                        </div>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(15%);" align="center">
                        <h4><?= 0 ?> <br>
                        <small style="font-size: 12px;">personas tienen más de 50 años</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                        <div class="round round-lg align-self-center round-info"><i class="mdi mdi-incognito"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $aniosm50 = cast_porcent(0,0);
                            $aniosf50 = cast_porcent(0,0); 
                        ?>
                        <label class="m-b-0">Hombres: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($aniosm50) ?>%; height:15px;" role="progressbar"><?= ($aniosm50) ?>%</div>
                        </div>
                        <label class="m-b-0">Mujeres: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($aniosf50) ?>%; height:15px;" role="progressbar"><?= ($aniosf50) ?>%%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">EXPEDIENTES POR RESULTADO </h3>
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                        <?php 
                            $total_exp_13 = 0 + 0 + 0 + 0 + 0 + 0 + 0 + 0;
                        ?>
                        <h4><?= 0 ?> <br>
                        <small style="font-size: 12px;">Casos de diferencia laboral y despidos de hecho o injustificados</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(30%);" align="center">
                        <div class="round round-lg align-self-center round-warning"><i class="mdi mdi-account-switch"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $pendientes = cast_porcent(0,$total_exp_13);
                            $conciliados = cast_porcent(0,$total_exp_13);
                            $noconciliados = cast_porcent(0,$total_exp_13);
                            $inasistencias = cast_porcent(0,$total_exp_13);
                            $desistidas = cast_porcent(0,$total_exp_13);
                            $amultados = cast_porcent(0,$total_exp_13);
                            $reinstalo = cast_porcent(0,$total_exp_13);
                            $segundacita = cast_porcent(0,$total_exp_13);
                        ?>
                        <label class="m-b-0">Pendientes: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($pendientes) ?>%; height:15px;" role="progressbar"><?= ($pendientes) ?>%</div>
                        </div>
                        <label class="m-b-0">Conciliados: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($conciliados) ?>%; height:15px;" role="progressbar"><?= ($conciliados) ?>%</div>
                        </div>
                        <label class="m-b-0">Sin Conciliar: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($noconciliados) ?>%; height:15px;" role="progressbar"><?= ($noconciliados) ?>%</div>
                        </div>
                        <label class="m-b-0">Inacistencias: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($inasistencias) ?>%; height:15px;" role="progressbar"><?= ($inasistencias) ?>%</div>
                        </div>
                        <label class="m-b-0">Desistidas: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($desistidas) ?>%; height:15px;" role="progressbar"><?= ($desistidas) ?>%</div>
                        </div>
                        <label class="m-b-0">A multados: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($amultados) ?>%; height:15px;" role="progressbar"><?= ($amultados) ?>%</div>
                        </div>
                        <label class="m-b-0">Reinstalo: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($reinstalo) ?>%; height:15px;" role="progressbar"><?= ($reinstalo) ?>%</div>
                        </div>
                        <label class="m-b-0">Pendiente p/ 2a cita: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($segundacita) ?>%; height:15px;" role="progressbar"><?= ($segundacita) ?>%</div>
                        </div>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(20%);" align="center">
                        <?php 
                            $total_exp_2 = 0 + 0;
                        ?>
                        <h4><?= 0 ?><br> 
                        <small style="font-size: 12px;">Renuncia voluntaria</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(15%);" align="center">
                        <div class="round round-lg align-self-center bg-inverse"><i class="mdi mdi-contact-mail"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $notificado = cast_porcent(0,$total_exp_2);
                            $nonotificados = cast_porcent(0,$total_exp_2); 
                        ?>
                        <label class="m-b-0">Hombres: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($notificado) ?>%; height:15px;" role="progressbar"><?= ($notificado) ?>%</div>
                        </div>
                        <label class="m-b-0">Mujeres: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($nonotificados) ?>%; height:15px;" role="progressbar"><?= ($nonotificados) ?>%</div>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">PERSONAS DISCAPACITADAS </h3>
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(15%);" align="center">
                        <h4><?= 0 ?><br>
                            <small style="font-size: 12px;">personas possen discapacidad</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                        <div class="round round-lg align-self-center round-info"><i class="fa fa-wheelchair"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $discapmasc = cast_porcent(0,0);
                            $discapfeme = cast_porcent(0,0); 
                        ?>
                        <label class="m-b-0">Hombres: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($discapmasc) ?>%; height:15px;" role="progressbar"><?= ($discapmasc) ?>%</div>
                        </div>
                        <label class="m-b-0">Mujeres: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($discapfeme) ?>%; height:15px;" role="progressbar"><?= ($discapfeme) ?>%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">PERSONAS LGBTI </h3>
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(15%);" align="center">
                        <h4><?= 0 ?><br>
                            <small style="font-size: 12px;">personas pertenecen a grupo LGBTI</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                        <div class="round round-lg align-self-center round-primary"><i class="mdi mdi-gender-transgender"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $lgbtimasc = cast_porcent(0,0);
                            $lgbtifeme = cast_porcent(0,0); 
                        ?>
                        <label class="m-b-0">Hombres: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($lgbtimasc) ?>%; height:15px;" role="progressbar"><?= ($lgbtimasc) ?>%</div>
                        </div>
                        <label class="m-b-0">Mujeres: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($lgbtifeme) ?>%; height:15px;" role="progressbar"><?= ($lgbtifeme) ?>%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">MUJERES EMBARAZADAS </h3>
                <div class="row">
                    <div class="col-lg-4" style="position: relative; top: 50%; transform: translateY(15%);" align="center">
                        <h4><?= 0 ?><br>
                            <small style="font-size: 12px;">mujeres embarazadas</small>
                        </h4>
                    </div>
                    <div class="col-lg-2" style="position: relative; top: 50%; transform: translateY(25%);" align="center">
                        <div class="round round-lg align-self-center round-primary"><i class="mdi mdi-human-pregnant"></i></div>
                    </div>
                    <div class="col-lg-6">
                        <?php 
                            $embarazada = 0;
                            $noembarazada = 0; 
                        ?>
                        <label class="m-b-0">Embarazadas: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($embarazada) ?>%; height:15px;" role="progressbar"><?= ($embarazada) ?>%</div>
                        </div>
                        <label class="m-b-0">No embarazadas: <?= 0 ?></label>
                        <div class="progress m-b-5">
                            <div class="progress-bar bg-success2" style="width: <?= intval($noembarazada) ?>%; height:15px;" role="progressbar"><?= ($noembarazada) ?>%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<?php
}
?>