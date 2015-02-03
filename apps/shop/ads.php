<?php
namespace shop;
use model as md;
if (!defined('START')) exit('No direct script access allowed');


/**
 +-----------------------------
 *	广告接口 供前台js调用  
 +-----------------------------
 */
class ads extends Front {
	
	function get(){
		//var_dump($this -> params );die;
		$p = $this -> params[0];
		$p= trim($p);
		
		$ads = new md\ads();
		$res= $ads -> search($p);
		//var_dump($res);
		//echo time();
		$re= json_encode($res);
		echo $re;
	}

	
}