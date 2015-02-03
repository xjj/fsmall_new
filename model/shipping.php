<?php
namespace model;

if (!defined('START')) exit('No direct script access allowed');

/**
 +-------------------------------
 *	配送类
 +-------------------------------
 */
class Shipping extends Front {
	
	//配送信息
	function info($shipping_id, $fields = array()){
		if (!isint($shipping_id) || $shipping_id <= 0){return false;}
		$str = empty($fields) ? '*' : $this -> db -> implode_field_keys($fields, ',');
		$sql = 'SELECT '.$str.' FROM `system_shipping` WHERE shipping_id = '.$shipping_id;
		return $this -> db -> row($sql);
	}
	
	//通过code 查询信息
	function get_info_by_code($shipping_code){
		$sql = 'SELECT * FROM `system_shipping` WHERE shipping_code = \''.encode($shipping_code).'\'';
		return $this -> db -> row($sql);
	}
	
	//添加配送信息
	function add($data){
		$shipping_name = trim($data['shipping_name']);
		$shipping_code = trim($data['shipping_code']);
		$content = trim($data['content']);
		$is_pay = trim($data['is_pay']);
		$displayorder = intval($data['displayorder']);
		
		if (empty($shipping_name)){return false;}
		if (empty($shipping_code)){return false;}
		if ($is_pay != 1){$is_pay = 0;}
		if ($displayorder <= 0){$displayorder = 0;}
		
		return $this -> db -> insert('system_shipping', array(
			'shipping_name' => $shipping_name,
			'shipping_code' => strtoupper($shipping_code),
			'content' => $content,
			'is_pay' => $is_pay,
			'displayorder' => $displayorder,
			'status' => 0
		));
	}
	
	//编辑配送信息
	function update($shipping_id, $data){
		if (!isint($shipping_id) || $shipping_id <= 0){return false;}
		
		$shipping_name = trim($data['shipping_name']);
		$shipping_code = trim($data['shipping_code']);
		$content = trim($data['content']);
		$displayorder = trim($data['displayorder']);
		
		if (empty($shipping_name)){return false;}
		if (empty($shipping_code)){return false;}
		if (!isint($displayorder) || $displayorder <= 0){$displayorder = 0;}
		
		return $this -> db -> update('system_shipping', array(
			'shipping_name' => $shipping_name,
			'shipping_code' => strtoupper($shipping_code),
			'content' => $content,
			'displayorder' => $displayorder
		), array(
			'shipping_id' => $shipping_id
		));
	}
	
	//删除配送信息
	function delete($shipping_id){
		if (!isint($shipping_id) || $shipping_id <= 0){return false;}
		$f = $this -> db -> delete('system_shipping', array('shipping_id' => $shipping_id)); 
		if ($f){
			$this -> db -> delete('system_shipping_fees', array('shipping_id' => $shipping_id));
			return true;
		}
		return false;
	}
	
	//设置有效无效
	function validate($shipping_id, $status){
		if (!isint($shipping_id) || $shipping_id <= 0){return false;}
		if ($status != 1){$status = 0;}
		return $this -> db -> update('system_shipping', array('status' => $status), array('shipping_id' => $shipping_id));
	}
	
	//查询所有配送方式
	function items($status = 1){
		if ($status == 1){
			$where = 'WHERE status = 1';
		} else {
			$where = '';
		}
		$sql = 'SELECT * FROM `system_shipping` '.$where.' ORDER BY shipping_id ASC';
		return $this -> db -> rows($sql);
	}
	
	//计算配送费用
	function shipping_fee($data){
		$shipping_id = $data['shipping_id'];
		$province_id = $data['province_id'];
		$city_id 	 = $data['city_id'];
		$county_id 	 = $data['county_id'];
		$total_weight= $data['total_weight'];
		$total_price = $data['total_price'];
		
		//查询配送方式信息
		$shipping_data = $this -> info($shipping_id);
		if ($shipping_data){
			$is_pay = $shipping_data['is_pay'];
		} else {
			return false;
		}
		
		//到付费用
		if ($is_pay == 1){return 0;}
		
		//查询所有配送资费信息
		$shipping_fee = new shipping_fee();
		$region_items = $shipping_fee -> region_fee_items($shipping_id);
		if ($region_items){} else {
			return false;
		}
		
		//查询配送费用方法
		$shipping_fee_item = false;
		foreach ($region_items as $region_id => $item){
			if ($region_id == $province_id){
				$shipping_fee_item = $item;
				break;
			}
		}
		
		if ($shipping_fee_item){} else {return false;}
		
		$fee_base = $shipping_fee_item['fee_base'];
		$fee_step = $shipping_fee_item['fee_step'];
		$free_amount = $shipping_fee_item['free_amount'];
		
		//免费的金额
		if ($free_amount > 0 && $total_price >= $free_amount){
			$fee = 0;
		} else {
			if ($total_weight > 1000){
				$fee = round($fee_base + ceil(($total_weight - 1000) / 1000) * $fee_step, 0);
			} else {
				$fee = $fee_base;
			}
		}
		return $fee;
	}
}