<?php
namespace model;

if (!defined('START')) exit('No direct script access allowed');

/**
 +---------------------------
 *	配送地区类
 +---------------------------
 */
class Region extends Front {
	
	//获取地区信息
	function info($region_id){
		if (!isint($region_id) || $region_id <= 0){return false;}
		$sql = 'SELECT * FROM `system_region` WHERE region_id = \''.$region_id.'\'';
		return $this -> db -> row($sql);
	}
	
	//获取地区id
	function get_name_by_id($region_id, $layer = 0){
		if (!isint($region_id) || $region_id <= 0){return false;}
		
		$where = 'WHERE region_id = '.$region_id.'';
		if (isint($layer) && $layer > 0){
			$where .= ' AND `layer` = '.$layer;
		}
		$sql = 'SELECT `region_name` FROM `system_region` '.$where;
		$row = $this -> db -> row($sql);
		if ($row){
			return $row['region_name'];
		} else {
			return false;
		}
	}
	
	//获取地区名称
	function get_name_by_ids($ids){
		if (!is_array($ids) || empty($ids)){return false;}

		$sql = 'SELECT `region_id`, `region_name` FROM `system_region` WHERE region_id IN ('.implode(',', $ids).')';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$data = array();
			foreach ($rows as $row){
				$region_id = $row['region_id'];
				$region_name = $row['region_name'];
				$data[$region_id] = $region_name;
			}
			return $data;
		} else {
			return false;
		}
	}
	
	//获取地区名
	function get_id_by_name($region_name){
		if (empty($region_name)){return false;}
		$sql = 'SELECT region_id FROM `system_region` WHERE `region_name` = \''.encode($region_name).'\'';
		$row = $this -> db -> row($sql);
		if ($row){
			return $row['region_id'];
		} else {
			return false;
		}
	}
	
	//所有国家
	function countrys(){
		return $this -> children(0, 0);
	}
	
	//所有省份
	function provinces($country_id = 1){
		return $this -> children($country_id, 1);
	}
	
	//所有城市
	function citys($province_id){
		return $this -> children($province_id, 2);
	}
	
	//所有县区
	function countys($city_id){
		return $this -> children($city_id, 3);
	}
	
	//获取兄弟地区
	function siblings($region_id){
		$info = $this -> info($region_id);
		if ($info){
			$parent_id = $info['parent_id'];
		} else {
			return false;
		}
		$sql = 'SELECT * FROM `system_region` WHERE parent_id = '.$parent_id.' ORDER BY displayorder ASC, region_id ASC';
		return $this -> db -> rows($sql);
	}
	
	//查询上级地区
	function parent_region($region_id){
		$info = $this -> info($region_id);
		if ($info){
			$parent_id = $info['parent_id'];
			if ($parent_id > 0){
				return $this -> info($parent_id);
			} 
		}
		return false;
	}
	
	//查询下级地区
	function children($parent_id, $layer = NULL){
		if (!isint($parent_id) || $parent_id < 0){return false;}
		if ($layer == NULL){
			$where = '';
		} else {
			$where = ' AND layer = '.$layer;
		}
		$sql = 'SELECT * FROM `system_region` WHERE parent_id = '.$parent_id.' '.$where.' ORDER BY displayorder ASC, region_id ASC';
		return $this -> db -> rows($sql);
	}
	
	//更新
	function update($region_id, $data){
		if (!isint($region_id) || $region_id <= 0){return false;}
		$displayorder = intval($data['displayorder']);
		
		$dt = array(
			'region_name' => $data['region_name'],
			'parent_id' => $data['parent_id'],
			'bigname' => $data['bigname'],
			'zipcode' => $data['zipcode'],
			'displayorder' => $displayorder
		);
		
		if (isset($data['layer']) && $data['layer'] >= 0){
			$dt['layer'] = $data['layer'];
		}
		
		return $this -> db -> update('system_region', $dt, array('region_id' => $region_id));
	}
	
	//添加
	function add($data){
		return $this -> db -> insert('system_region', array(
			'region_name' => $data['region_name'],
			'parent_id' => $data['parent_id'],
			'bigname' => $data['bigname'],
			'layer' => intval($data['layer']),
			'zipcode' => intval($data['zipcode']),
			'displayorder' => intval($data['displayorder']),
		));
		
		
	}
	
	//删除
	function delete($region_id){
		if (!isint($region_id) || $region_id <= 0){return false;}
		return $this -> db -> delete('system_region', array('region_id' => $region_id));
	}
	
	//获取快递大字
	function fetch_bigname($region_id){
		$info = $this -> info($region_id);
		if ($info){
			return $info['bigname'];
		}
		return false;
	}
}
?>