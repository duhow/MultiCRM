<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct(){
		parent::__construct();

		if(!$this->uri->segment(2)){
			http_response_code(401);
			die();
		}

		$token = $this->uri->segment(2);
		if(!$this->_verifyToken($token)){ return $this->index(); }
		header("Content-Type: application/json");
	}

	public function index($token = NULL){
		http_response_code(401);
		die();
	}

	private function _verifyToken($token){
		$query = $this->db
			->where('id', $token)
			->group_start()
				->where('date_expire >=', 'NOW()', false)
				->or_where('date_expire IS NULL')
			->group_end()
		->get('token');

		// Token does not exist
		if($query->num_rows() == 0){
			return FALSE;
		}

		// Get 
		$token = $query->row();

		// Check source IP
		if($token->ip != "0.0.0.0" and !empty($token->ip)){
			if($_SERVER['REMOTE_ADDR'] != $token->ip){
				return FALSE;
			}
		}

		return TRUE;
	}

	public function contact($id = NULL, $type = NULL){
		// GET contact/id
		if($this->input->method(TRUE) == "GET" and is_numeric($id) and empty($type)){
			return $this->contact_get_all($id);
		}

		// HEAD contact/id
		if($this->input->method(TRUE) == "HEAD" and is_numeric($id) and empty($type)){
			return $this->contact_get_all($id);
		}

		// DELETE contact/id/tag
		if($this->input->method(TRUE) == "DELETE" and is_numeric($id) and $type == "tag"){
			return $this->contact_delete_tags($id);
		}
	}

	private function contact_delete_tags($id, $tags = NULL){
		if(empty($tags)){ $tags = file_get_contents('php://input'); }
		if(!$tags){
			http_response_code(400);
			return FALSE;
		}

		$tags = json_decode($tags);

		if(!$tags or count($tags) == 0){
			http_response_code(400);
			return FALSE;
		}

		$res = $this->functions->delete_contact_tags($id, $tags);

		if(!$res){
			http_response_code(500);
			return FALSE;
		}

		if($this->db->affected_rows() == 0){ http_response_code(204); }
		return TRUE;
	}

	private function contact_get_all($id){
		$mtime = microtime(TRUE);

		$contactinfo = $this->functions->get_contact_basic($id);
		$contact = array();

		if(!$contactinfo){
			$json = ['status' => 'ERROR', 'data' => 'NOT_FOUND'];
			$json = json_encode($json);
			http_response_code(404);
			echo $json;
			die();
		}

		// TODO Add timezone based on country or manual set TZ.
		$contact['contact'] = $contactinfo;

		// ----------------------

		$phones = $this->functions->get_contact_phones($id);
		if($phones){
			$contact['sources']['phone'] = $phones;
		}
		
		// ----------------------

		$emails = $this->functions->get_contact_emails($id);
		if($emails){
			$contact['sources']['email'] = $emails;
		}
		
		// ----------------------

		$tags = $this->functions->get_contact_tags($id);
		if($tags){
			$contact['tags'] = $tags;
		}

		// ----------------------

		$tasks = $this->functions->get_contact_tasks($id);
		if($tasks){
			$contact['tasks'] = $tasks;
		}

		// ----------------------

		$mtime = floor((microtime(TRUE) - $mtime) * 100000);
		$json = ['status' => 'OK', 'data' => $contact, 'time' => $mtime];
		$json = json_encode($json);
		// $json = json_encode($json, JSON_PRETTY_PRINT);
		echo $json;
		die();

	}
}
