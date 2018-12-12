<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="description" content=""><meta name="author" content=""><link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/images/Logo-min.png"><title>Login - SIRCT</title><script src="<?php echo base_url(); ?>assets/js/jquery-3.2.1.min.js"></script><link href="<?php echo base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"><link href="<?php echo base_url(); ?>assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css"><link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet"><link href="<?php echo base_url(); ?>assets/css/colors/blue.css" id="theme" rel="stylesheet">
<script type="text/javascript">
    function showhidepassword(){
        if(document.getElementById("cheque_pass").checked){
            $("#password").attr('type','text');
        }else{
            $("#password").attr('type','password');
        }
    }
</script>
</head>
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
        .row::after{
            display: block;
            clear: both;
            content: "";
        }
        .col-lg-12{ width: 85%;}
    <?php } ?>
</style>
<body class="fix-header fix-sidebar card-no-border logo-center">
    <div class="preloader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg></div>
    <header class="topbar">
        <nav class="navbar top-navbar navbar-light" style="justify-content: space-between;">
            <div class="pull-left">
                <span class="nav-item" style="position: inline-block;"> <a class="nav-link sidebartoggler text-white waves-effect waves-dark" href="javascript:void(0)" style="font-size: 20px;"> <span class="mdi mdi-login-variant"></span> INICIO DE SESIÓN</a> </span>
            </div>
        </nav>
    </header>

    <section id="wrapper" class="login-register img-responsive login-sidebar" style="background-image:url(<?php echo base_url(); ?>assets/images/portadas/resolucion.jpg);">
  <div class="login-box card">
    <div class="card-body">
     <?php
        if($navegatorless){
            echo form_open('', array('id' => 'loginform', 'style' => 'margin-top: 0px;', 'class' => ''));
        }else{
            echo form_open('', array('id' => 'loginform', 'style' => 'margin-top: 0px;', 'class' => 'form-horizontal form-material'));
        }
     ?>
        <a href="javascript:void(0)" class="text-center db"><img height="160px;" src="<?php echo base_url(); ?>assets/images/Logo.png" alt="Home" /></a>
       <div class="form-group m-t-40">
          <div class="col-lg-12"><input class="form-control" type="text" name="usuario" required="" placeholder="Persona usuaria"></div>
        </div>
        <div class="form-group" style="margin-bottom: 10px;">
          <div class="col-lg-12"><input class="form-control" type="password" id="password" name="password" required="" placeholder="Contraseña"></div>
        </div>
        <div class="form-group" style="margin-top: 10px;">
            <div class="col-lg-12">
                <div class="switch" align="center" style="height: 28px;">
                    <label>Ocultar
                        <input type="checkbox" id="cheque_pass" onclick="showhidepassword();"><span class="lever"></span>Mostrar contraseña</label>
                </div>
            </div>
        </div>

        <div class="form-group text-center" style="margin-top: 10px; <?php if($navegatorless){ echo "width: 108%;"; } ?>" align="center">
          <div class="col-lg-12"><button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Ingresar <span class="fa fa-sign-in"></span></button></div>
        </div>
        <h3 align="center" class="text-primary" style="display: none;" id="Save_info"><b><span class="fa fa-spinner fa-pulse"></span> <small>Espere. Estamos buscando en el sistema...</small></b></h3>
      </form>
    </div>
  </div>


</section>
<script>
$(function(){
    $("#loginform").on("submit", function(e){
        e.preventDefault(); var f = $(this);
        $("#Save_info").find("small").text("Espere. Estamos buscando en el sistema...");
        $("#Save_info").show(0);
        var formData = new FormData(document.getElementById("loginform"));
        $.ajax({
            url: "<?php echo site_url(); ?>/login/verificar_usuario",
            type: "post", dataType: "html", data: formData, cache: false, contentType: false, processData: false
        })
        .done(function(res){
            console.log(res);
            if(res == "exito"){
                $("#Save_info").find("small").text("Cargando para iniciar sesión");
                var c = new Date();
                localStorage["expirasesionvyp"] = new Date(c.getFullYear(),c.getMonth(),c.getDate(),c.getHours(),c.getMinutes(),c.getSeconds());
                localStorage["ventanasvyp"] = 0;
                location.href = "<?php echo base_url(); ?>";
            }else if(res == "usuario"){
                swal({ title: "¡Usuario no existe!", text: "La persona usuaria que intenta identificar no exíste.", type: "warning", showConfirmButton: true });
                $("#Save_info").hide(0);
            }else if(res == "estado"){
                swal({ title: "¡Cuenta inactiva!", text: "La cuenta de esta persona usuaria esta deshabilitada.", type: "warning", showConfirmButton: true });
                $("#Save_info").hide(0);
            }else if(res == "password"){
                swal({ title: "¡Clave no válida!", text: "La clave ingresada no es válida.", type: "warning", showConfirmButton: true });
                $("#Save_info").hide(0);
            }else if(res == "activeDirectory"){
                swal({ title: "¡No encontrado en Active Directory!", text: "Persona usuaria o contraseña no encontrado en Active Directory.", type: "warning", showConfirmButton: true });
                $("#Save_info").hide(0);
            }else if(res == "sesion"){
                swal({ title: "¡Ocurrió un error!", text: "Falló el inicio de sesión. Por favor intentelo nuevamente.", type: "error", showConfirmButton: true });
                $("#Save_info").hide(0);
            }else{
                swal({ title: "¡Ocurrió un error!", text: "La persona usuaria o contraseña son incorrectos, o no se logró conectar a Active Directory.", type: "error", showConfirmButton: true });
                $("#Save_info").hide(0);
            }
        })

        .fail( function( jqXHR, textStatus, res ) {
            swal({ title: "¡Error en el sistema!", text: "¡Algo salió mal! notifícalo a la UDT", type: "error", showConfirmButton: true });
            $("#Save_info").hide(0);
            console.log(res);
        });

    });
});
</script>
</div>
    <script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script><script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/popper.min.js"></script><script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script><script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.js"></script><script src="<?php echo base_url(); ?>assets/js/waves.js"></script><script src="<?php echo base_url(); ?>assets/js/sidebarmenu.js"></script><script src="<?php echo base_url(); ?>assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script><script src="<?php echo base_url(); ?>assets/plugins/sparkline/jquery.sparkline.min.js"></script><script src="<?php echo base_url(); ?>assets/js/custom.min.js"></script><script src="<?php echo base_url(); ?>assets/plugins/sweetalert/sweetalert.min.js"></script><script src="<?php echo base_url(); ?>assets/plugins/toast-master/js/jquery.toast.js"></script>
    </script>
</body>

</html>
