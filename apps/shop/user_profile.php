<?php
namespace shop;

use model as md;

/**
 +--------------------------------------
 *	会员中心 - 个人资料
 +--------------------------------------
 */
class User_Profile extends Front {
	
	//个人资料
	function index(){
		if (isset($_POST['submit'])){
			$this -> submit();
			exit();
		}
		
		//获取用户信息
		$uid = $_SESSION['uid'];
		$user = new md\user();
		$user_data = $user -> info($uid);
		
		//获取地区信息 - 省级
		$region = new md\region();
		$province_items = $region -> provinces();
		
		$province_id = $user_data['province_id'];
		$city_id = $user_data['city_id'];
		$county_id = $user_data['county_id'];
		
		//查询市
		$city_items = $county_items = false;
		if ($city_id > 0){
			$city_items = $region -> siblings($city_id, 2);
 		}
		if ($county_id > 0){
			$county_items = $region -> siblings($county_id, 3);
		}
		
		$this -> smarty -> assign('title', '帐号资料');
		$this -> smarty -> assign('province_items', $province_items);
		$this -> smarty -> assign('city_items', $city_items);
		$this -> smarty -> assign('county_items', $county_items);
		$this -> smarty -> display('user/profile.tpl');
	}
	
	//提交保存操作
	private function submit(){
		$uid = $_SESSION['uid'];
		
		$sex = intval($_POST['sex']);
		$mobile = $_POST['mobile'];
		$province_id = intval($_POST['province_id']);
		$city_id = intval($_POST['city_id']);
		$county_id = intval($_POST['county_id']);
		$address = trim($_POST['address']);
		$birth_year = intval($_POST['birth_year']);
		$birth_month = intval($_POST['birth_month']);
		$birth_day = intval($_POST['birth_day']);
		$qq = trim($_POST['qq']);
		$about = trim($_POST['about']);
		
		if ($sex != 1 && $sex != 2){$sex = 0;}
		if (!empty($mobile) && isint($mobile) && strlen($mobile) == 11 && strpos($mobile, '1') === 0){} else {
			$mobile = '';
		}
		
		//查询省
		if ($province > 0){
			$region = new md\region();
			$province_name = $region -> get_name_by_id($province_id, 1);
			if ($province_name){
				
				//查询城市 开始
				if ($city_id > 0){
					$city_name = $region -> get_name_by_id($city_id, 2);
					if ($city_name){
						
						//查询县 开始
						if ($county_id > 0){
							$county_name = $region -> get_name_by_id($county_id, 3);
							if ($county_name){} else {
								$county_id = 0;
								$county_name = '';
							}
						} else {
							$county_name = '';
						}
						//查询县 结束
						
					} else {
						$city_id = 0;
						$city_name = $county_name = '';
					}
				} else {
					$city_name = $county_name = '';
				}
				//查询市 结束
				
			} else {
				$province_id = 0;
				$privince_name = $city_name = $county_name = '';
			}
		} else {
			$privince_name = $city_name = $county_name = '';
		}
		
		
		//验证年份是否合法
		if ($birth_year < 1940 || $birth_year > date('Y')){
			cpmsg(false, '出生日期的年份范围不正确！', -1);
		}
		if ($birth_month < 1 || $birth_month > 12){
			cpmsg(false, '出生日期的月份范围不正确！', -1);
		}
		if ($birth_day < 1 || $birth_day > 31){
			cpmsg(false, '出生日期的日期范围不正确！', -1);
		}
		$f = $this -> leap_year($birth_year);
		if ($f){
			if ($birth_month == 2 && $birth_day > 29){
				cpmsg(false, '该年是闰年二月份只有29天！', -1);
			}
		} else {
			if ($birth_month == 2 && $birth_day > 28){
				cpmsg(false, '该年二月份只有28天！', -1);
			}
		}
		
		if (!isint($qq) || strlen($qq) < 5){
			cpmsg(false, '您填写的QQ号码，貌似不正确！', -1);
		}
		
		if (strlen($about) > 600){
			cpmsg(false, '个人介绍太长，请限制在200个汉字以内！', -1);
		}
		
		$user = new md\User();
		$num = $user -> update($uid, array(
			'sex' => $sex,
			'mobile' => $mobile,
			'province_id' => $province_id,
			'province_name' => $province_name,
			'city_id' => $city_id,
			'city_name' => $city_name,
			'county_id' => $county_id,
			'county_name' => $county_name,
			'address' => $address,
			'birth_year' => $birth_year,
			'birth_month' => $birth_month,
			'birth_day' => $birth_day,
			'qq' => $qq,
			'about' => $about
		));
		if ($num > 0){
			cpmsg(true, '恭喜，资料信息更新成功！', '/user/profile');
		} else {
			cpmsg(false, '资料信息更新失败，没有数据被修改！', -1);
		}
	}
	
	//判断是否是闰年
	private function leap_year($year){
		if (($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0){
			return true;
		}
		return false;
	}
}