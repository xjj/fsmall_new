<?php
namespace model;

/**
 +----------------------------------
 *	订单消息
 +----------------------------------
 */

class Order_yundanno extends Front {
	//获取可用的单号数目
	function count_yundanno($ship_code='yto'){
		$ship_code=strtolower($ship_code);
		$sql="select count(*) as num from order_yundanno where status=0 and logiser='$ship_code'";
		$re= $this->db->row($sql);
		return intval($re['num']);
	}
	function get_yundanno($ship_code='yto'){
		$ship_code=strtolower($ship_code);
		$sql="select yundanno from order_yundanno where status=0 and logiser ='$ship_code' order by id asc limit 1";
		$res= $this->db->row($sql);
		if(!$res){
			return false;	
		}
		$mailNo=$res['yundanno'] ;
		return $mailNo;
	}
	//获取定的中已到货的货物信息
	function get_order_product($order_id=''){
		$order_id=intval($order_id);
		$sql="select * from order_product_cn_shipping os left join order_product op on os.op_id=op.op_id where os.send_time=0 and os.order_id =$order_id ";
		$res= $this->db->rows($sql);
		if(!$res){
			return false;	
		}
		return $res;
	}
	//添加
	function add($data){
		if (!is_array($data)){return false;}
		$f = $this -> db -> insert_multi('order_yundanno', $data);
		return $f; 
	}
	
	//更改已使用的单号状态为已使用
	function update_yundanno($mailNo){
		if (empty($mailNo) ){return false;}
		$data=array('status'=>1);
		$condition=array('yundanno'=>$mailNo);
		return $this -> db -> update('order_yundanno',$data,$condition);
	}
	
}