<?php
namespace shop;

/**
 +-----------------------------
 *	输出显示条码
 +-----------------------------
 */
class barcode extends front {

	function index(){
		$this -> output();	
	}	
	
	function output(){
		$bc = $_GET['code'];
		$h = intval($_GET['h']);
		
		if (!isint($bc)){
			return false;	
		}
		if ($h <= 0){$h = 25;}
		
		$barcode = new \barcode();
		$barcode -> draw($bc, $h);
	}
}
