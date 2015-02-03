<?php
namespace admin\system;

if (!defined('START')) exit('No direct script access allowed');

use admin as adm;
use model as md;

/**
 +-----------------------------
 *	汇率页面
 +-----------------------------
 */

class Rate extends adm\Front {

	//汇率列表页
	function items(){
		$page = intval($_GET['page']);
		$page = $page <= 0 ? 1 : $page;
		$pagesize = 20;
		
		$rate = new md\Rate();
		$ret = $rate -> search($page, $pagesize);
		$rate_items = $ret['items'];
		$total = $ret['total'];
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this->col,
		));
		
		$this -> smarty -> assign('rate_items', $rate_items);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('system/rate_list.tpl');
	}
	
	//添加汇率页
	function add(){
		if (isset($_POST['submit'])){
			//添加处理部分
			$this -> add_submit();
		}
		
		//输出页面部分
		$this -> smarty -> assign('more_navs', '添加汇率');
		$this -> smarty -> display('system/rate_add.tpl');
	}
	
	//添加操作
	private function add_submit(){
		$date_line = trim($_POST['date_line']);
		$krw = trim($_POST['krw']);
		$usd = trim($_POST['usd']);
		$content = trim($_POST['content']);
		
		$rate = new md\rate();
		$f = $rate -> isDate($date_line);
		if (!$f){
			cpmsg(false, '日期格式不正确..', -1);
		}
		if (!is_numeric($krw) || $krw <= 0){
			cpmsg(false, '韩元汇率不正确..', -1);
		}
		if (!is_numeric($usd) || $usd <= 0){
			cpmsg(false, '美元汇率不正确..', -1);
		}
		
		$f = $rate -> add(array(
			'date_line' => $date_line,
			'krw' => $krw,
			'usd' => $usd,
			'content' => $content
		));
		
		if ($f){
			cpmsg(true, '汇率添加成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '汇率添加失败！', -1);	
		}
	}
	
	//编辑汇率页
	function edit(){
		if (isset($_POST['submit'])){
			//编辑部分
			$this -> edit_submit();
		}
		
		//输出页面部分
		$date_line = $this -> params[1];
		$rate = new md\Rate();
		$f = $rate -> isDate($date_line);
		if (!$f){
			cpmsg(false, '错误的日期格式。', -1);
		}
		$rate_data = $rate -> info($date_line);
		if ($rate_data){} else {
			cpmsg(false, '没有查询到该日期的汇率数据。', -1);
		}
		
		$this -> smarty -> assign('rate_data', $rate_data);
		$this -> smarty -> assign('more_navs', '编辑汇率');
		$this -> smarty -> display('system/rate_edit.tpl');
	}
	
	//编辑操作
	private function edit_submit(){
		$date_line = trim($_POST['date_line']);
		$krw = trim($_POST['krw']);
		$usd = trim($_POST['usd']);
		$content = trim($_POST['content']);
		
		$rate = new md\rate();
		$f = $rate -> isDate($date_line);
		if (!$f){
			cpmsg(false, '日期格式不正确..', -1);
		}
		if (!is_numeric($krw) || $krw <= 0){
			cpmsg(false, '韩元汇率不正确..', -1);
		}
		if (!is_numeric($usd) || $usd <= 0){
			cpmsg(false, '美元汇率不正确..', -1);
		}
		
		$num = $rate -> update($date_line, array(
			'krw' => $krw,
			'usd' => $usd,
			'content' => $content
		));
		
		if ($num > 0){
			cpmsg(true, '汇率编辑成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '汇率编辑失败！', -1);
		}
	}
	
	//删除功能
	function del(){
		$date_line = $this -> params[1];
		$page = intval($page);
		$rate = new md\rate();
		$f = $rate -> isDate($date_line);
		if (!$f){
			cpmsg(false, '错误的日期格式。', -1);
		}
		
		$num = $rate -> delete($date_line);
		if ($num){
			$url = '/'.$this->mod.'/'.$this->col.'';
			if ($page > 1){$url .= '?page='.$page;}
			cpurl($url);
		} else {
			cpmsg(false, '汇率删除失败，该日期的汇率不存在！', -1);
		}
	}
}