<?php
namespace model;

if (!defined('START')) exit('No direct script access allowed');

/**
 +---------------------------
 *	网站管理员类
 +---------------------------
 */
class System_User extends Front {
	
	//获取管理员信息
	function info($uid){
		if (!isint($uid) || $uid <= 0){return false;}
		$row = $this -> db -> row('SELECT * FROM `system_user_info` WHERE uid = '.$uid.'');
		if ($row){
			$row['ip'] = IP2str($row['ip']);
		}
		return $row; 
	}
	
	//添加
	function add($data){
		return $this -> db -> insert('system_user_info', array(
			'uname' => trim($data['uname']),
			'upass' => md5($data['upass']),
			'email' => trim($data['email']),
			'add_time' => time(),
			'ip' => getip(),
			'status' => 1,
		));
	}
	
	//判断用户名或邮箱是否存在
	function isExist($data){
		if (empty($data['uname']) && empty($data['email'])){exit('PARAMS ERROR');}
		
		$where = '(';
		if (!empty($data['uname'])){
			$where .= ' `uname` = \''.encode($data['uname']).'\'';
		}
		if (!empty($data['email'])){
			if ($where == '('){
				$where .= ' `email` = \''.encode($data['email']).'\'';
			} else {
				$where .= ' OR `email` = \''.encode($data['email']).'\'';
			}
		}
		$where .= ' )';
		
		if (isset($data['uid']) && isint($data['uid']) && $data['uid'] > 0){
			$where .= ' AND uid != '.$data['uid'];
		}
		$sql = 'SELECT COUNT(*) AS num FROM `system_user_info` WHERE '.$where.'';
		$row = $this -> db -> row($sql);
		if ($row['num'] > 0){
			return true;
		} else {
			return false;
		}
	}
	
	//更新
	function update($uid, $data){
		return $this -> db -> update('system_user_info', $data, array('uid' => $uid));
	}
	
	//删除
	function delete($uid){
		if (!isint($uid) || $uid <= 0){return false;}
		return $this -> db -> delete('system_user_info', array('uid' => $uid));
	}
	
	//验证登录
	function verify($uname, $upass){
		$sql = 'SELECT * FROM `system_user_info` WHERE upass = \''.md5($upass).'\' AND status = 1 ';
		if (strpos($uname, "@") > 0){
			$sql .= 'AND email = \''.encode($uname).'\'';
		} else {
			$sql .= 'AND uname = \''.encode($uname).'\'';
		}
		$row = $this -> db -> row($sql);
		if ($row){
			return $row;
		} else {
			return false;
		}
	}
	
	//记录登录
	function record($uid){
		$info = $this -> fetch_login_records($uid, 1);
		if ($info){
			$time = $info[0]['add_time'];
			if (time() - $time <= 1800){return false;}
		}
		$this -> db -> insert('system_user_login', array('uid' => $uid, 'ip' => getip(),'add_time' => time()));
		$this -> update($uid, array('login_time' => time()));
	}
	
	//获取登录记录
	function fetch_login_records($uid, $count = 1){
		$sql = 'SELECT * FROM `system_user_login` WHERE uid = \''.$uid.'\' ORDER BY id DESC LIMIT 0,'.$count;
		return $this -> db -> rows($sql);
	}
	
	
	//查询
	function search($params, $page, $pagesize){
		$sql = 'SELECT * FROM `system_user_info` ORDER BY uid ASC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$user_items = $this -> db -> rows($sql);
		$sql = 'SELECT COUNT(*) AS num FROM `system_user`';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		return array('items' => $user_items, 'total' => $total);
	}
	
	//有效无效设置
	function validate($uid, $status){
		if (!isint($uid) || $uid <= 0){return false;}
		if ($status != 1){$status = 0;}
		
		return $this -> update($uid, array('status' => $status));
	}
}