<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Functions extends CI_Model {
	public function ip_requires_captcha($ip = NULL){
		if(empty($ip)) { $ip = $_SERVER['REMOTE_ADDR']; }
		$query = $this->db
			->where('ip', $ip)
			->where('expires >=', date("Y-m-d H:i:s"))
		->get('captcha');

		return $query->num_rows() > 0;
	}

	public function ip_set_captcha($expires = 86400, $ip = NULL){
		if(empty($ip)) { $ip = $_SERVER['REMOTE_ADDR']; }
		// REMOVE
		if($expires === FALSE){
			return $this->db
				->where('ip', $ip)
			->delete('captcha');
		}
		// CREATE
		if(is_numeric($expires) and $expires < time()){
			$expires = time() + $expires;
		}elseif(is_string($expires)){
			$expires = strtotime($expires);
		}
		$expires = date("Y-m-d H:i:s", $expires);
		$data = ['ip' => $ip, 'expires' => $expires];

		$sql = $this->db->insert_string('captcha', $data) .' ON DUPLICATE KEY UPDATE expires = "' .$expires .'"';
		return $this->db->query($sql);
	}

	public function check_login(){
		if(!$this->session->userdata('id')){ return FALSE; }
		$id = (int) $this->session->userdata('id');

		$query = $this->db
			->where('id', $id)
			->where('blocked', FALSE)
		->get('user');

		return ($query->num_rows() == 1);
	}

	public function log_exit($code, $type = NULL, $message = NULL){
		http_response_code($code);
		if($type and $message){
			log_message($type, $message);
		}
		exit();
	}

	public function login($username, $password){
		$type = (filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username');
		$query = $this->db
			->where($type, $username)
		->get('user');

		if($query->num_rows() != 1){
			// User or email not found.
			return FALSE;
		}

		$user = $query->row();
		if(!password_verify($password, $user->password)){
			// Incorrect password
			return FALSE;
		}

		return $user;
	}

	public function get_contact_basic($id){
		$query = $this->db
			->where('id', $id)
		->get('contact');

		if($query->num_rows() == 0){ return FALSE; }
		return $query->row_array();
	}

	public function get_contact_phones($id){
		$query = $this->db
			->where('contactid', $id)
			->order_by('priority')
		->get('contact_source_phone');

		if($query->num_rows() == 0){ return array(); }
		$phones = array();
		foreach($query->result() as $row){
			$data = [
				'type' => $row->type,
				'country' => $row->country,
				'phone' => $row->phone,
				'extension' => $row->extension,
				'verified' => (bool) $row->verified,
			];
			if($row->last_date){
				$data['last'] = [
					$row->last_date,
					$row->last_user
				];
			}
			$phones[] = $data;
		}
		return $phones;
	}

	public function get_contact_emails($id){
		$query = $this->db
			->where('contactid', $id)
			->order_by('priority')
		->get('contact_source_email');

		if($query->num_rows() == 0){ return array(); }
		$emails = array();
		foreach($query->result() as $row){
			$data = [
				'type' =>  $row->type,
				'email' => $row->email,
				'verified' => (bool) $row->verified,
				'subscribed' => (bool) $row->subscribed,
			];
			$emails[] = $data;
		}
		return $emails;
	}

	public function get_contact_tags($id){
		$query = $this->db
			->select('tag')
			->where('contactid', $id)
			->order_by('tag')
		->get('contact_tags');

		if($query->num_rows() == 0){ return array(); }
		return array_column($query->result_array(), 'tag');
	}

	public function delete_contact_tags($id, $tags){
		if(is_string($tags)){ $tags = [$tags]; }

		return $this->db
			->where('contactid', $id)
			->where_in('tag', $tags)
		->delete('contact_tags');
	}

	public function add_contact_tags($id, $tags){
		if(is_string($tags)){ $tags = [$tags]; }

		$inserts = array();
		foreach($tags as $tag){
			$inserts[] = [
				'contactid' => $id,
				'tag' => $tag
			];
		}

		return $this->db->insert_batch('contact_tags', $inserts);
	}

	public function get_contact_tasks($id, $finished = FALSE){
		if(!$finished){
			$this->db->where('date_completed IS NULL');
		}
		$query = $this->db
			->where('contactid', $id)
			->order_by('date_add')
		->get('contact_tasks');
			if($query->num_rows() == 0){ return array(); }

		$tasks = array();
		foreach($query->result() as $task){
			$tasks[] = [
				'id' => $task->id,
				'user' => $task->user,
				'task' => $task->task,
				'priority' => $task->priority,
				'date_add' => $task->date_add,
				'date_completed' => $task->date_completed,
				'date_pending' => $task->date_pending
			];
		}
		return $tasks;
	}
}
