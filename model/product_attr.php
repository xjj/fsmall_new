<?php
namespace model;

/**
 +--------------------------------
 *	商品淘宝属性信息类 	
 +--------------------------------
 */
class product_attr extends Front {
	
	//将属性信息批量写入到数据库
	function add_multi($prd_id, $data){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		
		//验证数组信息
		if (!is_array($data) || empty($data)){return false;}
		
		$insert_data = array();
		foreach ($data as $item){
			$insert_data[] = array(
				'prd_id' => $prd_id,
				'tb_attr_id' => $item['tb_attr_id'],
				'tb_attr_value' => $item['tb_attr_value'],
				'add_time' => time()
 			);
		}
		
		return $this -> db -> insert_multi('product_info_tbattr', $insert_data);
	}
	
	//添加一条淘宝属性
	function add($data){
		if (!is_array($data) || empty($data)){return false;}
		
		$insert_data = array(
			'prd_id' => $data['prd_id'],
			'tb_attr_id' => $data['tb_attr_id'],
			'tb_attr_value' => $data['tb_attr_value'],
			'add_time' => time(),
			'is_delete' => 0
		);
		
		return $this -> db -> insert('product_info_tbattr', $insert_data);
	}
	
	//批量编辑商品属性信息
	function update_multi($prd_id, $data){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		
		//验证数组信息
		if (!is_array($data) || empty($data)){return false;}
		
		//先清除所有淘宝属性
		$this -> delete($prd_id);
		
		//逐条更新或写入
		foreach ($data as $item){
			$tb_attr_id = $item['tb_attr_id'];
			$tb_attr_value = $item['tb_attr_value'];
			
			$number = $this -> db -> update('product_info_tbattr', array(
				'is_delete' => 0
			), array(
				'prd_id' => $prd_id,
				'tb_attr_id' => $tb_attr_id,
				'tb_attr_value' => $tb_attr_value,
			));
			if ($number == 0){
				$this -> add(array(
					'prd_id' => $prd_id,
					'tb_attr_id' => $tb_attr_id,
					'tb_attr_value' => $tb_attr_value,
				));
			}
		}
	}
	
	//删除某商品属性信息
	function delete($prd_id){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		return $this -> db -> update('product_info_tbattr', array('is_delete' => 1), array('prd_id' => $prd_id)); 
	}
	
	//判断属性是否存在
	function isExist($prd_id, $tb_attr_id){
		$sql = 'SELECT COUNT(*) AS num FROM `product_info_tbattr` WHERE prd_id = '.$prd_id.' AND tb_attr_id = '.$tb_attr_id.' AND is_delete = 0';
		$row = $this -> db -> row($sql);
		if ($row['num'] > 0){
			return true; 
		} else {
			return false;
		}
	}
	
	//通过商品ID获取淘宝属性信息
	function items($prd_id){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		$sql = 'SELECT * FROM `product_info_tbattr` WHERE prd_id = '.$prd_id.' AND is_delete = 0 ORDER BY attr_id ASC';
		return $this -> db -> rows($sql);
	}
}