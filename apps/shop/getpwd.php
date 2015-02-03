<?php
namespace shop;

use model as md;

/**
 +-----------------------------
 *	找回密码
 +-----------------------------
 */
class GetPWD extends Front {
	
	//重置页面
	function index(){
		$this -> smarty -> assign('title', '找回密码');
		$this -> smarty -> display('getpwd.tpl');
	}
	
	//发送邮件页面
	function sendmail(){
		$email = trim($_POST['email']);
		
		//验证邮箱
		if (empty($email)){
			echo json_encode(array('error' => -1, 'message' => '请输入邮箱地址！'));
			exit();
		}
		
		if (!isEmail($email)){
			echo json_encode(array('error' => -1, 'message' => '邮箱格式不正确，请检查！'));
			exit();
		}
		
		//查询用户信息
		$user = new md\user();
		$userdata = $user -> get_info_by_email($email);
		if ($userdata){
			$uid = $userdata['uid'];
			$uname = $userdata['uname'];
		} else {
			echo json_encode(array('error' => -1, 'message' => '该邮箱用户不存在或已被删除！'));
			exit();
		}
		
		$user_email = new md\user_email();
		
		//查询是否已发送邮件
		$f = $user_email -> isExist($uid, $email, 'VRY');
		if ($f){
			echo json_encode(array('error' => -1, 'message' => '密码重置邮件已发送，请到邮箱查收！'));
			exit();
		}
		
		$f = $this -> send($uid, $uname, $email);
		if ($f){
			echo json_encode(array('error' => 0));
		} else {
			echo json_encode(array('error' => -1, 'message' => '密码重置邮件发送失败！'));
		}
	}
	
	//密码重置页面
	function reset(){
		$e = urldecode($_GET['e']);		//邮箱
		$c = urldecode($_GET['c']);		//验证码
		
		$email = base64_decode($e);
		if ($email){} else {
			cpmsg(false, '密码重置链接不正确！', '/getpwd');
		}
		
		//查询用户信息
		$user = new md\user();
		$userdata = $user -> get_info_by_email($email);
		if ($userdata){
			$uid = $userdata['uid'];
			$uname = $userdata['uname']; 
		} else {
			cpmsg(false, '您要重置密码的用户不存在或已被删除！', '/getpwd');
		}
		
		//判断验证码
		$user_email = new md\user_email();
		if (strlen($c) == 6){
			$f = $user_email -> verify($uid, $email, $c, 'VRY');
			if (!$f){
				cpmsg(false, '密码重置链接已过期，请重新设置！', '/getpwd');
			}
		} else {
			cpmsg(false, '密码重置链接不正确！', '/getpwd');
		}
		
		//重置操作
		if (isset($_POST['submit'])){
			$upass  = $_POST['upass'];
			$upass2 = $_POST['upass2'];
			
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
			
			//更新密码
			$num = $user -> update($uid, array('upass' => md5($upass)));
			if ($num > 0){
				$user_email -> clear($uid, $email);
				cpmsg(true, '密码已更新，请重新登录！', '/login');
			} else {
				cpmsg(false, '抱歉，密码更新失败！', -1);
			}
			exit();
		}
		
		$this -> smarty -> assign('email', $email);
		$this -> smarty -> assign('uname', $uname);
		$this -> smarty -> assign('title', '密码重置');
		$this -> smarty -> display('getpwd_reset.tpl');
	}
	
	
	//发送验证邮件
	private function send($uid, $uname, $email){
		
		$ecode = base64_encode($email);
		
		$code = random(6);
		$link = URI_PATH.'/getpwd/reset?e='.urlencode($ecode).'&c='.$code;
		
		
		//邮件内容
		$content = ''
		. '<p>尊敬的非尚网用户，<b>'.$uname.' ：</b></p>'
		. '<p>您的邮箱验证码为：<b>'. $code .'</b></p>'
		. '<p>您也可以点击如下链接来完成密码修改。</p>'
		. '<p><a href="'.$link.'" target="_blank">'.$link.'</a></p>'
		. '<p>如果上面的链接无法点击，您也可以复制链接，粘贴到您浏览器的地址栏内，然后按“回车”键打开预设页面，完成相应功能。</p>'
		. '<p>验证将会在一小时后失效，请尽快完成邮箱验证，否则需要重新进行验证。</p>'
		. '<p>通过验证后，您可以通过该邮箱找回密码。</p>'
		. '<br />'
		. '<p>此邮件由系统自动发送，请不要回复该邮件。</p>';
		
		$title = '非尚密码修改';
		
		$mail = new \Mail();
		$f = $mail -> send($email, $title, $content);
		if ($f){
			$user_email = new md\user_email();
			$user_email -> add(array('uid' => $uid, 'email' => $email, 'code' => $code, 'type' => 'VRY'));
			return true;
		}
		return false;
	}
}