<?php
namespace admin\operate;

if (!defined('START')) exit('No direct script access allowed');

use admin as adm;
use model as md;

/**
 +---------------------------------
 *	文章
 +---------------------------------
 */
class Article extends adm\Front {
	
	//列表
	function items(){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20;
		
		$art = new md\article();
		$ret = $art -> search($_GET, $page, $pagesize);
		$article_items = $ret['items'];
		$total = $ret['total'];
		
		$params = fetch_url_query($_GET);
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'params' => $_GET,
			'url' => '/'.$this->mod.'/'.$this->col.'',
		));
		
		$this -> smarty -> assign('article_items', $article_items);
		$this -> smarty -> assign('pagebox', $pg -> show());
		$this -> smarty -> assign('params', $params);
		$this -> smarty -> display('operate/article_list.tpl');
	}
	
	//添加
	function add(){
		if (isset($_POST['submit'])){
			//添加操作
			$this -> add_submit();
		}
		
		//获取所有分类
		$cat = new md\article_category();
		$cat_items = $cat -> items();
		
		//页面显示
		$this -> smarty -> assign('more_navs', '添加文章');
		$this -> smarty -> assign('cat_items', $cat_items);
		$this -> smarty -> display('operate/article_add.tpl');
	}

	//添加操作
	private function add_submit(){
		$title = trim($_POST['title']);
		$content = stripslashes(trim($_POST['content']));
		
		$cat_id = intval($_POST['cat_id']);
		$short_url = trim($_POST['short_url']);
		$displayorder = intval($_POST['displayorder']);
		
		if (empty($title)){
			cpmsg(false, '请填写文章标题！', -1);
		}
		if ($cat_id <= 0){
			cpmsg(false, '请选择文章分类！', -1);	
		}
		
		$art = new md\article();
		$f = $art -> add(array(
			'title' => $title,
			'content' => $content,
			'cat_id' => $cat_id,
			'short_url' => $short_url,
			'displayorder' => $displayorder,
		));
		if ($f){
			cpmsg(true, '文章信息发布成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '文章信息发布失败！', -1);
		}
	}
	
	//编辑
	function edit(){
		if (isset($_POST['submit'])){
			$this -> edit_submit();
		}
		
		$art_id = $this -> params[1];
		if (!isint($art_id) || $art_id <= 0){
			cpmsg(false, '编辑地址不正确，错误的请求！', -1);
		}
		
		$art = new md\article();
		$article_data = $art -> info($art_id);
		if ($article_data){} else {
			cpmsg(false, '该文章不存在或已被删除！', -1);
		}
		
		//读取分类
		$cat = new md\article_category();
		$cat_items = $cat -> items();
		
		$this -> smarty -> assign('more_navs', '编辑文章');
		$this -> smarty -> assign('cat_items', $cat_items);
		$this -> smarty -> assign('article_data', $article_data);
		$this -> smarty -> display('operate/article_edit.tpl');
	}
	
	private function edit_submit(){
		$art_id = $_POST['art_id'];
		
		$title = trim($_POST['title']);
		$cat_id = intval($_POST['cat_id']);
		$short_url = trim($_POST['short_url']);
		$displayorder = intval($_POST['displayorder']);
		$content = stripslashes(trim($_POST['content']));
		
		if (!isint($art_id) || $art_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		if (empty($title)){
			cpmsg(false, '请填写文章标题！', -1);
		}
		if ($cat_id <= 0){
			cpmsg(false, '请选择文章分类！', -1);	
		}
		
		$art = new md\article();
		$num = $art -> update($art_id, array(
			'title' => $title,
			'content' => $content,
			'cat_id' => $cat_id,
			'short_url' => $short_url,
			'displayorder' => $displayorder
		));
		
		cpmsg(true, '文章编辑成功！', '/'.$this->mod.'/'.$this->col);
	}
	
	//删除
	function del(){
		$art_id = $this -> params[1];
		if (!isint($art_id) || $art_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$art = new md\article();
		$art -> delete($art_id);
		
		$params = fetch_url_query($_GET);
		$url = '/'.$this->mod.'/'.$this->col.'';
		if ($params){
			$url .= '?'.$params;
		}
		cpurl($url);
	}
	
	//显示
	function show(){
		$art_id = $this -> params[1];
		if (!isint($art_id) || $art_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$params = fetch_url_query($_GET);
		
		$art = new md\article();
		$art -> validate($art_id, 1);
		
		$url = '/'.$this->mod.'/'.$this->col.'';
		if ($params){
			$url .= '?'.$params;
		}
		cpurl($url);
		
	}
	
	//隐藏
	function hide(){
		$art_id = $this -> params[1];
		if (!isint($art_id) || $art_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$params = fetch_url_query($_GET);
		
		$art = new md\article();
		$art -> validate($art_id, 0);
		
		$url = '/'.$this->mod.'/'.$this->col.'';
		if ($params){
			$url .= '?'.$params;
		}
		cpurl($url);
	}
	
	//分类
	function cat(){
		$p = $this -> params[1];
		$ps = array('items','add','edit','del', 'show', 'hide'); 
		if (!in_array($p, $ps)){$p = 'items';}
		$art_cat = new article_category();
		$art_cat -> $p();	
	}
}