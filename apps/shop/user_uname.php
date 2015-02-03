<?php
namespace shop;

/**
 +-----------------------------
 *	用户名更新
 +-----------------------------
 */
class User_Uname extends Front {
	
	function index(){
		
		
		$this -> smarty -> display('user/update_uname.tpl');	
	}
	
	private function submit(){
			
	}
}