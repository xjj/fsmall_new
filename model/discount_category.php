<?php
namespace model;

/**
 +-------------------------------
 *	类目折扣类
 +-------------------------------
 */
class Discount_Category extends Front {
	
	//信息
	function info($id){
		if (!isint($id) || $id <= 0){return false;}
		$sql = 'SELECT * FROM `product_discount_category` WHERE id = '.$id;
		return $this -> db -> row($sql);
	}
	
	//获取折扣列表
	function items($page = 1, $pagesize = 20){
		$sql = 'SELECT * FROM `product_discount_category` WHERE is_delete = 0 ORDER BY status DESC, id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$items = $this -> db -> rows($sql);
		
		$sql = 'SELECT COUNT(*) AS num FROM `product_discount_category` WHERE is_delete = 0';
		$row = $this -> db -> row($sql);
		$total = $row['total'];
		
		return array('items' => $items, 'total' => $total);
	}
	
	//清除过期
	function clear(){
		$where = 'end_time > 0 AND end_time < '.time();
		$this -> db -> update('product_discount_category', array('is_over' => 1), $where);
	}
	
	//添加类目
	function add($params){
		$cat_id = intval($params['cat_id']);
		$brand_id = intval($params['brand_id']);
		$discount = intval($params['discount']);
		$start_time = trim($params['start_time']);
		$end_time = trim($params['end_time']);
		
		
		if ($cat_id <= 0){return false;}
		if ($discount < 1 || $discount > 99){return false;}
		if (!isDate($start_time) || !isDate($end_time)){return false;}
		
		$start_line = strtotime($start_time);
		$end_line 	= strtotime($end_time);
		$now_line 	= time();
		if ($end_line < $now_line){return false;}
		if ($start_line > $end_line){return false;}
		
		
		//获取类目路径名
		$cat = new category();
		$data = $cat -> parent_catname_path($cat_id);
		if ($data){
			$cat_name_path = implode(' &gt; ', $data);
		} else {
			return false;
		}
		
		//获取品牌名称
		if ($brand_id > 0){
			$brand = new Brand();
			$info = $brand -> info($brand_id);
			if ($info){
				$brand_name = $info['brand_name'];
			}
		}

		return $this -> db -> insert('product_discount_category', array(
			'cat_id' => $cat_id,
			'brand_id' => $brand_id,
			'discount' => $discount,
			'start_time' => $start_line,
			'end_time' => $end_line,
			'cat_name_path' => $cat_name_path,
			'brand_name' => $brand_name,
			'add_time' => time(),
			'status' => 0,
			'is_over' => 0,
		));
	}
	
	//编辑更新
	function update($id, $params){
		if (!isint($id) || $id <= 0){return false;}
		
		$info = $this -> info($id);
		if ($info){} else {return false;}
		
		//已结束的不可被修改
		if ($info['is_over'] == 1){return false;}
		
		$cat_id   = intval($params['cat_id']);
		$brand_id = intval($params['brand_id']);
		$discount = intval($params['discount']);
		$start_time = trim($params['start_time']);
		$end_time = trim($params['end_time']);
		
		if ($discount < 1 || $discount > 99){return false;}
		
		if (!isDate($start_time) || !isDate($end_time)){return false;}
		$start_line = strtotime($start_time);
		$end_line = strtotime($end_time);
		if ($start_line > $end_line){return false;}
		
		//获取类目路径名
		$cat = new category();
		$data = $cat -> parent_catname_path($cat_id);
		if ($data){
			$cat_name_path = implode(' &gt; ', $data);
		} else {
			return false;
		}
		
		//获取品牌名称
		if ($brand_id > 0){
			$brand = new Brand();
			$info = $brand -> info($brand_id);
			if ($info){
				$brand_name = $info['brand_name'];
			}
		}
		
		return $this -> db -> update('product_discount_category', array(
			'cat_id' => $cat_id,
			'brand_id' => $brand_id,
			'discount' => $discount,
			'start_time' => $start_line,
			'end_time' => $end_line,
			'cat_name_path' => $cat_name_path,
			'brand_name' => $brand_name
		), array(
			'id' => $id
		));
	}
	
	//删除
	function delete($id){
		if (!isint($id) || $id <= 0){return false;}
		return $this -> db -> update('product_discount_category', array('is_delete' => 1, 'delete_time' => time()), array('id' => $id));
	}
	
	//有效
	function validate($id, $status){
		if (!isint($id) || $id <= 0){return false;}
		if ($status != 1){$status = 0;}
		return $this -> db -> update('product_discount_category', array('status' => $status), array('id' => $id));
	}
	
	//设置
	function over($id){
		if (!isint($id) || $id <= 0){return false;}
		return $this -> db -> update('product_discount_category', array('is_over' => 1, 'over_time' => time()), array('id' => $id));
	}
}