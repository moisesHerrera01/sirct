<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mantenimiento extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->load->view('templates/header');
		$this->load->view('templates/mantenimiento');
		$this->load->view('templates/footer');
	}
}
?>