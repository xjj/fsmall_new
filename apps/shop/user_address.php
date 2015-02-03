<?php
namespace shop;

use model as md;

/**
 +-----------------------------------
 *	收货地址
 +-----------------------------------
 */
class User_Address extends Front {
	
	//列表
	function index(){
		$this -> items();
	}
	
	function items(){
		$uid = $_SESSION['uid'];
		$address = new md\user_address();
		$address_items = $address -> items($uid);
		
		//获取地区信息 - 省级
		$region = new md\region();
		$province_items = $region -> provinces();
		$city_items = $county_items = false;
		
		//编辑时获取收获地址信息
		$addr_id = intval($this->params[0]);
		if ($addr_id > 0){
			$address_data = $address -> info($addr_id);
			if ($address_data){} else {
				cpmsg(false, '该信息不存在或已被删除！', -1);
			}
			
			//获取地区信息 - 市
			$city_items = $region -> citys($address_data['province_id']);
			
			//获取地区信息 - 县区
			$county_items = $region -> countys($address_data['city_id']);	
		}
		
		
		$this -> smarty -> assign('title', '收货地址');
		$this -> smarty -> assign('address_items', $address_items);
		$this -> smarty -> assign('province_items', $province_items);
		$this -> smarty -> assign('city_items', $city_items);
		$this -> smarty -> assign('county_items', $county_items);
		$this -> smarty -> assign('address_data', $address_data);
		$this -> smarty -> display('user/address.tpl');	
	}
	
	//添加
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
			exit();
		} else {
			cpurl('/user/address');	
		}
	}
	
	//添加
	private function add_submit(){
		$uid = $_SESSION['uid'];
		
		$province_id = intval($_POST['province_id']);
		$city_id = intval($_POST['city_id']);
		$county_id = intval($_POST['county_id']);
		
		$address = trim($_POST['address']);
		$consignee = trim($_POST['consignee']);
		$zipcode = intval($_POST['zipcode']);
		$mobile = trim($_POST['mobile']);
		
		if (empty($consignee)){
			cpmsg(false, '请输入收货人姓名！', -1);
		}
		if ($province_id <= 0){
			cpmsg(false, '请选择所在地的省/直辖市！', -1);
		}
		if ($city_id <= 0){
			cpmsg(false, '请选择所在地的城市！', -1);
		}
		if ($county_id <= 0){
			cpmsg(false, '请选择所在地的县区！', -1);
		}
		if (empty($address)){
			cpmsg(false, '请填写收货具体地址！', -1);
		}
		if (empty($zipcode)){
			cpmsg(false, '请填写邮编，如果不知道所在地的邮编，百度一下吧！', -1);
		}
		if (empty($mobile)){
			cpmsg(false, '请填写手机或电话，这个是必须的！', -1);
		}
		
		$addr = new md\user_address();
		$f = $addr -> add(array(
			'uid' => $uid,
			'province_id' => $province_id,
			'city_id' => $city_id,
			'county_id' => $county_id,
			'address' => $address,
			'consignee' => $consignee,
			'zipcode' => $zipcode,
			'mobile' => $mobile,
		));
		
		if ($f){
			cpmsg(true, '收货地址添加成功！', '/user/address');
		} else {
			cpmsg(false, '收货地址添加失败！', -1);
		}
	}
	
	
	//编辑
	function edit(){
		if (isset($_POST['submit'])){
			$this -> edit_submit();
			exit();
		} else {
			cpurl('/user/address');
		}
	} 
	
	//编辑操作
	private function edit_submit(){
		$uid = $_SESSION['uid'];
		
		$addr_id = intval($_POST['addr_id']);
		$province_id = intval($_POST['province_id']);
		$city_id = intval($_POST['city_id']);
		$county_id = intval($_POST['county_id']);
		
		$address = trim($_POST['address']);
		$consignee = trim($_POST['consignee']);
		$zipcode = intval($_POST['zipcode']);
		$mobile = trim($_POST['mobile']);
		
		
		if (!isint($addr_id) || $addr_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		if ($province_id <= 0){
			cpmsg(false, '请选择所在地的省/直辖市！', -1);
		}
		if ($city_id <= 0){
			cpmsg(false, '请选择所在地的城市！', -1);
		}
		if ($county_id <= 0){
			cpmsg(false, '请选择所在地的县区！', -1);
		}
		if (empty($consignee)){
			cpmsg(false, '请输入收货人姓名！', -1);
		}
		if (empty($address)){
			cpmsg(false, '请填写收货具体地址！', -1);
		}
		if (empty($zipcode)){
			cpmsg(false, '请填写邮编，如果不知道所在地的邮编，百度一下吧！', -1);
		}
		if (empty($mobile)){
			cpmsg(false, '请填写手机或电话，这个是必须的！', -1);
		}
		
		$addr = new md\user_address();
		$f = $addr -> update($addr_id, array(
			'uid' => $uid,
			'province_id' => $province_id,
			'city_id' => $city_id,
			'county_id' => $county_id,
			'address' => $address,
			'consignee' => $consignee,
			'zipcode' => $zipcode,
			'mobile' => $mobile,
		));
		
		if ($f){
			cpmsg(true, '收货地址编辑成功！', '/user/address');
		} else {
			cpmsg(false, '收货地址编辑失败！', -1);
		}
	}
	
	/**
	 *	删除
	 */
	private function del(){
		$addr_id  = $this -> params[1];
		$uid = $_SESSION['uid'];
		
		if (!isint($addr_id) || $addr_id <= 0){
			cpmsg(false, '错误的请求地址！', -1);
		}
		
		$addr = new md\user_address();
		$f = $addr -> delete($addr_id, $uid);
		if ($f){
			cpurl('/user/address');
		} else {
			cpmsg(false, '删除收货地址失败！', -1);
		}
	} 
	
	//设置默认收获地址
	function setDefault(){
		$addr_id = $this->params[1];
		
		if (!isint($addr_id) || $addr_id <= 0){
			cpmsg(false, '错误的请求地址！', -1);	
		}	
		
		$address = new md\user_address();
		$address_data = $address -> info($addr_id);
		if ($address_data){} else {
			cpmsg(false, '该收获地址信息已不存在或已被删除！', -1);	
		}
		
		if ($address_data['uid'] != $_SESSION['uid']){
			cpmsg(false, '错误的请求地址！', -1);	
		}
		
		$address -> setDefault($_SESSION['uid'], $addr_id);
		cpurl('/user/address');
	}
}