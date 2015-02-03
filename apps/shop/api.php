<?php
namespace shop;

use model as md;
use shop\api as api;

/**
 +------------------------------
 *	
 +------------------------------
 */

class Api extends Front {
	
	
	
	// 正品验证
	function zhengpin_verify(){
		$prof = new api\zhengpin_verify();
		$prof -> index();//执行
	}
	
	
}