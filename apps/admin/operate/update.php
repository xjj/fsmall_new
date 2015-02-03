<?php
namespace admin\operate;

use model as md;
use admin as adm;

/**
 +----------------------------------
 *	定期数据更新
 +----------------------------------
 */
class Update extends adm\front {
	
	function items(){
		$setting = new md\system_setting();
		$cfg = $setting -> get(1);
		$this -> smarty -> assign('cfg', $cfg);
		$this -> smarty -> display('operate/update.tpl');	
	}
	
	//品牌的类目更新 -- 手动
	function brand_category(){
		$brand = new md\brand();
		$brand_items = $brand -> items(1);
		
		if ($brand_items){} else {
			cpmsg(false, '没有查询到品牌信息！', -1);	
		}
		
		
		$prd = new md\product();
		$brand_cat = new md\brand_category();
		foreach ($brand_items as $item){
			$idArr = $prd -> brand_catid($item['brand_id']);
			if ($idArr){
				$brand_cat -> update($item['brand_id'], $idArr);	
			}
		}
		
		$setting = new md\system_setting();
		$setting -> update('update_brand_category_time', time());
		
		cpmsg(true, '更新成功！', '/'.$this->mod.'/'.$this->col);
	}
	
	//商品的销售统计 -- 自动
	
	
}