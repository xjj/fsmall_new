<?php
namespace admin\system;

use admin as adm;
use model as md;

/**
 +----------------------------
 *	地区信息页面
 +----------------------------
 */

class Region extends adm\Front {
	
	//地区列表页
	function items(){
		$parent_id = $this -> params[0];
		if (!isint($parent_id) || $parent_id <= 0){$parent_id = 0;}
		
		$region = new md\region();
		if ($parent_id == 0){
			$country_items 		= $region -> countrys();
			$province_items 	= false;
			$city_items 		= false;
			$region_items		= $country_items;
			$country_id 		= 0;
			$province_id 		= 0;
			$city_id 			= 0;
		} else {
			$info = $region -> info($parent_id);
			switch ($info['layer']){
				case 0: //由国查省
					$country_items 		= $region -> countrys();
					$province_items 	= $region -> provinces($parent_id);
					$city_items 		= false;
					$region_items		= $province_items;
					$country_id 		= $parent_id;
					$province_id 		= 0;
					$city_id 			= 0;
					break;
				case 1: //由省查市
					$country_items 		= $region -> countrys();
					$province_items 	= $region -> provinces($info['parent_id']);
					$city_items 		= $region -> citys($parent_id);
					$region_items		= $city_items;
					$country_id 		= $info['parent_id'];
					$province_id 		= $parent_id;
					$city_id 			= 0;
					break;
				case 2: //由市查县
					$country_items 		= $region -> countrys();
					$city_items 		= $region -> citys($info['parent_id']);
					$provinfo 			= $region -> info($info['parent_id']);
					$province_items 	= $region -> provinces($provinfo['parent_id']);
					$region_items 		= $region -> children($parent_id);
					$country_id 		= $provinfo['parent_id'];
					$province_id		= $info['parent_id'];
					$city_id 			= $parent_id;
					break;
				default:
					cpmsg(false, '该地区不存在或已被删除！', -1);
					exit();
					break;
			}
		}
		
		$this -> smarty -> assign('country_items', $country_items);
		$this -> smarty -> assign('province_items', $province_items);
		$this -> smarty -> assign('city_items', $city_items);
		$this -> smarty -> assign('region_items', $region_items);
		$this -> smarty -> assign('country_id', $country_id);
		$this -> smarty -> assign('province_id', $province_id);
		$this -> smarty -> assign('city_id', $city_id);
		$this -> smarty -> assign('parent_id', $parent_id);
		$this -> smarty -> display('system/region_list.tpl');
	}
	
	//添加地区信息页
	function add(){
		if (isset($_POST['submit'])){
			//添加处理部分
			$this -> add_submit();
		}
		
		//输出页面部分
		$parent_id = $this -> params[1];
		if (!isint($parent_id) || $parent_id <= 0){$parent_id = 0;}
		
		$region = new md\region();
		if ($parent_id == 0){
			$country_items 	= $region -> countrys();
			$province_items = false;
			$city_items 	= false;
			$country_id 	= 0;
			$province_id 	= 0;
			$city_id 		= 0;
		} else {
			$info = $region -> info($parent_id);
			switch ($info['layer']){
				case 0: //由国查省
					$country_items 	= $region -> countrys();
					$province_items = $region -> provinces($parent_id);
					$city_items 	= false;
					$country_id 	= $parent_id;
					$province_id 	= 0;
					$city_id 		= 0;
					break;
				case 1: //由省查市
					$country_items 	= $region -> countrys();
					$province_items = $region -> provinces($info['parent_id']);
					$city_items 	= $region -> citys($parent_id);
					$country_id 	= $info['parent_id'];
					$province_id 	= $parent_id;
					$city_id 		= 0;
					break;
				case 2: //由市查县
					$country_items 	= $region -> countrys();
					$city_items 	= $region -> citys($info['parent_id']);
					$provinfo 		= $region -> info($info['parent_id']);
					$province_items	= $region -> provinces($provinfo['parent_id']);
					$country_id 	= $provinfo['parent_id'];
					$province_id	= $info['parent_id'];
					$city_id 		= $parent_id;
					break;
			}
		}
		
		$this -> smarty -> assign('country_items', $country_items);
		$this -> smarty -> assign('province_items', $province_items);
		$this -> smarty -> assign('city_items', $city_items);
		$this -> smarty -> assign('country_id', $country_id);
		$this -> smarty -> assign('province_id', $province_id);
		$this -> smarty -> assign('city_id', $city_id);
		$this -> smarty -> assign('parent_id', $parent_id);
		$this -> smarty -> display('system/region_add.tpl');
	}
	
