<?php
namespace admin\user;
use model as md;
use admin as adm;
/**
 +------------------------------
 *	批发会员审核
 +------------------------------
 */
class Upgrade extends adm\front {
		//列表
	function items(){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		$User_upgrade_apply = new md\User_upgrade_apply();
		$ret = $User_upgrade_apply -> search($_GET, $page, $pagesize);
		 //dump($ret);
		$items = $ret['items'];
		$total = $ret['total'];
		
		$url = '/'.$this->mod.'/'.$this->col.'/items';
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => $url,
			'params' => $params
		));
		
		$this -> smarty -> assign('items', $items);
		$this -> smarty -> assign('total', $total);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('user/upgrade_list.tpl');	
	}
	//通过
	function agree(){
		$id = intval($this -> params[1]);
		$uid = intval($this -> params[2]);
		if ($id <1 ){
			cpmsg(false, '无效的参数！', -1);	
		}
		$adm_uid= $_SESSION['admin']['uid'];
		
		$user = new md\user();
		$e = $user -> update($uid,array('grade_id'=>2));
		
		$User_upgrade_apply = new md\User_upgrade_apply();
		$f = $User_upgrade_apply -> update($id,array('status'=>1,'adm_uid'=>$adm_uid));
		//改成批发会员等级
		
		if ($f){
			cpmsg(true, '操作成功！', -1);	
		} else {
			cpmsg(false, '操作失败！', -1);	
		}
	}
	
	//拒绝
	function disagree(){
		$id = intval($this -> params[1]);
		if ($id <1 ){
			cpmsg(false, '无效的参数！', -1);	
		}
		$adm_uid= $_SESSION['admin']['uid'];

		$User_upgrade_apply = new md\User_upgrade_apply();
		$f = $User_upgrade_apply -> update($id,array('status'=>2,'adm_uid'=>$adm_uid));
		
		if ($f){
			cpmsg(true, '操作成功！', -1);	
		} else {
			cpmsg(false, '操作失败！', -1);	
		}

	}
}
