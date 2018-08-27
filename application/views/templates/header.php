<?php
    $user = $this->session->userdata('usuario');
    if(empty($user)){ header("Location: ".site_url()."/login"); exit(); }
    $id_usuario = $this->session->userdata('id_usuario');
    $pos = strpos($user, ".")+1;
    $inicialUser = strtoupper(substr($user,0,1).substr($user, $pos,1));
    setlocale(LC_ALL,"es_ES");
    date_default_timezone_set('America/El_Salvador');
    $ruta_segmento = trim(obtener_segmentos(2));

// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="description" content=""><meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/images/Logo-min.png">
    <title>TravelExp</title>
    <!-- CSS Requerido -->
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.2.1.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/colors/blue.css" id="theme" rel="stylesheet">
    <!--
    <link href="<?php echo base_url(); ?>assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />
    -->
<?php if($ruta_segmento == "configuraciones/informacion_empleado"){ ?>
    <link href="<?php echo base_url(); ?>assets/plugins/cropper/cropper.min.css" rel="stylesheet">
<?php } ?>

<?php if($ruta_segmento == "pagos/emergencias" || $ruta_segmento == "pasajes/pasaje"){ ?>
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
<?php } ?>

<?php if($ruta_segmento == "viaticos/solicitud_viatico"){ ?>
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/plugins/dropify/dist/css/dropify.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/plugins/Magnific-Popup-master/dist/magnific-popup.css" rel="stylesheet">
<?php } ?>

<?php if($ruta_segmento == "informes/menu_reportes"){ ?>
    <!-- CSS Reportes -->
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/plugins/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/plugins/ion-rangeslider/css/ion.rangeSlider.skinModern.css" rel="stylesheet">
<?php } ?>

</head>
<script>
    var minutos = 15; var warning = 9.90; var danger = 3;

    $(document).ready(function() {
        $("#password_val").val(""); localStorage["ventanasvyp"]++;
        if(hora() >= 60*minutos || localStorage["expirasesionvyp"] == "expirada"){ cerrar_sesion(0); }
    });

    function cambiar_hora_expira(s){
        if(localStorage["expirasesionvyp"] == "expirada"){
            $("#contador").text("Expira: expirada");
        }else{
            s = (60*minutos) - s; var secs = s % 60; s = (s - secs) / 60; var mins = s % 60; var hrs = (s - mins) / 60; horas = addZ(mins) + ':' + addZ(secs);
            $("#contador").text("Expira: "+horas);
        }
    }

    window.onbeforeunload = function() {
        localStorage["ventanasvyp"]--;
    }

    function hora(){
        var c = new Date(); var a = new Date(c.getFullYear(),c.getMonth(),c.getDate(),c.getHours(),c.getMinutes(),c.getSeconds()); var b = new Date(localStorage["expirasesionvyp"]); var result = ((a-b)/1000); cambiar_hora_expira(result);
        return result;
    }

    function addZ(n) { return (n<10? '0':'') + n; }

    var iniciar_conteo = (function(){
        var condicion; var moviendo= false;
        document.onmousemove = function(){ moviendo= true; };
        setInterval (function() {
            if (!moviendo || localStorage["expirasesionvyp"] == "expirada") {
                // No ha habido movimiento desde hace un segundo, aquí tu codigo
                condicion = ((60*minutos)-hora())
                if(hora() >= 60*minutos){ cerrar_sesion(1000); }
                if(localStorage["expirasesionvyp"] == "expirada"){ cerrar_sesion(0); }
                if(condicion < (warning*60) && condicion > (danger*60)){ $("#initial_user").removeClass("text-danger animacion_nueva"); $("#initial_user").addClass("text-warning"); $("#initial_user").show(0); }
                if(condicion <= (danger*60)){ $("#initial_user").removeClass("text-warning"); $("#initial_user").addClass("text-danger animacion_nueva"); $("#initial_user").show(0); }
            } else {
                moviendo=false; var c = new Date(); localStorage["expirasesionvyp"] = new Date(c.getFullYear(),c.getMonth(),c.getDate(),c.getHours(),c.getMinutes(),c.getSeconds()); hora(); $("#initial_user").hide(0);
            }
       }, 1000); // Cada segundo, pon el valor que quieras.
    })()

    function cerrar_sesion(t){ $("#congelar").fadeIn(t); $("#main-wrapper").fadeOut(t); localStorage["expirasesionvyp"] = "expirada"; }

    function objetoAjax(){
        var xmlhttp = false;
        try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { xmlhttp = false; }
        }
        if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp = new XMLHttpRequest(); }
        return xmlhttp;
    }

    function verificar_usuario2(){
        var usuario = $("#ususario_val").val(); var password = $("#password_val").val(); jugador = document.getElementById('jugador');
        ajax = objetoAjax();
        ajax.open("POST", "<?php echo site_url(); ?>/login/verificar_usuario", true);
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 4){
                jugador.value = (ajax.responseText);
                if(jugador.value == "exito"){
                    continuar_sesion();
                }else{
                    swal({ title: "¡Error!", text: "la contraseña no es válida", type: "warning", showConfirmButton: true });
                    $("#password_val").val("");
                }
            }
        }
        ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        ajax.send("&usuario="+usuario+"&password="+password)
    }

    function continuar_sesion(){
        $("#congelar").fadeOut(1000); $("#main-wrapper").fadeIn(1000); var c = new Date();
        localStorage["expirasesionvyp"] = new Date(c.getFullYear(),c.getMonth(),c.getDate(),c.getHours(),c.getMinutes(),c.getSeconds());
    }

    function esEnter(e) { if (e.keyCode == 13) { $("#btnClickUser").click(); } }

    function toggle(){
        $("#form_toggle").fadeToggle(300);
    }

