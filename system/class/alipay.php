<?php
if (!defined('START')) exit('No direct script access allowed');

/**
 +----------------------------
 *	支付宝_境外 收单接口类
 +----------------------------
 */
class AliPay {
	
	private $gateway = "https://mapi.alipay.com/gateway.do?";	//
	
	private $partner;							//
	private $security_code;						//
	
	private $service = 'create_forex_trade';	//
	
	private $notify_url;
	private $return_url;
	
	//初始化
	function __construct($partner, $security_code){
		
		$this -> partner = $partner;
		$this -> security_code = $security_code;
	}
	
	//设置通知地址 -- 后端
	function set_notify_url($url){
		$this -> notify_url = $url;
	}
	
	//设置返回地址 -- 前端
	function set_return_url($url){
		$this -> return_url = $url;
	}
	
	/**
	 *	生成支付代码
	 *	$data = [order_id,order_sn,order_amount]
	 */
	function create_url($data){
		
		$params = array(
			'service'		=> $this -> service,
			'partner'		=> $this -> partner,
			'notify_url'	=> $this -> notify_url,
			'return_url'	=> $this -> return_url,
			'subject'		=> 'FS-MALL ORDER:'.$data['order_sn'],
			'out_trade_no'	=> $data['order_id'], 
			'currency'		=> 'USD',
			'rmb_fee'		=> $data['order_amount'],	//RMB价格 如：20.00
			'_input_charset'=> 'utf-8',
		);
		
		$params['sign'] = $this -> sign($params);
		$params['sign_type'] = 'MD5';
		
		return $this -> gateway . http_build_query($params);
	}
	
	//过滤掉空值或不需要的值
	function param_filter($parameter) {
		$params = array();
		while (list ($key, $val) = each ($parameter)) {
			if ($key == "sign" || $key == "sign_type" || $val == "") {
				continue;
			} else {
				$params[$key] = $parameter[$key];
			}
		}
		return $params;
	}
	
	//签名
	function sign($params){
		$params = $this -> param_filter($params);
		
		ksort($params);
		reset($params);
		$str = $dot = '';
		foreach ($params as $key => $val){
			$str .= $dot.$key.'='.$val;
			$dot = '&';
		}
		$str .= $this -> security_code;
		return md5($str);
	}
	
	
	//验证返回值数据
	function verify_result($data){
		
		if (empty($data['sign'])){
			return false;
		}
		unset($data['sign']);
		
		$sign = $this -> sign($data);
		
		if ($sign == $data['sign']){
			return true; 
		} else {
			return false;
		}
	}
	
}

