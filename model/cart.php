<?php
namespace model;

/**
 +--------------------------------
 *	购物车类
 +--------------------------------
 */
class Cart extends Front {
	
	private $maxNumber = 99; 	//购物车商品的最多数量 
	
	function get_maxNumber(){
		return $this -> maxNumber;	
	}
	
	
	//获取一条购物车商品信息
	function info($cart_id){
		if (!isint($cart_id) || $cart_id <= 0){return false;}
		$sql = 'SELECT * FROM `order_cart` WHERE cart_id = '.$cart_id.'';
		return $this -> db -> row($sql);
	}
	
	//获取购物车商品信息
	function get_info_by_SKU($uid, $sku_id){
		if (!isint($sku_id) || $sku_id <= 0 || !isint($uid) || $uid <= 0){return false;}
		$sql = 'SELECT * FROM `order_cart` WHERE sku_id = '.$sku_id.' AND uid = '.$uid.'';
		return $this -> db -> row($sql);
	}
	
	//用户所有购物车信息
	function items($uid, $grade_id, $cartIdArr = array()){
		if (!isint($uid) || $uid <= 0){return false;}
		if (!isint($grade_id) || $grade_id <= 0){return false;}
		if (!empty($cartIdArr) && is_array($cartIdArr)){
			$where = ' AND cart.cart_id IN ('.implode(',', $cartIdArr).')';		
		} else {
			$where = '';
		}
		
		$sql  = ' SELECT cart.*, ';
		$sql .= ' prd.product_name, prd.product_name_kr, prd.product_sn, ';
		$sql .= ' prd.cat_id, prd.brand_id, prd.is_spot, prd.freight, prd.is_freight, prd.weight, ';
		$sql .= ' brand.brand_name ';
		$sql .= ' FROM `order_cart` cart ';
		$sql .= ' LEFT JOIN `product_info` prd ON cart.prd_id = prd.prd_id ';
		$sql .= ' LEFT JOIN `product_brand` brand ON prd.brand_id = brand.brand_id ';
		$sql .= ' WHERE cart.uid = '.$uid.' AND cart.expired_time > '.time().' '.$where.'';
		$sql .= ' ORDER BY cart.cart_id DESC';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$data = array();
			$prd_price = new product_price();
			foreach ($rows as $item){
				
				//查询商品当前价格是否更新
				$params = array(
					'cat_id' 	=> $item['cat_id'],
					'brand_id' 	=> $item['brand_id'],
					'is_spot' 	=> $item['is_spot'],
					'freight' 	=> $item['freight'],
					'is_freight'=> $item['is_freight'],
					'price' 	=> $item['price'],
				);
				$price = $prd_price -> price($item['prd_id'], $params, $uid, $grade_id);
				if ($price != $item['price']){
					$f = $this -> update_price($item['cart_id'], $price);
					if ($f){
						$item['price'] = $price;
					}
				}
				
				$item['prop_value'] = unserialize($item['prop_value']);
				$item['prop_value_kr'] = unserialize($item['prop_value_kr']);
				$data[] = $item;
			}
			return $data;
		}
		return false;
	}
	
	
	//添加一条信息到购物车
	function add($data){
		$uid = intval($data['uid']);
		$grade_id = intval($data['grade_id']);
		$prd_id = intval($data['prd_id']);
		$sku_id = intval($data['sku_id']);
		$number = intval($data['number']);
		
		if ($prd_id <= 0 || $sku_id <= 0 || $number <= 0){
			return false; 
		}
		
		//判断购物车中是否有该商品
		$f = $this -> isExist($uid, $sku_id);
		if ($f){
			//有则更新数量- -- 这里只有加number
			return $this -> update_number($sku_id, $uid, $number);
		}
		
		//查询SKU信息
		$prd_sku = new product_sku();
		$sku_data = $prd_sku -> info($sku_id);
		if ($sku_data) {} else {
			return false;
		}
		
		//查询商品信息
		$prd = new product();
		$prd_data = $prd -> info($prd_id);
		if ($prd_data){} else {
			return false;
		}
		
		//计算价格
		$prd_price = new product_price();
		$params = array(
			'cat_id' => $prd_data['cat_id'],
			'brand_id' => $prd_data['brand_id'],
			'is_spot' => $prd_data['is_spot'],
			'freight' => $prd_data['freight'],
			'is_freight' => $prd_data['is_freight'],
			'price' => $sku_data['price'],
		);
		$ret = $prd_price -> price($prd_id, $params, $uid, $grade_id);
		if ($ret){
			$price = $ret['price'];
		} else {
			return false;
		}
		
		return $this -> db -> insert('order_cart', array(
			'uid' => $uid,
			'prd_id' => $prd_id,
			'sku_id' => $sku_id,
			'number' => $number,
			'price' => $price,
			'price_kr' => $sku_data['price_kr'],
			'prop_value' => $sku_data['prop_value'],
			'prop_value_kr' => $sku_data['prop_value_kr'],
			'pic_thumb' => $prd_data['pic_thumb'],
			'pic_small' => $prd_data['pic_small'],
			'add_time' => time(),
			'expired_time' => time() + 24*3600
		));
	}
	
	//判断购物车中是否有该商品
	function isExist($uid, $sku_id){
		$sql = 'SELECT COUNT(*) AS num FROM `order_cart` WHERE uid = '.$uid.' AND sku_id = '.$sku_id.' AND expired_time > '.time();
		$row = $this -> db -> row($sql);
		if ($row['num'] > 0){
			return true;
		} else {
			return false;
		}
	}
	
	//判断是否存在cart_id,判断选择商品是否存在
	function isExistCartIds($uid, $cartids){
		$sql = 'SELECT COUNT(*) AS num FROM `order_cart` WHERE uid = '.$uid.' AND cart_id IN ('.implode(',', $cartids).') AND expired_time > '.time();
		return $this -> db -> row($sql);	
	}
	
	//更新购物车商品价格 -- 该价格为实时计算得到的商品当前销售价格
	function update_price($cart_id, $price){
		if (!isint($cart_id) || $cart_id <= 0){return false;}
		if (!isint($price) || $price < 0){return false;}
		
		$time = time() + 24*3600;
		return $this -> db -> update('order_cart', array('price' => $price), array('cart_id' => $cart_id, 'expired_time' => $time));
	}
	
	//清空购物车
	function clear($uid){
		return $this -> db -> delete('order_cart', array('uid' => $uid));
	}
	
	//清空过期的
	function clear_timeout(){
		$where = 'expired_time < '.time();
		return $this -> db -> delete('order_cart', $where);
	}
	
	//重置过期时间
	function reset_timeout($uid){
		$time = time() + 24*3600;
		return $this -> db -> update('order_cart', array('expired_time' => $time), array('uid' => $uid));
	}
	
	//删除某商品
	function delete($sku_id, $uid){
		return $this -> db -> delete('order_cart', array('sku_id' => $sku_id, 'uid' => $uid));
	}
	
	//更新数量
	//$number 真实的数量
	function update_number($sku_id, $uid, $number){
		$time = time() + 24*3600;
		$where = array('sku_id' => $sku_id, 'uid' => $uid);
		return $this -> db -> update('order_cart', array('number' => $number, 'expired_time' => $time), $where);
	}
	
	//查询购物车中商品总数
	function product_total_number($uid){
		$sql = 'SELECT SUM(number) as number FROM `order_cart` WHERE uid = '.$uid.' AND expired_time > '.time();
		$row = $this -> db -> row($sql);
		return $row['number'];	
	}
}