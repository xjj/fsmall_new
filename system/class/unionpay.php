<?php
if (!defined('START')) exit('No direct script access allowed');
/**
 +------------------------------
 *	银联支付
 +------------------------------
 */
class UnionPay {
	private $gateway = 'https://pay.veritrans-link.com/epayment/payment?';
	private $frontURL;
	private $backURL;
	private $merID;					//
	private $acqID;					//收单行ID
	private $signKey;				//签名密钥
	private $paymentSchema = 'UP';	//
	
	/**
	 *	构造函数
	 */
	function __construct($merID, $acqID, $signKey){
		$this -> merID = $merID;
		$this -> acqID = $acqID;
		$this -> signKey = $signKey;
	}
	
	/**
	 *	设置前端地址
	 */
	function set_front_url($url){
		$this -> frontURL = $url;
	}
	
	/**
	 *	设置后端地址
	 */
	function set_back_url($url){
		$this -> backURL = $url;
	}
	
	/**
	 *	金额转换
	 *	$order_amount == CNY
	 */
	function format_amount($order_amount){
		$order_amount = number_format($order_amount, 2, '.', '')*100;
		$order_amount = str_pad($order_amount, 12, '0', STR_PAD_LEFT);
		return $order_amount; 
	}
	
	
	/**
	 *	请求接口
	 *	$command	
	 *	$data = [order_id, order_sn, order_amount]
	 */
	function create_url($data){
		$order_amount = $this -> format_amount($data['order_amount']);
		$params = array(
			'version'		=> 'VER000000001',
			'charSet'		=> 'UTF-8',
			'transType'		=> 'PURC',
			'orderNum'		=> $data['order_sn'],
			'orderAmount'	=> $order_amount,
			'orderCurrency' => 'CNY',
			'merReserve'	=> $data['order_id'],
			'frontURL'		=> $this -> frontURL,
			'backURL'		=> $this -> backURL,
			'merID'			=> $this -> merID,
			'acqID'			=> $this -> acqID,
			'paymentSchema' => $this -> paymentSchema,
			'transTime'		=> date('YmdHis'), 
			'signType'		=> 'MD5',
		);
		
		$params['signature'] = $this -> sign($params);
		
		return $this -> gateway . http_build_query($params);
	}
	
	//生成签名
	function sign($params){
		ksort($params);
		reset($params);
		$str = $dot = '';
		foreach ($params as $key => $val){
			$str .= $dot.$key.'='.$val;
			$dot = '&';
		}
		$str .= $this -> signKey;
		return md5($str);
	}
	
	//验证返回值数据
	function verify_result($data){
		$signature = $data['signature'];
		if (empty($signature)){
			return false;
		}
		unset($data['signature']);
		
		$sign = $this -> sign($data);
		
		if ($sign == $signature){
			return true; 
		} else {
			return false;
		}
	}
}
?>