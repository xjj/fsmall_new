<?php
namespace admin\order;

if (!defined('START')) exit('No direct script access allowed');

use admin as adm;
use model as md;

/**
 +------------------------------
 *	后台订单控制器
 +------------------------------
 */
class order extends adm\Front {
	
	//订单列表
	function items(){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 30;
		
		$order = new md\order();
		$ret = $order -> search_admin($_GET, $page, $pagesize);
		$order_items = $ret['items'];
		$total = $ret['total'];
		
		$GET = $_GET;
		if (isset($GET['page'])){unset($GET['page']);}
		$params = fetch_url_query($GET);
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this->col,
			'params' => $_GET
		));
		
		$order_status_items = $order -> fetch_all_status();		
		
		$this -> smarty -> assign('order_items', $order_items);
		$this -> smarty -> assign('order_status_items', $order_status_items);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> assign('params', $params);
		$this -> smarty -> display('order/order_list.tpl');
	}
	
	//订单商品信息页面
	function detail(){
		$order_id = $this -> params[1];
		if (!isint($order_id) || $order_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$order = new md\order();
		$order_product = new md\order_product();
		
		//查询订单信息
		$order_data = $order -> info($order_id);
		if ($order_data){
			$order_data['order_amount'] = format_money($order_data['order_amount']);
			$order_data['product_amount'] = format_money($order_data['product_amount']);
			$order_data['pay_amount'] = format_money($order_data['pay_amount']);
			$order_data['shipping_fee'] = format_money($order_data['shipping_fee']);
			$order_data['refund_amount'] = format_money($order_data['refund_amount']);
			$order_data['order_status_text'] = $order -> fetch_order_status($order_data['order_status']);
		} else {
			cpmsg(false, '该订单不存在或已被删除！', -1);
		}
		
		//查询订单的所有商品
		$product_items = $order_product -> items($order_id);
		
		//获取订单留言信息
		$order_message = new md\order_message();
		$order_message_items = $order_message -> items($order_id);
		
		$more_navs = array('订单号：'.$order_data['order_sn']);
		
		$this -> smarty -> assign('more_navs', $more_navs);
		$this -> smarty -> assign('order_data', $order_data);
		$this -> smarty -> assign('product_items', $product_items);
		$this -> smarty -> assign('order_message_items', $order_message_items);
		$this -> smarty -> display('order/order_detail.tpl');
	}
	
	//取消订单 -- 未付款的订单可以取消
	function cancle(){
		$order_id = $this -> params[1];
		if (!isint($order_id) || $order_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		//查询订单信息
		$order = new md\order();
		$order_data = $order -> info($order_id);
		if ($order_data){} else {
			cpmsg(false, '该订单不存在或已被删除！', -1);
		}
		
		if (intval($order_data['order_status']) !== 0){
			cpmsg(false, '只有待付款订单才能取消！', -1);
		}
		
		if ($order_data['pay_status'] == 1 || $order_data['pay_time'] > 0){
			cpmsg(false, '该订单存在支付信息，不能取消！', -1);
		}
		
		$url = '/'.$this -> mod.'/'.$this -> col;
		$params = fetch_url_query($_GET);
		if (!empty($params)){$url .= '?'.$params;}
		
		//取消订单
		$num = $order -> cancle($order_id);
		if ($num > 0){
			cpurl($url);
		} else {
			cpmsg(false, '取消订单失败，更新数据库错误！', -1);
		}
	}
	
	//订单确认
	function confirm(){
		$order_id = $this->params[1];
		
		if (!isint($order_id) || $order_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$order = new md\order();
		$order_data = $order -> info($order_id);
		if ($order_data){} else {
			cpmsg(false, '该订单不存在或已被删除！', -1);
		}
		
		if (
			$order_data['pay_status'] == 1 && 		//支付状态
			$order_data['order_status'] == 1 && 	//订单状态 = 已付款
			$order_data['pay_amount'] > 0 && 		//支付金额
			$order_data['pay_time'] > 0 && 			//支付时间
			$order_data['pay_log_id'] > 0			//支付日志
		) {} else {
			cpmsg(false, '该订单信息异常，不能进行订单确认，请与管理员联系！', -1);
		}
		
		//检查是否有订单商品
		$order_PRD = new md\order_product();
		$order_PRD_items = $order_PRD -> status_items($order_id, 0);
		if ($order_PRD_items){} else {
			cpmsg(false, '该订单没有有效的商品信息！', -1);
		}
		
		$f = $order -> confirm($order_id);
		if ($f){
			cpmsg(true, '订单确认成功！', '/'.$this->mod.'/'.$this->col.'/detail/'.$order_id);
		} else {
			cpmsg(false, '订单确认失败！', -1);
		}
	}
	
	//订单订货
	function ordered(){
		$order_id = $this -> params[1];
		
		if (!isint($order_id) || $order_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$order = new md\order();
		$order_data = $order -> info($order_id);
		if ($order_data){} else {
			cpmsg(false, '该订单不存在或已被删除！', -1);
		}
		
		if ($order_data['order_status'] != 2) {
			cpmsg(false, '该订单非已确认状态，不能进行订货操作！', -1);
		}
		
		//订单订货操作
		$f = $order -> orderToKR($order_id);
		if ($f){	
			cpmsg(true, '订货成功，订单商品已向韩方订货！', '/'.$this->mod.'/'.$this->col.'/detail/'.$order_id);
		} else {
			cpmsg(false, '订货操作失败！', -1);
		}
	}
	
	//订单留言
	function message(){
		$order_id = $_POST['order_id'];
		$content = trim($_POST['content']);
		
		if (empty($content)){
			cpmsg(false, '留言内容不能为空！', -1);
		}
		
		$order_message = new md\order_message();
		$f = $order_message -> add(array(
			'adm_uid' => $_SESSION['admin']['uid'],
			'order_id' => $order_id,
			'content' => $content,
		));
		
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col.'/detail/'.$order_id);
		} else {
			cpmsg(false, '留言失败！', -1);
		}
	}
	
	
	//取消订单商品
	function op_cancle(){
		$order_id = $this -> params[1];
		$op_id = $this -> params[2];
		
		if (!isint($order_id) || $order_id <= 0 || !isint($op_id) || $op_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$order = new md\order();
		$order_data = $order -> info($order_id);
		if ($order_data){} else {
			cpmsg(false, '订单不存在或已被删除！', -1);
		}
		
		$order_status = $order_data['order_status'];
		if ($order_status == -1){
			cpmsg(false, '该订单已被取消，不能执行此操作！', -1);
		}
		
		if ($order_status == 0){
			$this -> op_cancle_nopay($order_data, $op_id);
		} else {
			$this -> op_cancle_haspay($order_data, $op_id);
		}
	}
	
	/**
	 +----------------------------------------
	 *	取消商品 -- 未付款的商品进行的操作
	 +----------------------------------------
	 */
	private function op_cancle_nopay($order_data, $op_id){
		$order_id = $order_data['order_id'];
		
		$order_PRD = new md\order_product();
		$order = new md\order();
		
		//获取商品信息
		$order_PRD_data = $order_PRD -> info($op_id);
		if ($order_PRD_data){} else {
			cpmsg(false, '该订单商品不存在！', -1);
		}
		
		if ($order_data['order_id'] != $order_PRD_data['order_id']){
			cpmsg(false, '异常，商品不在订单中！', -1);
		}
		
		//判断是否仅剩此商品
		$f = 0;
		$order_PRD_items = $order_PRD -> status_items($order_id, 0);
		if ($order_PRD_items){
			foreach ($order_PRD_items as $item){
				if ($item['op_id'] != $op_id){$f = 1; break;}
			}
		} else {
			cpmsg(false, '该订单没有商品可以被取消！', -1);
		}
		//订单中没有商品时直接取消订单
		if ($f == 0){
			$order -> cancle($order_id);
			cpmsg(true, '取消该商品后订单为空，该订单同时被取消！', '/'.$this->mod.'/'.$this->col.'/detail/'.$order_id);
		}
		
		//取消操作
		$f = $order_PRD -> cancle_nopay($order_id, $op_id);
		if ($f){
			//重新计算订单价格
			$ret = $order -> Count_order_amount($order_id, $order_data);
			if ($ret !== false){
				$order -> update($order_id, array(
					'order_amount' => $ret['order_amount'],
					'shipping_fee' => $ret['shipping_fee'],
					'product_amount' => $ret['product_amount'] 
				));
				cpurl('/'.$this->mod.'/'.$this->col.'/detail/'.$order_id);
			} else {
				//取消刚才的取消操作
				$order_PRD -> cancle_nopay_back($order_id, $op_id);
				cpmsg(false, '订单价格重新计算失败，取消订单失败，请与管理员联系！');
			}
		} else {
			cpmsg(false, '订单商品取消失败！', -1);
		}
	}
	 
	/**
	 +----------------------------------------
	 *	取消商品 -- 已付款的商品进行的操作
	 +----------------------------------------
	 */
	private function op_cancle_haspay($order_data, $op_id){
		$order_id = $order_data['order_id'];
		$order_sn = $order_data['order_sn'];
		$uid = $order_data['uid'];
		
		$order_PRD = new md\order_product();
		$order = new md\order();
		
		//获取商品信息
		$order_PRD_data = $order_PRD -> info($op_id);
		if ($order_PRD_data){
			$prd_id = $order_PRD_data['prd_id'];
			$product_sn = $order_PRD_data['product_sn'];
		} else {
			cpmsg(false, '该订单商品不存在！', -1);
		}
		
		if ($order_id != $order_PRD_data['order_id']){
			cpmsg(false, '异常，商品不在订单中！', -1);
		}
		
		if ($order_PRD_data['product_status'] == 0 || $order_PRD_data['product_status'] == 1){
		} else {
			cpmsg(false, '该商品所处状态不能被取消！', -1);	
		}
		
		$amount = intval($order_PRD_data['price'] * $order_PRD_data['number']);
		
		if ($amount == intval($order_data['product_amount'])){
			//返还订单金额
			$refund_amount = $order_data['order_amount'];
		} else {
			//返还商品金额
			$refund_amount = $amount;
		}
		
		$f = $order_PRD -> cancle_haspay($order_id, $op_id);
		if ($f){
			$log_data = array(
				'uid' => $uid,
				'adm_uid' => $_SESSION['admin']['uid'],
				'order_sn' => $order_sn,
				'content' => '删除订单中商品'.$product_sn.'#'.$op_id.'，返还金额￥'.$refund_amount
			);
			//返还商品金额到用户余额
			$balance = new md\balance();
			$f = $balance -> add($uid, $refund_amount, $log_data);
			if ($f){
				//更新订单返还金额
				$order -> refund($order_id, $refund_amount);
			} else {
				cpmsg(false, '订单金额返还错误，请与管理员联系！', -1);
			}
			
			//查询是否有有效商品
			$PRD_items = $order_PRD -> validate_items($order_id);
			if ($PRD_items){} else {
				//订单全断货处理
				$order -> soldout($order_id);
			}

			cpurl('/'.$this->mod.'/'.$this->col.'/detail/'.$order_id);		
			
		} else {
			cpmsg(false, '取消商品失败！', -1);
		}
	}
	
	
	/**
	 +----------------------------------------
	 *	设置商品断货 -- 未发货的商品才能断货
	 +----------------------------------------
	 *	1	未付款的断货	
	 *	2	已付款的断货
	 +----------------------------------------
	 */
	function op_soldout(){
		$order_id = $this -> params[1];
		$op_id = $this -> params[2];
		
		if (!isint($order_id) || $order_id <= 0 || !isint($op_id) || $op_id <= 0){
			cpmsg(false, '请求错误！', -1);
		}
		
		$order = new md\order();
		
		//查询订单信息
		$order_data = $order -> info($order_id);
		if ($order_data){} else {
			cpmsg(false, '商品所属订单不存在或已被删除！', -1);
		}
		
		
		$order_status = $order_data['order_status'];
		if ($order_status == -1){
			cpmsg(false, '该订单已被取消，不能执行此操作！', -1);
		}
		
		if ($order_status == 0){
			$this -> op_soldout_nopay($order_data, $op_id);
		} else {
			$this -> op_soldout_haspay($order_data, $op_id);
		}
	}
	
	/**
	 +----------------------------------
	 *	未付款的订单断货
	 +----------------------------------
	 */
	private function op_soldout_nopay($order_data, $op_id){
		$order_id = $order_data['order_id'];
		
		$order = new md\order();
		$order_PRD = new md\order_product();
		
		//获取商品信息
		$order_PRD_data = $order_PRD -> info($op_id);
		if ($order_PRD_data){
			$prd_id = $order_PRD_data['prd_id'];
		} else {
			cpmsg(false, '该商品不存在！', -1);
		}
		
		if ($order_data['order_id'] != $order_PRD_data['order_id']){
			cpmsg(false, '异常，商品不在订单中！', -1);
		}
		
		//判断是否仅剩此商品
		$f = 0;
		$order_PRD_items = $order_PRD -> status_items($order_id, 0);
		if ($order_PRD_items){
			foreach ($order_PRD_items as $item){
				if ($item['op_id'] != $op_id){$f = 1; break;}
			}
		} else {
			cpmsg(false, '该订单没有商品可以被取消！', -1);
		}
		//订单中没有商品时直接取消订单
		if ($f == 0){
			$order -> cancle($order_id);
			cpmsg(true, '该商品断货后订单为空，该订单同时被取消！', '/'.$this->mod.'/'.$this->col.'/detail/'.$order_id);
		}
		
		//取消操作
		$f = $order_PRD -> soldout_nopay($order_id, $op_id);
		if ($f){
			//商品断货更新
			$soldout = new md\product_soldout();
			$soldout -> add($prd_id);
			
			//重新计算订单价格
			$ret = $order -> Count_order_amount($order_id, $order_data);
			if ($ret !== false){
				$order -> update($order_id, array(
					'order_amount' => $ret['order_amount'],
					'shipping_fee' => $ret['shipping_fee'],
					'product_amount' => $ret['product_amount'] 
				));
				cpurl('/'.$this->mod.'/'.$this->col.'/detail/'.$order_id);
			} else {
				//取消刚才的取消操作
				$order_PRD -> soldout_nopay_back($order_id, $op_id);
				cpmsg(false, '订单价格重新计算失败，取消订单失败，请与管理员联系！');
			}
		} else {
			cpmsg(false, '订单商品取消失败！', -1);
		}	
	}
	
	/**
	 +-------------------------------
	 *	已付款的订单断货
	 +-------------------------------
	 */
	private function op_soldout_haspay($order_data, $op_id){
		$order_id = $order_data['order_id'];
		$order_sn = $order_data['order_sn'];
		$uid = $order_data['uid'];
		
		$order_PRD = new md\order_product();
		$order = new md\order();
		
		//获取商品信息
		$order_PRD_data = $order_PRD -> info($op_id);
		if ($order_PRD_data){
			$prd_id = $order_PRD_data['prd_id'];
			$product_sn = $order_PRD_data['product_sn'];
		} else {
			cpmsg(false, '该订单商品不存在！', -1);
		}
		
		if ($order_id != $order_PRD_data['order_id']){
			cpmsg(false, '异常，商品不在订单中！', -1);
		}
		
		if ($order_PRD_data['product_status'] == 0 || $order_PRD_data['product_status'] == 1){
		} else {
			cpmsg(false, '该商品所处状态不能被取消！', -1);	
		}
		
		$amount = intval($order_PRD_data['price'] * $order_PRD_data['number']);
		
		if ($amount == intval($order_data['product_amount'])){
			//返还订单金额
			$refund_amount = $order_data['order_amount'];
		} else {
			//返还商品金额
			$refund_amount = $amount;
		}
		
		$f = $order_PRD -> soldout_haspay($order_id, $op_id);
		if ($f){
			
			//商品断货更新
			$soldout = new md\soldout();
			$soldout -> add($prd_id);
			
			$log_data = array(
				'uid' => $uid,
				'adm_uid' => $_SESSION['admin']['uid'],
				'order_sn' => $order_sn,
				'content' => '订单中商品'.$product_sn.'断货#'.$op_id.'，返还金额￥'.$refund_amount
			);
			
			//返还商品金额到用户余额
			$balance = new md\balance();
			$f2 = $balance -> add($uid, $refund_amount, $log_data);
			if ($f2){
				//更新订单返还金额
				$order -> refund($order_id, $refund_amount);
			}
			
			//查询是否有有效商品
			$PRD_items = $order_PRD -> validate_items($order_id);
			if ($PRD_items){} else {
				//订单全断货处理
				$order -> soldout($order_id);
			}

			cpurl('/'.$this->mod.'/'.$this->col.'/detail/'.$order_id);		
			
		} else {
			cpmsg(false, '商品断货失败！', -1);
		}
	}
	
}