</script>
<style>
    .animacion_nueva { animation : scales 4.0s ease infinite; -webkit-animation: scales 1.9s ease-in infinite alternate; -moz-animation: scales 1.9s ease-in infinite alternate; animation: scales 1.9s ease-in infinite alternate; }
    @-moz-keyframes scales {
      from { -webkit-transform: scale(0.8); -moz-transform: scale(0.8); transform: scale(0.8); 
      } to { -webkit-transform: scale(1.1); -moz-transform: scale(1.1); transform: scale(1.1); }
    }
    @-webkit-keyframes scales {
        from { -webkit-transform: scale(1.0); -moz-transform: scale(1.0); transform: scale(1.0); 
        } to { -webkit-transform: scale(1.2); -moz-transform: scale(1.2); transform: scale(1.2); }
    }
    @-o-keyframes scales {
        from { -webkit-transform: scale(1.0); -moz-transform: scale(1.0); transform: scale(1.0);
        } to { -webkit-transform: scale(1.2); -moz-transform: scale(1.2); transform: scale(1.2); }
    }
    @keyframes scales {
        from { -webkit-transform: scale(1.0); -moz-transform: scale(1.0); transform: scale(1.0);
        } to { -webkit-transform: scale(1.2); -moz-transform: scale(1.2); transform: scale(1.2); }
    }
    .modal-body { max-height:450px; overflow-y:scroll; }

    <?php if($navegatorless){ ?>
        .form-control{padding: 0rem 0.75rem;}
        .input-group .form-control{ width: 85%; }
        .input-group .input-group-addon{ width: 4%; float: left; height: 23px;}
        .input-group .input-group-addon-right{ width: 4%; float: right; height: 24px;}
        .dataTables_filter input{
            padding: .0rem .75rem;
            font-size: 1rem;
            line-height: 1.25;
            color: #495057;
            background-color: #fff;
            background-image: none;
            background-clip: padding-box;
            border: 1px solid rgba(0,0,0,.15);
            border-radius: .25rem;
            transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        }
        .row::after{
            display: block;
            clear: both;
            content: "";
        }
        .col-lg-1{ width: 7%;}
        .col-lg-2{ width: 14%;}
        .col-lg-3{ width: 21%;}
        .col-lg-4{ width: 30%;}
        .col-lg-5{ width: 38%;}
        .col-lg-6{ width: 45%;}
        .col-lg-7{ width: 54%;}
        .col-lg-8{ width: 63%;}
        .col-lg-9{ width: 70%;}
        .col-lg-12{ width: 93%;}
    <?php } ?>
