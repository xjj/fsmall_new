<?php
namespace model;

/**
 +-----------------------------
 *	用户资金记录
 +-----------------------------
 */
class User_Money_Log extends Front {
	
	//添加金额记录
	function add($data){
		$uid = intval($data['uid']);
		$adm_uid = intval($data['adm_uid']);
		$content = trim($data['content']);
		$order_sn = $data['order_sn'];
		$barcode = intval($data['barcode']);
		$money = abs($data['money']);
		
		if ($data['act_type'] == 'add' || $data['act_type'] == '+'){
			$act_type = 0;
		} else {
			$act_type = 1;	
		}
		
		if (!isint($order_sn)){$order_sn = 0;}
		if (!isint($uid) || $uid <= 0){return false;}
		if (!isint($adm_uid) || $adm_uid <= 0){$adm_uid = 0;}
		
		return $this -> db -> insert('user_money_log', array(
			'uid' => $uid,
			'adm_uid' => $adm_uid,
			'act_type' => $act_type,
			'money' => $money,
			'content' => $content,
			'order_sn' => $order_sn,
			'barcode' => $barcode,
			'add_time' => time(),
		));
	}
	
	//查询资金日志
	function search($params, $page, $pagesize){
		if (!empty($params['uname'])){
			//查询出用户ID
			$uname = trim($params['uname']);
			$user = new user();
			$uid = $user -> get_uid_by_uname($uname);
		}
		
		if (isint($params['uid']) && $params['uid'] > 0){
			$uid = $params['uid'];	
		}
		
		if (isint($uid) && $uid > 0){
			$where = ' WHERE log.uid = '.$uid.'';	
		}
		
		$sql = 'SELECT u.uname, log.* FROM `user_money_log` log LEFT JOIN `user_info` u ON log.uid = u.uid '.$where.' ORDER BY log.id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$log_items = $this -> db -> rows($sql);
		
		$sql = 'SELECT COUNT(*) AS num FROM `user_money_log` log LEFT JOIN `user_info` u ON log.uid = u.uid '.$where.'';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array(
			'items' => $log_items,
			'total' => $total
		);	
	}
}