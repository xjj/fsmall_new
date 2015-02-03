<?php
namespace model;

/**
 +---------------------------
 *	支付记录
 +---------------------------
 */
class Pay_Log extends front {
	
	//添加记录
	function add($data){
		return $this -> db -> insert('pay_log', array(
			'order_id' => $data['order_id'],
			'uid' => $data['uid'],
			'ip' => $data['ip'],
			'pay_amount' => $data['pay_amount'],
			'pay_code' => $data['pay_code'],
			'pay_time' => time(),
		)); 
	}
	
	//创建日志文件
	function log_to_file($data){
		
		$pay_code = $data['pay_code'];
		$order_sn = $data['order_sn'];
		$order_amount = $data['order_amount'];
		$order_amount = format_money($order_amount);
		
		$dir = LOG_PATH . '/pay/'.date('Y/md');
		makedir($dir);
		$file = $dir.'/'.$order_sn.'.txt';
		$content = $order_sn.'|'.$order_amount.'|'.strtoupper($pay_code).'|CNY|'.date('YmdHis'). PHP_EOL;
		file_put_contents($file, $content, FILE_APPEND);	
	}
}