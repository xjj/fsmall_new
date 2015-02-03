<?php
namespace shop;

use model as md;

class Logout extends Front {
	
	function index(){
		
		$login = new login();
		$login -> destroy();
		
		cpurl('/');
	}
}