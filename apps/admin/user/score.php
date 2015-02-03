<?php
namespace admin\user;
use admin as adm;
use model as md;

/**
 +-----------------------------
 *	会员积分
 +-----------------------------
 */

class score extends adm\front {
	
	//积分操作
	function add(){
		if(isset($_POST['submit'])){
			$this -> add_submit();
			exit();
		}
		
		$uid = intval($this -> params[1]);
		
		//查询用户
		$user = new md\user();
		$uname = $user -> get_uname_by_uid($uid);
		if ($uname){} else {
			cpmsg(false, '该用户信息不存在或已被删除！', -1);	
		}
		
		$this -> smarty -> assign('uname', $uname);
		$this -> smarty -> assign('uid', $uid);
		$this -> smarty -> display('user/score_edit.tpl');
	}
	
	//积分操作更新
	private function add_submit(){
		$uid = intval($_POST['uid']);
		$act_type = trim($_POST['act_type']);
		$reason = trim($_POST['reason']);
		$score = round(abs(floatval(trim($_POST['score']))), 2);
		
		if ($act_type == 'add' || $act_type == '+'){
			$act_type = 'add';	 //加
		} else {
			$act_type = 'minus';	
		}
		
		if ($score == 0){
			cpmsg(false, '操作的积分不能为零！', -1);	
		}
		
		if (empty($reason)){
			cpmsg(false, '请填写积分操作的原因！', -1);	
		}
		
		//获取用户现有积分
		$userScore = new md\user_score();
		$score_current = $userScore -> get($uid);
		if (is_numeric($score_current)){} else {
			cpmsg(false, '没有查询到该用户，获取积分失败！', -1);	
		}
		
		if ($act_type == 'minus' && $score_current < $score){
			cpmsg(false, '抱歉，积分不足，扣除失败！', -1);	
		}
		
		$data = array(
			'uid' => $uid,
			'adm_uid' => $_SESSION['admin']['uid'],
			'score' => $score,
			'reason' => $reason
		);
		if ($act_type == 'add'){
			$f = $userScore -> add($data);
		} else {
			$f = $userScore -> minus($data);	
		}
		
		if ($f){
			cpmsg(true, '积分操作成功！', '/'.$this->mod.'/'.$this->col.'/items/'.$uid);	
		} else {
			cpmsg(false, '积分操作失败！', -1);	
		}
	}
	
	//积分操作日志
	function items(){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		$uid = $this -> params[1];
		if (isint($uid) && $uid > 0){
			$_GET['uid'] = $uid;	
		}
		
		$log = new md\user_score_log();
		$ret = $log -> search($_GET, $page, $pagesize);
		$log_items = $ret['items'];
		$total = $ret['total'];
		
		
		$url = '/'.$this->mod.'/'.$this->col.'/items';
		if (isint($uid) && $uid > 0){$url .= '/'.$uid;}
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => $url,
			'params' => $_GET
		));
		$this -> smarty -> assign('log_items', $log_items);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('user/score_log.tpl');
	}
}