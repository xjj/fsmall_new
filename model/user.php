<?php
namespace model;

/**
 +-------------------------------
 *	用户帐号类
 +-------------------------------
 */
class User extends Front {

	//获取用户信息
	function info($uid, $fields = array()){
		if (!isint($uid) || $uid <= 0){return false;}
		$str = empty($fields) ? '*' : $this -> db -> implode_field_key($fields, ',');
		$sql = 'SELECT '.$str.' FROM `user_info` WHERE uid = '.$uid.'';
		$row = $this -> db -> row($sql);
		if ($row){
			if (isset($row['head'])){
				$row['head_large'] = $this -> headUrl($row['head'], 200);
				$row['head_thumb'] = $this -> headUrl($row['head'], 50);
			}
		}
		return $row;
	}
	
	//获取头像
	function headUrl($path, $width){
		if (empty($path)){
			return '/images/head.png';
		}
		$pic = new picture();
		return $pic -> thumb_url($path, $width, $width);
	}
	
	//获取用户信息通过邮箱
	function get_info_by_email($email, $fields = array()){
		$str = empty($fields) ? '*' : $this -> db -> implode_field_keys($fields, ',');
		$sql = 'SELECT '.$str.' FROM `user_info` WHERE email = \''.encode($email).'\'';
		return $this -> db -> row($sql);
	}
	
	//添加帐号信息
	function add($data){
		return $this -> db -> insert('user_info', array(
			'email'	=> $data['email'],
			'uname'	=> $data['uname'],
			'upass'	=> md5($data['upass']),
			'grade_id' => 1,
			'email_status' => 0,
			'mobile_status' => 0,
			'reg_time' => time(),
			'reg_ip' => getip(),
			'status' => 0
		));
	}
	
	//更新帐号信息
	function update($uid, $data){
		if (!isint($uid) || $uid <= 0){return false;}
		$data['update_time'] = time();
		return $this -> db -> update('user_info', $data, array('uid' => $uid));
	}
	
	//更新统计数据
	function update_counter($uid, $data){
		$f = $this -> db -> update_counter('user_info', $data, array('uid' => $uid));
		if ($f > 0){
			return true;
		} else {
			return false;
		}
	}
	
	//根据邮箱获取用户ID
	function get_uid_by_email($email){
		$sql = 'SELECT uid FROM `user_info` WHERE email = \''.encode($email).'\'';
		$row = $this -> db -> row($sql);
		if ($row){
			return $row['uid'];
		} else {
			return false;
		}
	}
	
	//根据用户名获取用户ID
	function get_uid_by_uname($uname){
		$sql = 'SELECT uid FROM `user_info` WHERE uname = \''.encode($uname).'\'';
		$row = $this -> db -> row($sql);
		if ($row){
			return $row['uid'];
		} else {
			return false;
		}
	}
	
	//获取用户名
	function get_uname_by_uid($uid){
		$info = $this -> info($uid, array('uname'));
		if ($info){
			return $info['uname'];	
		} else {
			return false;	
		}
	}
	
}