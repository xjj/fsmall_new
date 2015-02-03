<?php
namespace model;

if (!defined('START')) exit('No direct script access allowed');

/**
 +-------------------------
 *	登录类
 +-------------------------
 */
class Login extends Front {
	
	//验证登录
	function verify($uname, $upass){
		$sql = 'SELECT * FROM `user_info` WHERE upass = \''.md5($upass).'\' AND is_delete = 0';
		if (strpos($uname, "@") > 0){
			$sql .= ' AND email = \''.encode($uname).'\'';
		} else {
			$sql .= ' AND uname = \''.encode($uname).'\'';
		}
		$row = $this -> db -> row($sql);
		if ($row){
			return $row;
		} else {
			return false;
		}
	}
	
	
	//记录登录
	function record_login($uid){
		$info = $this -> login_records($uid, 1);
		if ($info){
			$time = $info[0]['add_time'];
			if (time() - $time <= 1800){return false;}
		}
		$this -> db -> insert('user_login', array('uid' => $uid, 'ip' => getip(), 'add_time' => time()));
		
		//更新登录信息
		$user = new User();
		$user -> update($uid, array(
			'last_login_time' => time(),
			'last_login_ip' => getip(),
		));
	}
	
	//获取登录记录
	function login_records($uid, $count = 1){
		$sql = 'SELECT * FROM `user_login` WHERE uid = \''.$uid.'\' ORDER BY id DESC LIMIT 0,'.$count;
		return $this -> db -> rows($sql);
	}
}