<?php
namespace shop;

/**
 *	错误控制器
 */
class Error extends Front {
	
	function show404(){
		$this -> smarty -> display('error/404.tpl');
	}
}