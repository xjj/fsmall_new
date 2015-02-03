<?php
namespace shop;

use model as md;

/**
 +------------------------------
 *	支付控制器
 +------------------------------
 */

class Pay extends Front {
	
	//支付跳转页面
	function index(){
		if (!$this -> islogin){
			$login = new login();
			$login -> index();
			exit();
		}
		
		$uid = $_SESSION['uid'];
		
		$order_sn = $this -> params[0];
		
		if (!isint($order_sn) || $order_sn <= 0){
			cpmsg(false, '错误的请求地址，请检查链接是否正确！', -1);
		}
		
		$this_order_page = '/order/'.$order_sn; 
		
		//查询订单信息
		$order = new md\order();
		$order_data = $order -> get_info_by_sn($order_sn);
		if ($order_data){} else {
			cpmsg(false, '您要支付的订单不存在或被删除了！', $this_order_page);
		}
		
		$order_id = $order_data['order_id'];
		$pay_code = strtolower($order_data['pay_code']);
		$order_amount = $order_data['order_amount'];
		
		
		//判断订单状态
		if ($order_data['order_status'] != 0){
			cpmsg(false, '该订单处于不能支付状态，请重新确认订单信息！', $this_order_page);
		}
		
		//判断订单所有者是否符合
		if ($uid != $order_data['uid']){
			cpmsg(false, '您不能给别人的订单支付！', -1);
		}
		
		//如果是余额支付的话，余额是否能够支付
		if ($pay_code == 'balance'){
			$balance = new md\balance();
			$balance_money = $balance -> fetch_user_balance($uid);
			if ($balance_money === false){
				cpmsg(false, '没有查询到用户的余额值！', $this_order_page);
			}
			
			if ($balance_money < $order_amount){
				cpmsg(false, '您的余额不足以支付您的订单金额~', $this_order_page);
			}
			
			$data = array(
				'order_id' => $order_id,
				'order_sn' => $order_sn,
				'order_amount' => format_money($order_amount),
				'uid' => $uid,
			);
			
			//余额支付单独处理
			$ret = $balance -> pay($data);
			if ($ret){
				//跳转到成功页面
				cpurl('/pay/success/balance/'.$order_sn.'');
			} else {
				cpmsg(false, '余额支付失败！', $this_order_page);
			}
			
			exit();
		}
		
		//调用支付接口开始支付
		switch ($pay_code){
			case 'alipay':
				$pay = new md\alipay();
				break;
			case 'unionpay':
				$pay = new md\unionpay();
				break;
		}
		
		$data = array(
			'order_id' => $order_id,
			'order_sn' => $order_sn,
			'order_amount' => format_money($order_amount),
		);
		
		$url = $pay -> pay($data);
		cpurl($url);
	}
	
	
	/**
	 *	支付回调 -- 后台
	 *	alipay,unionpay
	 */
	function respond(){
		$pay_code = $this -> params[0];
		$pay_code = strtolower($pay_code);
		
		if ($pay_code == 'alipay'){
			$this -> respond_alipay();
		} elseif ($pay_code == 'unionpay'){
			$this -> respond_unionpay();
		} else {
			cpmsg(false, '暂未设置该支付类型的回调程序，请与管理员联系！', '/');
		}
	}
	
	//境外支付宝回调函数
	private function respond_alipay(){
		$pay = new \alipay_jw();
		$f = $pay -> verify_result($_POST);
		if ($f){
			$order_id = $_POST['out_trade_no'];
			$order_amount = $_POST['rmb_fee'];
			if ($_POST['trade_status'] == 'TRADE_FINISHED'){
				$order = new md\order();
				$order_data = $order -> info($order_id);
				if ($order_data){
					$order_sn = $order_data['order_sn'];
				} else {
					return false;
				}
				
				$payment = new md\payment();
				$payment -> success(array(
					'pay_code' => 'alipay_jw',
					'pay_name' => '境外支付宝',
					'uid' => $uid,
					'order_id' => $order_id,
					'order_sn' => $order_sn,
					'order_amount' => $order_amount
				));
			}
		}
	}
	
	//银联卡回调函数
	private function respond_unionpay(){
		//验证数据签名
		$pay = new \unionpay();
		$f = $pay -> verify_result($_POST);
		if ($f){
			$order_sn = $_GET['orderNum'];
			$order_amount = intval($_GET['orderAmount']);
			$order_amount = $order_amount / 100;
			
			if ($_GET['RespCode'] == '00'){
				$order = new md\order();
				$order_data = $order -> get_info_by_order_sn($order_sn);
				if ($order_data){
					$order_id = $order_data['order_id'];
				} else {
					return false;
				}
				
				$payment = new md\payment();
				$payment -> success(array(
					'pay_code' => 'unionpay',
					'pay_name' => '银联支付',
					'uid' => $uid,
					'order_id' => $order_id,
					'order_sn' => $order_sn,
					'order_amount' => $order_amount
				));
			}	
		}
	}
	
	//支付成功提示页面 -- 前台
	function success(){
		$pay_code = $this -> params[0];
		$pay_code = strtolower($pay_code);
		
		$this -> smarty -> display('pay_success.tpl');
	}
}