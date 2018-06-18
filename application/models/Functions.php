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
}
