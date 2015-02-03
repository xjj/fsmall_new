<?php
namespace model;

if (!defined('START')) exit('No direct script access allowed');

/**
 +-------------------------------------------
 *	网站配置模型
 +-------------------------------------------
 *	
 +-------------------------------------------
 */
class System_setting extends Front {
	
	//查询
	function search($page, $pagesize,$condition=''){
		$where= ' where 1=1 ';
		if(!empty($condition)&&is_array($condition)){
			foreach($condition as $k=>$v){
				$where.="and  $k = '$v' ";	
			}				
		}
		$sql = 'SELECT * FROM `system_setting` '.$where.'  LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$items = $this -> db -> rows($sql);
		//echo $sql;
		$sql = 'SELECT COUNT(*) as num FROM `system_setting` '.$where;
		$row = $this -> db -> row($sql);
		$total = $row['num']; 
		
		return array('items' => $items, 'total' => $total);
	}
	
	//编辑
	function update($key, $val){
		if (empty($key)){return false;}
			
		return $this -> db -> update('system_setting', array('value' => trim($value)), array('key' => $key));
	}
	
	//获取全部配置信息
	function get($group = 0){
		$sql = 'SELECT * FROM `system_setting`';
		if ($group > 0){
			$sql .= ' WHERE group = '.$group;
		}
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$data = array();
			foreach ($rows as $row){
				$key = $row['key'];
				$data[$key] = $row['value'];
			}	
			return $data;
		}
		return false;	
	}
}