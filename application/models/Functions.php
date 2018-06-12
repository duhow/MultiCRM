<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Functions extends CI_Model {
	public function ip_requires_captcha($ip = NULL){
		if(empty($ip)) { $ip = $_SERVER['REMOTE_ADDR']; }
		// DEBUG
		return TRUE;
	}
}
