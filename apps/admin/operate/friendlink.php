<?php
namespace admin\operate;

if (!defined('START')) exit('No direct script access allowed.');

use admin as adm;
use model as md;

/**
 +--------------------------------
 *	友情链接
 +--------------------------------
 */
class Friendlink extends adm\Front {
	
	//列表
	function items(){
		$fl = new md\friendlink();
		$friendlink_items = $fl -> items('all');
		
		$this -> smarty -> assign('friendlink_items', $friendlink_items);
		$this -> smarty -> display('operate/friendlink_list.tpl');
	}
	
	//添加
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
		}
		
		$this -> smarty -> assign('more_navs', '添加友情链接');
		$this -> smarty -> display('operate/friendlink_add.tpl');
	}
	
	//添加操作
	private function add_submit(){
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		$logo = trim($_POST['logo']);
		$url = trim($_POST['url']);
		$displayorder = intval($_POST['displayorder']);
		
		if (empty($title)){
			cpmsg(false, '请填写友情链接标题！', -1);
		}
		if (!empty($url)){
			$url2 = strtolower($url);
			if (strpos($url2, 'http://') === 0){} else {
				cpmsg(false, '链接地址必须以 (http://) 开头。', -1);
			}
		}
			
		$fl = new md\friendlink();
		$f = $fl -> add(array(
			'title' => $title,
			'content' => $content,
			'logo' => $logo,
			'url' => $url,
			'displayorder' => $displayorder,
		));
		
		if ($f > 0){
			cpmsg(true, '友情链接添加成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '友情链接添加失败！', -1);
		}
	}
	
	//编辑广告
	function edit(){
		if (isset($_POST['submit'])){
			$this -> edit_submit();
		}
		
		
		$id = $this -> params[1];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		$fl = new md\friendlink();
		$fldata = $fl -> info($id);
		if ($fldata){} else {
			cpmsg(false, '该友情链接不存在或已被删除！', -1);
		}
		
		$this -> smarty -> assign('more_navs', '编辑友情链接');
		$this -> smarty -> assign('fldata', $fldata);
		$this -> smarty -> display('operate/friendlink_edit.tpl');
	}
	
	//编辑操作
	private function edit_submit(){
		$id = trim($_POST['id']);
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		$logo = trim($_POST['logo']);
		$url = trim($_POST['url']);
		$displayorder = intval($_POST['displayorder']);
		
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		if (empty($title)){
			cpmsg(false, '请填写友情链接标题！', -1);
		}
		if (!empty($url)){
			$url2 = strtolower($url);
			if (strpos($url2, 'http://') === 0){} else {
				cpmsg(false, '链接地址必须以 (http://) 开头。', -1);
			}
		}
			
		$fl = new md\friendlink();
		$num = $fl -> update($id, array(
			'title' => $title,
			'content' => $content,
			'logo' => $logo,
			'url' => $url,
			'displayorder' => $displayorder,
		));
		
		if ($num > 0){
			cpmsg(true, '友情链接编辑成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '友情链接编辑失败，或没有信息被修改！', -1);
		}
	}
	
	
	//删除广告
	function del(){
		$id = $this -> params[1];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$fl = new md\friendlink();
		$f = $fl -> delete($id);
		if ($f > 0){
			cpurl('/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '友情链接删除失败！', -1);
		}
	}
	
	//显示广告
	function show(){
		$id = $this -> params[1];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$fl = new md\friendlink();
		$fl -> show($id);
		cpurl('/'.$this->mod.'/'.$this->col);
	}
	
	//隐藏广告
	function hide(){
		$id = $this -> params[2];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$fl = new md\friendlink();
		$fl -> hide($id);
		cpurl('/'.$this->mod.'/'.$this->col);
	}
}