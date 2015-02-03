<?php
namespace model;

/**
 +------------------------------
 *	
 +------------------------------
 */
class Api extends front {

	function zhengpin_verify($prd_id){ 
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		$data=array('verify_time'=>time());
		return $this -> db ->update('order_product_barcode',$data,' id= '.$prd_id); 
	}


}