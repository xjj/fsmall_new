<?php
namespace model;

if (!defined('START')) exit('No direct script access allowed');

/**
 +------------------------------
 *	配送地区费用设置类
 +------------------------------
 */
class Shipping_Fee extends Front {
	
	//获取地区信息
	function info($fee_id){
		if (!isint($fee_id) || $fee_id <= 0){return false;}
		return $this -> db -> row('SELECT * FROM `system_shipping_fee` WHERE fee_id = '.$fee_id);
	}
	
	//查询所有配送方式的资费信息 ---- 返回值按照 region_id 为键
	function region_fee_items($shipping_id){
		$sql = 'SELECT * FROM `system_shipping_fee` WHERE shipping_id = '.$shipping_id.' ORDER BY fee_id ASC';
		$rows =  $this -> db -> rows($sql);
		if ($rows){
			$data = array();
			foreach ($rows as $row){
				$region_id = $row['region_id'];
				$ids = explode(',', $region_id);
				foreach ($ids as $id){
					$data[$id] = $row;
				}
			}
			if (!empty($data)){
				return $data;
			}
		}
		return false; 
	}
	
	
	//添加地区信息
	function add($data){
		$fee_name = trim($data['fee_name']);
		$shipping_id = trim($data['shipping_id']);
		$fee_base = trim($data['fee_base']);
		$fee_step = trim($data['fee_step']);
		$free_amount = trim($data['free_amount']);
		$region_id = $data['region_id'];
		
		if (empty($fee_name)){return false;}
		if (!isint($shipping_id) || $shipping_id <= 0){return false;}
		if (!isint($fee_base) || $fee_base < 0){return false;}
		if (!isint($fee_step) || $fee_step < 0){return false;}
		if (!isint($free_amount) || $free_amount < 0){$free_amount = 0;}
		if (empty($region_id)){return false;}
		
		//判断地区id
		$region_id = is_array($region_id) ? $region_id : explode(',', $region_id);
		
		//已设置的地区id
		$ids2 = $this -> fee_regions($shipping_id); 
		
		$ids = array();
		foreach ($region_id as $id){
			if (!empty($id) && !in_array($id, $ids2)){
				$ids[] = $id;
			}
		}
		if (empty($ids)){return false;}
		
		//获取地区名称
		$region = new region();
		$region_name_array = $region -> get_name_by_ids($ids);
		if ($region_name_array){} else {
			return false;
		}
		
		return $this -> db -> insert('system_shipping_fee', array(
			'fee_name' => $fee_name,
			'shipping_id' => $shipping_id,
			'fee_base' => $fee_base,
			'fee_step' => $fee_step,
			'free_amount' => $free_amount,
			'region_id' => implode(',', $ids),
			'region_name' => implode(', ', $region_name_array),
		));
	}
	
	//编辑地区信息
	function update($fee_id, $data){
		$fee_name = trim($data['fee_name']);
		$shipping_id = trim($data['shipping_id']);
		$fee_base = trim($data['fee_base']);
		$fee_step = trim($data['fee_step']);
		$free_amount = trim($data['free_amount']);
		$region_id = $data['region_id'];
		
		if (!isint($fee_id) || $fee_id <= 0){return false;}
		if (empty($fee_name)){return false;}
		if (!isint($shipping_id) || $shipping_id <= 0){return false;}
		if (!isint($fee_base) || $fee_base < 0){return false;}
		if (!isint($fee_step) || $fee_step < 0){return false;}
		if (!isint($free_amount) || $free_amount < 0){$free_amount = 0;}
		if (empty($region_id)){return false;}
		
		//判断地区id
		$region_id = is_array($region_id) ? $region_id : explode(',', $region_id);
		
		//已设置的地区id
		$ids2 = $this -> fee_regions($shipping_id, $fee_id); 
		
		$ids = array();
		foreach ($region_id as $id2){
			if (!empty($id2) && !in_array($id2, $ids2)){
				$ids[] = $id2;
			}
		}
		if (empty($ids)){return false;}
		
		//获取地区名称
		$region = new region();
		$region_name_array = $region -> get_name_by_ids($ids);
		if ($region_name_array){} else {
			return false;
		}
		
		return $this -> db -> update('system_shipping_fee', array(
			'fee_name' => $fee_name,
			'fee_base' => $fee_base,
			'fee_step' => $fee_step,
			'free_amount' => $free_amount,
			'region_id' => implode(',', $ids),
			'region_name' => implode(', ', $region_name_array),
		), array(
			'fee_id' => $fee_id,
			'shipping_id' => $shipping_id,
		));
	}
	
	//删除地区信息
	function delete($fee_id){
		if (!isint($fee_id) || $fee_id <= 0){return false;}
		return $this -> db -> delete('system_shipping_fee', array('fee_id' => $fee_id));
	}
	
	//查询所有已设置地区
	function fee_regions($shipping_id, $fee_id = 0){
		
		$sql = 'SELECT region_id FROM `system_shipping_fee` WHERE shipping_id = '.$shipping_id.'';
		if (isint($fee_id) && $fee_id > 0){
			$sql .= ' AND fee_id != '.$fee_id.'';
		}
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$data = array();
			foreach ($rows as $row){
				$region_id = $row['region_id'];
				if (!empty($region_id)){
					$ids = explode(',', $region_id);
					foreach ($ids as $id){
						if (!in_array($id, $data)){
							$data[] = $id;
						}
					}
				}
			}
			if (!empty($data)){
				return $data; 
			}
		} 
		return array();
	}
	
	//配送费用
	function items($shipping_id){
		$sql = 'SELECT * FROM `system_shipping_fee` WHERE shipping_id = '.$shipping_id.' ORDER BY fee_id ASC';
		return $this -> db -> rows($sql);
	}
}