<?php
namespace shop;

use model as md;

/**
 *	个人中心 -- 修改密码
 */
class User_Password extends front {
	
	/**
	 *	页面
	 */
	function index(){
		if (isset($_POST['submit'])){
			$this -> submit();
		}
		
		$this -> smarty -> assign('title', '修改密码');
		$this -> smarty -> display('user/password.tpl');
	}
	
	/**
	 *	修改操作
	 */
	private function submit(){
		$upass = $_POST['upass'];
		$upass_new = $_POST['upass_new'];
		$upass_new_confirm = $_POST['upass_new_confirm'];
		
		if (empty($upass)){
			cpmsg(false, '请输入帐号的原密码！', -1);
		}
		if (empty($upass_new)){
			cpmsg(false, '请输入新密码！', -1);
		}
		if ($upass_new != trim($upass_new)){
			cpmsg(false, '密码的首尾不能有空格，请重新填写！', -1);
		}
		$len = strlen($upass_new);
		if ($len < 6 || $len > 18){
			cpmsg(false, '密码的位数请限制在6 ~ 18位之间！', -1);
		}
		if ($upass_new != $upass_new_confirm){
			cpmsg(false, '确认密码不正确，请再次输入一遍确认！', -1);
		}
		
		$uid = $_SESSION['uid'];
		
		$user = new md\user();
		$num = $user -> update($uid, array('upass' => md5($upass_new)));
		if ($num > 0){
			cpmsg(true, '密码修改成功！', '/user/password');
		} else {
			cpmsg(false, '密码修改失败，原密码没有被更改！', -1);
		}
	}
}
