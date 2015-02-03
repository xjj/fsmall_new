<?php
namespace admin\order;

use model as md;
use admin as adm;

/**
 +--------------------------------------
 *	退货
 +--------------------------------------
 */
class refund extends adm\front {
	
	//退货列表
	function items(){
		$un = trim($_GET['un']);
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		if (!empty($un)){
			$where = ' WHERE u.uname like \'%'.encode($un).'%\'';
		} else {
			$where = '';
		}
		
		$sql = 'SELECT t.*, u.uname FROM `refunds` t LEFT JOIN `users` u ON t.uid = u.uid '.$where.' ORDER BY t.id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$refund_items = $this -> db -> rows($sql);
		
		
		$sql = 'SELECT COUNT(*) AS num FROM `refunds` t LEFT JOIN `users` u ON t.uid = u.uid '.$where.'';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		$GET = $_GET;
		if (isset($GET['page'])){unset($GET['page']);}
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this->col,
			'params' => fetch_url_query($GET)
		));
		
		$this -> smarty -> assign('refund_items', $refund_items);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('order/refund_list.tpl');
	}
}