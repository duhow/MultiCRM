<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function login(){
		if($this->session->userdata('id')){
			// Verify user is active, not blocked
		}

		$data = array();
		if($this->config->item('captcha') and $this->functions->ip_requires_captcha()){
			$data['captcha'] = TRUE;
		}

		$this->load->view('header', $data);
		$this->load->view('login', $data);
		$this->load->view('footer');
	}

	public function logout(){
		session_destroy();
		redirect('login');
	}
}
