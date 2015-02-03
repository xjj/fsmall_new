<?php
namespace admin\system;

if (!defined('START')) exit('No direct script access allowed.');

use admin as adm;
use model as md;

/**
 +---------------------------
 *	管理员类
 +---------------------------
 */
class User extends adm\Front {
	
	//列表页面	
	function items(){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 25;
		
		//查询会员列表
		$user = new md\system_user();
		$ret = $user -> search(array(), $page, $pagesize);
		$user_items = $ret['items'];
		$total = $ret['total'];
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this->col,
		));
		
		$this -> smarty -> assign('user_items', $user_items);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('system/user_list.tpl');
	}
	
	//添加页面
	function add(){
		if (isset($_POST['submit'])){
			//添加操作
			$this -> add_submit();			
		}
		
		//页面部分
		$this -> smarty -> assign('more_navs', '添加管理员');
		$this -> smarty -> display('system/user_add.tpl');
	}
	
	//添加操作
	private function add_submit(){
		$uname = trim($_POST['uname']);
		$email = trim($_POST['email']);
		$upass  = trim($_POST['upass']);
		$upass2 = trim($_POST['upass2']);
		
		$user = new md\system_user();
		$len = (strlen($uname) + mb_strlen($uname,'UTF8')) / 2;
		if ($len < 4 || $len > 20){
			cpmsg(false, '用户名长度要2 ~ 10个汉字，相当于4 ~ 20个字母或数字。', -1);
		}
		if (!isCnWord($uname, 2, 20)){
			cpmsg(false, '用户名请填写中文，英文，下划线。', -1);
		}
		if (!isEmail($email)){
			cpmsg(false, '邮箱格式不正确，请检查邮箱。', -1);
		}
		$f = $user -> isExist(array('uname' => $uname, 'email' => $email));
		if ($f){
			cpmsg(false, '该用户名或邮箱已存在。', -1);
		}
		$len = strlen($upass);
		if ($len < 6 || $len > 18){
			cpmsg(false, '密码位数要 6 ~ 18位 之间。', -1);
		}
		if ($upass != $upass2){
			cpmsg(false, '确认密码不正确，请重新输入。', -1);
		}
		
		$f = $user -> add(array('uname' => $uname, 'email' => $email, 'upass' => $upass,));
		if ($f){
			cpmsg(true, '管理员添加成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '管理员添加失败，数据保存失败！', -1);
		}
	}
	
	//编辑页面
	function edit(){
		if (isset($_POST['submit'])){
			$this -> edit_submit();
		}
		
		//页面部分
		$uid = $this -> params[1];
		if (!isint($uid) || $uid <= 0){
			cpmsg(false, '缺少重要参数！', -1);
		}
		
		$user = new md\system_user();
		$user_data = $user -> info($uid);
		if ($user_data){} else {
			cpmsg(false, '该管理员不存在或已被删除！', -1);
		}
		
		$this -> smarty -> assign('more_navs', '编辑管理员');
		$this -> smarty -> assign('user_data', $user_data);
		$this -> smarty -> display('system/user_edit.tpl');
	}
	
	//编辑操作
	private function edit_submit(){
		$uid   = $_POST['uid'];
		$uname = trim($_POST['uname']);
		$email = trim($_POST['email']);
		$upass  = trim($_POST['upass']);
		$upass2 = trim($_POST['upass2']);
		
		if (!isint($uid) || $uid <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$user = new md\system_user();
		$len = (strlen($uname) + mb_strlen($uname,'UTF8')) / 2;
		if ($len < 4 || $len > 20){
			cpmsg(false, '用户名长度要2 ~ 10个汉字，相当于4 ~ 20个字母或数字。', -1);
		}
		if (!isCnWord($uname, 2, 20)){
			cpmsg(false, '用户名请填写中文，英文，下划线。', -1);
		}
		if (!isEmail($email)){
			cpmsg(false, '邮箱格式不正确，请检查邮箱。', -1);
		}
		$f = $user -> isExist(array('uname' => $uname, 'email' => $email, 'uid' => $uid));
		if ($f){
			cpmsg(false, '该用户名或邮箱已存在。', -1);
		}
		if (!empty($upass)){
			$len = strlen($upass);
			if ($len < 6 || $len > 18){
				cpmsg(false, '密码位数要 6 ~ 18位 之间。', -1);
			}
			if ($upass != $upass2){
				cpmsg(false, '确认密码不正确，请重新输入。', -1);
			}
		}
		
		$data = array('uname' => $uname,'email' => $email);
		if (!empty($upass)){$data['upass'] = $upass;}
		
		$num = $user -> edit($uid, $data);
		if ($num > 0){
			cpmsg(true, '管理员信息编辑成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '管理员信息编辑失败！', -1);
		}
	}
	
	//删除功能
	function del(){
		$uid = $this -> params[1];
		if (!isint($uid) || $uid <= 0){
			cpmsg(false, '缺少重要参数！', -1);
		}
		$user = new md\system_user();
		$f = $user -> delete($uid);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '管理员删除失败！', -1);
		}
	}
	
	//设置权限
	function auth(){
		if (isset($_POST['submit'])){
			//保存权限表
			$this -> auth_submit();
		}
		
		//页面部分
		$uid = $this -> params[1];
		if (!isint($uid) || $uid <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		$user = new md\system_user();
		$user_data = $user -> info($uid);
		if ($user_data){
			$auths = trim($user_data['auths']);
			$auths = trim($user_data['auths'], ',');
			if ($auths != ''){
				$user_data['authsArr'] = explode(',', $auths);
			} else {
				$user_data['authsArr'] = array();
			}
		} else {
			cpmsg(false, '该管理员不存在或已被删除！', -1);
		}
		
		$this -> smarty -> assign('more_navs', '编辑权限');
		$this -> smarty -> assign('user_data', $user_data);
		$this -> smarty -> display('system/user_auth.tpl');
	}
	
	//权限操作
	private function auth_submit(){
		$uid = $_POST['uid'];
		if (!isint($uid) || $uid <= 0){
			cpmsg(false, '错误的请求！', -1);
		}

		$auths = $_POST['auths'];
		if (empty($_POST['auths'])){
			$data = '';
		} else {
			$data = implode(',', $auths);
		}
		$user = new md\system_user();
		$num = $user -> update($uid, array('auths' => $data));
		if ($num > 0){
			cpmsg(true, '管理员权限编辑成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '管理员权限编辑失败！', -1);
		}
	}
	
	//管理员日志
	function logs(){
		$this -> smarty -> display('system/user_logs.tpl');
	}
	
	//设置有效
	function show(){
		$uid = $this -> params[1];
		if (!isint($uid) || $uid <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$user = new md\system_user();
		$user -> validate($uid, 1);
		
		$url = '/'.$this->mod.'/'.$this->col;
		$params = fetch_url_query();
		if ($params){$url .= '?'.$params;}
		cpurl($url);
	}
	
	//设置无效
	function hide(){
		$uid = $this -> params[1];
		if (!isint($uid) || $uid <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$user = new md\system_user();
		$user -> validate($uid, 0);
		
		$url = '/'.$this->mod.'/'.$this->col;
		$params = fetch_url_query();
		if ($params){$url .= '?'.$params;}
		cpurl($url);
	}
}