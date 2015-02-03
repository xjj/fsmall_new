<?php
if (!defined('START')) exit('No direct script access allowed');

require_once(SYSTEM_PATH.'/libs/phpmailer/class.phpmailer.php');

/*
 +------------------------------
 *	邮件发送类
 +------------------------------
 */
class Mail {
	private $PHPMailer;
	private  $config;
	
	function __construct($config = array()){
		if (empty($config)){
			global $_G;
			$config = $_G['MAIL'];
		}
		$this -> config = $config;
		$this -> PHPMailer = new PHPMailer();
		$this -> PHPMailer -> IsSMTP();
		$this -> PHPMailer -> Host 		= strtoupper($this -> config['SERVER']); 	
		$this -> PHPMailer -> SMTPAuth 	= true;
		$this -> PHPMailer -> Username 	= $this -> config['USERNAME'];
		$this -> PHPMailer -> Password 	= $this -> config['PASSWORD'];
		$this -> PHPMailer -> Port 		= 25;
		$this -> PHPMailer -> From 		= $this -> config['FROMMAIL'];
		$this -> PHPMailer -> FromName 	= $this -> config['FROMUSERNAME'];
		$this -> PHPMailer -> CharSet 	= "UTF-8";
		$this -> PHPMailer -> Encoding 	= "base64";
		$this -> PHPMailer -> IsHTML(true);
	}
	
	/*
	-----------------------------
		添加收件人
		$mail 		收件人邮箱
		$username	收件人姓名
	-----------------------------
	*/
	function addAddress($email, $username){
		$this -> PHPMailer -> addAddress($email, $username);
	}
	
	/*
	------------------------------
	邮件发送
	$address		接收邮箱 array(email,username) | 邮箱地址
	$title			邮件标题
	$content		邮件内容
	------------------------------
	*/
	function send($address, $title, $content){
		$html  = "<!DOCTYPE html>\n";
		$html .= "<html>\n";
		$html .= "<head>\n";
		$html .= "<meta charset=\"utf-8\" />\n";
		$html .= "<title>{$title}</title>\n";
		$html .= "</head>\n";
		$html .= "<body>\n{$content}</body>\n";
		$html .= "</html>";
		
		if (is_array($address)){
			$email 	  = $address['email'];
			$username = $address['username'];
		} else {
			$email = $address;
			$username = '';
		}
		$this -> PHPMailer -> Subject = $title;
		$this -> PHPMailer -> addAddress($email, $username); 
		$this -> PHPMailer -> Body = $html;
		if (!$this -> PHPMailer -> Send()){
			//echo $this -> PHPMailer -> ErrorInfo;
			return false;
		} else {
			return true;
		}
	}
}
?>