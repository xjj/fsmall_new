<?php
namespace admin\order;
use model as md;
use admin as adm;

/**
 +-------------------------------
 *	断货取消
 +-------------------------------
 */
class KRCancel extends adm\Front {
	
	//韩国取消的商品列表
	function items(){
		
		$brand = new md\brand();
		$brand_items = $brand -> items(0);	
		$this -> smarty -> assign('brands',$brand_items);
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		$krCancel = new md\krCancel();
		$ret = $krCancel -> search($_GET, $page, $pagesize);
		
		$items = $ret['items'];
		$total = $ret['total'];
		
		$params = fetch_url_query($_GET);
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this->col,
			'params' => $params
		));
		
		$this -> smarty -> assign('items', $items);
		$this -> smarty -> assign('params', $params);

		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('order/krcancel_list.tpl');
	}

	//返还金额
	function return_money(){
		$id = intval($this -> params[1]);
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		$params = fetch_url_query($this -> params);
		$prd = new md\krCancel();
		$f = $prd -> return_money($id);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col);//?0=return_money&1=5
		} else {
			cpmsg(false, '操作失败！', -1);
		}
	}


}