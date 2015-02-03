<?php
namespace shop;

use model as md;

/**
 +---------------------------------
 *	公告
 +---------------------------------
 */
class Notice extends Front {
	
	function index(){
		$this -> items();	
	}
	
	//列表页面
	function items(){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		$grade_id = intval($_SESSION['grade_id']);
		if ($grade_id <= 0){
			$grade_id = 0;
		}
		
		$data = array(
			'k' => urldecode($_GET['k']),
			'grade_id' => $grade_id
		);
		
		$notice = new md\notice();
		$ret = $notice -> search($data, $page, $pagesize);
		$notice_items = $ret['items'];
		$total = $ret['total'];
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/notice',
			'params' => $_GET
		));
		
		$this -> smarty -> assign('notice_items', $notice_items);
		$this -> smarty -> assign('title', '公告');
		$this -> smarty -> display('notice.tpl');	
	}
	
	//细节页面
	function detail(){
		
		$notice_id = $this -> params[0];
		
		$notice = new md\notice();
		$notice_data = $notice -> info($notice_id);
		if ($notice_data){} else {
			cpmsg(false, '没有查询到该公告信息，该公告不存在或已被删除！', -1);	
		}
		
		$this -> smarty -> assign('title', $notice_data['title'].'_公告');
		$this -> smarty -> assign('notice_data', $notice_data);
		$this -> smarty -> display('notice_detail.tpl');	
	}
}
