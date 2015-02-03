<?php
namespace model;

/**
 +---------------------------------
 *	商品SKU信息
 *	sku_id		[值大于1000]
 +---------------------------------
 */
class Product_SKU extends Front {
	
	/**
	 +------------------------------------
	 *	添加SKU信息 -- 同时保存销售属性
	 +------------------------------------
	 *	完成功能：
	 *	1.将销售属性写入商品销售属性表
	 *	2.将销售属性写入淘宝销售属性表
	 +------------------------------------
	 */
	function add($prd_id, $data){
		if (empty($data) || !is_array($data)){return false;}
		
		$insert_number = 0;
		
		//循环每一条SKU
		foreach ($data as $sku){
			if (empty($sku) || !is_array($sku)){return false;} //数据格式不正确
		
			$prop_value_cn = $prop_value_kr = array();
			$prd_prop   = new product_prop();
			$prop_value = new prop_value();
			
			//循环SKU的销售属性
			foreach ($sku['props'] as $item){
				
				$value = $item['value'];
				$value_kr = $item['value_kr'];
				$prop_value_id1 = $item['prop_value_id'];
				
				//获取prop_value_id2的值
				$prop_value_id2 = $prop_value -> fetch($value, $value_kr);
				
				//写入销售属性表
				$prop_id = $prd_prop -> fetch(array(
					'prd_id' => $prd_id,
					'prop_value_id1' => $prop_value_id1,
					'prop_value_id2' => $prop_value_id2,
					'value' => $value,
					'value_kr' => $value_kr
				));
				
				//获取prop_value_id1对应的属性名
				$prop_value_id1_data = $prop_value -> info($prop_value_id1);
				
				$key 	= $prop_value_id1.':'.$prop_value_id2;
				$val_cn = $prop_value_id1_data['prop_value'].':'.$value;
				$val_kr = $prop_value_id1_data['prop_value_kr'].':'.$value_kr;
				$prop_value_cn[$key] = $val_cn;
				$prop_value_kr[$key] = $val_kr;
			}
			
			//将sku信息写入数据库
			$sku_id = $this -> db -> insert('product_info_sku', array(
				'prd_id' => $prd_id,
				'number' => $sku['number'],
				'stock' => $sku['stock'],
				'price' => $sku['price'],
				'price_kr' => $sku['price_kr'],
				'prop_value' => serialize($prop_value_cn),
				'prop_value_kr' => serialize($prop_value_kr),
				'is_soldout' => $sku['is_soldout'],
				'add_time' => time(),
				'update_time' => time(),
			));
			if ($sku_id){
				$insert_number += 1;
			}
		}
		
		if ($insert_number > 0){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 +----------------------------------------------
	 *	编辑更新sku信息 -- 同时更新销售属性 -- 后台商品编辑
	 +----------------------------------------------
	 */
	function edit($prd_id, $data){
		if (empty($data) || !is_array($data)){return false;}
		
		//清除所有销售属性
		$prd_prop = new product_prop();
		$prd_prop -> delete($prd_id);
		
		$update_number = 0;
		
		foreach ($data as $sku){
			if (empty($sku) || !is_array($sku)){return false;} //数据格式不正确
		
			$prop_value_cn = $prop_value_kr = array();
			$prd_prop   = new product_prop();
			$prop_value = new prop_value();
			
			$sku_id = $sku['sku_id'];
			if ($sku_id <= 0){return false;}
			
			
			foreach ($sku['props'] as $item){
				
				$value = $item['value'];
				$value_kr = $item['value_kr'];
				$prop_value_id1 = $item['prop_value_id'];
				
				//获取prop_value_id2的值
				$prop_value_id2 = $prop_value -> fetch($value, $value_kr);
				
				//更新销售属性信息--没有记录则添加
				$prop_id = $prd_prop -> fetch(array(
					'prd_id' => $prd_id,
					'prop_value_id1' => $prop_value_id1,
					'prop_value_id2' => $prop_value_id2,
					'value' => $value,
					'value_kr' => $value_kr,
				));
				if ($prop_id){} else {return false;}
				
				//获取prop_value_id1对应的属性名
				$prop_value_id1_data = $prop_value -> info($prop_value_id1);
				
				$key 	= $prop_value_id1.':'.$prop_value_id2;
				$val_cn = $prop_value_id1_data['prop_value'].':'.$value;
				$val_kr = $prop_value_id1_data['prop_value_kr'].':'.$value_kr;
				$prop_value_cn[$key] = $val_cn;
				$prop_value_kr[$key] = $val_kr;
			}
			
			$is_soldout = $sku['is_soldout'];
			if ($is_soldout == 1){
				$soldout_time = time();
			} else {
				$soldout_time = 0;
			}
			
			//更新sku信息写入数据库
			$f = $this -> db -> update('product_info_sku', array(
				'prd_id' => $prd_id,
				'number' => $sku['number'],
				'stock' => $sku['stock'],
				'price' => $sku['price'],
				'price_kr' => $sku['price_kr'],
				'prop_value' => serialize($prop_value_cn),
				'prop_value_kr' => serialize($prop_value_kr),
				
				'is_soldout' => $is_soldout,
				'soldout_time' => $soldout_time,
				'update_time' => time(),
				'is_delete' => 0,
				'delete_time' => 0,
			), array(
				'prd_id' => $prd_id,
				'sku_id' => $sku_id
			));
			
			if ($f > 0){
				$update_number += 1;
			}
		}
		
		if ($update_number > 0){
			return true;
		} else {
			return false;
		}
	}
	
	
	//商品所有SKU信息
	function items($prd_id, $is_soldout = 1){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		
		if ($is_soldout === 1){
			$where = ' AND is_soldout = 1';
		} elseif ($is_soldout === 0){
			$where = ' AND is_soldout = 0';
		} else {
			$where = '';
		}
		$sql = 'SELECT * FROM `product_info_sku` WHERE prd_id = '.$prd_id.' AND is_delete = 0 '.$where.' ORDER BY sku_id ASC';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$data = array();
			foreach ($rows as $row){
				$row['prop_value']    = unserialize($row['prop_value']);
				$row['prop_value_kr'] = unserialize($row['prop_value_kr']);
				$row['prop_value_ids'] = array_keys($row['prop_value']);
				
				//分析出可用于输出的数据
				$row['edit_data'] = $this -> SKUs_edit_data($row['prop_value'], $row['prop_value_kr']);
				
				$data[] = $row;
			}
			
			if (!empty($data)){
				return $data;
			}
		}
		return false; 
	}
	
	//删除SKU信息
	function delete($sku_id){
		if (!isint($sku_id) || $sku_id <= 0){return false;}
		return $this -> db -> update('product_info_sku', array('is_delete' => 1, 'delete_time' => time()), array('sku_id' => $sku_id));
	}
	
	//删除全部SKU
	function delete_SKUs($prd_id){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		return $this -> db -> update('product_info_sku', array('is_delete' => 1, 'delete_time' => time()), array('prd_id' => $prd_id));
	}
	
	//查询一条sku信息
	function info($sku_id){
		if (!isint($sku_id) || $sku_id <= 0){return false;}
		$sql = 'SELECT * FROM `product_info_sku` WHERE sku_id = '.$sku_id.'';
		return $this -> db -> row($sql);
	}
	
	//查询sku的销售属性信息 -- 添加商品到购物车查询商品销售属性
	function fetch_sku_props($prd_id, $tb_cat_id, $props){
		
		//查询出所有的销售属性信息
		$tb_attr = new tb_attr();
		$rows = $tb_attr -> items($tb_cat_id, 1, 1);
		if ($rows){
			foreach ($rows as $row){
				$tb_attr_id = $row['tb_attr_id'];
				$tb_attr_items[$tb_attr_id] = $row;
			}
		} else {
			return false;
		}
		
		//查商品的所有销售属性值信息
		$prd_prop = new product_prop();
		$rows = $prd_prop -> items($prd_id);
		if ($rows){
			foreach ($rows as $row){
				$prop_id = $row['prop_id'];
				$prop_value_items[$prop_id] = $row;
			}
		} else {
			return false;
		}
		
		//分析SKU属性
		$data = array();
		$arrs = explode(';', rtrim($props, ';'));
		foreach ($arrs as $item){
			$arrs2 = explode(':', $item);
			
			$value = $value_kr = '';
			if (isset($arrs2[0]) && isset($arrs2[1]) && $arrs2[0] > 0 && $arrs2[1] > 0){
				$tb_attr_id = $arrs2[0];
				$prop_id = $arrs2[1];
				
				$tb_attr_value = $tb_attr_items[$tb_attr_id]['tb_attr_value'];
				$tb_attr_value_kr = $tb_attr_items[$tb_attr_id]['tb_attr_value_kr'];
				
				$prop_value = $prop_value_items[$prop_id]['value'];
				$prop_value_kr = $prop_value_items[$prop_id]['value_kr'];
				
				
				$data[] = array(
					'prop_id' => $prop_id, 
					'tb_attr_id' => $tb_attr_id,
					'value' => $prop_value,
					'value_kr' => $prop_value_kr,
					'tb_attr_value' => $tb_attr_value,
					'tb_attr_value_kr' => $tb_attr_value_kr,
				);
			}
		}
		
		if (!empty($data)){
			return $data; 
		}
		return false;
	}
	
	//库存更新 $number 增加或减少的数目
	function update_stcok($sku_id, $number){
		return $this -> db -> update_counter('product_info_sku', array('stock' => $number), array('sku_id' => $sku_id));
	}
	
	
	//设置某商品SKU断货
	function update_soldout($prd_id, $sku_id = 0){
		if ($sku_id == 0){
			return $this -> db -> update('product_info_sku', array('is_soldout' => 1, 'soldout_time' => time()), array('prd_id' => $prd_id, 'is_delete' => 0));
		} else {
			return $this -> db -> update('product_info_sku', array('is_soldout' => 1, 'soldout_time' => time()), array('sku_id' => $sku_id, 'is_delete' => 0));
		}
	}
	
	//取消某商品SKU断货
	function update_soldout_cancle($sku_id){
		return $this -> db -> update('product_info_sku', array('is_soldout' => 0, 'soldout_time' => 0), array('sku_id' => $sku_id, 'is_delete' => 0));
		
	}
	
	//计算出SKU编辑输出数据 -- 后台商品编辑中SKU信息
	function SKUs_edit_data($value, $value_kr){
		if (!is_array($value) || !is_array($value_kr)){return false;}
		
		$data = array();
		
		//解析出中文信息
		foreach ($value as $key => $val){
			$idArr = explode(':', $key);
			$prop_value_id1 = $idArr[0];
			
			$valArr_cn = explode(':', $val);
			$val_cn = $valArr_cn[1];
			
			$valArr_kr = explode(':', $value_kr[$key]);
			$val_kr = $valArr_kr[1];
			
			$data[$prop_value_id1] = array($val_cn, $val_kr);
		}
		
		return $data;
	}
}