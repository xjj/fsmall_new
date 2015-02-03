<?php
namespace admin;

use model as md;

if (!defined('START')) exit('No direct script access allowed');

/**
 +--------------------------------
 *	类目控制器
 +--------------------------------
 */
class Category extends Front {
	
	
	/**
	 *	获取子类目数据[JSON]
	 *	url=/category/children/{$parent_id}
	 */
	function children(){
		$parent_id = $this -> params[0];
		if (!isint($parent_id) || $parent_id <= 0){
			echo json_encode(array('error' => 1, 'message' => 'Category ID Error！'));
			exit();
		}
		
		$cat = new md\category();
		$items = $cat -> child_items($parent_id, 1);
		if ($items){
			$data = array();
			foreach ($items as $row){
				$data[] = array(
					'cat_id' 	=> $row['cat_id'],
					'parent_id' => $row['parent_id'],
					'cat_name' 	=> $row['cat_name'],
					'cat_tb_id' => $row['cat_tb_id'],
					'cat_tb_name' => $row['cat_tb_name'],
					'weight' 	=> $row['weight'],
					'layer' 	=> $row['layer']
				);
			}
			echo json_encode(array('error' => 0, 'data' => $data));
		} else {
			echo json_encode(array('error' => 0, 'data' => ''));	
		}
	}
}