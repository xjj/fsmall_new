<?php
namespace suplier;

use model as md;

class Account extends Front {
	
	//查询
	function index(){
		$this -> items();
	}
	
	//查询
	function items(){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 60;
		
		$brand_id = $_SESSION['suplier']['brand_id'];
		
		$acc = new md\suplier_account();
		$ret = $acc -> search($brand_id, $_GET, $page, $pagesize);
		$account_items = $ret['items'];
		$total = $ret['total'];
		
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/account',
			'params' => $_GET,
		));

		$this -> smarty -> assign('account_items', $account_items);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('account.tpl');	
	}
	
	//查询某日的发货记录
	function detail(){
		$brand_id = $_SESSION['suplier']['brand_id'];
		$start_time = $_GET['start_time'];
		$end_time = $_GET['end_time'];
		
		if (isDate($start_time)){
			$start_time = strtotime($start_time);	
		}
		if (isDate($end_time)){
			$end_time = strtotime($end_time);		
		}
		
		if (!isint($brand_id) || $brand_id <= 0 || !isint($start_time) || $start_time <= 0 || !isint($end_time) || $end_time <= 0){
			cpmsg(false, $this->LANG['ERROR_QUERY_URL'], -1);	
		}
		
		if ($this->params[0] == 'refund'){
			$this -> refund_items($brand_id, $start_time, $end_time);
		} else {
			$this -> send_items($brand_id, $start_time, $end_time);
		}
	}
	
	//查询发货的商品
	private function send_items($brand_id, $start_time, $end_time){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 30;	
		
		$kr_send = new md\order_send_kr();
		$ret = $kr_send -> send_items($brand_id, $start_time, $end_time, $page, $pagesize);
		$order_items = $ret['items'];
		$total = $ret['total'];
		
		$this -> smarty -> assign('account_type', 'send');
		$this -> smarty -> assign('start_time', $start_time);
		$this -> smarty -> assign('end_time', $end_time);
		$this -> smarty -> assign('order_items', $order_items);
		$this -> smarty -> display('account_detail.tpl');	
	}
	
	private function refund_items($brand_id, $start_time, $end_time){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 30;	
		
		$kr_send = new md\order_send_kr();
		$ret = $kr_send -> refund_items($brand_id, $start_time, $end_time, $page, $pagesize);
		$order_items = $ret['items'];
		$total = $ret['total'];
		
		$this -> smarty -> assign('account_type', 'refund');
		$this -> smarty -> assign('start_time', $start_time);
		$this -> smarty -> assign('end_time', $end_time);
		$this -> smarty -> assign('order_items', $order_items);
		$this -> smarty -> display('account_detail.tpl');	
	}
}