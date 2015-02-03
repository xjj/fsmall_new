<?php
namespace shop;

use model as md;

/**
 +-----------------------------
 *	购物车控制器
 +-----------------------------
 */
class Cart extends Front {
	
	//购物车商品信息列表
	function index(){
		if (!$this -> islogin){
			$login = new login();
			$login -> index();
			exit();
		}
		
		$uid = $_SESSION['uid'];
		$user = new md\user();
		$user_data = $user -> info($uid);
		
		//清理掉过期的商品
		$cart = new md\cart();
		$cart -> clear_timeout();
		
		//重置未过期时间
		$cart -> reset_timeout($uid);
		
		//查询购物车商品信息
		$total_number = $total_price = 0;
		$cart_items = $cart -> items($uid, $user_data['grade_id']);
		if ($cart_items){
			foreach ($cart_items as $item){
				$total_number += $item['number'];
				$total_price  += $item['number'] * $item['price'];
			}	
		}
		
		//统计
		$this -> smarty -> assign('title', '我的购物车');
		$this -> smarty -> assign('user_data', $user_data);
		$this -> smarty -> assign('cart_items', $cart_items);
		$this -> smarty -> assign('total_number', $total_number);
		$this -> smarty -> assign('total_price', $total_price);
		$this -> smarty -> display('user/cart.tpl');
	}
	
