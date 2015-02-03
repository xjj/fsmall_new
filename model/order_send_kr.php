<?php
namespace model;

/**
 +-------------------------------------
 *	韩方发货类
 +-------------------------------------
 */
class Order_Send_KR	 extends Front {
	
	function search($brand_id, $params, $page, $pagesize){
		
		$product_name 	= trim($params['product_name']);
		$product_sn		= trim($params['product_sn']);
		$status			= trim(strtolower($params['status']));
		$order_sn		= trim($params['order_sn']);	//订单号或条码
		$start_time		= trim($params['start_time']);
		$end_time		= trim($params['end_time']);
		
		$where = $this -> sql_where($brand_id, $params);
		
		$sql  = ' SELECT kr.*, op.prd_id, op.order_sn, op.product_name_kr, op.product_sn, op.price_kr, op.pic_small, op.prop_value_kr ';
		$sql .= ' FROM `order_product_kr_shipping` kr ';
		$sql .= ' LEFT JOIN `order_product` op ON kr.op_id = op.op_id ';
		$sql .= ' '.$where.' ';
		$sql .= ' ORDER BY kr.op_id DESC, kr.id DESC ';
		$sql .= ' LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$order_items = $this -> db -> rows($sql);
		if ($order_items){
			foreach ($order_items as $key => $item){
				$order_items[$key]['prop_value_kr'] = unserialize($item['prop_value_kr']);
				$price_kr = $item['price_kr'];
				
				//已经发货的以发货的折扣为准
				if ($row['discount'] > 0){
					$discount = $row['discount'];
				} else {
					$discount = $this -> PRD_discount($brand_id, $item['prd_id']);
				}
				$order_items[$key]['price_kr_discount'] = round($price_kr * $discount);
			}
		}
		
		$sql = 'SELECT COUNT(*) as num FROM (SELECT 1 FROM FROM `order_product_kr_shipping` kr LEFT JOIN `order_product` op ON kr.op_id = op.op_id '.$where.') t';
		$row = $this -> db -> row($sql);
		$total = $row['num'];	
		
		return array(
			'items' => $order_items,
			'total' => $total
		);
	}
	
	
	//查询商品的折扣
	function PRD_discount($brand_id, $prd_id){
		static $brand_discount_items = NULL;
		static $product_discount_items = NULL;
		
		if ($brand_discount_items === NULL){
			//查询所有品牌折扣
			$brand_discount = new suplier_brand_discount();
			$brand_discount_items = $brand_discount -> items();
		}
		
		if ($product_discount_items === NULL){
			//查询所有商品折扣
			$product_discount = new suplier_product_discount(); 
			$product_discount_items = $product_discount -> items();	
		}
		
		if (is_array($brand_discount_items) && isset($brand_discount_items[$brand_id])){
			if (is_array($product_discount_items) && isset($product_discount_items[$prd_id])){
				return $product_discount_items[$prd_id]/100;
			}
			return $brand_discount_items[$brand_id]/100;
		} else {
			exit('BRAND_DISCOUNT_NOT_SET');
		}
	}
	
	
	//下载查询结果
	//将结果写到文件中
	function prepareData($brand_id, $params){
		
		$product_name 	= trim($params['product_name']);
		$product_sn		= trim($params['product_sn']);
		$status			= trim(strtolower($params['status']));
		$order_sn		= trim($params['order_sn']);	//订单号或条码
		$start_time		= trim($params['start_time']);
		$end_time		= trim($params['end_time']);
		
		//查询品牌名
		$brand = new brand();
		$brand_name = $brand -> get_name_by_id($brand_id);
		
		//查询商品类目
		$cat = new category();
		$cat_items = $cat -> layer_items(3, 0); //所有
		$cat_name_kr = array();
		if ($cat_items){
			foreach ($cat_items as $item){
				$cat_id = $item['cat_id'];
				$cat_name_kr[$cat_id] = $item['cat_name_kr'];
			}	
		}
		
		$where = $this -> sql_where($brand_id, $params);
		
		$sql  = ' SELECT kr.*, op.prd_id, op.order_sn, op.product_name_kr, op.product_sn, op.price_kr,  op.prop_value_kr, p.cat_id, o.consignee, o.province_name, o.city_name, o.county_name, o.address, o.zipcode, o.mobile';
		$sql .= ' FROM `order_product_kr_shipping` kr ';
		$sql .= ' LEFT JOIN `order_product` op ON kr.op_id = op.op_id ';
		$sql .= ' LEFT JOIN `product_info` p ON op.prd_id = p.prd_id ';
		$sql .= ' LEFT JOIN `order_info` o ON kr.order_id = o.order_id ';
		$sql .= ' '.$where.' ';
		$sql .= ' ORDER BY kr.op_id DESC, kr.id DESC';
		
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$H = array(
				'NO',						//编号
				'Order Date',				//日期
				'Vendor',					//供应商
				'Order No',					//订单号
				'Barcode',					//条码
				'GOODS SN',					//商品编号
				'Seller',					//卖家
				'Seller Addr',				//卖家地址
				'Category',					//分类
				'Material',					//材料
				'Brand',					//品牌
				'Item Name',				//商品名称
				'Item Quantity',			//数量
				'Item Opt.(Color/Size)',	//属性
				'Price(KRW)', 				//韩价
				'AfterDiscount',			//折后价
				'Customer',					//客户
				'Customer Tel',				//客户电话
				'Zip Code',					//邮编
				'Terminal',					//省市县地址
				'Customer Addr.',			//客户地址
				'W/H In:',
				'Area No',
				'W/H Out',
				'C/T',
				'Weight',					//重量
				'Carton No',				//
				'Master B/L',
				'House B/L',
				'Local B/L',
				'Local Deliver',
				'Order Time',				//订单时间
				'Send Time'					//发货时间
			);
			$i = 1;
			$ret = array();
			foreach ($rows as $row){
				//获取属性值
				$prop_value_kr = unserialize($row['prop_value_kr']);
				$prop_value_output = '';
				foreach ($prop_value_kr as $value){
					$prop_value_output .= $value.';';	
				}
				
				//获取折扣价格
				if ($row['discount'] > 0){
					$discount = $row['discount'];
				} else {
					$discount = $this -> PRD_discount($brand_id, $row['prd_id']);
				}
				$price_kr_discount = round($row['price_kr'] * $discount);
				
				//地址
				$terminal = $row['province_name'].$row['city_name'].$row['county_name'];
				$address = $terminal.' '.$row['address'];
				
				$ret[] = array(
					$i,
					date('Y-m-d', $row['order_time']),
					'FS',
					$row['order_sn'],
					$row['barcode'],
					$row['product_sn'],
					$brand_name,
					'',
					$cat_name_kr[$row['cat_id']],
					'',
					$brand_name,
					$row['product_name_kr'],
					1,
					$prop_value_output,
					$row['price_kr'],
					$price_kr_discount,
					$row['consignee'],
					$row['mobile'],
					$row['zipcode'],
					$terminal,
					$address,
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					'',
					date('Y-m-d H:i:s', $row['order_time']),
					$row['send_time'] > 0 ? date('Y-m-d H:i:s', $row['send_time']) : ''
				);
				$i += 1; 
			}
			
			array_unshift($ret, $H);
			
			if (empty($params)){
				$params['brand_id'] = $brand_id;	
			} else {
				$params = array_merge($params, array('brand_id' => $brand_id));	
			}
			$params['date'] = time();
			
			$filename = $this -> fileSign($params);
			$filepath = ROOT_PATH .'/runtime/suplier/'. date('Ymd').'/'. $filename.'.php';
			makedir(ROOT_PATH .'/runtime/suplier/'. date('Ymd').'/');
			
			if (file_exists($filepath)){
				unlink($filepath);
			} 
			
			file_put_contents($filepath, "<?php return ".var_export($ret, TRUE)."?>");
			
			return array('folder' => date('Ymd'), 'filename' => $filename);
		} else {
			return false;	
		}
	}
	
	//sellmate -- 数据准备
	function prepareData_sellmate($brand_id, $params){
		
		$where = $this -> sql_where($brand_id, $params);
		$sql  = ' SELECT kr.*, op.prd_id, op.order_sn, op.product_name_kr, op.product_sn, op.price_kr,  op.prop_value_kr ';
		$sql .= ' FROM `order_product_kr_shipping` kr ';
		$sql .= ' LEFT JOIN `order_product` op ON kr.op_id = op.op_id ';
		$sql .= ' LEFT JOIN `product_info` p ON op.prd_id = p.prd_id ';
		$sql .= ' '.$where.' ';
		$sql .= ' ORDER BY kr.op_id DESC, kr.id DESC';
		
		$rows = $this -> db -> rows($sql);
		$ret = array();
		if ($rows){
			$H = array( 
				'주문번호', 			//订单号 
				'주문상품명',			//商品名称 
				'상품옵션',			//颜色
				'수량',				//数量
				'상품가격',			//韩国原单价
				'주문자명',			//订货人
				'주소',				//联系地址
				'핸드폰',				//电话
				'우편번호',			//
			);	
			
			foreach ($rows as $row){
				//获取属性值
				$prop_value_kr = unserialize($row['prop_value_kr']);
				$prop_value_output = '';
				foreach ($prop_value_kr as $value){
					$prop_value_output .= $value.';';	
				}
				
				$ret[] = array(
					$row['order_sn'],
					$row['product_name_kr'],
					$prop_value_output,
					1,
					$row['price_kr'],
					'FS',							//订货人
					'㈜ 아이씨에스 코리아 서울 특별시 강서구 마곡동 326-2',	 //地址
					'010-3209-2246',				//联系电话
					'157-210',						//收货人
				);	
			}
			
			array_unshift($ret, $H);
			
			if (empty($params)){
				$params['brand_id'] = $brand_id;	
			} else {
				$params = array_merge($params, array('brand_id' => $brand_id));	
			}
			$params['date'] = time();
			
			$filename = $this -> fileSign($params);
			$filepath = ROOT_PATH .'/runtime/suplier/'. date('Ymd').'/'. $filename.'.php';
			makedir(ROOT_PATH .'/runtime/suplier/'. date('Ymd').'/');
			
			if (file_exists($filepath)){
				unlink($filepath);
			} 
			
			file_put_contents($filepath, "<?php return ".var_export($ret, TRUE)."?>");
			
			return array('folder' => date('Ymd'), 'filename' => $filename);
		}
	}
	
	private function sql_where($brand_id, $params){
		$product_name 	= trim($params['product_name']);
		$product_sn		= trim($params['product_sn']);
		$status			= trim(strtolower($params['status']));
		$order_sn		= trim($params['order_sn']);	//订单号或条码
		$start_time		= trim($params['start_time']);
		$end_time		= trim($params['end_time']);
		
		$where = 'WHERE kr.brand_id = '.$brand_id.' ';
		if (!in_array($status, array('all', 'undo', 'done', 'ucancle', 'kcancle'))){
			$status = 'undo';
		}
		switch ($status){
			case 'undo':
				$where .= ' AND kr.status = 0'; 
				break;
			case 'done':
				$where .= ' AND kr.status = 1'; 
				break;
			case 'ucancle':
				$where .= ' AND kr.status = 3'; 
				break;
			case 'kcancle':
				$where .= ' AND kr.status = 2'; 
				break;
		}
		
		if (!empty($product_name)){
			$where .= ' AND op.product_name_kr like \'%'.encode($product_name).'%\'';
		}
		if (!empty($product_sn)){
			$where .= ' AND op.product_sn like \'%'.encode($product_sn).'%\'';
		}
		if (isint($order_sn)){
			$len = strlen($order_sn);
			if ($len == 14){
				$where .= ' AND op.order_sn = '.$order_sn.'';
			} else {
				$where .= ' AND kr.barcode = '.$order_sn.'';
			}
		}
		if (isDate($start_time)){
			$where .= ' AND kr.order_time >= '.strtotime($start_time).'';
		}
		if (isDate($end_time)){
			$where .= ' AND kr.order_time <= '.strtotime($end_time).'';
		}
		
		return $where; 	
	}
	
	
	
	//保存的文件名称
	function fileSign($params) {
		ksort($params);
		
		$stringToBeSigned = '';
		foreach ($params as $k => $v) {
			if("@" != substr($v, 0, 1)){
				$stringToBeSigned .= "$k$v";
			}
		}
		unset($k, $v);

		return strtoupper(md5($stringToBeSigned));
	}
	
	
	//发货的
	function send_items($brand_id, $start_time, $end_time, $page, $pagesize){
		
		$where = 'WHERE kr.brand_id = '.$brand_id.' AND kr.`status` = 1 AND kr.send_time >= '.$start_time.' AND kr.send_time <= '.$end_time.'';
		
		$sql  = ' SELECT kr.`status`, kr.barcode, kr.order_time, kr.send_time, kr.refund_time, ';
		$sql .= ' op.prd_id, op.order_sn, op.product_name_kr, op.product_sn, op.price_kr, ';
		$sql .= ' op.pic_small, op.prop_value_kr ';
		$sql .= ' FROM `order_product_kr_shipping` kr ';
		$sql .= ' LEFT JOIN `order_product` op ON kr.op_id = op.op_id ';
		$sql .= ' '.$where.' ';
		$sql .= ' ORDER BY kr.op_id DESC, kr.id DESC ';
		$sql .= ' LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$order_items = $this -> db -> rows($sql);
		if ($order_items){
			foreach ($order_items as $key => $item){
				$order_items[$key]['prop_value_kr'] = unserialize($item['prop_value_kr']);
				$price_kr = $item['price_kr'];
				
				//已经发货的以发货的折扣为准
				if ($row['discount'] > 0){
					$discount = $row['discount'];
				} else {
					$discount = $this -> PRD_discount($brand_id, $item['prd_id']);
				}
				$order_items[$key]['price_kr_discount'] = round($price_kr * $discount);
			}
		}
		
		$sql = 'SELECT COUNT(*) as num FROM (SELECT 1 FROM FROM `order_product_kr_shipping` kr LEFT JOIN `order_product` op ON kr.op_id = op.op_id '.$where.') t';
		$row = $this -> db -> row($sql);
		$total = $row['num'];	
		
		return array(
			'items' => $order_items,
			'total' => $total
		);
	}
	
	//退货的
	function refund_items($brand_id, $start_time, $end_time, $page, $pagesize){
		$where = 'WHERE kr.brand_id = '.$brand_id.' AND kr.refund_time >= '.$start_time.' AND kr.refund_time <= '.$end_time.'';
		
		$sql  = ' SELECT kr.`status`, kr.barcode, kr.order_time, kr.send_time, kr.refund_time,  ';
		$sql .= ' op.prd_id, op.order_sn, op.product_name_kr, op.product_sn, op.price_kr, ';
		$sql .= ' op.pic_small, op.prop_value_kr ';
		$sql .= ' FROM `order_product_kr_shipping` kr ';
		$sql .= ' LEFT JOIN `order_product` op ON kr.op_id = op.op_id ';
		$sql .= ' '.$where.' ';
		$sql .= ' ORDER BY kr.op_id DESC, kr.id DESC ';
		$sql .= ' LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$order_items = $this -> db -> rows($sql);
		if ($order_items){
			foreach ($order_items as $key => $item){
				$order_items[$key]['prop_value_kr'] = unserialize($item['prop_value_kr']);
				$price_kr = $item['price_kr'];
				
				//已经发货的以发货的折扣为准
				if ($row['discount'] > 0){
					$discount = $row['discount'];
				} else {
					$discount = $this -> PRD_discount($brand_id, $item['prd_id']);
				}
				$order_items[$key]['price_kr_discount'] = round($price_kr * $discount);
			}
		}
		
		$sql = 'SELECT COUNT(*) as num FROM (SELECT 1 FROM FROM `order_product_kr_shipping` kr LEFT JOIN `order_product` op ON kr.op_id = op.op_id '.$where.') t';
		$row = $this -> db -> row($sql);
		$total = $row['num'];	
		
		return array(
			'items' => $order_items,
			'total' => $total
		);	
	} 
}