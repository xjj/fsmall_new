<?php
namespace shop;

use model as md;

/**
 *	注册控制器
 */
class Register extends Front {
	
	/**
	 *	注册页面
	 */
	function index(){
		if (isset($_POST['submit'])){
			$this -> submit();
			exit();
		}
		$this -> smarty -> assign('title', '会员注册');
		$this -> smarty -> display('register.tpl');
	}
	
	/**
	 *	注册提交验证
	 */
	private function submit(){
		$uname = trim($_POST['uname']);
		$email = trim($_POST['email']);
		$upass = $_POST['upass'];
		$upass2 = $_POST['upass2'];
		
		//验证邮箱
		if (empty($email)){
			cpmsg(false, '请输入登录邮箱！', -1);
		}
		
		if (!isEmail($email)){
			cpmsg(false, '邮箱格式不正确，请检查！', -1);
		}
		
		$user = new md\user();
		$uid = $user -> get_uid_by_email($email);
		if ($uid){
			cpmsg(false, '该邮箱已被注册，请更换一个！', -1);
		}
		
		//验证用户名
		if (empty($uname)){
			cpmsg(false, '请输入用户名，支持中文、英文和数字，不能为纯数字！', -1);
		} 
		
		if (is_numeric($uname)){
			cpmsg(false, '用户名不能为纯数字！', -1);
		}
		
		if (!isCnWord($uname, 2, 20)){
			cpmsg(false, '用户名请使用中文、英文字母或数字，10个汉字，20个字符以内！', -1);
		}
		
		$len = strlen(iconv("UTF-8","GBK", $uname));
		if ($len < 4){
			cpmsg(false, '用户名太短，至少4个字符或2个汉字！', -1);
		} elseif ($len > 20){
			cpmsg(false, '用户名太长，最多20个字符或10个汉字！', -1);
		} else {
			$uid = $user -> get_uid_by_uname($uname);
			if ($uid){
				cpmsg(false, '该用户名已存在，请更换一个！', -1);
			}
		}
		
		//验证密码
		if (trim($upass) == ''){
			cpmsg(false, '请输入登录密码！', -1);
		}
		
		if (trim($upass) != $upass){
			cpmsg(false, '密码首尾不能有空格！', -1);
		} else {
			$len = strlen($upass);
			if ($len < 6 || $len > 18){
				cpmsg(false, '密码长度请限制在6~18之间！', -1);
			}
		}
		
		if ($upass != $upass2){
			cpmsg(false, '确认密码不正确，请重新输入！', -1);
		}
		
		//创建帐号信息
		$uid = $user -> add(array('uname' => $uname, 'email' => $email, 'upass' => $upass));
		
		if ($uid){
			$code = base64_encode($email);
			cpurl('/register/verify?e='.urlencode($code));
		} else {
			cpmsg(false, '抱歉，注册信息写入数据库失败！', -1);
		}
	}
	
	/**
	 *	邮箱验证-激活帐号页面
	 */
	function verify(){
		$e = urldecode($_GET['e']);		//邮箱
		$c = urldecode($_GET['c']);		//验证码
		
		$email = base64_decode($e);
		if ($email){} else {
			cpmsg(false, '参数不正确，请重新进行邮箱验证！', '/login');
		}
		
		//查询用户信息
		$user = new md\user();
		$userdata = $user -> get_info_by_email($email);
		if ($userdata){
			$uid = $userdata['uid'];
			$uname = $userdata['uname']; 
		} else {
			cpmsg(false, '您要验证的用户不存在或已被删除！', '/login');
		}
		
		//判断验证码
		if (strlen($c) == 6){
			$user_email = new md\user_email();
			$f = $user_email -> verify($uid, $email, $c, 'REG');
			if ($f){
				//更新用户帐号状态
				$user -> update($uid, array('status' => 1, 'email_status' => 1));
				
				//清除验证信息
				$user_email -> clear($uid, $email);
				
				//保存登录信息
				$udata = array('uid' => $uid, 'uname' => $uname, 'email' => $email);
				
				//
				$login1 = new Login();
				$login1 -> session($uid, $udata);
				
				$login = new md\login();
				$login -> record_login($uid, $udata);
				
				
				if ($_SESSION['login_remember'] == 1){
					$login1 -> cookies($uid, $udata);
					unset($_SESSION['login_remember']);
				}
				
				cpmsg(true, '邮箱验证通过，帐号已成功激活！', '/user');
			} else {
				cpmsg(false, '邮箱验证失败，验证码不正确！', '/register/verify?e='.$e);
			}
			exit();
		}
		
		$this -> smarty -> assign('title', '邮箱验证_帐号激活');
		$this -> smarty -> assign('uid', $uid);
		$this -> smarty -> assign('email', $email);
		$this -> smarty -> display('register_verify.tpl');
	}
	
	/**
	 *	发送验证码邮件页面｛ajax｝
	 */
	function sendmail(){
		$uid   = urldecode($_POST['uid']);
		$email = urldecode($_POST['email']);
		
		if (!isint($uid) || $uid <= 0){
			echo json_encode(array('error' => -1, 'message' => '用户信息错误，无法获取到用户信息！'));
			exit();
		}
		
		//查询用户信息
		$user = new md\user();
		$userdata = $user -> info($uid);
		if ($userdata){
			$uname = $userdata['uname'];
		} else {
			echo json_encode(array('error' => -1, 'message' => '该邮箱用户不存在或已被删除！'));
			exit();
		}
		
		if ($userdata['email'] != $email){
			echo json_encode(array('error' => -1, 'message' => '该邮箱与用户登录邮箱不匹配！'));
			exit();
		}
		
		$f = $this -> send($uid, $uname, $email);
		if ($f){
			echo json_encode(array('error' => 0));
		} else {
			echo json_encode(array('error' => -1, 'message' => '验证邮件发送失败！'));
		}
	}
	
	
	//发送验证邮件 --- 这个放到控制器里
	function send($uid, $uname, $email){
		
		$ecode = base64_encode($email);
		
		$code = random(6);
		$link = URI_PATH.'/register/verify?e='.urlencode($ecode).'&c='.$code;
		
		
		//邮件内容
		$content = ''
		. '<p>尊敬的非尚网用户，<b>'.$uname.' ：</b></p>'
		. '<p>您好！</p>'
		. '<p>您的邮箱验证码为：<b>'. $code .'</b></p>'
		. '<p>您也可以点击如下链接来完成帐号验证。</p>'
		. '<p><a href="'.$link.'" target="_blank">'.$link.'</a></p>'
		. '<p>如果上面的链接无法点击，您也可以复制链接，粘贴到您浏览器的地址栏内，然后按“回车”键打开预设页面，完成相应功能。</p>'
		. '<p>验证将会在一小时后失效，请尽快完成邮箱验证，否则需要重新进行验证。</p>'
		. '<p>通过验证后，您可以通过该邮箱找回密码。</p>'
		. '<br />'
		. '<p>此邮件由系统自动发送，请不要回复该邮件。</p>';
		
		$title = '非尚邮箱注册验证';
		
		$mail = new \Mail();
		$f = $mail -> send($email, $title, $content);
		if ($f){
			$user_email = new md\user_email();
			$f2 = $user_email -> add(array('uid' => $uid, 'email' => $email, 'code' => $code, 'type' => 'REG'));
			if ($f2){
				return true;
			}
		}
		return false;
	}
}