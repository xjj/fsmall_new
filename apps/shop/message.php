<?php
namespace shop;

/**
 +-----------------------------
 *	消息提示类
 +-----------------------------
 */
class message extends Front {
	
	//商品不存在或已被删除
	function Product_Not_Exist(){
		
		$this -> smarty -> assign('title', '错误提示');
		$this -> smarty -> display('message/not_exist_product.tpl');
	}
}