<?php
namespace model;

/**
 +-------------------------------
 *	商品折扣类
 +-------------------------------
 */
class Discount extends Front {
	
	//计算类目折扣
	//类目ID都为三级类目ID -- 后台添加
	function category_discount($cat_id, $brand_id){
		static $disc = NULL;
		
		//查询出所有可用的类目折扣
		if ($disc === NULL){
			$now = time();
			$sql = 'SELECT discount, cat_id, brand_id FROM `product_discount_category` WHERE is_over = 0 AND start_time < '.$now.' AND end_time > '.$now.' AND status = 1 AND is_delete = 0  ORDER BY id DESC';
			$disc = $this -> db -> rows($sql);
		}
		
		//判断折扣
		if ($disc) {
			$disc_0 = $disc_1 = 0;
			
			//查询出全局的类目折扣 -- 以最近设置的一个为准
			foreach ($disc as $row){
				if ($row['cat_id'] == $cat_id && $row['brand_id'] == 0){
					$disc_0 = $row['discount'];
					break;
				}
			}
			
			//查询出类目的品牌折扣
			if ($brand_id > 0) {
				foreach ($disc as $row){
					if ($row['cat_id'] == $cat_id && $row['brand_id'] == $brand_id){
						$disc_1 = $row['discount'];
						break;
					}
				}
			}
			
			//如果品牌的折扣被单独设置了，那么以品牌的折扣为准，否则以全局的折扣为准
			return ($disc_1 > 0) ? $disc_1 : $disc_0;
		} else {
			return false;
		}
	}
	
	//计算品牌折扣
	//类目为三级类目ID
	function brand_discount($brand_id, $cat_id){
		static $disc = NULL;
		
		if ($disc === NULL){
			$now = time();
			$sql = 'SELECT discount, brand_id, cat_id FROM `product_discount_brand` WHERE is_over = 0 AND start_time < '.$now.' AND end_time > '.$now.' AND status = 1 AND is_delete = 0 ORDER BY id DESC';
			$disc = $this -> db -> rows($sql);
		}
		
		if ($disc){
			$disc_0 = $disc_1 = 0;
			
			//查询出全局的
			foreach ($disc as $row){
				if ($row['brand_id'] == $brand_id && $row['cat_id'] == 0){
					$disc_0 = $row['discount'];
					break;
				}
			}
			
			//查询出品牌
			if ($cat_id > 0) {
				foreach ($disc as $row){
					if ($row['brand_id'] == $brand_id && $row['cat_id'] == $cat_id){
						$disc_1 = $row['discount'];
						break;
					}
				}
			}
			
			//如果类目的折扣被单独设置了，那么以类目的折扣为准，否则以全局的折扣为准
			return ($disc_1 > 0) ? $disc_1 : $disc_0;
		} else {
			return false;
		}
	}
	
	//计算商品折扣
	function product_discount($prd_id, $uid){
		static $disc = NULL;
		
		if ($disc === NULL){
			$now = time();
			$sql = 'SELECT prd_id, discount, uid FROM `product_discount_product` WHERE is_over = 0 AND start_time < '.$now.' AND end_time > '.$now.' AND status = 1 AND price = 0 AND discount > 0 AND is_delete = 0 ORDER BY id DESC';
			$disc = $this -> db -> rows($sql);
		}
		
		if ($disc){
			$disc_0 = $disc_1 = 0;
			
			foreach ($disc as $row){
				if ($row['prd_id'] == $prd_id && $row['uid'] == 0){
					$disc_0 = $row['discount'];
					break;
				}
			}
			
			if ($uid > 0) {
				foreach ($disc as $row){
					if ($row['prd_id'] == $prd_id && $row['uid'] == $uid){
						$disc_1 = $row['discount'];
						break;
					}
				}
			}
			
			//如果用户的折扣被单独设置了，那么以用户的折扣为准，否则以全局的折扣为准
			return ($disc_1 > 0) ? $disc_1 : $disc_0;
		} else {
			return false;
		}
	}
	
	//计算现货折扣
	function spot_discount(){
		static $disc = NULL;
		
		if ($disc === NULL){
			$now = time();
			$sql = 'SELECT discount FROM `product_discount_spot` WHERE is_over = 0 AND start_time < '.$now.' AND end_time > '.$now.' AND status = 1 AND is_delete = 0 ORDER BY id DESC LIMIT 0, 1';
			$disc = $this -> db -> row($sql);
		}
		
		if ($disc){
			return $disc['discount'];
		} else {
			return false;
		}
	}
	
	//计算会员等级折扣
	function grade_discount($grade_id, $brand_id){
		static $disc = NULL;
		
		if ($disc === NULL){
			$now = time();
			$sql = 'SELECT discount, grade_id, brand_id FROM `product_discount_grade` ORDER BY id DESC';
			$disc = $this -> db -> rows($sql);
		}
		
		if ($grade_id <= 0){$grade_id = 1;} //注册会员
		
		if ($disc){
			$disc_0 = $disc_1 = 0;
			
			foreach ($disc as $row){
				if ($row['grade_id'] == $grade_id && $row['brand_id'] == 0){
					$disc_0 = $row['discount'];
					break;
				}
			}
			
			if ($brand_id > 0) {
				foreach ($disc as $row){
					if ($row['grade_id'] == $grade_id && $row['brand_id'] == $brand_id){
						$disc_1 = $row['discount'];
						break;
					}
				}
			}
			
			//如果品牌的折扣被单独设置了，那么以品牌的折扣为准，否则以全局的折扣为准
			return ($disc_1 > 0) ? $disc_1 : $disc_0;
		} else {
			return false;
		}
	}
	
	
	//商品的直接促销价格
	function product_discount_price($prd_id, $uid){
		static $disc = NULL;
		
		if ($disc === NULL){
			$now = time();
			$sql = 'SELECT price, prd_id, uid FROM `product_discount_product` WHERE is_over = 0 AND start_time < '.$now.' AND end_time > '.$now.' AND status = 1 AND price > 0 AND discount = 0 AND is_delete = 0 ORDER BY id DESC';
			$disc = $this -> db -> rows($sql);
		}
		
		if ($disc){
			$disc_0 = $disc_1 = 0;
			
			foreach ($disc as $row){
				if ($row['prd_id'] == $prd_id && $row['uid'] == 0){
					$disc_0 = $row['price'];
					break;
				}
			}
			
			if ($uid > 0) {
				foreach ($disc as $row){
					if ($row['prd_id'] == $prd_id && $row['uid'] == $uid){
						$disc_1 = $row['price'];
						break;
					}
				}
			}
			
			//如果商品的用户价格被单独设置了，那么以设置的价格为准，否则以全局的价格为准
			return ($disc_1 > 0) ? $disc_1 : $disc_0;
		} else {
			return false;
		}
	}
}