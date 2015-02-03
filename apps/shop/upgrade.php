<?php
namespace shop;

/**
 +----------------------------------
 *	会员申请
 +----------------------------------
 */
class UpGrade extends Front {
	
	function index(){
		
		$this -> smarty -> display('user/upgrade.tpl');		
	}	
	
}
