<?php
namespace model;

/**
 +----------------------------------
 *	会员等级折扣类
 +----------------------------------
 */
class Discount_Grade extends Front {
	
	//信息
	function info($id){
		if (!isint($id) || $id <= 0){return false;}
		$sql = 'SELECT * FROM `product_discount_grade` WHERE id = '.$id;
		return $this -> db -> row($sql);
	}
	
	//获取折扣列表
	function items($page = 1, $pagesize = 20){
		$sql = 'SELECT * FROM `product_discount_grade` ORDER BY brand_id ASC, id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$items = $this -> db -> rows($sql);
		
		$sql = 'SELECT COUNT(*) AS num FROM `product_discount_grade`';
		$row = $this -> db -> row($sql);
		$total = $row['total'];
		
		return array('items' => $items, 'total' => $total);
	}
	
	//添加
	function add($params){
		$discount2 = intval($params['discount2']);
		$grade_id = intval($params['grade_id']);
		$brand_id = intval($params['brand_id']);
		
		if (!isint($grade_id) || $grade_id <= 0){return false;}
		if ($discount2 <= 0 || $discount2 >= 200){return false;}
		if ($brand_id <= 0){$brand_id = 0;}
		
		$discount = 100 - $discount2;
		
		//查询会员等级
		$grade = new user_grade();
		$grade_info = $grade -> info($grade_id);
		if ($grade_info){
			$grade_name = $grade_info['grade_name'];
		} else {
			return false;
		}
		
		//查询品牌
		$brand_name = '';
		if ($brand_id > 0){
			$brand = new brand();
			$brand_info = $brand -> info($brand_id);
			if ($brand_info){
				$brand_name = $brand_info['brand_name'];
			}
		}
		
		//查询是否存在
		$f = $this -> isExist($grade_id, $brand_id);
		if ($f){
			return false;
		}

		return $this -> db -> insert('product_discount_grade', array(
			'discount' => $discount,
			'discount2' => $discount2,
			'grade_id' => $grade_id,
			'grade_name' => $grade_name,
			'brand_id' => $brand_id,
			'brand_name' => $brand_name,
			'add_time' => time(),
			'update_time' => time()
		));
	}
	
	//编辑更新
	function update($id, $params){
		if (!isint($id) || $id <= 0){return false;}
		
		$info = $this -> info($id);
		if ($info){} else {return false;}
		
		$discount2 = intval($params['discount2']);
		$grade_id = intval($params['grade_id']);
		$brand_id = intval($params['brand_id']);
		
		if (!isint($grade_id) || $grade_id <= 0){return false;}
		if ($discount2 <= 0 || $discount2 >= 200){return false;}
		if ($brand_id <= 0){$brand_id = 0;}
		
		$discount = 100 - $discount2;
		
		//查询会员等级
		$grade = new user_grade();
		$grade_info = $grade -> info($grade_id);
		if ($grade_info){
			$grade_name = $grade_info['grade_name'];
		} else {
			return false;
		}
		
		//查询品牌
		$brand_name = '';
		if ($brand_id > 0){
			$brand = new brand();
			$brand_info = $brand -> info($brand_id);
			if ($brand_info){
				$brand_name = $brand_info['brand_name'];
			}
		}
		
		//查询是否存在
		$f = $this -> isExist($grade_id, $brand_id, $id);
		if ($f){
			return false;
		}
		
		return $this -> db -> update('product_discount_grade', array(
			'discount' => $discount,
			'discount2' => $discount2,
			'grade_id' => $grade_id,
			'grade_name' => $grade_name,
			'brand_id' => $brand_id,
			'brand_name' => $brand_name,
			'update_time' => time()
		), array(
			'id' => $id
		));
	}
	
	//删除
	function delete($id){
		if (!isint($id) || $id <= 0){return false;}
		return $this -> db -> delete('product_discount_grade', array('id' => $id));
	}
	
	//判断是否存在
	function isExist($grade_id, $brand_id, $id = 0){
		$sql = 'SELECT COUNT(*) AS num FROM `product_discount_grade` WHERE grade_id = '.$grade_id.' AND brand_id = '.$brand_id.' ';
		if ($id > 0){
			$sql .= ' AND id != '.$id.'';
		}
		$row = $this -> db -> row($sql);
		if ($row['num'] > 0){
			return true;
		} else {
			return false;
		}
	}
}