</style>
<body class="fix-header fix-sidebar card-no-border mini-sidebar" onload="iniciar();">
    <!-- ============================================================== -->
    <!-- Icono de cargando página... -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>

<input type="hidden" name="jugador" id="jugador">
<section id="congelar" style="display: none;">
    <div class="login-register" style="background-color: rgb(238, 245, 249);" >
        <div class="login-box card">
            <div class="card-body" style="z-index: 999;">
                <div align="right">
                    <a class="btn" href="<?php echo site_url(); ?>/login" class="btn btn-default" data-toggle="tooltip" title="Ir al login"><span class="fa fa-chevron-left"></span> Volver </a>
                </div>
                <div class="form-group">
                  <div class="col-xs-12 text-center">
                    <div class="user-thumb text-center">
                        <h3 class="text-warning"><span class="mdi mdi-information"></span> La sesión ha expirado</h3>
                        <h4 style="font-size: 70px; margin-bottom: 0;" class="text-info mdi mdi-account"></h4>
                        <h4><?php echo ucwords(strtolower($this->session->userdata('nombre_usuario'))); ?></h4>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="ususario_val" id="ususario_val" value="<?php echo $this->session->userdata('usuario') ?>">
                <div class="form-group ">
                  <div class="col-xs-12">
                    <input onkeypress="esEnter(event);" class="form-control" type="password" id="password_val" name="password_val" required="" placeholder="password">
                  </div>
                </div>
                <div class="form-group text-center">
                  <div class="col-xs-12">
                    <button id="btnClickUser" class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" onclick="verificar_usuario2()" type="button">Continuar</button>
                  </div>
                </div>
            </div>
          </div>
    </div>
