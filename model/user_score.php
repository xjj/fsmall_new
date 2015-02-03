<?php
namespace model;

/**
 +-------------------------------------
 *	用户积分类
 +-------------------------------------
 */
class User_Score extends Front {
		
	//添加积分
	function add($data){
		$uid = $data['uid'];
		$adm_uid = $data['adm_uid'];
		$score = $data['score'];
		$reason = $data['reason'];
		
		if (!isint($uid) || $uid <= 0 || !isint($score) || $score <= 0 || empty($reason)){return false;}
		$user = new user();
		$f = $user -> update_counter($uid, array('score' => $score));	
		if ($f){
			$log = new user_score_log();
			$log -> add(array(
				'uid' => $uid,
				'adm_uid' => $adm_uid,
				'score' => $score,
				'reason' => $reason,
				'act_type' => '+'
			));	
		}
		return $f;
	}
	
	//扣除积分
	function minus($data){
		$uid = $data['uid'];
		$adm_uid = $data['adm_uid'];
		$score = $data['score'];
		$reason = $data['reason'];	
		
		if (!isint($uid) || $uid <= 0 || !isint($score) || $score <= 0 || empty($reason)){return false;}
		$user = new user();
		$f = $user -> update_counter($uid, array('score' => -$score));	
		if ($f){
			$log = new user_score_log();
			$log -> add(array(
				'uid' => $uid,
				'adm_uid' => $adm_uid,
				'score' => $score,
				'reason' => $reason,
				'act_type' => '-'
			));	
		}
		return $f;
	}
	
	//查询某用户积分
	function get($uid){
		$user = new user();
		$info = $user -> info($uid, array('score'));	
		if ($info){
			return $info['score'];	
		} else {
			return false;	
		}
	}
}