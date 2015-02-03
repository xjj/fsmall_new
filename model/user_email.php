<?php
namespace model;

/**
 +--------------------------
 *	邮箱验证类
 +--------------------------
 */

class User_Email extends Front {
	
	//添加邮箱验证信息
	function add($data){
		return $this -> db -> insert('user_email_verify', array(
			'uid' => $data['uid'],
			'email' => $data['email'],
			'code' => $data['code'],
			'type' => strtoupper($data['type']),
			'add_time' => time(),
		));
	}
	
	//验证邮箱
	function verify($uid, $email, $code, $type){
		$time = time() - 3600;
		$sql = 'SELECT COUNT(*) AS num FROM `user_email_verify` WHERE uid = \''.encode($uid).'\' AND `type` = \''.$type.'\' AND email = \''.encode($email).'\' AND code = \''. encode($code) .'\' AND add_time > '.$time.'';
		$row = $this -> db -> row($sql);
		$num = $row['num'];
		if ($num > 0){
			return true;
		} else {
			return false;
		}
	}
	
	//清除过期验证信息
	function clear($uid, $email){
		$time = time() - 3600;
		$where = 'uid = \''.$uid.'\' AND email = \''.encode($email).'\' AND add_time < '.$time.'';
		return $this -> db -> delete('user_email_verify', $where);
	}
	
	//查询最近是否已发送
	function isExist($uid, $email, $type){
		$time = time() - 3600;
		$type = strtoupper($type);
		$sql = 'SELECT COUNT(*) AS num FROM `user_email_verify` WHERE uid = '.$uid.' AND `type` = \''.$type.'\' AND email = \''.encode($email).'\' AND add_time > '.$time.'';
		$row = $this -> db -> row($sql);
		if ($row['num'] > 0){
			return true;
		} else {
			return false;
		}
	}
}