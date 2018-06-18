<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {

	public function index(){
		$this->load->view('welcome_message');
	}

	public function view($id = NULL){
		if($id === NULL){ redirect("../"); }
		$data['title'] = 'Lista de contactos';
		$data['css_files'] = ['base.css'];

		$this->load->view('header', $data);
		$this->load->view('common/navbar');
		$this->load->view('common/header');
		$this->load->view('contact/view');

	}

	// route via "list"
	public function list_contacts(){
		$data['title'] = 'Lista de contactos';
		$data['css_files'] = ['base.css'];

		$this->load->view('header', $data);
		$this->load->view('common/navbar');
		$this->load->view('common/header');
		$this->load->view('contact/list');
	}
}