</section>
<div id="main-wrapper">
    <!-- ============================================================== -->
    <!-- Barra superior -->
    <!-- ============================================================== -->
    <header class="topbar">
        <nav class="navbar top-navbar navbar-expand-md navbar-light" style="justify-content: space-between;">

            <div class="pull-left">
                <span class="navbar-header" style="background: none;">
                    <a class="navbar-brand" href="<?php echo site_url(); ?>">
                        <!-- Logo icono --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="<?php echo base_url(); ?>assets/images/Logo-min.png" height='45px' alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="<?php echo base_url(); ?>assets/images/Logo-min.png" height='45px' alt="homepage" class="light-logo" />
                        </b>
                        <!--Fin Logo icon -->
                        <!-- Logo text --><span>
                         <!-- dark Logo text -->
                         <img src="<?php echo base_url(); ?>assets/images/texto.png" height='30px;' alt="homepage" class="dark-logo" />
                         <!-- Light Logo text -->
                         <img src="<?php echo base_url(); ?>assets/images/texto.png" style="margin-left: 10px; margin-top: 10px;"  height='30px;' class="light-logo" alt="homepage" /></span> </a>
                </span>
                <span class="nav-item" style="position: inline-block;"> <a id="clic" class="nav-link sidebartoggler hidden-sm-down text-white waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </span>
                <span class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-white waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </span>
            </div>
            <div class="pull-right">
                <div class="navbar-collapse">
                    <ul class="navbar-nav my-lg-0">
                        <?php if(!$navegatorless){ ?>
                            <li class="nav-item pull-right"> <a id="initial_user" style="display: none;" class="nav-link waves-effect waves-dark" href="javascript:void(0)"><span id="contador"></span></a> </li>
                        <?php } ?>
                        <!-- ============================================================== -->
                        <!-- Profile -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown pull-right">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="round round-success" <?php if($navegatorless && $ua["name"] == "Mozilla Firefox"){ ?>onclick="toggle();" <?php } ?>><?php echo $inicialUser; ?></span></a>
                            <div class="dropdown-menu dropdown-menu-right scale-up" <?php if($navegatorless && $ua["name"] == "Mozilla Firefox"){ ?> style="display: none;" <?php } ?> id="form_toggle">
                                <ul class="dropdown-user">
                                    <li>
                                        <div class="dw-user-box">
                                            <div class="u-text">
                                                <h4><?php echo $this->session->userdata('nombre_usuario'); ?></h4>

                                                <p align="right"><a href="#!" class="btn btn-rounded btn-info waves-effect waves-light">Activo</a></p>
                                            </div>
                                        </div>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#" onclick="cerrar_sesion(1000);"><i class="fa fa-lock"></i> Bloquear sesión</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?php echo site_url(); ?>/cerrar_sesion"><i class="fa fa-power-off"></i> Salir</a></li>
                                </ul>
                            </div>
                        </li>
                        <?php if($navegatorless){ ?>
                            <li class="nav-item pull-right"> <a id="initial_user" style="display: none;" class="nav-link waves-effect waves-dark" href="javascript:void(0)"><span id="contador"></span></a> </li>
                        <?php } ?>
                        <!-- ============================================================== -->
                        <!-- End Profile -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </div>
            
        </nav>
    </header>
    <!-- ============================================================== -->
    <!-- End Topbar header -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
            <!-- Sidebar navigation-->
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li class="nav-small-cap text-center">MENÚ</li>
                    <li class="nav-devider" style="margin:5px;"></li>

 <?php
    $id_sistema=$this->config->item("id_sistema");
    $modulos = $this->db->query("SELECT m.* FROM org_modulo AS m  WHERE m.id_sistema = '".$id_sistema."' AND (m.dependencia = '' OR m.dependencia = 0 OR m.dependencia IS NULL) AND m.id_modulo IN(SELECT P.id_modulo FROM org_rol_modulo_permiso as P INNER JOIN org_usuario_rol as U ON P.id_rol=U.id_rol WHERE P.id_modulo = m.id_modulo AND U.id_usuario='".$id_usuario."' AND P.estado = '1') ORDER BY orden");
    if($modulos->num_rows() > 0){
        foreach ($modulos->result() as $fila) {
        $modulos2 = $this->db->query("SELECT m.* FROM org_modulo AS m WHERE m.id_sistema = '".$id_sistema."' AND m.dependencia = ".$fila->id_modulo." AND m.id_modulo IN(SELECT P.id_modulo FROM org_rol_modulo_permiso as P INNER JOIN org_usuario_rol as U ON P.id_rol=U.id_rol WHERE P.id_modulo = m.id_modulo AND U.id_usuario='".$id_usuario."' AND P.estado = '1') ORDER BY orden");
            if($modulos2->num_rows() > 0){
                echo '<li><a class="has-arrow waves-effect waves-dark" href="'.$fila->url_modulo.'" aria-expanded="false"><i class="'.$fila->img_modulo.'"></i><span class="hide-menu">'.$fila->nombre_modulo.'</span></a>';
                echo '<ul aria-expanded="false" class="collapse">';
                foreach ($modulos2->result() as $fila2) {
    ?>
                    <li><a href="<?php echo site_url()."/"; ?><?php echo $fila2->url_modulo; ?>"><span class="<?php echo $fila2->img_modulo; ?>"></span> <?php echo $fila2->nombre_modulo; ?></a></li>
    <?php
                }
                $modulos2->free_result();
                echo "</ul>";
                echo "</li>";
            }
        }
        $modulos->free_result();
    }
?>

                </ul>
            </nav>
            <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
        <!-- Bottom points-->
        <div class="sidebar-footer">
            <!-- item--><a href="" class="link" data-toggle="tooltip" title="Settings"><i class="ti-settings"></i></a>
            <!-- item--><a href="" class="link" data-toggle="tooltip" title="Email"><i class="mdi mdi-gmail"></i></a>
            <!-- item--><a href="" class="link" data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a> </div>
        <!-- End Bottom points-->
    </aside>
    <!-- ============================================================== -->
    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->