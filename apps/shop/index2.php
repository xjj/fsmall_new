<?php
namespace shop;

class index2 extends front {
	
	function index(){
		$db = fetch_db_instance(1);
		
		$row = $db -> row('select arr FROM `ecs_tao` where tbcatid = 50010368 order by id ASC limit 0,1');	
		$arr = $row['arr'];
		$arr2 = eval("return {$arr};");
		header('Content-type: text/html; charset=utf-8');
		var_export($arr2);
	}
	
	function update(){
	
		//$arr = var_export($arr, true);
		//$db = fetch_db_instance(1);
		//echo $db -> update('ecs_tao', array('arr' => $arr), array('tbcatid' => 50010368));
	}
	
	
	function spot(){
		$db = fetch_db_instance(1);
		$sql = 'SELECT g.goods_id, g.goods_hh, g.goods_name, g.goods_cn_name, g.goods_number, g.shop_price, kr.korea, g.goods_source_url FROM `ecs_goods` g INNER JOIN `ecs_goods_other` kr ON g.goods_id = kr.goods_id WHERE g.is_current = 1 and g.is_delete = 0 and g.is_on_sale = 1 ORDER BY g.goods_id DESC';
		$grows = $db -> rows($sql);
		if ($grows){
			$goodsIdArr = array();
			foreach ($grows as $row){
				$goodsIdArr[] = $row['goods_id'];	
			}
		} else {
			exit();	
		}
		
		$goodsIdStr = implode(',', $goodsIdArr);
		//echo $goodsIdStr;
		
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
		
		//------------------------当前汇率--------------------------//
		$sql = "select rate from ecs_goods_rate where 1";
		$row = $db->row($sql);
		$rate = $row['rate'];
		
		//-----------------获取商品的销售属性值信息---------------------//
		$sql = "select goods_attr_id, goods_id, attr_id, attr_value, attr_price from ecs_goods_attr where goods_id in ({$goodsIdStr})";
		$rows = $db->rows($sql);
		$groupedAttr = array();
		foreach ($rows as $p){
			$p['attr_value'] = $p['attr_value'];
			if (in_array($p['attr_id'], $colorAttr)){
				$groupedAttr[$p['goods_id']]['color'][] = array('attr_value'=>$p['attr_value'], 'attr_price'=>floatval($p['attr_price']/$rate) );
			}elseif(in_array($p['attr_id'], $sizeAttr)){
				$groupedAttr[$p['goods_id']]['size'][] = array('attr_value'=>$p['attr_value'], 'attr_price'=>floatval($p['attr_price']/$rate) );
			}else{
				$groupedAttr[$p['goods_id']]['other'][$p['attr_id']][] = array('attr_value'=>$p['attr_value'], 'attr_price'=>intval($p['attr_price']/$rate) );
			}
		}
		
		$data = array();
		foreach ($grows as $row){
			$skus = $this -> getSKUs($row['shop_price'],$groupedAttr[$row['goods_id']]['color'], $groupedAttr[$row['goods_id']]['size']);
			
			$data[] = array(
				'goods_id' => $row['goods_id'],
				'goods_name' => $row['goods_name'],
				'goods_cn_name' => $row['goods_cn_name'],
				'goods_hh' => $row['goods_hh'],
				'shop_price' => $row['shop_price'],
				'korea' => $row['korea'],
				'source_url' => $row['goods_source_url'],
				'skus' => $skus
				
			);	
		}
		//print_r($data);
		//exit();
		header("Content-type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=current.xls");
		echo $this -> print_excel($data);
	
	}
	
	function getSKUs($shop_price, $colors, $sizes){
		$data = array();
		foreach ($colors as $color){
			if (is_array($sizes)){
				foreach ($sizes as $size){
					$data[] = array(
						'color' => $color['attr_value'],
						'size' => $size['attr_value'],
						'price' => $shop_price + $color['attr_price'] + $color['attr_price'],
					);	
				}	
			} else {
				$data[] = array(
					'color' => $color['attr_value'],
					'size' => '',
					'price' => $shop_price + $color['attr_price'],
				);	
			}	
		}
		
		return $data;
	}
	
	function print_excel($data){
		$table = '<table border=1>';
		foreach ($data as $row){
			$count = count($row['skus']);
			$table .= '<tr>';
			$table .= '<td rowspan="'.$count.'">'.$row['goods_id'].'</td>';
			$table .= '<td rowspan="'.$count.'">'.$row['goods_name'].'</td>';
			$table .= '<td rowspan="'.$count.'">'.$row['goods_cn_name'].'</td>';
			$table .= '<td rowspan="'.$count.'">'.$row['goods_hh'].'</td>';
			$table .= '<td rowspan="'.$count.'">'.$row['source_url'].'</td>';
			
			$table .= '<td>'.$row['skus'][0]['color'].'</td>';
			$table .= '<td>'.$row['skus'][0]['size'].'</td>';
			$table .= '<td>'.$row['skus'][0]['price'].'</td>';
			$table .= '</tr>';
			if ($count > 1){
				for ($i = 1; $i < $count; $i++){
					$table .= '<tr>';	
					$table .= '<td>'.$row['skus'][$i]['color'].'</td>';
					$table .= '<td>'.$row['skus'][$i]['size'].'</td>';
					$table .= '<td>'.$row['skus'][$i]['price'].'</td>';
					$table .= '</tr>';
				}	
			}
		}
		$table .=  '</table>';
		
		return $table;
	}
}