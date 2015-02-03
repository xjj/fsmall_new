<?php
namespace shop;

class Categorys extends Front {
	
	function index(){
		
		$this -> smarty -> display('categorys.tpl');	
	}		
}