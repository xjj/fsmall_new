<?php
namespace admin\system;

if (!defined('START')) exit('No direct script access allowed');

use admin as adm;
use model as md;

/**
 +-----------------------------
 *	网站设置 具体控制器
 +-----------------------------
 */

class Setting extends adm\Front {

	//列表页
	function items(){
		$page = intval($_GET['page']);
		$page = $page <= 0 ? 1 : $page;
		$pagesize = 20;
		
		$system_Setting = new md\system_Setting();
		$ret = $system_Setting -> search($page, $pagesize);
		$setting_items = $ret['items'];
		$total = $ret['total'];
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this->col,
		));
		
		$this -> smarty -> assign('setting_items', $setting_items);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('system/setting_list.tpl');
	}
	//编辑
	function edit(){
		if (isset($_POST['submit'])){
			//编辑部分
			$this -> edit_submit();
		}else{
			cpmsg(false, '错误的请求', -1);
		}
	}
	
	//编辑操作
	private function edit_submit(){
		//var_dump( $_POST);die;
		unset( $_POST['submit']);
		$system_setting = new md\system_setting();
		$f = $system_setting -> update($_POST);
		
		if ($f||$f==0){
			cpmsg(true, '编辑成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			//echo mysql_error();
			//echo 12;
			//var_dump($f);
			cpmsg(false, '编辑失败！', -1);
		}
	}
	//-----------------------分割线--------------------
		//添加页
	function add(){
		if (isset($_POST['submit'])){
			//添加处理部分
			$this -> add_submit();
		}
		
		//输出页面部分
		//$this -> smarty -> assign('more_navs', '添加汇率');
		$this -> smarty -> display('system/setting_add.tpl');
	}

	
	
	//添加操作
	private function add_submit(){
		$key = trim($_POST['key']);
		$value = trim($_POST['value']);
		
		$system_setting = new md\system_setting();
		$f = $system_setting -> search(1,2,array('key'=>$key));
		var_dump($f);die;
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