	//添加处理
	private function add_submit(){
		$parent_id = $_POST['parent_id'];
		$region_name = $_POST['region_name'];
		$bigname = $_POST['bigname'];
		$displayorder = intval($_POST['displayorder']);
		$country_id = $_POST['country_id'];
		$province_id = $_POST['province_id'];
		$city_id = $_POST['city_id'];
		$zipcode = intval($_POST['zipcode']);
		if ($country_id == 0){
			$parent_id = 0;
			$layer = 0;
		} elseif ($province_id == 0){
			$parent_id = $country_id;
			$layer = 1;
		} elseif ($city_id == 0){
			$parent_id = $province_id;
			$layer = 2;
		} else {
			$parent_id = $city_id;
			$layer = 3;
		}
		
		if (empty($region_name)){
			cpmsg(false, '请输入地区名称！', -1);
		}
		if ($layer > 1 && empty($bigname)){
			cpmsg(false, '大字必须填写！', -1);
		}
		if ($displayorder <= 0){$displayorder = 0;}
		
		$region = new md\region();
		$f = $region -> add(array(
			'region_name' => $region_name,
			'bigname' => $bigname,
			'parent_id' => $parent_id,
			'zipcode' => $zipcode,
			'layer' => $layer,
			'displayorder' => $displayorder
		));
		if ($f){
			cpmsg(true, '地区信息添加成功！', '/'.$this->mod.'/'.$this->col.'/'.$parent_id);
		} else {
			cpmsg(false, '地区信息添加失败！', -1);	
		}
	}
	
