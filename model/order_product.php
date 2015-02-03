<?php
namespace model;

/**
 +---------------------------
 *	订单商品表
 +---------------------------
 */
class Order_Product extends front {
	
	private $product_status = array(
		-1 	=> '已取消',
		0  	=> '待订货',
		1	=> '已订货',
		2	=> '已断货',			//韩方取消 -- 以断货处理
		3	=> '客户已取消',
		4	=> '韩方已发货',
		5 	=> '国内已到货',
		6	=> '国内已发货',
		7   => '已签收',
	);
	
	//获取订单商品信息
	function info($op_id){
		if (!isint($op_id) || $op_id <= 0){return false;}
		$sql = 'SELECT * FROM `order_product` WHERE op_id = '.$op_id.'';
		return $this -> db -> row($sql);
	}
	
	//查询商品信息
	function prdInfo($op_id){
		if (!isint($op_id) || $op_id <= 0){return false;}
		$sql = 'SELECT p.* FROM `order_product` op LEFT JOIN `product_info` p ON op.prd_id = p.prd_id WHERE op.op_id = '.$op_id.'';
		return $this -> db -> row($sql);	
	}
	
	//查询订单中所有商品信息
	function items($order_id){
		$sql = 'SELECT * FROM `order_product` WHERE order_id = '.$order_id.' ORDER BY op_id ASC';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			foreach ($rows as $k => $row){
				$rows[$k]['price'] = format_money($row['price']);
				$rows[$k]['prop_value'] = unserialize($row['prop_value']);
				$rows[$k]['prop_value_kr'] = unserialize($row['prop_value_kr']);
				$rows[$k]['product_status_text'] = $this -> fetch_product_status($row['product_status']);
			}
		}
		return $rows;
	}
	
	//查询某一状态的商品
	function status_items($order_id, $status = 0){
		$sql = 'SELECT * FROM `order_product` WHERE order_id = '.$order_id.' AND product_status = '.$status.'';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			foreach ($rows as $k => $row){
				$rows[$k]['prop_value'] = unserialize($row['prop_value']);
				$rows[$k]['prop_value_kr'] = unserialize($row['prop_value_kr']);
				$rows[$k]['product_status_text'] = $this -> fetch_product_status($row['product_status']);
			}
		}
		return $rows;
	}
	
	//查询未发货的商品 -- 待订货和已订货 状态的商品
	function nosend_items($order_id){
		$sql  = 'SELECT * FROM `order_product` WHERE order_id = '.$order_id.' AND product_status = 0';
		$sql .= ' UNION ';
		$sql .= 'SELECT * FROM `order_product` WHERE order_id = '.$order_id.' AND product_status = 1';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			foreach ($rows as $k => $row){
				$rows[$k]['prop_value'] = unserialize($row['prop_value']);
				$rows[$k]['prop_value_kr'] = unserialize($row['prop_value_kr']);
				$rows[$k]['product_status_text'] = $this -> fetch_product_status($row['product_status']);
			}
		}
		return $rows;
	}
	
	//查询非断货和取消的SKU -- 有效的
	function validate_items($order_id){
		$sql  = 'SELECT * FROM `order_product` WHERE order_id = '.$order_id.' AND product_status = 0';
		$sql .= ' UNION ';
		$sql .= 'SELECT * FROM `order_product` WHERE order_id = '.$order_id.' AND product_status = 1';
		$sql .= ' UNION ';
		$sql .= 'SELECT * FROM `order_product` WHERE order_id = '.$order_id.' AND product_status = 4';
		$sql .= ' UNION ';
		$sql .= 'SELECT * FROM `order_product` WHERE order_id = '.$order_id.' AND product_status = 5';
		$sql .= ' UNION ';
		$sql .= 'SELECT * FROM `order_product` WHERE order_id = '.$order_id.' AND product_status = 6';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			foreach ($rows as $k => $row){
				$rows[$k]['prop_value'] = unserialize($row['prop_value']);
				$rows[$k]['prop_value_kr'] = unserialize($row['prop_value_kr']);
				$rows[$k]['product_status_text'] = $this -> fetch_product_status($row['product_status']);
			}
		}
		return $rows;
	}
	
	//查询订货后的有效SKU
	function ordered_validate_items($order_id){
		$sql  = 'SELECT * FROM `order_product` WHERE order_id = '.$order_id.' AND product_status IN (1,4,5,6)';
		return $this -> db -> rows($sql);
	}
	
	//更新订单商品信息
	function update($op_id, $data){
		return $this -> db -> update('order_product', $data, array('op_id' => $op_id));
	}
	
	//获取订单商品的状态
	function fetch_product_status($product_status){
		return $this -> product_status[$product_status];
	}
	
	//获取所有商品状态
	function fetch_all_status(){
		return $this -> product_status;
	}
	
	//写入一条商品信息
	function add($data){
		$dt = array(
			'uid' => $data['uid'],
			'order_id' => $data['order_id'],
			'order_sn' => $data['order_sn'],
			'prd_id' => $data['prd_id'],
			'sku_id' => $data['sku_id'],
			'number' => $data['number'],
			'pic_thumb' => $data['pic_thumb'],
			'pic_small' => $data['pic_small'],
			'product_name' => $data['product_name'],
			'product_name_kr' => $data['product_name_kr'],
			'product_sn' => $data['product_sn'],
			'product_status' => 0,
			'prop_value' => $data['prop_value'], 		//已经转译的字符串
			'prop_value_kr' => $data['prop_value_kr'],	//已经转译的字符串
			'price' => $data['price'],
			'price_kr' => $data['price_kr'],
			'is_spot' => $data['is_spot'],
			'add_time' => time(),
		);
		return $this -> db -> insert('order_product', $dt);
	}
	
	//批量写入订单商品表
	function add_multi($data){
		if (empty($data) || !is_array($data)){return false;}
		$dt = array();
		foreach ($data as $item){
			$dt[] = array(
				'uid' => $item['uid'],
				'order_id' => $item['order_id'],
				'order_sn' => $item['order_sn'],
				'prd_id' => $item['prd_id'],
				'sku_id' => $item['sku_id'],
				'number' => $item['number'],
				'pic_thumb' => $item['pic_thumb'],
				'pic_small' => $item['pic_small'],
				'product_name' => $item['product_name'],
				'product_name_kr' => $item['product_name_kr'],
				'product_status' => 0,
				'product_sn' => $item['product_sn'],
				'prop_value' => $item['prop_value'],			//已经转译的字符串
				'prop_value_kr' => $item['prop_value_kr'],		//已经转译的字符串
				'price' => $item['price'],
				'price_kr' => $item['price_kr'],
				'is_spot' => $item['is_spot'],
				'add_time' => time(),
			);
		}
		return $this -> db -> insert_multi('order_product', $dt);
	}
	
	//取消未付款订单中的商品 -- [admin]
	function cancle_nopay($order_id, $op_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		if (!isint($op_id) || $op_id <= 0){return false;}
		
		return $this -> db -> update('order_product', array(
			'product_status' => -1,
			'cancle_time' => time(),
		), array(
			'order_id' => $order_id, 
			'op_id' => $op_id,
			'product_status' => 0,
		));
	}
	
	//取消未付款订单商品的操作 -- 程序运行有错时取消上面函数的操作
	function cancle_nopay_back($order_id, $op_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		if (!isint($op_id) || $op_id <= 0){return false;}
		
		return $this -> db -> update('order_product', array(
			'product_status' => 0,
			'cancle_time' => 0,
		), array(
			'order_id' => $order_id, 
			'op_id' => $op_id,
			'product_status' => -1,
		));
	}
	
	//取消已支付订单中的商品
	function cancle_haspay($order_id, $op_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		if (!isint($op_id) || $op_id <= 0){return false;}
		
		$where = 'order_id = '.$order_id.' AND op_id = '.$op_id.' AND (product_status = 0 OR product_status = 1)';
	
		$f = $this -> db -> update('order_product', array('product_status' => -1,'cancle_time' => time()), $where);
		if ($f){
			
		}
		return $f;
	}
	
	//断货未付款订单中的商品 -- [admin]
	function soldout_nopay($order_id, $op_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		if (!isint($op_id) || $op_id <= 0){return false;}
		
		return $this -> db -> update('order_product', array(
			'product_status' => 2,
			'soldout_time' => time(),
		), array(
			'order_id' => $order_id, 
			'op_id' => $op_id,
			'product_status' => 0,
		));
	}
	
	//未付款订单商品的断货操作取消 -- 程序运行有错时取消上面函数的操作
	function soldout_nopay_back($order_id, $op_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		if (!isint($op_id) || $op_id <= 0){return false;}
		
		return $this -> db -> update('order_product', array(
			'product_status' => 0,
			'soldout_time' => 0,
		), array(
			'order_id' => $order_id, 
			'op_id' => $op_id,
			'product_status' => 2,
		));
	}
	
	//断货已支付订单中的商品
	function soldout_haspay($order_id, $op_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		if (!isint($op_id) || $op_id <= 0){return false;}
		
		$where = 'order_id = '.$order_id.' AND op_id = '.$op_id.' AND (product_status = 0 OR product_status = 1)';
		$f = $this -> db -> update('order_product', array('product_status' => 2,'soldout_time' => time()), $where);
		if ($f){
			
		}
		return $f;
	}
	
	//订单商品批量生成条码 -- 对订货的商品处理
	function orderToBC($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		
		//检查是否有订单商品 -- 并获取商品信息 -- 以便进行订货
		$order_PRD_items = $this -> status_items($order_id, 0);
		if ($order_PRD_items){
			$PRD_data = array();
			foreach ($order_PRD_items as $item){
				$PRD_data[] = array(
					'order_id' => $order_id,
					'op_id' => $item['op_id'],
					'number' => $item['number'],
				);
			}
		} else {
			return false;
		}
		
		//生成条码,写入条码表
		$f2 = false;
		$barcode = new order_product_barcode();
		foreach ($PRD_data as $item){
			$n2 = $barcode -> add($item);
			if ($n2 === false){
				$f2 = true;
				break;
			}
		}
		//写入条码表有失误，清除写入的数据
		if ($f2){
			$barcode -> clear($order_id);
			return false;
		}
		return true;
	}
	
	//订单商品订货操作 -- 非现货订货
	function orderToKR($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		
		//更新订单商品状态
		$f3 = $this -> db -> update('order_product', array(
			'product_status' => 1, 
			'order_time' => time()
		), array(
			'order_id' => $order_id,
			'product_status' => 0,
		));
		if ($f3 > 0){
			return true;
		} else {
			return false;
		}
	}
	
	//现货直接写入到到货表 -- 订货操作时执行
	function spotToRecv($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		
		$sql = 'SELECT bc.order_id, bc.op_id, bc.barcode FROM `order_product_barcode` bc LEFT JOIN `order_product` op ON bc.op_id = op.op_id WHERE bc.order_id = '.$order_id.' AND op.is_spot = 1 AND op.product_status = 1';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$cn_shipping = new order_product_cn_shipping();
			$cn_shipping -> add_multi($rows);
		}
		return true; 
	}
	
	//非现货直接写入韩国发货表 -- 订货操作时执行
	function orderToKRSend($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		
		$sql = 'SELECT bc.order_id, bc.op_id, bc.barcode, p.brand_id FROM `order_product_barcode` bc LEFT JOIN `order_product` op ON bc.op_id = op.op_id LEFT JOIN `product_info` p ON op.prd_id = p.prd_id WHERE bc.order_id = '.$order_id.' AND op.is_spot = 0 AND op.product_status = 1';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$kr_shipping = new order_product_kr_shipping();
			$kr_shipping -> add_multi($rows);
		}
		return true; 	
	}
	
	//发货信息更新 -- 
	function CNSendUpdate($data){
		
		//更新条件 -- 当前商品已到货或已发货（多条商品时）
		$where = 'op_id = '.$data['op_id'].' AND order_id = '.$data['order_id'].' AND (product_status = 5 OR product_status = 6)';
		
		$f = $this -> db -> update('order_product', array(
			'product_status' => 6,
			'cn_send_time' => time(),
			'cn_shipping_code' => $data['shipping_code'],
			'cn_shipping_name' => $data['shipping_name'],
			'cn_shipping_number' => $data['shipping_number'],
		), $where);	
		if ($f){
			$this -> db -> update_counter('order_product', array('cn_send_number' => 1), array(
				'op_id' => $data['op_id'],
				'order_id' => $data['order_id'],
			));
		}
		return $f;
	}
	
	//国内到货更新 -- 
	function CNRecvUpdate($data){
		
		//更新条件
		$where = 'op_id = '.$data['op_id'].' AND order_id = '.$data['order_id'].' AND (product_status = 4 OR product_status = 5)';	
		
		$f = $this -> db -> update('order_product', array(
			'product_status' => 5,
			'cn_recv_time' => time(),
		), $where);	
		if ($f){
			$this -> db -> update_counter('order_product', array('cn_recv_number' => 1), array(
				'op_id' => $data['op_id'],
				'order_id' => $data['order_id'],
			));
		}
		return $f;
	}
}
