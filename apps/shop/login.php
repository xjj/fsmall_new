<?php
namespace shop;

if (!defined('START')) exit('No direct script access allowed');

use model as md;

/**
 +----------------------------
 *	登录控制器
 +----------------------------
 */
class Login extends Front {
	
	private $expired = 604800;			//过期秒数
	private $path	 = '/';				//COOKIE保存路径
	private $domain	 = '';				//COOKIE保存域名
	
	//登录页面
	function index(){
		if (isset($_POST['submit'])){
			$this -> submit();
			exit();
		}
	
		$this -> smarty -> assign('title', '会员登录');
		$this -> smarty -> display('login.tpl');
	}
	
	//验证登陆
	private function submit(){
		
		$uname = trim($_POST['uname']);		//用户名
		$upass = $_POST['upass'];			//密码
		$remember = $_POST['remember'];
		
		if (empty($uname)){
			cpmsg(false, '请输入用户名或邮箱！', -1);
		}
		if (empty($upass)){
			cpmsg(false, '请输入登录密码！', -1);
		}
		
		$login = new md\login();
		$data = $login -> verify($uname, $upass);
		if ($data){
			$uid = $data['uid'];
			$email = $data['email'];
			
			//判断帐号状态 -- 是否激活
			if ($data['status'] == 0){
				$code = base64_encode($email);
				
				//标记是否记住密码 -- 在状态激活后会用到
				$_SESSION['login_remember'] = $remember;
				
				cpmsg(false, '您的帐号尚未激活，请完成邮箱验证，激活帐号！', '/register/verify?e='.urlencode($code));
			}
			
			//保存登录信息
			$udata = array(
				'uid' 	=> $data['uid'],
				'uname' => $data['uname'],
				'email' => $data['email']
			);
			
			$this -> session($uid, $udata);
			if ($remember == 1){
				$this -> cookies($uid);
			}
			
			$login -> record_login($uid, $udata);
			
			//跳转
			$url = $_SERVER['HTTP_REFERER'];	//来源地址
			if (empty($url) || strpos($url, '/login')){
				$url = '/user';
			}
			cpurl($url);
		} else {
			cpmsg(false, '用户名或密码不正确！', -1);
		}
	}
	
	
	//自动登录
	function autologin(){
		
		if (!empty($_COOKIE['uid']) && $_COOKIE['expired'] > time()){
			$uid = $_COOKIE['uid'];
			$verify = $_COOKIE['verify'];
			
			$encrypt = new \encrypt();
			$uid2  = $encrypt -> decode($verify);
			if (($uid2 == $uid) && isint($uid) && $uid > 0){
				$user = new md\user();
				$data = $user -> info($uid);
				$udata = array(
					'uid' => $uid,
					'uname' => $data['uname'],
					'email' => $data['email'],
					'grade_id' => $data['grade_id'],
				);
				$this -> session($uid, $udata);
				$this -> cookies($uid);
				
				$login = new md\login();
				$login -> record_login($uid, $udata);
				
				return true;
			}
		} 
		return false;
	}
	
	//设置session
	function session($uid, $data = array()){
		if (empty($data)){
			$user = new md\user();
			$data = $user -> info($uid);	
		}
		
		$_SESSION['uid']   = $data['uid'];
		$_SESSION['uname'] = $data['uname'];
		$_SESSION['email'] = $data['email'];
		$_SESSION['grade_id'] = $data['grade_id'];
	}
	
	//设置cookie
	function cookies($uid){
		$expired = time() + $this -> expired;
		$encrypt = new \encrypt();
		$verify  = $encrypt -> encode($uid, $this -> expired);
		
		setcookie("uid",	 	$uid,   	$expired, $this -> path, $this -> domain);
		setcookie("expired", 	$expired,	$expired, $this -> path, $this -> domain);
		setcookie("verify",  	$verify,	$expired, $this -> path, $this -> domain);
	}
	
	//注销
	function destroy(){
		session_destroy();
		$seconds = time()-3600;
		setcookie("uid",   	 '', $seconds, $this -> path, $this -> domain);
		setcookie("expired", '', $seconds, $this -> path, $this -> domain);
		setcookie('verify',  '', $seconds, $this -> path, $this -> domain);
	}
	
