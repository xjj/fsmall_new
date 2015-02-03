<?php
namespace model;

if (!defined('START')) exit('No direct script access allowed');

/**
 +-----------------------------
 *	支付信息
 +-----------------------------
 */
class Payment extends Front {
	
	//获取支付信息
	function info($pay_id){
		if (!isint($pay_id) || $pay_id <= 0){return false;}
		$sql = 'SELECT * FROM `system_payment` WHERE pay_id = '.$pay_id;
		$row = $this -> db -> row($sql);
		if ($row){
			$conf = decode($row['configure']);
			$row['conf'] = unserialize($conf);
			return $row;
		} else {
			return false;
		}
	}
	
	//查询所有支付方式信息
	function items($status = 1){
		if ($status == 1){
			$where = ' WHERE status = 1';
		} else {
			$where = '';
		}
		$sql = 'SELECT * FROM `system_payment` '.$where.' ORDER BY pay_id ASC';
		return $this -> db -> rows($sql);
	}
	
	//查询所有支付方式
	function enable_items(){
		$sql = 'SELECT * FROM `system_payment` WHERE status = 1 AND enable = 1 ORDER BY pay_id ASC';
		return $this -> db -> rows($sql);
	}
	
	//通过支付代码获取支付信息
	function get_info_by_code($pay_code){
		if (empty($pay_code)){return false;}
		
		$sql = 'SELECT * FROM `system_payment` WHERE pay_code = \''.$pay_code.'\'';
		$row = $this -> db -> row($sql);
		if ($row){
			$conf = decode($row['configure']);
			$row['conf'] = unserialize($conf);
			return $row;
		} else {
			return false;
		}
	}
	
	//添加支付信息
	function add($data){
		$pay_name = trim($data['pay_name']);
		$pay_code = trim($data['pay_code']);
		$content  = trim($data['content']);
		
		if (!is_array($data['conf']) || empty($data['conf'])){return false;}
		if (empty($pay_name)){return false;}
		
		$f = $this -> isExist_pay_code($pay_code);
		if ($f){return false;}
		
		return $this -> db -> insert('system_payment', array(
			'pay_name' => $pay_name,
			'pay_code' => $pay_code,
			'content' => $content,
			'configure' => serialize($data['conf']),
			'add_time' => time(),
			'update_time' => time(),
			'status' => 0
		));
	}
	
	//编辑支付信息
	function update($pay_id, $data){
		if (!isint($pay_id) || $pay_id <= 0){return false;}
		
		$pay_name = trim($data['pay_name']);
		$pay_code = trim($data['pay_code']);
		$content  = trim($data['content']);
		
		if (!is_array($data['conf']) || empty($data['conf'])){return false;}
		if (empty($pay_name) || empty($pay_code)){return false;}
		
		$f = $this -> isExist_pay_code($pay_code, $pay_id);
		if ($f){return false;}
		
		return $this -> db -> update('system_payment', array(
			'pay_name' => $pay_name,
			'pay_code' => $pay_code,
			'content' => $content,
			'configure' => serialize($data['conf']),
			'update_time' => time()
		), array(
			'pay_id' => $pay_id
		));
	}
	
	//删除支付信息
	function delete($pay_id){
		if (!isint($pay_id) || $pay_id <= 0){return false;}
		return $this -> db -> delete('system_payment', array('pay_id' => $pay_id));
	}
	
	//更新支付状态
	function validate($pay_id, $status){
		if (!isint($pay_id) || $pay_id <= 0){return false;}
		if ($status != 1){$status = 0;}
		return $this -> db -> update('system_payment', array('status' => $status), array('pay_id' => $pay_id));
	}
	
	//判断支付代码是否存在
	function isExist_pay_code($pay_code, $pay_id = 0){
		$sql = 'SELECT COUNT(*) AS num FROM `system_payment` WHERE pay_code = \''.encode($pay_code).'\'';
		if (isint($pay_id) && $pay_id > 0){
			$sql .= ' AND pay_id != '.$pay_id;
		}
		$row = $this -> db -> row($sql);
		if ($row['num'] > 0){
			return true;
		} else {
			return false;
		}
	}
}