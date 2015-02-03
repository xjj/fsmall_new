<?php
namespace admin\order;
use model as md;
use admin as adm;

/**
 +-------------------------------
 *	退货管理
 +-------------------------------
 */
class Refund extends adm\Front {
	
	//退货列表
	function items(){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 10;
		
		$refund = new md\order_refund();
		$ret = $refund -> search($_GET, $page, $pagesize);
		$refund_items = $ret['items'];
		$total = $ret['total'];
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this->col,
			'params' => $_GET
		));
		
		$params = fetch_url_query($_GET, 'page');
		
		$this -> smarty -> assign('refund_items', $refund_items);
		$this -> smarty -> assign('params', $params);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('order/refund_list.tpl');
	}
	
	//审核通过
	function allow(){
		$id = intval($this -> params[1]);
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		$reply = trim($_GET['reply']);
		
		$refund = new md\order_refund();
		$info = $refund -> info($id);
		if ($info){} else {
			cpmsg(false, '该退货信息不存在或已被删除！', -1);	
		}
		
		if ($info['refund_status'] != 1){
			cpmsg(false, '该退货信息已被处理！', -1);
		}
		
		if ($info['return_status'] == 1){
			cpmsg(false, '资金已经返还 无法更改状态！', -1);
		}
		
		$params = fetch_url_query($_GET);
		
		$f = $refund -> allow(array(
			'id' => $id, 
			'reply' => $reply,
			'adm_uid' => $_SESSION['admin']['uid']
		));
		if ($f){
			$url = '/'.$this->mod.'/'.$this->col;
			$url .= empty($params) ? '' : '?'.$params;
			cpurl($url);
		} else {
			cpmsg(false, '操作失败！', -1);
		}
	}
	
	//审核拒绝
	function deny(){
		$id = intval($this -> params[1]);
		
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$reply = trim($_GET['reply']);
		
		$refund = new md\order_refund();
		$info = $refund -> info($id);
		if ($info){} else {
			cpmsg(false, '该退货信息不存在或已被删除！', -1);	
		}
		
		if ($info['refund_status'] != 1){
			cpmsg(false, '该退货信息已被处理！', -1);
		}
		
		if ($info['return_status'] == 1){
			cpmsg(false, '资金已经返还 无法更改状态！', -1);
		}
		
		$params = fetch_url_query($_GET);
		
		$f = $refund -> deny(array(
			'id' => $id, 
			'reply' => $reply,
			'adm_uid' => $_SESSION['admin']['uid']
		));
		
		if ($f){
			$url = '/'.$this->mod.'/'.$this->col;
			$url .= empty($params) ? '' : '?'.$params;
			cpurl($url);
		} else {
			cpmsg(false, '操作失败！', -1);
		}
	}
	
	//返还金额
	function return_money(){
		$id = intval($this -> params[1]);
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$params = fetch_url_query($_GET);
		
		$refund = new md\Order_refund();
		
		$f = $refund -> return_money($id, $_SESSION['admin']['uid']);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col.'?'.$params);
		} else {
			cpmsg(false, '操作失败！退还资金请先审核通过', -1);
		}
	}
	
	//不返还金额
	function return_no_money(){
		$id = intval($this -> params[1]);
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$params = fetch_url_query($_GET);
		
		$refund = new md\Order_refund();
		$f = $refund -> return_no_money($id);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col.'?'.$params);
		} else {
			cpmsg(false, '操作失败！', -1);
		}	
	}

	//审核退货
	function detail(){
		$id = $this -> params[1];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		//查询
		$refund = new md\order_refund();
		$refund_info = $refund -> info($id);
		if ($refund_info){} else {
			cpmsg(false, '没有查询到该退货信息！', -1);
		}
		
		if (!empty($refund_info['pics'])){
			$refund_info['pics'] = explode('|', trim($refund_info['pics'],'|'));	
		}
		
		//获取商品信息
		$op_id = $refund_info['op_id'];
		$order_PRD = new md\order_product();
		$orderPrdInfo = $order_PRD -> info($op_id);
		if ($orderPrdInfo){
			$refund_info['product_name'] = $orderPrdInfo['product_name'];
			$refund_info['product_sn'] = $orderPrdInfo['product_sn'];
			$refund_info['price'] = $orderPrdInfo['price'];
		}
		
		$this -> smarty -> assign('refund_info', $refund_info);
		$this -> smarty -> display('order/refund_detail.tpl');
	}
	
	//韩方同意退货
	function agree(){
		$id = $this -> params[1];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		//查询退货信息
		$refund = new md\order_refund();
		$info = $refund -> info($id);
		if ($info){
			$refund_status = $info['refund_status'];
			$kr_agree = $info['kr_agree'];	
			$barcode = $info['barcode'];
		} else {
			cpmsg(false, '退货信息不存在或已被删除！', -1);	
		} 
		
		if ($refund_status != 2){
			cpmsg(false, '只有同意退货的订单，才能执行该操作！', -1);	
		}
		
		if ($kr_agree != 0){
			cpmsg(false, '该退货信息的状态，不准许执行此操作！', -1);	
		}
		
		$f = $refund -> agree($id, $barcode);
		if ($f){
			$params = fetch_url_query($_GET);
			$url = '/'.$this->mod.'/'.$this->col.'';
			if (!empty($params)){$url .= '?'.$params;}
			cpurl($url);
		} else {
			cpmsg(false, '更新失败！', -1);	
		}	
	}
	
	//韩方不同意退货
	function disagree(){
		$id = $this -> params[1];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		//查询退货信息
		$refund = new md\order_refund();
		$info = $refund -> info($id);
		if ($info){
			$refund_status = $info['refund_status'];
			$kr_agree = $info['kr_agree'];	
		} else {
			cpmsg(false, '退货信息不存在或已被删除！', -1);	
		} 
		
		if ($refund_status != 2){
			cpmsg(false, '只有同意退货的订单，才能执行该操作！', -1);	
		}
		
		if ($kr_agree != 0){
			cpmsg(false, '该退货信息的状态，不准许执行此操作！', -1);	
		}
		
		$f = $refund -> disagree($id);
		if ($f){
			$params = fetch_url_query($_GET);
			$url = '/'.$this->mod.'/'.$this->col.'';
			if (!empty($params)){$url .= '?'.$params;}
			cpurl($url);
		} else {
			cpmsg(false, '更新失败！', -1);	
		}		
	}
}