	//qq登录
	function qq(){
		require_once(SYSTEM_PATH."/class/qqlogin.php");
		$qc = new \QC();//\QC
		$qc->qq_login();//跳到请求地址
	}
	//回调
	function qqcallback(){
		require_once(SYSTEM_PATH."/class/qqlogin.php");
		$qc = new \QC();//\QC
		$acs = $qc->qq_callback();//回调 
		
		$open_id =  $qc->get_openid();
		
		$qc = new \QC($acs,$open_id);
    	$uinfo = $qc->get_user_info();

		$nickname = $uinfo['nickname'];//昵称
		//根据 OPEN_ID 判断数据
    	$userAuth = new md\userauth(); 
		$row = $userAuth -> search(array('open_id'=>$open_id));
		
		if($row['total']>=1)
		{
			//曾经登陆过 有保存的授权,存在信息 ,调用信息登录
			$rowa= $row['items'][0];
			$uid = $rowa['uid'];
			$uname = $rowa['uname'];
			$email = $rowa['email'];
			$password = $rowa['upass'];
			//保存登录信息
			$udata = array('uid' => $uid, 'uname' => $uname, 'email' => $email);
			$login1 = new Login();
			$re= $login1 -> session($uid, $udata);
			
			$login = new md\login();
			$res= $login -> record_login($uid, $udata);
			if ($_SESSION['login_remember'] == 1){
				$login1 -> cookies($uid, $udata);
				unset($_SESSION['login_remember']);
			}
			cpmsg(true, '登录成功！', '/user');

		}else{   //open_id记录 入表
			//注册新用户 直接登录过去  
			$user = new md\user();
			$email = 'qq_login'.time().'@qq.com';//
			$uname=  'qq_'.$nickname;
			//创建帐号信息
			$uid = $user -> add(array('uname' => $uname, 'email' => $email, 'upass' => '123456'));
			if ($uid){
				//记录
				$data= array('open_id'=>$open_id,
								'uid'=>$uid,
								'type'=>'qq',
								'expires_in'=>3600*24*90,//3个月
								'access_token'=>$acs,
							);
				$r = $userAuth -> add($data);
				if($r){
					
					//var_dump($r);die;
					//保存登录信息
					$udata = array('uid' => $uid, 'uname' => $uname, 'email' => $email);
					$login1 = new Login();
					$re= $login1 -> session($uid, $udata);
					
					$login = new md\login();
					$res= $login -> record_login($uid, $udata);
					if ($_SESSION['login_remember'] == 1){
						$login1 -> cookies($uid, $udata);
						unset($_SESSION['login_remember']);
					}
					cpmsg(true, '登录成功！', '/user');
				}else{
					cpmsg(false, '抱歉，授权信息写入数据库失败！', -1);
					
				}
			} else {
				cpmsg(false, '抱歉，注册信息写入数据库失败！', -1);
			}
		}
	}
	
	//alipay登录
	function alipay(){
		require_once(SYSTEM_PATH."/class/alipay_login.php");
		//防钓鱼时间戳
		$anti_phishing_key  = '';
		//获取客户端的IP地址，建议：编写获取客户端IP地址的程序
		$exter_invoke_ip = '';
		//构造要请求的参数数组
		$parameter = array(
				"service"			=> "alipay.auth.authorize",
				"target_service"	=> 'user.auth.quick.login',
				
				"partner"			=> trim($aliapy_config['partner']),
				"_input_charset"	=> trim(strtolower($aliapy_config['input_charset'])),
				"return_url"		=> trim($aliapy_config['return_url']),
		
				"anti_phishing_key"	=> $anti_phishing_key,
				"exter_invoke_ip"	=> $exter_invoke_ip
		);
		//构造快捷登录接口
		$alipayService = new \AlipayService($aliapy_config);
		$html_text = $alipayService->alipay_auth_authorize($parameter);
		echo $html_text;
	}
	
	//回调  alipay
	function alipay_callback(){
		require_once(SYSTEM_PATH."/class/alipay_login.php");
		//计算得出通知验证结果
		$alipayNotify = new \AlipayNotify($aliapy_config);
		$verify_result = $alipayNotify->verifyReturn();
		if($verify_result) {//验证成功

			$user_id	= $_GET['user_id'];	//支付宝用户id
			$token		= $_GET['token'];	//授权令牌
			$real_name  = $_GET['real_name'];//昵称
			//根据 OPEN_ID 判断数据
			$userAuth = new md\userauth(); 
			$row = $userAuth -> search(array('open_id'=>$user_id));
			
			if($row['total']>=1)
			{	
				//曾经登陆过 有保存的授权,存在信息 ,调用信息登录
				$rowa= $row['items'][0];
				$uid = $rowa['uid'];
				$uname = $rowa['uname'];
				$email = $rowa['email'];
				$password = $rowa['upass'];
				//保存登录信息
				$udata = array('uid' => $uid, 'uname' => $uname, 'email' => $email);
				$login1 = new Login();
				$re= $login1 -> session($uid, $udata);
				
				$login = new md\login();
				$res= $login -> record_login($uid, $udata);
				if ($_SESSION['login_remember'] == 1){
					$login1 -> cookies($uid, $udata);
					unset($_SESSION['login_remember']);
				}
				cpmsg(true, '登录成功！', '/user');
			}else{
				//插入 
				$user = new md\user();
				$email = 'alipay_login'.time().'@alipay.com';
				//创建帐号信息
				$uid = $user -> add(array('uname' => 'alipay_'.$real_name, 'email' => $email, 'upass' => '123456'));
				if ($uid){
					if(empty($open_id)){  $open_id=time(); }
					//记录 open_id acs_token
					$data= array('open_id'=>$user_id,
									'uid'=>$uid,
									'type'=>'alipay',
									'expires_in'=>3600*24*90,//3个月
									'access_token'=>$token,
									 );
					$r = $userAuth -> add($data);
					if(!$r){
						cpmsg(false, '抱歉，注授权信息写入数据库失败！', -1);	
					}
					//保存登录信息
					$udata = array('uid' => $uid, 'uname' => $uname, 'email' => $email);
					$login1 = new Login();
					$re= $login1 -> session($uid, $udata);
					//var_dump($_SESSION);
					$login = new md\login();
					$res= $login -> record_login($uid, $udata);
					if ($_SESSION['login_remember'] == 1){
						$login1 -> cookies($uid, $udata);
						unset($_SESSION['login_remember']);
					}
					cpmsg(true, '登录成功！', '/user');
				} else {
					cpmsg(false, '抱歉，注册信息写入数据库失败！', -1);
				}
			}
		}
		else {
			//验证失败
			//如要调试，请看alipay_notify.php页面的verifyReturn函数，比对sign和mysign的值是否相等，或者检查$responseTxt有没有返回true
			echo "验证失败";
		}
	}
}