	//编辑地区信息页
	function edit(){
		if (isset($_POST['submit'])){
			//添加处理部分
			$this -> edit_submit();
		}
		
		//输出页面部分
		$region_id = $this -> params[1];
		if (!isint($region_id) || $region_id <= 0){$region_id = 0;}
		
		$region = new md\region();
		$region_data = $region -> info($region_id);
		if ($region_data){} else {
			cpmsg(false, '该地区信息不存在或已被删除！', -1);
		}
		
		$parent_id = $region_data['parent_id'];
		if ($parent_id == 0){
			$country_items 		= $region -> countrys();
			$province_items 	= false;
			$city_items 		= false;
			$country_id 		= 0;
			$province_id 		= 0;
			$city_id 			= 0;
		} else {
			$info = $region -> info($parent_id);
			switch ($info['layer']){
				case 0: //由国查省
					$country_items 		= $region -> countrys();
					$province_items 	= $region -> provinces($parent_id);
					$city_items 		= false;
					$country_id 		= $parent_id;
					$province_id 		= 0;
					$city_id 			= 0;
					break;
				case 1: //由省查市
					$country_items 		= $region -> countrys();
					$province_items 	= $region -> provinces($info['parent_id']);
					$city_items 		= $region -> citys($parent_id);
					$country_id 		= $info['parent_id'];
					$province_id 		= $parent_id;
					$city_id 			= 0;
					break;
				case 2: //由市查县
					$country_items 		= $region -> countrys();
					$city_items 		= $region -> citys($info['parent_id']);
					$provinfo 			= $region -> info($info['parent_id']);
					$province_items 	= $region -> provinces($provinfo['parent_id']);
					$country_id 		= $provinfo['parent_id'];
					$province_id		= $info['parent_id'];
					$city_id 			= $parent_id;
					break;
			}
		}
		
		$this -> smarty -> assign('country_items', $country_items);
		$this -> smarty -> assign('province_items', $province_items);
		$this -> smarty -> assign('city_items', $city_items);
		$this -> smarty -> assign('country_id', $country_id);
		$this -> smarty -> assign('province_id', $province_id);
		$this -> smarty -> assign('city_id', $city_id);
		$this -> smarty -> assign('parent_id', $parent_id);
		$this -> smarty -> assign('region_data', $region_data);
		$this -> smarty -> display('system/region_edit.tpl');
	}
	
	
	//编辑处理操作
	private function edit_submit(){
		$parent_id = $_POST['parent_id'];
		$region_id = $_POST['region_id'];
		
		$region_name = $_POST['region_name'];
		$bigname = $_POST['bigname'];
		$displayorder = $_POST['displayorder'];
		$country_id = $_POST['country_id'];
		$province_id = $_POST['province_id'];
		$city_id = $_POST['city_id'];
		$zipcode = intval($_POST['zipcode']);
		if ($country_id == 0){
			$parent_id = 0;
			$layer = 0;
		} elseif ($province_id == 0){
			$parent_id = $country_id;
			$layer = 1;
		} elseif ($city_id == 0){
			$parent_id = $province_id;
			$layer = 2;
		} else {
			$parent_id = $city_id;
			$layer = 3;
		}
		if (!isint($region_id) || $region_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		if (empty($region_name)){
			cpmsg(false, '请输入地区名称！', -1);
		}
		if ($layer > 1 && empty($bigname)){
			cpmsg(false, '大字必须填写！', -1);
		}
		
		if (!isint($displayorder) || $displayorder <= 0){$displayorder = 0;}
		
		$region = new md\region();
		$num = $region -> update($region_id, array(
			'region_name' => $region_name,
			'parent_id' => $parent_id,
			'bigname' => $bigname,
			'layer' => $layer,
			'zipcode' => $zipcode,
			'displayorder' => $displayorder
		));
		
		if ($num){
			cpmsg(true, '地区信息编辑成功！', '/'.$this->mod.'/'.$this->col.'/'.$parent_id);
		} else {
			cpmsg(false, '地区信息编辑失败！', -1);	
		}
	}
	
	//删除功能
	function del(){
		$region_id = $this -> params[1];
		
		if (!isint($region_id) || $region_id <= 0){
			cpmsg(false, '缺少重要参数！', -1);
		}
		
		$region = new md\region();
		$info = $region -> info($region_id);
		if ($info){
			$parent_id = $info['parent_id'];
		} else {
			cpmsg(false, '该地区信息不存在或已被删除！', -1);
		}
		$num = $region -> delete($region_id);
		if ($num){
			cpurl('/'.$this->mod.'/'.$this->col.'/'.$parent_id);
		} else {
			cpmsg(false, '删除失败，该地区信息不存在或已被删除！', -1);
		}
	}
	
	//批量修改功能
	function bat_edit(){
		$parent_id = $_POST['parent_id'];
		$region_names = $_POST['region_name'];
		
		$region = new md\region();
		if ($parent_id == 0){
			$layer = 0;
		} else {
			$region_parent_data = $region -> info($parent_id);
			if ($region_parent_data){
				$layer = $region_parent_data['layer'] + 1;
			} else {
				cpmsg(false, '没有查询到该地区信息！', -1);
			}
		}
		
		
		if (!is_array($region_names)){
			cpmsg(false, '错误的请求！', -1);
		}
			
		foreach ($region_names as $region_id => $region_name){
			$displayorder = intval($_POST['displayorder'][$region_id]);
			if ($displayorder <= 0){$displayorder = 0;}
			$zipcode = intval($_POST['zipcode'][$region_id]);
			$bigname = trim($_POST['bigname'][$region_id]);
			if ($layer > 1 && empty($bigname)){
				continue;
			}
			
			$region -> update($region_id, array(
				'region_name' => $region_name,
				'parent_id' => $parent_id,
				'bigname' => $bigname,
				'zipcode' => $zipcode,
				'displayorder' => $displayorder
			));
		}
		
		cpmsg(true, '地区信息编辑成功！', '/'.$this->mod.'/'.$this->col.'/'.$parent_id);
	}
	
	
	//查询子节点
	//system/region/children/{$parent_id}
	function children(){
		$parent_id = $this -> params[1];
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