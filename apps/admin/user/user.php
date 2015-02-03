<?php
namespace admin\user;

use admin as adm;
use model as md;


/**
 +-----------------------------
 *	会员列表页面
 +-----------------------------
 */

class user extends adm\front {
	
	//列表页面
	function items(){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 25;
		
		//查询所有会员等级
		$grade = new md\user_grade();
		$grade_items = $grade -> items();
		
		
		$grade = $_GET['grade'];		//会员等级
		$grade_tb = $_GET['grade_tb'];	//淘宝卖家等级
		$status = $_GET['status'];		//是否激活
		$k = trim($_GET['k']);			//用户名或邮箱的关键词
		
		//组合查询语句
		$where = '';
		if (isint($grade) && $grade > 0){
			$where .= ' AND grade = '.$grade.'';
		}
		if (isint($grade_tb) && $grade_tb > 0){
			$where .= ' AND grade_tb = '.$grade_tb.'';
		}
		if ($status === 1 || $status === 0){
			$where .= ' AND status = '.$status.'';
		}
		if (!empty($k)){
			if (strpos($k, '@') > 0 && strpos($k, '.') > 0){
				$where .= ' AND email like \'%'.encode($k).'%\'';
			} elseif (isint($k)) {
				$where .= ' AND mobile like \'%'.$k.'%\''; 
			} else {
				$where .= ' AND uname like \'%'.encode($k).'%\''; 
			}
		}
		if (isint($m) && $m > 0){
			$where .= ' AND amount_month >= '.$m.'';
		}
		
		//查询用户列表
		$sql = 'SELECT * FROM `user_info` WHERE 1 '.$where.' ORDER BY uid DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$user_items = $this -> db -> rows($sql);
		
		$sql = 'SELECT COUNT(*) AS num FROM `user_items` WHERE 1 '.$where.'';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		$GET = $_GET;
		if (isset($GET['page'])){unset($GET['page']);}
		$params = fetch_url_query($GET);
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this->col,
			'params' => $params
		));
		
		$this -> smarty -> assign('grade_items', $grade_items);
		$this -> smarty -> assign('user_items', $user_items);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('user/user_list.tpl');
	}
	
}