<?php
namespace model;

/**
 +---------------------------
 *	会员等级类
 +---------------------------
 */
class User_Grade extends Front {
	
	private $discount_1 = 125;		//普通会员的默认折扣
	
	function info($grade_id){
		if (!isint($grade_id) || $grade_id <= 0){return false;}
		$sql = 'SELECT * FROM `user_grade` WHERE grade_id = '.$grade_id;
		return $this -> db -> row($sql);
	}
	
	//所有会员等级信息
	function items(){
		$sql = 'SELECT * FROM `user_grade` WHERE is_delete = 0 AND status = 1 ORDER BY grade_id ASC';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$data = array();
			foreach ($rows as $row){
				$data[$row['grade_id']] = array(
					'grade_id' => $row['grade_id'],
					'grade_name' => $row['grade_name'],
					'discount' => $row['discount'],
				);
			}
			return $data;
		} 
		return false;
	}
	
	//添加会员等级
	function add($data){
		$grade_name = trim($data['grade_name']);
		
		if (empty($grade_name)){return false;}
		
		return $this -> db -> insert('user_grade', array(
			'grade_name' => $grade_name,
			'discount' => $discount,
			'status' => 0,
			'is_delete' => 0,
			'add_time' => time()
		));
	}
	
	//获取会员等级折扣
	function discount($grade_id, $brand_id){
		//容错设置 -- 计算价格时用到
		if ($grade_id == 0){
			$grade_id = 1;
		}
		
		//获取会员等级折扣
		$grade_items = $this -> items();
		
		//判断会员等级折扣
		$grade_disc = intval($grade_items[$grade_id]['discount']);
		
		//获取会员等级品牌折扣
		$brand_disc = $this -> brand_discount($grade_id, $brand_id);
		
		if ($brand_disc){
			return $brand_disc; 
		} else {
			return $grade_disc;	
		}
	} 
	
	//编辑会员等级信息
	function update($grade_id, $data){
		if (!isint($grade_id) || $grade_id <= 0){return false;}
		
		$grade_name = trim($data['grade_name']);
		$discount = intval($data['discount']);
		if (empty($grade_name)){return false;}
		
		return $this -> db -> update('user_grade', array(
			'grade_name' => $grade_name,
			'discount' => $discount,
		), array(
			'grade_id' => $grade_id
 		));
	}
	
	//删除会员等级
	function delete($grade_id){
		if (!isint($grade_id) || $grade_id <= 0){return false;}
		return $this -> db -> update('user_grade', array('is_delete' => 1), array('grade_id' => $grade_id));
	}
	
	//设置有效
	function show($grade_id){
		if (!isint($grade_id) || $grade_id <= 0){return false;}
		return $this -> db -> update('user_grade', array('status' => 1), array('grade_id' => $grade_id));
	}
	
	//设置为无效
	function hide($grade_id){
		if (!isint($grade_id) || $grade_id <= 0){return false;}
		return $this -> db -> update('user_grade', array('status' => 0), array('grade_id' => $grade_id));
	}
	
	//查询会员等级的品牌折扣
	function brand_discount_items($grade_id){
		if (!isint($grade_id) || $grade_id <= 0){return false;}
		$sql = 'SELECT t.*, b.brand_name FROM `user_grade_discount` t inner join `brands` b on t.brand_id = b.brand_id WHERE t.grade_id = '.$grade_id.' ORDER BY t.id ASC';
		return $this -> db -> rows($sql); 
	}
	
	//添加会员等级的品牌折扣
	function add_brand_discount($grade_id, $brand_id, $discount){
		if (!isint($grade_id) || $grade_id <= 0){return false;}
		if (!isint($brand_id) || $brand_id <= 0){return false;}
		
		return $this -> db -> insert('user_grade_discount', array(
			'grade_id' => $grade_id,
			'brand_id' => $brand_id,
			'discount' => $discount,
			'add_time' => time()
		));
	}
	
	//删除会员等级的品牌折扣
	function delete_brand_discount($grade_id, $brand_id){
		if (!isint($grade_id) || $grade_id <= 0){return false;}
		if (!isint($brand_id) || $brand_id <= 0){return false;}
		
		return $this -> db -> delete('user_grade_discount', array(
			'grade_id' => $grade_id,
			'brand_id' => $brand_id,
		));
	}
	
	//获取品牌折扣
	function brand_discount($grade_id, $brand_id){
		static $result = NULL;
		if ($result === NULL){
			$sql = 'SELECT * FROM `user_grade_discount` ORDER BY id ASC';
			$rows = $this -> db -> rows($sql);
			if ($rows){
				$data = array();
				foreach ($rows as $row){
					$grade_id = $row['grade_id'];
					$brand_id = $row['brand_id'];
					$data[$grade_id][$brand_id] = $row['discount'];
				}
				$reslut = $data;
			} else {
				$result = array();
			}
		}
		
		if (isset($result[$grade_id][$brand_id]) && $result[$grade_id][$brand_id] > 0){
			return $result[$grade_id][$brand_id];
		}
		return false; 
	}
}