<?php
namespace admin\user;

use model as md;
use admin as adm;

/**
 +------------------------------
 *	第三方登录 
 +------------------------------
 */

class otherlogin extends adm\front {
	
	//列表
	function items(){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		$userauth = new md\userauth();
		$ret = $userauth -> search(array(), $page, $pagesize);
		//var_dump($ret);
		$items = $ret['items'];
		$total = $ret['total']>0?$ret['total']:0;
		
		$url = '/'.$this->mod.'/'.$this->col.'/';
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => $url,
			'params' => $params
		));
		
		$this -> smarty -> assign('items', $items);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('user/userauth_list.tpl');	
	}
	
	//删除 
	function del(){
			$params = $this -> params;
			//var_dump($params);die;
		$id=  $params[1];
		$userauth = new md\userauth();
		$ret = $userauth -> delete($id);
		//var_dump($ret);
		if($ret){
			cpmsg(true, '操作成功！', -1);	
		}else{
			//echo mysql_error();die;
			cpmsg(false, '操作失败！', -1);		
				
		}
		//$url = '/'.$this->mod.'/'.$this->col.'/';
		
		
	}
}
