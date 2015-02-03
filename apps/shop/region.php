<?php
namespace shop;

use model as md;

if (!defined('START')) exit('No direct script access allowed');

class Region extends Front {
	
	/**
	 *	获取子地区列表[JSON]
	 *	url=/region/children/{$parent_id}
	 */
	function children(){
		$parent_id = $this -> params[0];
		if (!isint($parent_id) || $parent_id <= 0){
			echo json_encode(array('error' => 1, 'message' => 'Region ID Error！'));
			exit();
		}
		
		$region = new md\Region();
		$region_items = $region -> children($parent_id);
		if ($region_items){
			$data = array();
			foreach ($region_items as $row){
				$data[] = array('region_id' => $row['region_id'], 'region_name' => $row['region_name'], 'zipcode' => $row['zipcode']);
			}
			echo json_encode(array('error' => 0, 'data' => $data));
		} else {
			echo json_encode(array('error' => 1, 'message' => 'Don\'t Region Children！'));
		}
	}
}