<?php
namespace model;

/**
 +----------------------------------
 *	用户认证 授权表模型
 +----------------------------------
 */
class userauth extends Front {
		//查询
	function search($params=array(), $page=1, $pagesize=20){
		
		$where=" and access_token!=''  ";
		$open_id = trim($params['open_id']);
		
		if(!empty( $open_id  )){
			$where.=" and ua.open_id=  '".$open_id."'";
		}
		
		$sql = "SELECT * FROM `user_auth` ua left join user_info ui on ua.uid=ui.uid  WHERE 1=1  $where   LIMIT ".($page-1)*$pagesize.",".$pagesize;
		$res = $this -> db -> rows($sql);
		//echo $sql;// die;
		$sql = 'SELECT COUNT(*) AS num FROM `user_auth` ua left join user_info ui on ua.uid=ui.uid  WHERE 1=1    '.$where.'';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array(
			'items' => $res,
			'total' => $total
		);	
		
	}
	
	
	/**
	 +-----------------------------------
	 *	添加
	 +-----------------------------------
	 */
	function add($params){
		$open_id = trim($params['open_id']);
		$type = trim($params['type']);
		$access_token = trim($params['access_token']);
		
		$expires_in = intval($params['expires_in']);
		$uid = intval($params['uid']);
		$login_time= time();
				
		if (empty($params['open_id'])){return false;}
		if (empty($params['uid'])){return false;}
		if (empty($params['access_token'])){return false;}
		 	
		return $this -> db -> insert('user_auth', array(
			'uid' => $uid,
			'open_id' => $open_id,
			'type' => $type,
			'expires_in' => $expires_in,
			'access_token' => $access_token,
			'login_time' => $login_time,
		));
	}
	
	//删除
	function delete($id){
		if (!isint($id) || $id <= 0){return false;}
		return $this -> db -> delete('user_auth', array('uid' => $id));
	}
}