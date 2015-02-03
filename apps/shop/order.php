<?php
namespace shop;

use model as md;

/**
 +-------------------------------
 *	订单控制器
 +-------------------------------
 */
class Order extends Front {
	
	function index(){
		$this -> items();
	}
	
	//订单列表
	function items(){
		if (!$this -> islogin){
			$login = new login();
			$login -> index();
			exit();
		}
		
		$uid = $_SESSION['uid'];
		$user = new md\user();
		$user_data = $user -> info($uid);
		$this -> smarty -> assign('user_data', $user_data);
		
		$status = $this -> params[0];
		if (!in_array($status, array('nopay', 'nosend', 'send', 'receive'))){
			$status = 'all';
		}
		
		$uid = $_SESSION['uid'];
		$grade_id = $_SESSION['grade_id'];
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		$k = trim($_GET['k']);
		$params = array('uid' => $uid);
		if (!empty($k)){$params['k'] = $k;}
		$params['status'] = $this -> params[0];
		
		$order = new md\order();
		$ret = $order -> search_user_order($params, $page, $pagesize);
		$order_items = $ret['items'];
		$total = $ret['total'];
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/order/items',
			'params' => $_GET,
		));
		
		$this -> smarty -> assign('title', '我的订单');
		$this -> smarty -> assign('status', $status);
		$this -> smarty -> assign('user_data', $user_data);
		$this -> smarty -> assign('order_items', $order_items);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('user/order_list.tpl');
	}
	
	//查看订单页面
	function detail(){
		$order_sn = $this -> params[0];
		
		$uid = $_SESSION['uid'];
		$user = new md\user();
		$user_data = $user -> info($uid);
		$this -> smarty -> assign('user_data', $user_data);
		
		//查询订单信息
		$order = new md\order();
		$order_data = $order -> get_info_by_sn($order_sn);
		if ($order_data){
			$order_id = $order_data['order_id'];
			$order_data['order_amount'] = format_money($order_data['order_amount']);
			$order_data['shipping_fee'] = format_money($order_data['shipping_fee']);
			$order_data['product_amount'] = format_money($order_data['product_amount']);
			$order_data['order_status_text'] = $order -> fetch_order_status($order_data['order_status']);
		} else {
			cpmsg(false, '没有查询到该订单信息，该订单不存在或已被删除！', -1);
		}
		
		$order_PRD = new md\order_product();
		$order_items = $order_PRD -> items($order_id);
		
		$this -> smarty -> assign('title', '订单：'.$order_sn);
		$this -> smarty -> assign('order_data', $order_data);
		$this -> smarty -> assign('order_items', $order_items);
		$this -> smarty -> display('user/order_detail.tpl');
	}
	
	
	//添加到订单 -- 将购物车信息添加到订单
	function add(){
		if (!$this -> islogin){
			echo json_encode(array(
				'error' => -1,
				'message' => '请登录！'
			));
			exit();
		}
		
		$data = $_POST;
		$data['uid'] = $_SESSION['uid'];
		$data['grade_id'] = $_SESSION['grade_id'];
		
		$order = new md\order();
		$ret = $order -> cart_to_order($data);
		echo json_encode($ret);
	}
	
	
	//取消订单
	//未付款的订单可以取消
	function cancle(){
		if (!$this -> islogin){
			echo json_encode(array(
				'error' => -1,
				'message' => '请登录！'
			));
			exit();
		}
		
		$order_id = $_POST['order_id'];
		$uid = $_SESSION['uid'];
		
		$order = new md\order();
		$order_data = $order -> info($order_id);
		if ($order_data){} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '该订单不存在！'
			));
			exit();
		}
		if ($order_data['order_status'] == -1){
			echo json_encode(array(
				'error' => -1,
				'message' => '该订单已被取消！'
			));
			exit();
		}
		if ($order_data['is_delete'] == 1){
			echo json_encode(array(
				'error' => -1,
				'message' => '该订单已被删除！'
			));
			exit();
		}
		if ($order_data['pay_status'] == 1 || $order_data['pay_time'] > 0){
			echo json_encode(array(
				'error' => -1,
				'message' => '该订单存在支付信息，不能执行此操作！'
			));
			exit();
		}
		if ($order_data['uid'] != $uid){
			echo json_encode(array(
				'error' => -1,
				'message' => '您没有权限执行此操作！'
			));
			exit();
		}
		if ($order_data['order_status'] != 0){
			echo json_encode(array(
				'error' => -1,
				'message' => '该订单不能执行此操作！'
			));
			exit();
		}
		
		$f = $order -> cancle($order_id);
		if ($f){
			echo json_encode(array(
				'error' => 0
			));
		} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '取消订单失败！'
			));
		}
	}
	
	//删除订单
	function delete(){
		if (!$this -> islogin){
			echo json_encode(array(
				'error' => -1,
				'message' => '请登录！'
			));
			exit();
		}
		
		$order_id = $_POST['order_id'];
		$uid = $_SESSION['uid'];
		
		$order = new md\order();
		$order_data = $order -> info($order_id);
		if ($order_data){} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '该订单不存在！'
			));
			exit();
		}
		if ($order_data['order_status'] != -1){
			echo json_encode(array(
				'error' => -1,
				'message' => '该订单不能执行此操作！'
			));
			exit();
		}
		if ($order_data['is_delete'] == 1){
			echo json_encode(array(
				'error' => -1,
				'message' => '该订单已被删除！'
			));
			exit();
		}
		if ($order_data['uid'] != $uid){
			echo json_encode(array(
				'error' => -1,
				'message' => '您没有权限执行此操作！'
			));
			exit();
		}
		$f = $order -> delete($order_id);
		if ($f){
			echo json_encode(array(
				'error' => 0
			));
		} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '取消删除失败！'
			));
		}
	}
	
	//客户收货
	function receive(){
		if (!$this -> islogin){
			echo json_encode(array(
				'error' => -1,
				'message' => '请登录！'
			));
			exit();
		}
		$order_id = $_POST['order_id'];
		$op_id = $_POST['op_id'];
		$uid = $_SESSION['uid'];
		
		$order = new md\order();
		$order_data = $order -> info($order_id);
		if ($order_data){} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '该订单不存在！'
			));
			exit();
		}
		if ($order_data['uid'] != $uid){
			echo json_encode(array(
				'error' => -1,
				'message' => '您没有权限执行此操作！'
			));
			exit();
		}
		
		$order_PRD = new md\order_product();
		$f = $order_PRD -> user_receive($order_id, $op_id);
		if ($f){
			echo json_encode(array('error' => 0));
		} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '确认收货失败！'
			));	
		}
	}
	
	//商品物流查询
	function shipping(){
		if (!$this -> islogin){
			echo json_encode(array(
				'error' => -1,
				'message' => '请登录！'
			));
			exit();
		}
		
		$order_id = $_POST['order_id'];
		$op_id = $_POST['op_id'];
		$uid = $_SESSION['uid'];
		
		$tpl = new md\order_product_shipping_log();
		$data = $tpl -> data($op_id);
		if (is_array($data)){
			echo json_encode(array(
				'error' => 0,
				'data' => $data
			));
		} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '没有物流信息！'
			));	
		}
	}
}