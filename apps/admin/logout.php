<?php
namespace admin;

if (!defined('START')) exit('No direct script access allowed');

/**
 +--------------------------
 *	注销控制器
 +--------------------------
 */

class Logout extends Front {
	
	function index(){
		$login = new login();
		$login -> destroy();
		cpurl('/login');
	}
}