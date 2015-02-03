<?php
namespace admin\system;

if (!defined('START')) exit('No direct script access allowed');

use admin as adm;
use model as md;

/**
 +-------------------------------
 *	支付信息设置
 +-------------------------------
 */

class Payment extends adm\Front {
	
	//支付列表页
	function items(){
		$payment = new md\payment();
		$payment_items = $payment -> items('all');
		
		$this -> smarty -> assign('payment_items', $payment_items);
		$this -> smarty -> display('system/payment_list.tpl');
	}
	
	//添加支付信息页
	function add(){
		if (isset($_POST['submit'])){
			//添加处理部分
			$this -> add_submit();
		}
		
		//输出页面部分
		$this -> smarty -> assign('more_navs', '添加支付信息');
		$this -> smarty -> display('system/payment_add.tpl');
	}
	
	//添加支付操作
	function add_submit(){
		$pay_name = trim($_POST['pay_name']);
		$pay_code = trim($_POST['pay_code']);
		$content  = trim($_POST['content']);
		
		$keys = $_POST['keys'];
		$vals = $_POST['vals'];
		
		if (empty($pay_name)){
			cpmsg(false, '请填写支付名称！', -1);
		}
		
		if (empty($pay_code)){
			cpmsg(false, '请填写支付代码！', -1);
		}
		
		if (!is_array($keys) || !is_array($vals)){
			cpmsg(false, '请填写支付参数，这个是必须的。', -1);
		}
		
		$conf = array();
		foreach ($keys as $k => $v){
			$key = trim($v);
			$val = trim($vals[$k]);
			if ($key !== ''){
				$conf[$key] = $val;
			}
		}
		
		if (empty($conf)){
			cpmsg(false, '支付参数不能为空，请填写..', -1);
		}
		
		$payment = new md\payment();
		$f = $payment -> isExist_pay_code($pay_code);
		if ($f){
			cpmsg(false, '该支付代码已存在，请更换一个。', -1);
		}
		
		$f = $payment -> add(array(
			'pay_name' => $pay_name,
			'pay_code' => $pay_code,
			'content' => $content,
			'conf' 	  => $conf,
		));
		
		if ($f){
			cpmsg(true, '支付信息添加成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '支付信息添加失败！', -1);	
		}
	}
	
	//编辑支付信息页
	function edit(){
		if (isset($_POST['submit'])){
			//编辑部分
			$this -> edit_submit();
		}
		
		//输出页面部分
		$pay_id = $this -> params[1];
		$payment = new md\payment();
		$payment_data = $payment -> info($pay_id);
		if ($payment_data){} else {
			cpmsg(false, '没有查询到该支付数据。', -1);
		}
		
		$this -> smarty -> assign('more_navs', '编辑支付信息');
		$this -> smarty -> assign('payment_data', $payment_data);
		$this -> smarty -> display('system/payment_edit.tpl');
	}
	
	//编辑操作
	private function edit_submit(){
		$pay_id = $_POST['pay_id'];
		if (!isint($pay_id) || $pay_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$pay_name = trim($_POST['pay_name']);
		$pay_code = trim($_POST['pay_code']);
		$content = trim($_POST['content']);
		
		$keys = $_POST['keys'];
		$vals = $_POST['vals'];
		
		if (empty($pay_name)){
			cpmsg(false, '请填写支付名称！', -1);
		}
		
		if (empty($pay_code)){
			cpmsg(false, '请填写支付代码！', -1);
		}
		
		if (!is_array($keys) || !is_array($vals)){
			cpmsg(false, '请填写支付参数，这个是必须的。', -1);
		}
		
		$conf = array();
		foreach ($keys as $k => $v){
			$key = trim($v);
			$val = trim($vals[$k]);
			if ($key !== ''){
				$conf[$key] = $val;
			}
		}
		
		if (empty($conf)){
			cpmsg(false, '支付参数不能为空，请填写..', -1);
		}
		
		$payment = new md\payment();
		$f = $payment -> isExist_pay_code($pay_code, $pay_id);
		if ($f){
			cpmsg(false, '该支付代码已存在，请更换一个。', -1);
		}
		
		$num = $payment -> update($pay_id, array(
			'pay_name' => $pay_name,
			'pay_code' => $pay_code,
			'content' => $content,
			'conf'	  => $conf
		));
		
		if ($num > 0){
			cpmsg(true, '支付信息编辑成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '支付信息编辑失败，该支付信息不存在或已被删除！', -1);	
		}
	}
	
	//设置为有效
	function show(){
		$pay_id = $this -> params[1];
		if (!isint($pay_id) || $pay_id <= 0){
			cpmsg(false, '缺少支付信息ID，请检查..', -1);
		}
		
		$payment = new md\payment();
		$payment -> validate($pay_id, 1);
		
		cpurl('/'.$this->mod.'/'.$this->col);
	}
	
	//设置为无效
	function hide(){
		$pay_id = $this -> params[1];
		if (!isint($pay_id) || $pay_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$payment = new md\payment();
		$payment -> validate($pay_id, 0);
		
		cpurl('/'.$this->mod.'/'.$this->col);
	}
	
	//删除
	function del(){
		$pay_id = $this -> params[1];
		if (!isint($pay_id) || $pay_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$payment = new md\payment();
		$payment -> delete($pay_id);
		
		cpurl('/'.$this->mod.'/'.$this->col);
	}
}