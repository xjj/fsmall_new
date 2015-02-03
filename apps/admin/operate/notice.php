<?php
namespace admin\operate;

if (!defined('START')) exit('No direct script access allowed');

use admin as adm;
use model as md;

/**
 +---------------------------------
 *	公告页面
 +---------------------------------
 */
class Notice extends adm\Front {
	
	//列表
	function items(){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		$notice = new md\notice();
		$ret = $notice -> search2($_GET, $page, $pagesize);
		$notice_items = $ret['items'];
		$total = $ret['total'];
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this->col,
			'params' => $_GET
		));
		
		$this -> smarty -> assign('notice_items', $notice_items);
		$this -> smarty -> assign('pagebox', $pg -> show());
		$this -> smarty -> display('operate/notice_list.tpl');
	}
	
	//添加
	function add(){
		if (isset($_POST['submit'])){
			//添加操作
			$this -> add_submit();
		}
		
		//页面显示
		$this -> smarty -> assign('more_navs', '添加公告');
		$this -> smarty -> display('operate/notice_add.tpl');
	}
	
	//添加操作
	private function add_submit(){
		$title = trim($_POST['title']);
		$content = stripslashes(trim($_POST['content']));
		$is_top = $_POST['is_top'];
		$auth = $_POST['auth'];
		
		if (empty($title)){
			cpmsg(false, '请填写公告的标题！', -1);
		}
		
		if ($is_top != 1){$is_top = 0;}
		if (!in_array($auth, array(0,1,2))){$auth = 0;}
		
		$notice = new md\notice();
		
		$f = $notice -> add(array(
			'title' => $title,
			'content' => $content,
			'is_top' => $is_top,
			'auth' => $auth
		));
		if ($f > 0){
			cpmsg(true, '公告信息发布成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '公告信息发布失败！', -1);
		}
	}
	
	
	//编辑
	function edit(){
		if (isset($_POST['submit'])){
			$this -> edit_submit();
		}
		
		$id = $this -> params[1];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '编辑地址不正确，错误的请求！', -1);
		}
		
		$notice = new md\notice();
		$notice_data = $notice -> info($id);
		if ($notice_data){} else {
			cpmsg(false, '该公告不存在或已被删除！', -1);
		}
		
		$this -> smarty -> assign('more_navs', '编辑公告');
		$this -> smarty -> assign('notice_data', $notice_data);
		$this -> smarty -> display('operate/notice_edit.tpl');
	}
	
	private function edit_submit(){
		$id = $_POST['id'];
		$title = trim($_POST['title']);
		$content = stripslashes(trim($_POST['content']));
		$is_top = $_POST['is_top'];
		$auth = $_POST['auth'];
		
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		if (empty($title)){
			cpmsg(false, '请填写公告标题！', -1);
		}
		
		if ($istop != 1){$istop = 0;}
		if (!in_array($auth, array(0,1,2))){$auth = 0;}
		
		$notice = new md\notice();
		$num = $notice -> edit($id, array(
			'title' => $title,
			'content' => $content,
			'is_top' => $is_top,
			'auth' => $auth
		));
		
		cpmsg(true, '公告信息编辑成功！', '/'.$this->mod.'/'.$this->col);
	}
	
	//删除
	function del(){
		$id = $this -> params[1];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$notice = new md\notice();
		$notice -> delete($id);
		
		$params = fetch_url_query();
		$url = '/'.$this->mod.'/'.$this->col.'';
		if ($params){
			$url .= '?'.$params;
		}
		cpurl($url);
	}
	
	//显示
	function show(){
		$id = $this -> params[1];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$params = fetch_url_query();
		
		$notice = new md\notice();
		$notice -> show($id);
		
		$url = '/'.$this->mod.'/'.$this->col.'';
		if ($params){
			$url .= '?'.$params;
		}
		cpurl($url);
		
	}
	
	//隐藏
	function hide(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$notice = new md\notice();
		$notice -> hide($id);
		
		$params = fetch_url_query();
		$url = '/'.$this->mod.'/'.$this->col.'';
		if ($params){
			$url .= '?'.$params;
		}
		cpurl($url);
	}
}