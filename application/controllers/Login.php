<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if("192.168.1.200" == $_SERVER['SERVER_NAME']){
	define("SERVER_MTPS","192.168.1.200");
}else if("192.168.11.239" == $_SERVER['SERVER_NAME']){
	define("SERVER_MTPS","192.168.11.239");
}else{
	define("SERVER_MTPS","MI IP");
}

class Login extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('login_model');
	}

	public function index()
	{
		$this->load->view('login');
	}

	public function vista_inicio(){
		$this->load->view('templates/header');
		$this->load->view('inicio');
		$this->load->view('templates/footer');
	}

	public function verificar_usuario(){
		$data = array(
		'usuario' => $this->input->post('usuario'),
		'password' => $this->input->post('password')
		);

		$response = "";
		
		$usuario = $this->login_model->verificar_usuario($data);// verifica si el usuario existe

		if($usuario == "existe"){
			$estado = $this->login_model->verificar_estado($data);	//verifica si la cuenta está activa
			if($estado == "activo"){
				$log = $this->login_model->verificar_usuario_password($data);	//verifica si el usuario y contraseña en la base 																   de datos es correcto
				if($log == "existe"){
					$response = "exito";
				}else{
					if(SERVER_MTPS == $_SERVER['SERVER_NAME']) {
						$active = $this->ldap_login($data['usuario'],$this->input->post('password'));
						if($active == "login"){
							$response = "exito";
						}else{
							$response = "activeDirectory";
						}
					}else{
						$response = "password";
					}
				}
			}else{
				$response = "estado";
			}
		}else{
			if(SERVER_MTPS == $_SERVER['SERVER_NAME']) {
				$active = $this->ldap_login($data['usuario'],$this->input->post('password'));
				if($active == "login"){
					$response = "exito";
				}else{
					$response = "activeDirectory";
				}
			}else{
				$response = "password";
			}
		}
		
		if($response == "exito"){
			$login = $this->login_model->get_data_user($data);
			if($login->num_rows() > 0){
				foreach ($login->result() as $fila) {
				}
				$usuario_data = array(
	               'id_usuario' => $fila->id_usuario,
	               'usuario' => $fila->usuario,
	               'nombre_usuario' => $fila->nombre_completo,
	               'sesion' => TRUE
	            );
				$this->session->set_userdata($usuario_data);
				/************** Inicio de fragmento bitácora *********************/
				$this->bitacora_model->bitacora(array( 'descripcion' => "Inició sesión", 'id_accion' => "1"));
	            /************** Fin de fragmento bitácora *********************/		
			}else{
				$response = "sesion";
				$this->session->sess_destroy();
			}
		}

		echo $response;
		
	}

	public function cerrar_sesion(){
		/************** Inicio de fragmento bitácora *********************/
		if(isset($_SESSION['id_usuario'])){
			$this->bitacora_model->bitacora(array( 'descripcion' => "Cerró sesión", 'id_accion' => "1"));
		}
        /************** Fin de fragmento bitácora *********************/
		unset(
		    $_SESSION['id_usuario'],
		    $_SESSION['usuario'],
		    $_SESSION['nombre_usuario'],
		    $_SESSION['sesion']
		);
		$this->index();
	}

	function ldap_login($user,$pass){
		error_reporting(0); $ldaprdn = $user.'@mtps.local'; $ldappass = $pass; $ds = 'mtps.local'; $dn = 'dc=mtps,dc=local'; $puertoldap = 389;  $ldapconn = @ldap_connect($ds,$puertoldap); 
		if ($ldapconn){ 
			ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION,3);  ldap_set_option($ldapconn, LDAP_OPT_REFERRALS,0); 
			$ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
			if ($ldapbind){  return "login";
			}else{  return "error"; } 
		}else{ 
			return "error";
		}
		ldap_close($ldapconn);
	}
}
?>