<?php
namespace model;

/**
 +----------------------------------
 *	商品品牌类
 +----------------------------------
 */
class Brand extends Front {
	
	public $brand_types = array(
		1 => '女装',
		2 => '男装',
		3 => '童装',
		4 => '情侣装',
		5 => '孕妇装',
		6 => '综合',
		7 => '其他',
	);
	
	//获取品牌类型
	function fetch_brand_types(){
		return $this -> brand_types; 
	}
	
	//品牌信息
	function info($brand_id){
		if (!isint($brand_id) || $brand_id <= 0){return false;}
		
		$sql = 'SELECT * FROM `product_brand` WHERE brand_id = '.$brand_id;
		return $this -> db -> row($sql);
	}
	
	//根据品牌名称查询品牌ID
	function get_id_by_name($brand_name){
		$sql = 'SELECT brand_id FROM `product_brand` WHERE brand_name = \''.encode($brand_name).'\'';
		$row = $this -> db -> row($sql);
		if ($row){
			return $row['brand_id'];
		}
		return false;
	}
	
	//
	function get_name_by_id($brand_id){
		$info = $this -> info($brand_id);
		if ($info){
			return $info['brand_name']; 	
		} else {
			return false;	
		}
	}
	
	//所有品牌
	function items($status = 1){
		$sql = 'SELECT * FROM `product_brand` WHERE is_delete = 0';
		if ($status == 1){
			$sql .= ' AND status = 1';
		}
		$sql .= ' ORDER BY brand_id ASC';
		return $this -> db -> rows($sql);
	}
	
	/**
	 +-----------------------------------
	 *	添加品牌信息
	 +-----------------------------------
	 */
	function add($params){
		$brand_name = trim($params['brand_name']);
		$logo = trim($params['logo']);
		$pic = trim($params['pic']);
		$web_url = trim($params['web_url']);
		$content = trim($params['content']);
		$type = intval($params['type']);
		$displayorder = intval($params['displayorder']);
		$keywords = trim($params['keywords']);
		$description = trim($params['discription']);
		$keywords = blank_space_replace($keywords);
		
		if (empty($params['brand_name'])){return false;}
		if ($this->isExist_brand_name($params['brand_name'])){return false;}
		if (empty($params['logo'])){return false;}
		if (empty($params['pic'])){return false;}
		 	
		return $this -> db -> insert('product_brand', array(
			'brand_name' => $brand_name,
			'logo' => $logo,
			'pic' => $pic,
			'web_url' => $web_url,
			'content' => $content,
			'type' => $type,
			'displayorder' => $displayorder,
			'keywords' => $keywords,
			'description' => $discription,
			'add_time' => time(),
			'status' => 0
		));
	}
	
	//更新
	function update($brand_id, $data){
		if (!isint($brand_id) || $brand_id <= 0){return false;}
		return $this -> db -> update('product_brand', $data, array('brand_id' => $brand_id));
	}
	
	//删除
	function delete($brand_id){
		if (!isint($brand_id) || $brand_id <= 0){return false;}
		
		return $this -> db -> update('product_brand', array(
			'is_delete' => 1, 
			'delete_time' => time()
		), array(
			'brand_id' => $brand_id
		));
	}

	
	//判断是否有同名的品牌
	function isExist_brand_name($brand_name, $brand_id = 0){
		$sql = 'SELECT COUNT(*) AS num FROM `product_brand` WHERE brand_name = \''.encode($brand_name).'\' AND is_delete = 0';
		if ($brand_id > 0){
			$sql .= ' AND brand_id != '.$brand_id;
		}
		$row = $this -> db -> row($sql);
		if ($row['num'] > 0){
			return true;
		} else {
			return false;
		}
	}
	
	//查询
	function search($params = array(), $page=1, $pagesize=20){
		$sql = 'SELECT * FROM `product_brand` WHERE is_delete = 0 ORDER BY displayorder ASC, brand_id ASC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$brand_items = $this -> db -> rows($sql);
		
		$sql = 'SELECT COUNT(*) AS num FROM `brands` WHERE is_delete = 0';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array(
			'items' => $brand_items,
			'total' => $total
		);
	}
	
	//有效无效
	function validate($brand_id, $status){
		if ($status != 1){$status = 0;}
		return $this -> update($brand_id, array('status' => $status));
	}
}