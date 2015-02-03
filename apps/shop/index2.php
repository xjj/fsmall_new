<?php
namespace shop;

class index2 extends front {
	
	function indexxx(){
		$db = fetch_db_instance(1);
		
		$row = $db -> row('select arr FROM `ecs_tao` where tbcatid = 50000436 order by id ASC limit 0,1');	
		$arr = $row['arr'];
		$arr2 = eval("return {$arr};");
		header('Content-type: text/html; charset=utf-8');
		var_export($arr2);
	}
	
	function updatexx(){
		//$arr = 
		//$arr = var_export($arr, true);
		//$db = fetch_db_instance(1);
		//$num = $db -> update('ecs_tao', array('arr' => $arr), array('tbcatid' => 50011746));
		//var_dump($num);
	}
	
	function get(){
		set_time_limit(0);
		ini_set('memory_limit', '512M');

		$goodsIdArr = array(250522,254506,259739,254913,267467,268714,250920,254499,268719,271421,296170,287327,282973,272491,274013,277888,275713,284819,287041,272147,278369,284755,292752,297252,298190,249802,253478,264026,287364,254509,287410,271461,275699,282973,287340,283727,298162,287374,255458,278832,258914,268714,254970,263726,286141);
		
		$goodsIdStr = implode(',', $goodsIdArr);
		
		$db = fetch_db_instance(1);
		
		//------------------------当前汇率--------------------------//
		$sql = "select rate from ecs_goods_rate where 1";
		$row = $db->row($sql);
		$rate = $row['rate'];
		
		//----------------------查询出销售属性类型--------------------//
		$sql = "select attr_id, attr_name from ecs_attribute";
		$attrArr = $db->rows( $sql );
		$colorAttr = $sizeAttr = $otherAttr = array();
		foreach ($attrArr as $at){
			if ($at['attr_name'] == '颜色'){
				$colorAttr[] = $at['attr_id'];
			} elseif($at['attr_name'] == '尺寸'){
				$sizeAttr[] = $at['attr_id'];
			} else {
				$otherAttr[] = $at['attr_id'];	
			}
		}
		
		//-----------------获取商品的销售属性值信息---------------------//
		$sql = "select goods_attr_id, goods_id, attr_id, attr_value, attr_price from ecs_goods_attr where goods_id in ({$goodsIdStr})";
		$rows = $db->rows($sql);
		$groupedAttr = array();
		foreach ($rows as $p){
			$p['attr_value'] = $p['attr_value'];
			if (in_array($p['attr_id'], $colorAttr)){
				$groupedAttr[$p['goods_id']]['color'][] = array('attr_value'=>$p['attr_value'], 'attr_price'=>floatval($p['attr_price']/$rate), 'attr_price_kr' => $p['attr_price'] );
			}elseif(in_array($p['attr_id'], $sizeAttr)){
				$groupedAttr[$p['goods_id']]['size'][] = array('attr_value'=>$p['attr_value'], 'attr_price'=>floatval($p['attr_price']/$rate), 'attr_price_kr' => $p['attr_price'] );
			}else{
				$groupedAttr[$p['goods_id']]['other'][$p['attr_id']][] = array('attr_value'=>$p['attr_value'], 'attr_price'=>intval($p['attr_price']/$rate), 'attr_price_kr' => $p['attr_price'] );
			}
		}
		
		$sql = "select g.*, t.arr, ok.korea from ecs_goods g left join ecs_goods_tao t on g.goods_id = t.goods_id left join `ecs_goods_other` ok on g.goods_id = ok.goods_id where g.goods_id in ({$goodsIdStr})";
		$rows = $db->rows($sql);
		
		$data = array();
		
		foreach ($rows as $item){
			
			$groupdColors = $groupedAttr[$item['goods_id']]['color'];
			$groupdSizes  = $groupedAttr[$item['goods_id']]['size'];
			
			$attribute = array();
			foreach ($groupdColors as $colorArr){
				foreach ($groupdSizes as $sizeArr){
					$attribute[] = array(
						'color' => $colorArr['attr_value'],
						'size' => $sizeArr['attr_value'],
						'price_kr' => $item['korea'] + $colorArr['attr_price_kr'] + $sizeArr['attr_price_kr'],
						'price_cn' => $item['shop_price'] + $colorArr['attr_price'] + $sizeArr['attr_price'],	
					);
				}	
			}
			
			$line = array();
			$line['goods_id'] = $item['goods_id'];
			$line['goods_name'] = $item['goods_name'];
			$line['goods_name_cn'] = $item['goods_cn_name'];
			$line['goods_img'] = $item['goods_img'];
			$line['goods_hh'] = $item['goods_hh'];
			$line['SKUs'] = $attribute; 
			$line['description'] = $item['goods_desc'];
			$line['detailPicture'] = $this -> fetchGoodsDetailPictures($item['goods_id']);
			
			$data[] = $line;
		}
		$x = json_encode($data);
		file_put_contents('fsdata_20150128.json', $x);
		echo 'over';
	}
	
	function fetchGoodsDetailPictures($goods_id){
		$db = fetch_db_instance(1);
		$sql = 'SELECT * from `ecs_goods_pics` WHERE `goods_id` = '.$goods_id.' and `status` = 3  and `isdelete` = 0 order by pid asc';
		$rows = $db -> rows($sql);
		if ($rows){} else {
			return false; 	
		}
		
		$data = array();
		foreach ($rows as $row){
			if ($row['status'] == 3){
				$data[] = 'http://img2.fs-mall.com/'.$row['pic_fs'];
				
			}
		}
		return $data;
	}
}