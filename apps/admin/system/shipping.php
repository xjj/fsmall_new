<?php
namespace admin\system;

use admin as adm;
use model as md;

/**
 +----------------------------
 *	配送方式设置
 +----------------------------
 */
class Shipping extends adm\Front {
	
	//配送列表页
	function items(){
		$shipping = new md\shipping();
		$shipping_items = $shipping -> items('all');
		$this -> smarty -> assign('shipping_items', $shipping_items);
		$this -> smarty -> display('system/shipping_list.tpl');
	}
	
	//添加
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
		}
		
		$this -> smarty -> assign('more_navs', '添加配送方式');
		$this -> smarty -> display('system/shipping_add.tpl');
	}
	
	//添加操作
	private function add_submit(){
		$shipping_name = trim($_POST['shipping_name']);
		$shipping_code = trim($_POST['shipping_code']);
		$content = trim($_POST['content']);
		$is_pay = trim($_POST['is_pay']);
		$displayorder = trim($_POST['displayorder']);
		
		if (empty($shipping_name)){
			cpmsg(false, '请填写配送方式名称！', -1);
		}
		if (empty($shipping_code)){
			cpmsg(false, '请填写配送方式代码！', -1);
		}
		if ($is_pay != 1){$is_pay = 0;}
		if (!isint($displayorder) || $displayorder <= 0){$displayorder = 0;}
		
		$shipping = new md\shipping();
		$f = $shipping -> add(array(
			'shipping_name' => $shipping_name,
			'shipping_code' => $shipping_code,
			'content' => $content,
			'is_pay' => $is_pay,
			'displayorder' => $displayorder,
		));
		
		if ($f){
			cpmsg(true, '配送方式添加成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '配送方式添加失败！', -1);
		}
	}
	
	//编辑
	function edit(){
		if (isset($_POST['submit'])){
			$this -> edit_submit();
		}
		
		$shipping_id = $this -> params[1];
		if (!isint($shipping_id) || $shipping_id <= 0){
			cpmsg(false, '缺少重要参数！', -1);
		}
		$shipping = new md\shipping();
		$shipping_data = $shipping -> info($shipping_id);
		if ($shipping_data){} else {
			cpmsg(false, '该配送方式不存在或已被删除！', -1);
		}
		
		$this -> smarty -> assign('more_navs', '编辑配送方式');
		$this -> smarty -> assign('shipping_data', $shipping_data);
		$this -> smarty -> display('system/shipping_edit.tpl');
	}
	
	//编辑配送方式
	//是否到付不可修改
	private function edit_submit(){
		$shipping_id = $_POST['shipping_id'];
		if (!isint($shipping_id) || $shipping_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
	
		$shipping_name = trim($_POST['shipping_name']);
		$shipping_code = trim($_POST['shipping_code']);
		$content = trim($_POST['content']);
		$displayorder = trim($_POST['displayorder']);
		
		if (empty($shipping_name)){
			cpmsg(false, '请填写配送方式名称！', -1);
		}
		if (empty($shipping_code)){
			cpmsg(false, '请填写配送方式代码！', -1);
		}
		if (!isint($displayorder) || $displayorder <= 0){$displayorder = 0;}
		
		$shipping = new md\shipping();
		$num = $shipping -> update($shipping_id, array(
			'shipping_name' => $shipping_name,
			'shipping_code' => $shipping_code,
			'content' => $content,
			'displayorder' => $displayorder,
		));
		if ($num > 0){
			cpmsg(true, '配送方式编辑成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '配送方式编辑失败，没有信息被修改！', -1);
		}
	}
	
	//删除
	function del(){
		$shipping_id = $this -> params[1];
		if (!isint($shipping_id) || $shipping_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		$shipping = new md\shipping();
		$f = $shipping -> delete($shipping_id);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '配送方式删除失败！', -1);
		}
	}
	
	//设置为有效
	function show(){
		$shipping_id = $this -> params[1];
		if (!isint($shipping_id) || $shipping_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		$shipping = new md\shipping();
		$f = $shipping -> validate($shipping_id, 1);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '配送方式设置失败！', -1);
		}
	}
	
	//设置为无效
	function hide(){
		$shipping_id = $this -> params[1];
		if (!isint($shipping_id) || $shipping_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		$shipping = new md\shipping();
		$f = $shipping -> validate($shipping_id, 0);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '配送方式设置失败！', -1);
		}
	}
	
	//地区费用列表
	function fee(){
		$p = $this -> params[2];
		if (!in_array($p, array('add', 'edit', 'del', 'items'))){
			$p = 'items';
		}
		$method = 'fee_'.$p;
		$this -> $method();
	}
	
	//地区费用列表
	function fee_items(){
		$shipping_id = $this -> params[1];
		if (!isint($shipping_id) || $shipping_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$shipping = new md\shipping();
		$shipping_data = $shipping -> info($shipping_id);
		if ($shipping_data){} else {
			cpmsg(false, '该配送方式不存在或已被删除！', -1);
		}
		
		$shipping_fee = new md\shipping_fee();
		$shipping_fee_items = $shipping_fee -> items($shipping_id);
		
		$this -> smarty -> assign('more_navs', '配送费用列表');
		$this -> smarty -> assign('shipping_data', $shipping_data);
		$this -> smarty -> assign('shipping_fee_items', $shipping_fee_items);
		$this -> smarty -> display('system/shipping_fee_list.tpl');
	}
	
	
	//地区费用添加
	function fee_add(){
		if (isset($_POST['submit'])){
			$this -> fee_add_submit();
		}
		
		$shipping_id = $this -> params[1];
		if (!isint($shipping_id) || $shipping_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$shipping = new md\shipping();
		$shipping_data = $shipping -> info($shipping_id);
		if ($shipping_data){} else {
			cpmsg(false, '该配送方式不存在或已被删除！', -1);
		}
		
		$region = new md\region();
		$province_items = $region -> provinces(1);
		
		$shipping_fee = new md\shipping_fee();
		$ids = $shipping_fee -> fee_regions($shipping_id);
		
		//去除--已被设置的地区
		$data = array();
		foreach ($province_items as $item){
			if (!in_array($item['region_id'], $ids)){
				$data[] = $item;
			}
		}
		
		if (empty($data)){
			cpmsg(false, '该配送方式的所有地区都已设置！', -1);
		}
		
		$this -> smarty -> assign('more_navs', '添加配送费用信息');
		$this -> smarty -> assign('shipping_data', $shipping_data);
		$this -> smarty -> assign('province_items', $data);
		$this -> smarty -> display('system/shipping_fee_add.tpl');
	}
	
	//配送费用添加操作
	private function fee_add_submit(){
		$fee_name = trim($_POST['fee_name']);
		$shipping_id = trim($_POST['shipping_id']);
		$fee_base = trim($_POST['fee_base']);
		$fee_step = trim($_POST['fee_step']);
		$free_amount = trim($_POST['free_amount']);
		$region_id = $_POST['region_id'];
		
		if (!isint($shipping_id) || $shipping_id <= 0){
			cpmsg(false, '重要参数缺失，未知的配送方式！', -1);	
		}
		if (empty($fee_name)){
			cpmsg(false, '请输入配送费用名称！', -1);
		}
		if (!isint($fee_base) || $fee_base < 0){
			cpmsg(false, '请填写首重费用！', -1);
		}
		if (!isint($fee_step) || $fee_step < 0){
			cpmsg(false, '请填写续重费用！', -1);
		}
		if (!isint($free_amount) || $free_amount < 0){$free_amount = 0;}
		if (empty($region_id)){
			cpmsg(false, '请选择所辖地区！', -1);
		}
		
		//判断地区id
		$ids = array();
		foreach ($region_id as $id){
			if (!empty($id)){$ids[] = $id;}
		}
		if (empty($ids)){
			cpmsg(false, '请选择所辖地区！', -1);
		}
		
		$shipping_fee = new md\shipping_fee();
		
		$f = $shipping_fee -> add(array(
			'fee_name' => $fee_name,
			'shipping_id' => $shipping_id,
			'fee_base' => $fee_base,
			'fee_step' => $fee_step,
			'free_amount' => $free_amount,
			'region_id' => $ids
		));
		if ($f){
			cpmsg(true, '配送费用信息添加成功！', '/'.$this->mod.'/'.$this->col.'/fee/'.$shipping_id);
		} else {
			cpmsg(false, '配送费用信息添加失败!', -1);
		}
	}
	
	
	//地区费用编辑
	function fee_edit(){
		if (isset($_POST['submit'])){
			$this -> fee_edit_submit();
		}
		
		$fee_id = $this -> params[3];
		if (!isint($fee_id) || $fee_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		//查询配送费用信息
		$shipping_fee = new md\shipping_fee();
		$shipping_fee_data = $shipping_fee -> info($fee_id);
		if ($shipping_fee_data){
			$region_ids = $shipping_fee_data['region_id'];
			if (!empty($region_ids)){
				$shipping_fee_data['region_ids'] = explode(',', $region_ids);
			}
		} else {
			cpmsg(false, '该配送费用信息不存在或已被删除！', -1);
		}
		
		//查询配送方式信息
		$shipping = new md\shipping();
		$shipping_data = $shipping -> info($shipping_fee_data['shipping_id']);
		if ($shipping_data){} else {
			cpmsg(false, '该配送方式不存在或已被删除！', -1);
		}
		
		//查询所有省份
		$region = new md\region();
		$province_items = $region -> provinces(1);
		
		//查询所有已设置的地区
		$ids = $shipping_fee -> fee_regions($shipping_fee_data['shipping_id'], $fee_id);
		
		$data = array();
		foreach ($province_items as $item){
			if (!in_array($item['region_id'], $ids)){
				if (in_array($item['region_id'], $shipping_fee_data['region_ids'])){
					$item['checked'] = 1;
				} else {
					$item['checked'] = 0;
				}
				$data[] = $item;
			}
		}
		
		$this -> smarty -> assign('more_navs', '编辑配送费用信息');
		$this -> smarty -> assign('shipping_data', $shipping_data);
		$this -> smarty -> assign('shipping_fee_data', $shipping_fee_data);
		$this -> smarty -> assign('province_items', $data);
		$this -> smarty -> display('system/shipping_fee_edit.tpl');
	}
	
	//编辑操作
	private function fee_edit_submit(){
		$fee_name = trim($_POST['fee_name']);
		$shipping_id = trim($_POST['shipping_id']);
		$fee_base = trim($_POST['fee_base']);
		$fee_step = trim($_POST['fee_step']);
		$free_amount = trim($_POST['free_amount']);
		$region_id = $_POST['region_id'];
		$fee_id = trim($_POST['fee_id']);
		
		if (!isint($fee_id) || $fee_id <= 0 || !isint($shipping_id) || $shipping_id <= 0){
			cpmsg(false, '错误的请求！', -1);	
		}
		if (empty($fee_name)){
			cpmsg(false, '请输入地区名称！', -1);
		}
		if (!isint($fee_base) || $fee_base < 0){
			cpmsg(false, '请填写首重费用！', -1);
		}
		if (!isint($fee_step) || $fee_step < 0){
			cpmsg(false, '请填写续重费用！', -1);
		}
		if (!isint($free_amount) || $free_amount < 0){$free_amount = 0;}
		if (empty($region_id)){
			cpmsg(false, '请选择所辖地区！', -1);
		}
		
		//判断地区id
		$ids = array();
		foreach ($region_id as $id){
			if (!empty($id)){$ids[] = $id;}
		}
		if (empty($ids)){
			cpmsg(false, '请选择所辖地区！', -1);
		}
		
		$shipping_fee = new md\shipping_fee();
		$f = $shipping_fee -> update($fee_id, array(
			'fee_name' => $fee_name,
			'shipping_id' => $shipping_id,
			'fee_base' => $fee_base,
			'fee_step' => $fee_step,
			'free_amount' => $free_amount,
			'region_id' => $ids
		));
		if ($f){
			cpmsg(true, '配送费用信息编辑成功！', '/'.$this->mod.'/'.$this->col.'/fee/'.$shipping_id);
		} else {
			cpmsg(false, '配送费用信息编辑失败!', -1);
		}
	}
	
	//地区费用删除
	function fee_del(){
		$fee_id = $this -> params[3];
		$shipping_id = $this -> params[1];
		if (!isint($fee_id) || $fee_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		$shipping_fee = new md\shipping_fee();
		$num = $shipping_fee -> delete($fee_id);
		if ($num > 0){
			cpurl('/'.$this->mod.'/'.$this->col.'/fee/'.$shipping_id);
		} else {
			cpmsg(false, '配送费用信息删除失败!', -1);
		}
	}
}