<?php
namespace model;

/**
 +--------------------------------
 *	积分日志
 +--------------------------------
 */
class User_Score_Log extends Front {
	
	//加积分操作记录
	function add($data){
		if ($data['act_type'] == 'add' || $data['act_type'] == '+'){
			$act_type = 0;	
		} else {
			$act_type = 1;	
		}
		return $this -> db -> insert('user_score_log', array(
			'uid'	=> $data['uid'],
			'act_type'	=> $act_type,
			'score'	=> $data['score'],
			'adm_uid' =>  $data['adm_uid'],
			'reason' =>  $data['reason'],
			'add_time' =>  time(),
		));
	}
	
	//查询日志记录
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
		
		$sql = 'SELECT u.uname, log.* FROM `user_score_log` log LEFT JOIN `user_info` u ON log.uid = u.uid '.$where.' ORDER BY log.id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$log_items = $this -> db -> rows($sql);
		
		$sql = 'SELECT COUNT(*) AS num FROM `user_score_log` log LEFT JOIN `user_info` u ON log.uid = u.uid '.$where.'';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array(
			'items' => $log_items,
			'total' => $total
		);
	}
}