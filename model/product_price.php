<?php
namespace model;

/**
 +------------------------------------
 *	商品价格类 -- 商品及SKU
 *	各种优惠，促销，活动的 价格变化都从这里计算
 +------------------------------------
 */
class Product_Price extends Front {
	
	/**
	 +----------------------------------
	 *	获取商品的销售价格
	 *	$prd_id			int			
	 *	$params			array	商品参数
	 *	$uid			int		当前会员
	 +----------------------------------
	 */
	public function price($prd_id, $params = array(), $uid = 0, $grade_id = 0){
		return $this -> discount_price($prd_id, $params, $uid, $grade_id);
	}
	
	/**
	 +----------------------------------------
	 *	计算商品的批发价格
	 *	$params			array
	 +----------------------------------------
	 */
	public function price_wholesale($prd_id, $params = array(), $uid = 0){
		return $this -> discount_price($prd_id, $params, $uid, 2);
	}
	
	/**
	 +----------------------------------------
	 *	计算商品的零售价格
	 *	$params			array
	 +----------------------------------------
	 */
	public function price_retail($prd_id, $params = array(), $uid = 0){
		return $this -> discount_price($prd_id, $params, $uid, 1);
	}
	
	
	//--------------------------------------------------//
	
	//私有方法
	
	//--------------------------------------------------//
	
	/**
	 +----------------------------------------
	 *	计算商品的促销价格
	 *	没有计算会员等级的折扣价格
	 +----------------------------------------
	 *	$prd_id			int			
	 *	$params			array	商品参数
	 +----------------------------------------
	 */
	private function discount_price($prd_id, $params = array(), $uid, $grade_id){
		if (empty($params) || !is_array($params)){
			$prd = new product();
			$prd_data = $prd -> info($prd_id, array('cat_id', 'brand_id', 'is_spot', 'price', 'freight', 'is_freight'));
			if ($prd_data){
				$cat_id = $prd_data['cat_id'];
				$brand_id = $prd_data['brand_id'];
				$is_spot = $prd_data['is_spot'];
				$price = $prd_data['price'];
				$freight = $prd_data['freight'];
				$is_freight = $prd_data['is_freight'];
			} else {
				return 0;
			}
		} else {
			$cat_id = intval($params['cat_id']);
			$brand_id = intval($params['brand_id']);
			$is_spot = intval($params['is_spot']);
			$price = intval($params['price']);
			$freight = intval($params['freight']);
			$is_freight = intval($params['is_freight']);
		}
		
		if ($is_freight != 1){$is_freight = 0;}
		
		//获取所有折扣值
		$disc = new discount();
		$disc_category = $disc -> category_discount($cat_id, $brand_id);
		$disc_brand = $disc -> brand_discount($brand_id, $cat_id);
		$disc_product = $disc -> product_discount($prd_id, $uid);
		if ($is_spot > 0){
			$disc_spot = $disc -> spot_discount();
		} else {
			$disc_spot = 0;
		}
		$disc_grade = $disc -> grade_discount($grade_id, $brand_id);
		
		$discArr = array();
		if ($disc_category && $disc_category > 0){$discArr['category'] = $disc_category;}
		if ($disc_brand && $disc_brand > 0){$discArr['brand'] = $disc_brand;}
		if ($disc_spot && $disc_spot > 0){$discArr['spot'] = $disc_spot;}
		if ($disc_product && $disc_product > 0){$discArr['product'] = $disc_product;}
		
		//获取最大折扣并计算价格
		if (empty($discArr)){
			$price_sale = $price;
		} else {
			$maxDisc = 0;
			foreach ($discArr as $val){
				if ($val > $maxDisc){$maxDisc = $val;}
			}
			$price_sale = $price * (100 - $maxDisc) / 100;
		}
		
		//获取商品直接促销价格并比较
		$promote_price = $disc -> product_discount_price($prd_id, $uid);
		if ($promote_price && $promote_price > 0 && $price_sale > $promote_price){
			$price_sale = $promote_price;
		}
		
		//会员等级折扣计算
		$price_sale = (100 - $disc_grade) / 100 * $price_sale;
		
		//促销价格
		$price_sale = format_money(round($price_sale + $freight * $is_freight));
		
		$ret = array('price' => $price_sale);
		
		if ($maxDisc > 0){
			$ret['discount'] = $maxDisc;
		}
		if ($promote_price && $promote_price > 0){
			$ret['is_special'] = 1;
		}
		
		return $ret;
	}
}
