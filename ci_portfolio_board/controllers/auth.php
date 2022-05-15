<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {
	
	function __construct() {
		parent::__construct();

	}
	
	function login() {
		$this->load->view("header");
		$this->load->view("login");
		$this->load->view("footer");
	}

	function logout() {
		$this->session->sess_destroy();
		//$this->session->unset_userdata('is_login');

		$this->load->helper("url");
		redirect("auth/login");
	}

	function authentication() {
		
		if ($this->input->post("id")=="egoing" && $this->input->post("password")=="1234") {
			$this->session->set_userdata('is_login',true);

			$this->load->helper("url");
			redirect("topic/add");
		} else {

			$this->session->set_flashdata("message","로그인 실패했습니다."); // flashdata : 잠깐 저장후 사라짐
			$this->load->helper("url");
			redirect("auth/login");
		}
	}
}