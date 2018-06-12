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

		if($this->input->post('user') and $this->input->post('pass')){
			// Check Recaptcha
			if($this->load->is_loaded('recaptcha') and $this->functions->ip_requires_captcha()){
				if(!$this->input->post('g-recaptcha-response')){
					log_message('error', 'Login failed for ['.$_SERVER['REMOTE_ADDR'] .'], missing Recaptcha.');
					http_response_code(401);
					die();
				}
				$res = $this->Recaptcha->verifyResponse($this->input->post('g-recaptcha-response'));
				if(!$res){
					log_message('error', 'Login failed for ['.$_SERVER['REMOTE_ADDR'] .'], failed Recaptcha.');
					http_response_code(401);
					die();
				}
			}

			http_response_code(402);
			die();
		}

		$data = array();
		if($this->load->is_loaded('recaptcha') and $this->functions->ip_requires_captcha()){
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
