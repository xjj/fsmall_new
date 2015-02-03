<?php
namespace shop;

use model as md;

/**
 +-----------------------------
 *	退货明细
 +-----------------------------
 */
class User_Refund extends Front {
	
	function index(){
		
		$this -> smarty -> display('user/refund.tpl');	
	}	
}