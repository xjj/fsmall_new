<?php
namespace model;

/**
 +------------------------------
 *	订单退货
 +------------------------------
 */
class Order_Refund extends front {
	
	//添加退货申请
	function add($data){
		$uid = $data['uid'];
		$order_id = $data['order_id'];
		$prd_id = $data['prd_id'];
		$sku_id = $data['sku_id'];
		
		//获取商品信息
		$product = new product();
		$product_data = $product -> info($prd_id); 
		
	}
	
	//获取一条退货信息
	function info($id){
		if (!isint($id) || $id <= 0){return false;}
		return $this -> db -> row('SELECT * FROM `order_product_refund` WHERE id = '.$id);	
	}
	
	//审核通过
	function allow($data){
		if (!isint($data['id']) || $data['id'] <= 0){return false;}
		$adm_uid = intval($data['adm_uid']);
		return $this -> db -> update('order_product_refund', array(
			'refund_status' => 2, 
			'reply' => $data['reply'],
			'allow_time' => time(),
			'adm_uid' => $adm_uid
		), array(
			'refund_status' => 1,
			'id' => $id
		)); 
	}
	
	//审核拒绝
	function deny($data){
		if (!isint($data['id']) || $data['id'] <= 0){return false;}
		$adm_uid = intval($data['adm_uid']);
		$f = $this -> db -> update('order_product_refund', array(
			'refund_status' => 3, 
			'reply' => $data['reply'],
			'deny_time' => time(),
			'adm_uid' => $adm_uid
		), array(
			'refund_status' => 1,
			'id' => $id
		)); 
		
		if ($f){
			$info = $this -> info($id);
			//返还积分
			$refund_type = $info['refund_type'];
			$score = $info['score'];
			$uid = $info['uid'];
			
			//不管是什么类型的申请 -- 拒绝后都要返还积分
			if ($score > 0){
				$userScore = new user_score();
				$userScore -> add(array(
					'uid' => $info['uid'],
					'adm_uid' => $adm_uid,
					'score' => $info['score'],
					'reason' => '拒绝退货，返还积分。ORDER_SN：'.$info['order_sn'].'，OP_ID：'.$info['op_id'].'，BARCODE：'.$info['barcode'].''
				));	
			}
			return true;
		}
		return false;
	}
	
	//退货返还资金
	function return_money($id, $adm_uid){
		if (!isint($id) || $id <= 0){return false;}
		
		//查询返还的商品的价格或订单价格
		$info = $this -> info($id);
		if ($info){} else {
			return false; 	
		}
		
		//自由通过的才可返还
		if ($info['refund_status'] != 2){
			return false;	
		}
		
		//返还状态
		if ($info['return_status'] != 0){
			return false;	
		}
		
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
		
		$order_product = new order_product();
		$orderPrdInfo = $order_product -> info($info['op_id']);
		if ($orderPrdInfo){
			$product_price = $orderPrdInfo['price'];	
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
			'barcode' => $info['barcode'],
			'content' => '订单退货，金额返还。'.$orderPrdInfo['product_sn'].'#'.$orderPrdInfo['op_id'].'，返还金额￥'.$refund_amount
		);
		//返还商品金额到用户余额
		$balance = new balance();
		$f = $balance -> add($uid, $refund_amount, $log_data);
		if ($f){
			//更新订单返还金额
			$order -> refund($order_id, $refund_amount);
			
			//更新退还金额状态
			$this -> db -> update('order_product_refund', array(
				'return_status' => 1,
			), array(
				'id' => $id,
				'return_status' => 0
			));
			
			return true;
		} else {
			return false;
		}
	}
	
	//不返还金额
	function return_no_money($id){
		if (!isint($id) || $id <= 0){return false;}
		
		return $this -> db -> update('order_product_refund', array(
			'return_status' => 2
		), array(
			'id' => $id,
			'refund_status' => 2,
			'return_status' => 0
		));
	}
	
	//查询记录
	function search($params, $page, $pagesize){
		$order_sn = trim($params['order_sn']);
		$start_time = trim($params['start_time']);
		$end_time = trim($params['end_time']);
		$refund_status = intval($params['refund_status']);
		$uname = trim($params['uname']);
		
		//组合查询语句
		$where = '';
		if (isint($refund_status) && $refund_status > 0 ){
			$where .= ' AND opr.refund_status = '.$refund_status.' ';
		}
		if (isint($order_sn) && $order_sn > 0){
			$where .= ' AND opr.order_sn = '.$order_sn.' ';
		}
		if (isDate($start_time)){
			$where .= ' AND opr.refund_time >= '.strtotime($start_time).' ';
		}
		if (isDate($end_time)){
			$where .= ' AND opr.refund_time <= '.strtotime($end_time).' ';
		}
		if (!empty($uname)){
			$where .= ' AND ui.uname like \'%'.encode($uname).'%\' '; 
		}
		
		//查询
		$sql = 'SELECT opr.*, ui.uname, op.product_sn, op.product_name, op.prop_value, op.price FROM `order_product_refund` opr left join `user_info` ui on opr.uid=ui.uid left join `order_product` op on op.op_id=opr.op_id WHERE 1 '.$where.' ORDER BY opr.id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$refund_items = $this -> db -> rows($sql);
		if ($refund_items){
			foreach($refund_items as $k=>$v){
				$refund_items[$k]['prop_value'] = unserialize($v['prop_value']);
			}	
		}
		
		$sql = 'SELECT COUNT(*) AS num FROM `order_product_refund` op left join `user_info` ui on op.uid=ui.uid WHERE 1 '.$where.'';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array(
			'items' => $refund_items,
			'total' => $total
		);	
	}
	
	//韩同意退货
	function agree($id, $barcode){
		if (!isint($id) || $id <= 0){return false;}
		
		$f = $this -> db -> update('order_product_refund', array(
			'kr_agree' => 1,
			'kr_agree_time' => time(),
		), array(
			'kr_agree' => 0,
			'id' => $id,
			'refund_status' => 2
		));
		if ($f){
			//更新到韩发货表里
			$kr_send = new order_send_kr();
			$kr_send -> updateRefund($barcode);	
		}
		return $f;
	}
	
	//韩方不同意
	function disagree($id){
		if (!isint($id) || $id <= 0){return false;}
		
		return $this -> db -> update('order_product_refund', array(
			'kr_agree' => 2,
		), array(
			'kr_agree' => 0,
			'id' => $id,
			'refund_status' => 2
		));	
	}
}