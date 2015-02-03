<?php
namespace model;

if (!defined('START')) exit('No direct script access allowed');

/**
 +-------------------------
 *	后台登录类
 +-------------------------
 */
class Suplier_User extends Front {
	
	//验证登录
	function verify($uname, $upass){
		$sql = 'SELECT * FROM `suplier_user` WHERE sp_uname = \''.encode($uname).'\' AND sp_upass = \''.encode(md5($upass)).'\' AND status = 1 ';
		$row = $this -> db -> row($sql);
		if ($row){
			return $row;
		} else {
			return false;
		}
	}
	
	//记录登录
	function setRecords($sp_uid){
		$info = $this -> getRecords($sp_uid, 1);
		if ($info){
			$time = $info[0]['add_time'];
			if (time() - $time <= 1800){return false;}
		}
		$this -> db -> insert('suplier_user_login', array('sp_uid' => $sp_uid, 'ip' => getip(),'add_time' => time()));
		$this -> db -> update('suplier_user', array('login_time' => time()), array('uid' => $uid));
	}
	
	//获取登录记录
	function getRecords($sp_uid, $count = 1){
		$sql = 'SELECT * FROM `suplier_user_login` WHERE sp_uid = \''.$sp_uid.'\' ORDER BY id DESC LIMIT 0,'.$count;
		return $this -> db -> rows($sql);
	}
	
	//更新
	function update($sp_uid, $data){
		return $this -> db -> update('suplier_user', $data, array('sp_uid' => $sp_uid));	
	}
	
	//判断用户名是否存在
	function isExist_spuname($sp_uname, $sp_uid, $brand_id = 0){
		$sql = 'SELECT COUNT(*) AS num FROM `suplier_user` WHERE sp_uname = \''.encode($sp_uname).'\' AND sp_uid != '.$sp_uid.' ';
		if ($brand_id > 0){
			$sql .= 'AND brand_id = '.$brand_id.'';	
		}
		$row = $this -> db -> row($sql);
		if ($row['num'] > 0){
			return true; 	
		} else {
			return false;	
		}
	}
	
	//有效设置
	function validate($sp_uid, $status){
		if (!isint($sp_uid) || $sp_uid <= 0){return false;}	
		if ($status != 1){$status = 0;}
		
		return $this -> db -> update('suplier_user', array('status' => $status,'update_time'=>time()), array('sp_uid' => $sp_uid));  	
	}

	//查询
	function search($params, $page, $pagesize){
		$sql = 'SELECT a.*, b.brand_name FROM `suplier_user` a LEFT JOIN `product_brand` b ON a.brand_id = b.brand_id ORDER BY a.status DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$items = $this -> db -> rows($sql);	
		
		$sql = 'SELECT COUNT(*) as num FROM `suplier_user`';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array('items' => $items, 'total' => $total);
	}
	
	//获取
	function info($sp_uid){
		if (!isint($sp_uid) || $sp_uid <= 0){return false;}
		$sql = 'SELECT * FROM `suplier_user` WHERE sp_uid = '.$sp_uid.'';
		return $this -> db -> row($sql);
	}
	
	//添加
	function add($data){
		if (empty($data)){return false;}
		return $this -> db -> insert('suplier_user', $data);
	}
	
	//删除
	function delete($sp_uid){
		if (!isint($sp_uid) || $sp_uid <= 0){return false;}	
		return $this -> db -> delete('suplier_user', array('sp_uid' => $sp_uid)); 
	}
}