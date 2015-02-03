<?php
namespace suplier;

if (!defined('START')) exit('No direct script access allowed');

use model as md;

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