	//商品加入购物车
	function add_to_cart(){
		if (!$this -> islogin){
			echo json_encode(array(
				'error' => -1,
				'message' => '您需要登录，才能进行这项操作！',
			));
			exit();
		}
		
		$prd_id = $_POST['prd_id'];
		$sku_id = $_POST['sku_id'];
		$number = $_POST['number'];
		
		$prd = new md\product();
		$prd_data = $prd -> info($prd_id);
		if ($prd_data){} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '该商品不存在或已下架！'
			));
			exit();
		}
		
		if ($prd_data['is_delete'] == 1){
			echo json_encode(array(
				'error' => -1,
				'message' => '抱歉，该商品已下架！'
			));
			exit();
		}
		
		if ($prd_data['is_soldout'] == 1){
			echo json_encode(array(
				'error' => -1,
				'message' => '抱歉，该商品已断货！'
			));
			exit();
		}
		
		$sku = new md\product_sku();
		$sku_data = $sku -> info($sku_id);
		if ($sku_data){} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '该商品不存在或已下架！'
			));
			exit();
		}
		if ($sku_data['is_delete'] == 1){
			echo json_encode(array(
				'error' => -1,
				'message' => '抱歉，该商品已下架！'
			));
			exit();
		}
		
		if ($sku_data['is_soldout'] == 1){
			echo json_encode(array(
				'error' => -1,
				'message' => '抱歉，该商品已断货！'
			));
			exit();
		}
		
		if ($sku_data['stock'] < $number){
			echo json_encode(array(
				'error' => -1,
				'message' => '该商品的库存数不足！'
			));
			exit();
		}
		
		$cart = new md\cart();
		
		//获取购物车内是否有该商品信息
		$cart_data = $cart -> get_info_by_SKU($_SESSION['uid'], $sku_id);
		if ($cart_data){
			$number = $cart_data['number'] + $number;
		} else {
			if ($sku_data['number'] > $number){
				echo json_encode(array(
					'error' => -1,
					'message' => '该商品的起售数量为'.$sku_data['number'].'！'
				));
				exit();
			}
		}
		
		$f = $cart -> add(array(
			'uid' => $_SESSION['uid'],
			'grade_id' => $_SESSION['grade_id'],
			'sku_id' => $sku_id,
			'prd_id' => $prd_id,
			'number' => $number,
		));
		if ($f){
			echo json_encode(array('error' => 0, 'message' => 'ok'));
		} else {
			echo json_encode(array('error' => -1, 'message' => '添加到购物车失败！'));
		}
	}
	
	//删除购物车中的商品
	function del(){
		if (!$this -> islogin){
			echo json_encode(array(
				'error' => -1,
				'message' => '您需要登录，才能进行这项操作！',
			));
			exit();
		}
		
		$sku_id = $_POST['sku_id'];
		if (!isint($sku_id) || $sku_id <= 0){
			echo json_encode(array(
				'error' => -1,
				'message' => '错误的请求！'
			));
			exit();
		}
		
		$cart = new md\cart();
		$f = $cart -> delete($sku_id, $_SESSION['uid']);
		if ($f > 0){
			echo json_encode(array(
				'error' => 0,
				'message' => 'ok'
			));
		} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '商品删除失败！'
			));
		} 
	}
	
	//更新商品的数量
	function update_number(){
		if (!$this -> islogin){
			echo json_encode(array(
				'error' => -1,
				'message' => '您需要登录，才能进行这项操作！',
			));
			exit();
		}
		
		$sku_id = $_POST['sku_id'];
		$number = $_POST['number'];
		
		//查询sku信息
		$sku = new md\product_sku();
		$sku_data = $sku -> info($sku_id);
		if ($sku_data){} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '该商品不存在或已被删除！'
			));
			exit();
		}
		
		if ($number < $sku_data['number']){
			echo json_encode(array(
				'error' => -1,
				'number' => $sku_data['number'],
				'message' => '购买数量不能低于该商品的起售数量！'
			));
			exit();
		}
		
		if ($number > $sku_data['stock']){
			echo json_encode(array(
				'error' => -1,
				'message' => '购买数量不能大于库存数！'
			));
			exit();
		}
		
		
		//查询该购物车信息
		$cart = new md\cart();
		$cart_info = $cart -> get_info_by_sku($_SESSION['uid'], $sku_id);
		if ($cart_info){} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '购物车中没有查询到该商品信息，或已被删除！'
			));
			exit();	
		}
		
		$f = $cart -> update_number($sku_id, $_SESSION['uid'], $number);
		if ($f > 0){
			echo json_encode(array(
				'error' => 0,
				'number' => $number
			));
		} else {
			echo json_encode(array(
				'error' => -1,
				'number' => $cart_info['number'],
				'message' => '商品数量更新失败！',
			));
		}
	}
	
	//删除多条
	function del_multi(){
		if (!$this -> islogin){
			echo json_encode(array(
				'error' => -1,
				'message' => '您需要登录，才能进行这项操作！',
			));
			exit();
		}
		
		$cart_ids = $_POST['cart_ids'];
		
		if (!empty($cart_ids)){
			$id_arrs = explode(',', $cart_ids);
			$ids = array();
			foreach ($id_arrs as $cart_id){
				if (isint($cart_id) && $cart_id > 0){
					$ids[] = $cart_id;
				}
			}
		} else {
			$ids = array();
		}
		
		if (empty($ids)){
			echo json_encode(array(
				'error' => -1,
				'message' => '删除失败，错误的商品参数！~',
			));
			exit();
		}
		
		$uid = $_SESSION['uid'];
		$where = 'uid = '.$uid.' AND cart_id IN ('.implode(',', $ids).') ';
		
		$f = $this -> db -> delete('carts', $where);
		if ($f > 0){
			echo json_encode(array(
				'error' => 0,
				'message' => 'ok'
			));
		} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '商品删除失败！'
			));
		}
	}
	
	//订单确认并填写订单信息页面
	function confirm(){
		if (!$this -> islogin){
			$login = new login();
			$login -> index();
			exit();
		}
		
		$uid = $_SESSION['uid'];
		$grade_id = $_SESSION['grade_id'];
		
		//清理掉过期的商品
		$cart = new md\cart();
		$cart -> clear_timeout();
		
		//重置未过期时间
		$cart -> reset_timeout($uid);
		
		//查询购物车信息
		$total_number = $total_price = $total_weight = 0;
		$cart_items = $cart -> items($uid, $grade_id);
		if ($cart_items){
			foreach ($cart_items as $item){
				$total_number += $item['number'];
				$total_price  += $item['number'] * $item['price'];
				$total_weight += $item['weight'];
			}	
		} else {
			cpurl('/cart');
		}
		
		//查询地址信息
		$addr = new md\user_address();
		$addr_items = $addr -> items($uid);
		
		//查询省份信息
		$region = new md\region();
		$province_items = $region -> provinces();
		
		//查询配送方式
		$shipping = new md\shipping();
		$shipping_items = $shipping -> items();
		
		//查询所有支付方式
		$payment = new md\payment();
		$payment_items = $payment -> items();
		
		//查询余额是否够支付
		$user = new md\user();
		$user_data = $user -> info($uid);
		$balance = $user_data['balance'];
		
		if ($balance < $total_price){
			if ($payment_items){
				foreach ($payment_items as $key => $item){
					if ($item['pay_code'] == 'balance'){
						unset($payment_items[$key]);
					}
				}
			}
		}
		
		
		$this -> smarty -> assign('title', '订单确认');
		$this -> smarty -> assign('cart_items', $cart_items);
		$this -> smarty -> assign('addr_items', $addr_items);
		$this -> smarty -> assign('total_number', $total_number);
		$this -> smarty -> assign('total_price', $total_price);
		$this -> smarty -> assign('total_weight', $total_weight);
		$this -> smarty -> assign('province_items', $province_items);
		$this -> smarty -> assign('shipping_items', $shipping_items);
		$this -> smarty -> assign('payment_items', $payment_items);
		$this -> smarty -> assign('balance', $balance);
		$this -> smarty -> display('user/cart_confirm.tpl');
	}
	
	
	//获取当前购物车内商品的总运费
	function get_shipping_fee(){
		if (!$this -> islogin){
			echo json_encode(array(
				'error' => -1,
				'message' => '您需要登录，才能进行这项操作！',
			));
			exit();
		}
		
		$uid = $_SESSION['uid'];
		$grade_id = $_SESSION['grade_id'];
		
		$shipping_id = $_POST['shipping_id'];
		$province_id = intval($_POST['province_id']);
		$city_id = intval($_POST['city_id']);
		$county_id = intval($_POST['county_id']);
		
		if (empty($shipping_id)){
			echo json_encode(array(
				'error' => -1, 
				'message' => '缺少配送方式参数，无法获取运费！'
			));
			exit();
		}
		
		if ($province_id <= 0 || $city_id <= 0 || $county_id <= 0){
			echo json_encode(array(
				'error' => -1,
				'message' => '地区信息不完整，请刷新重选！',
			));
			exit();
		}
		
		//获取总价格和总重量
		$total_weight = $total_price = 0;
		$cart = new md\cart();
		$cart_items = $cart -> items($uid, $grade_id);
		if ($cart_items){
			foreach ($cart_items as $item){
				$total_price  += $item['number'] * $item['price'];
				$total_weight += $item['weight'] * $item['number'];
			}	
		} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '购物车为空，无法计算运费！'
			));
			exit();
		}
		
		//查询所有配送资费信息
		$shipping = new md\shipping();
		$fee = $shipping -> shipping_fee(array(
			'shipping_id' => $shipping_id,
			'province_id' => $province_id,
			'city_id' => $city_id,
			'county_id' => $county_id,
			'total_weight' => $total_weight,
			'total_price' => $total_price,
		));
		if ($fee === false){
			echo json_encode(array(
				'error' => -1,
				'message' => '获取配送费用失败！'
			));
			exit();
		}
		
		echo json_encode(array(
			'error' => 0,
			'shipping_fee' => $fee,
			'total_price' => $total_price + $fee 
		));
	}
}