<?php
namespace model;

/**
 +-------------------------------
 *	资金管理
 +-------------------------------
 */
class Money extends Front {


	//
	function add_score_log($data){
		return $this -> db -> insert('user_money_log', array(
			'uid'	=> $data['uid'],
			'acttype'	=> $data['edittype'],
			'money'	=> $data['score'],
			'adm_uid' =>  $data['adm_uid'],
			'content' =>  $data['reason'],
			'add_time' =>  time(),
			
		));
	}
	//
	function update($uid, $data){
		if (!isint($uid) || $uid <= 0){return false;}
		$data['update_time'] = time();
		return $this -> db -> update('user_info', $data, array('uid' => $uid));
	}
	

}