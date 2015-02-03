<?php
namespace model;

/**
 +--------------------------------
 *	收货地址类
 +--------------------------------
 */
class User_Address extends Front {
	
	//获取一条地址信息
	function info($addr_id, $uid = 0){
		if (!isint($addr_id) || $addr_id <= 0){return false;}
		$sql = 'SELECT * FROM `user_address` WHERE addr_id = '.$addr_id;
		if ($uid > 0){
			$sql .= ' AND uid = '.$uid.'';
		}
		return $this -> db -> row($sql); 
	}
	
	//添加一条记录
	function add($data){
		$uid = intval($data['uid']);
		$province_id = intval($data['province_id']);
		$city_id = intval($data['city_id']);
		$county_id = intval($data['county_id']);
		$address = trim($data['address']);
		$consignee = trim($data['consignee']);
		$zipcode = intval($data['zipcode']);
		$mobile = trim($data['mobile']);
		
		if ($province_id <= 0 || $city_id <= 0 || $county_id <= 0){return false;}
		if (empty($consignee)){return false;}
		if (empty($address)){return false;}
		if (empty($zipcode)){return false;}
		if (empty($mobile)){return false;}
		
		$region = new region();
		$province_name = $region -> get_name_by_id($province_id, 1);
		if ($province_name){} else {return false;}
		
		$city_name = $region -> get_name_by_id($city_id, 2);
		if ($city_name){} else {return false;}
		
		$county_name = $region -> get_name_by_id($county_id, 3);
		if ($county_name){} else {return false;}
		
		//查询地址信息数
		$addr_items = $this -> user_address_items($uid);
		if ($addr_items){
			$is_check = 1;
		} else {
			$is_check = 0;
		}
		
		return $this -> db -> insert('user_address', array(
			'uid' => $uid,
			'consignee' => $consignee,
			'province_id' => $province_id,
			'city_id' => $city_id,
			'county_id' => $county_id,
			'province_name' => $province_name,
			'city_name' => $city_name,
			'county_name' => $county_name,
			'address' => $address,
			'zipcode' => $zipcode,
			'mobile' => $mobile,
			'is_check' => $is_check,
			'add_time' => time(),
		));
	}
	
	//编辑信息
	function update($addr_id, $data){
		$uid = intval($data['uid']);
		$province_id = intval($data['province_id']);
		$city_id = intval($data['city_id']);
		$county_id = intval($data['county_id']);
		$address = trim($data['address']);
		$consignee = trim($data['consignee']);
		$zipcode = intval($data['zipcode']);
		$mobile = trim($data['mobile']);
		
		if ($province_id <= 0 || $city_id <= 0 || $county_id <= 0){return false;}
		if (empty($consignee)){return false;}
		if (empty($address)){return false;}
		if (empty($zipcode)){return false;}
		if (empty($mobile)){return false;}
		
		$region = new region();
		$province_name = $region -> get_name_by_id($province_id, 1);
		if ($province_name){} else {return false;}
		
		$city_name = $region -> get_name_by_id($city_id, 2);
		if ($city_name){} else {return false;}
		
		$county_name = $region -> get_name_by_id($county_id, 3);
		if ($county_name){} else {return false;}
		
		return $this -> db -> update('user_address', array(
			'consignee' => $consignee,
			'province_id' => $province_id,
			'city_id' => $city_id,
			'county_id' => $county_id,
			'province_name' => $province_name,
			'city_name' => $city_name,
			'county_name' => $county_name,
			'address' => $address,
			'zipcode' => $zipcode,
			'mobile' => $mobile,
			'update_time' => time()
		), array(
			'uid' => $uid,
			'addr_id' => $addr_id
		));
	}
	
	//设置为当前地址
	function setDefault($uid, $addr_id){
		if (!isint($addr_id) || $addr_id <= 0){return false;}
		$this -> db -> update('user_address', array('is_check' => 0), array('uid' => $uid));
		$this -> db -> update('user_address', array('is_check' => 1), array('addr_id' => $addr_id, 'uid' => $uid));
	}
	
	//删除
	function delete($addr_id, $uid){
		if (!isint($addr_id) || $addr_id <= 0){return false;}
		if ($uid > 0){
			$where = array('addr_id' => $addr_id, 'uid' => $uid);
		} else {
			$where = array('addr_id' => $addr_id);
		}
		return $this -> db -> delete('user_address', $where);
	}
	
	//查询所有地址
	function items($uid){
		if (!isint($uid) || $uid <= 0){return false;}
		$sql = 'SELECT * FROM `user_address` WHERE uid = '.$uid.' ORDER BY is_check DESC, addr_id ASC';
		return $this -> db -> rows($sql);
	}
}