<?php
namespace admin\user;

use model as md;
use admin as adm;

/**
 +------------------------------
 *	用户金额 充值与提现
 +------------------------------
 */

class Money extends adm\front {

	//修改金额
	function add(){
		if (isset($_POST['submit'])){
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
		$this -> smarty -> display('user/money_edit.tpl');
	}
	
	//
	function add_submit(){
		
		$uid = intval($_POST['uid']);
		$act_type = trim($_POST['act_type']);
		$reason = trim($_POST['reason']);
		$money = round(abs(floatval(trim($_POST['money']))),2);
		
		if ($act_type == 'add' || $act_type == '+'){
			$act_type = 'add';
		} else {
			$act_type = 'minus';	
		}
		
		if ($money == 0){
			cpmsg(false, '操作的金额不能为零！', -1);	
		}
		
		if (empty($reason)){
			cpmsg(false, '请填写资金操作的原因！', -1);
		}
		
		//获取用户现有账户余额
		$balance = new md\balance();
		$money_current = $balance -> get($uid);
		if (is_numeric($money_current)){} else {
			cpmsg(false, '没有查询到该用户，获取账户余额失败！', -1);	
		}
		
		if ($act_type == 'minus' && $money_current < $money){
			cpmsg(false, '抱歉，账户余额不足，扣除失败！', -1);	
		}
		
		$data = array(
			'uid' => $uid,
			'adm_uid' => $_SESSION['admin']['uid'],
			'money' => $money,
			'content' => $reason
		);
		
		if ($act_type == 'add'){
			$f = $balance -> add($uid, $money, $data);
		} else {
			$f = $balance -> minus($uid, $money, $data);	
		}
		
		if ($f){
			cpmsg(true, '账户余额操作成功！', '/'.$this->mod.'/'.$this->col.'/items/'.$uid);	
		} else {
			cpmsg(false, '账户余额操作失败！', -1);	
		}
	}
	
	
	
	//日志列表
	function items(){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		$uid = $this -> params[1];
		if (isint($uid) && $uid > 0){
			$_GET['uid'] = $uid;	
		}
		
		$log = new md\user_money_log();
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
			'params' => $params
		));
		
		$this -> smarty -> assign('log_items', $log_items);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('user/money_log.tpl');	
	}
}
