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
			redirect("/");
		}

		if($this->input->post('user') and $this->input->post('pass')){
			// Check Recaptcha
			$captcha = ($this->load->is_loaded('recaptcha') and $this->functions->ip_requires_captcha());
			if($captcha){
				if(!$this->input->post('g-recaptcha-response')){
					$this->functions->log_exit(401, 'error', 'Login failed for ['.$_SERVER['REMOTE_ADDR'] .'], missing Recaptcha.');
				}
				$res = $this->recaptcha->verifyResponse($this->input->post('g-recaptcha-response'));
				if(!$res){
					$this->functions->log_exit(401, 'error', 'Login failed for ['.$_SERVER['REMOTE_ADDR'] .'], failed Recaptcha.');
				}
			}

			// Do login
			$login = $this->functions->login($this->input->post('user', TRUE), $this->input->post('pass'));
			if(!$login){
				$this->functions->ip_set_captcha();
				$this->functions->log_exit(401, 'error', 'Login failed for ['.$_SERVER['REMOTE_ADDR'] .'], invalid login.');
			}

			// Remove captcha if any and log
			if($captcha){ $this->functions->ip_set_captcha(FALSE); }
			log_message('info', "User $login->id logged in from [" .$_SERVER['REMOTE_ADDR'] ."]");

			// Save session
			$this->session->set_userdata('id', $login->id);

			redirect("/");
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
