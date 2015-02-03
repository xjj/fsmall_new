<?PHP
namespace model;

/**
 +------------------------------
 *	断货取消类
 +------------------------------
 */

class KrCancel extends Front {
	
	//返还资金
	function return_money($barcode){
		
		if (!isint($barcode) || $barcode <= 0){return false;}
		
		//计算应返还额度
		$kr = new order_product_kr_shipping();
		$info = $kr -> info($barcode);
		if ($info){} else {
			return false;	
		}
		
		if ($info['status'] != 2){
			return false;	
		}
		
		$product_price = $info['price'];
		
		
		//计算返还的金额 -- 查询订单金额，商品价格
		$order = new order();
		$orderInfo = $order -> info($info['order_id']);
		if ($orderInfo){
			$product_amount = $orderInfo['product_amount'];
			$order_amount = $orderInfo['order_amount'];	
			
			$uid = $orderInfo['uid'];
			$order_id = $orderInfo['order_id'];
		} else {
			return false;	
		}
		
		if ($product_price == $product_amount){
			$refund_amount = $order_amount;	
		} else {
			$refund_amount = $product_price;	
		}	
		
		//返还金额到用户余额
		$log_data = array(
			'uid' => $uid,
			'adm_uid' => $adm_uid,
			'order_sn' => $orderPrdInfo['order_sn'],
			'barcode' => $barcode,
			'content' => '商品断货，韩方取消订单，金额返还。'.$orderPrdInfo['product_sn'].'#'.$orderPrdInfo['op_id'].'，返还金额￥'.$refund_amount
		);
		//返还商品金额到用户余额
		$balance = new balance();
		$f = $balance -> add($uid, $refund_amount, $log_data);
		if ($f){
			//更新订单返还金额
			$order -> refund($order_id, $refund_amount);
			
			//更新退还金额状态
			$this -> db -> update('order_product_kr_shipping', array(
				'is_refund' => 1,
				'refund_time' => time()
			), array(
				'barcode' => $barcode,
			));
			
			return true;
		} else {
			return false;
		}
	}
	
	//查询
	function search($params, $page, $pagesize){
		$id = trim($params['id']);
		$order_sn = trim($params['order_sn']);
		$brand_id = trim($params['brand_id']);
		$where = ' and opks.status = 2 and opks.is_refund = 0 ';
		if (isint($id) && $id > 0){
			$where .= ' and opks.id = '.$id;	
		}
		if (isint($order_sn) && $order_sn > 0){
			$where .= ' and op.order_sn = '.$order_sn ;	
		}
		if (isint($brand_id) && $brand_id > 0){
			$where .= ' and opks.brand_id = '.$brand_id ;	
		}
		$sql = 'SELECT opks.*, pb.brand_name, op.product_name, op.product_sn, op.order_sn FROM `order_product_kr_shipping` opks left join `order_product` op on op.op_id=opks.op_id left join `product_brand` pb on opks.brand_id=pb.brand_id  WHERE 1 '.$where.'  LIMIT '.($page-1)*$pagesize.','.$pagesize;

		$items = $this -> db -> rows($sql);	
		$sql = 'SELECT COUNT(*) AS num FROM `order_product_kr_shipping` opks left join `order_product` op on op.op_id=opks.op_id left join `product_brand` pb on opks.brand_id=pb.brand_id  WHERE 1 '.$where.'';

		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array('items' => $items, 'total' => $total);
	}	
	
}