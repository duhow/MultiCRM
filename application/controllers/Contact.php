<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends CI_Controller {

	public function __construct(){
		parent::__construct();

		if($this->functions->check_login() !== TRUE){
			redirect('logout');
		}
	}

	public function index(){ redirect('contact/list'); }

	public function view(int $id = NULL){
		if($id === NULL){ redirect('contact/list'); }
		$data['title'] = 'Lista de contactos';
		$data['css_files'] = ['base.css'];

		$contactinfo = $this->functions->get_contact_basic($id);

		if(!$contactinfo){
			redirect("/");
		}

		$contact = [
			'contact' => $contactinfo,
			'sources' => [
				'phone' => $this->functions->get_contact_phones($id),
				'email' => $this->functions->get_contact_emails($id),
			],
			'tags' => $this->functions->get_contact_tags($id),
			'tasks' => $this->functions->get_contact_tasks($id),
			'actions' => array()
		];

		$this->load->view('header', $data);
		$this->load->view('common/navbar');
		$this->load->view('common/header');
		$this->load->view('contact/view', ['contact' => $contact]);
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
