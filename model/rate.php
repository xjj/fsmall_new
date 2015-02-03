<?php
namespace model;

if (!defined('START')) exit('No direct script access allowed');

/**
 +-------------------------------------------
 *	汇率类
 +-------------------------------------------
 *	这里的汇率是指 1KRW = ?CNY		1USD = ?CNY
 +-------------------------------------------
 */
class Rate extends Front {
	
	//查询某日期汇率
	function info($date_line = 0){
		if ($date_line == 0){
			$sql = 'SELECT * FROM `system_rate` ORDER BY date_line DESC LIMIT 0,1';
		} else {
			$sql = 'SELECT * FROM `system_rate` WHERE date_line = \''.encode($date_line).'\'';
		}
		return $this -> db -> row($sql);
	}
	
	//添加
	function add($data){
		$f = $this -> isDate($data['date_line']);
		if ($f){
			$date_line = $data['date_line'];
		} else {
			$date_line = date('Ymd');
		}
		
		$info = $this -> info($date_line);
		if ($info){
			return false;
		}
		
		if (empty($data['usd']) || !is_numeric($data['usd']) || $data['usd'] <= 0){
			return false; 
		}
		if (empty($data['krw']) || !is_numeric($data['krw']) || $data['krw'] <= 0){
			return false; 
		}
		
		return $this -> db -> insert('system_rate', array(
			'date_line' => $date_line,
			'usd' => $data['usd'],
			'krw' => $data['krw'],
			'content' => $data['content'],
			'add_time' => time(),
			'update_time' => time(),
		));
	}
	
	//编辑
	function update($date_line, $data){
		$f = $this -> isDate($date_line);
		if ($f){
			if (empty($data['usd']) || !is_numeric($data['usd']) || $data['usd'] <= 0){
				return false; 
			}
			if (empty($data['krw']) || !is_numeric($data['krw']) || $data['krw'] <= 0){
				return false; 
			}
			return $this -> db -> update('system_rate', array(
				'usd' => $data['usd'],
				'krw' => $data['krw'],
				'content' => $data['content'],
				'update_time' => time(),
			), array(
				'date_line' => $date_line
			));
		} else {
			return false;
		}
	}
	
	//删除
	function delete($date_line){
		$f = $this -> isDate($date_line);
		if ($f){
			return $this -> db -> delete('system_rate', array('date_line' => $date_line));	
		} else {
			return false;
		}
	}
	
	//判断日期是否正确
	function isDate($date_line){
		if (strlen($date_line) != 8){return false;}
		$dArr = str_split($date_line, 2);
		foreach ($dArr as $key => $val){
			$dArr[$key] = intval($val);
		}
		$year = $dArr[0]*100 + $dArr[1];
		if ($year < 2014){return false;}
		if ($dArr[2] > 12 || $dArr[2] < 1){return false;}
		if ($dArr[3] > 31 || $dArr[3] < 1){return false;}
		
		return true;
	}
	
	//获取当前汇率
	function now_rate(){
		static $rate = false;
		
		if ($rate == false){
			$info = $this -> info();
			if ($info){
				$rate = array(
					'krw' => $info['krw'],
					'usd' => $info['usd'],
				);
			} else {
				exit('error: not find rate information.');
			}
		}
		return $rate;
	}
	
	//获取韩元汇率
	function krw_rate(){
		$rate = $this -> now_rate();
		return $rate['krw']; 
	}
	
	//获取美元汇率
	function usd_rate(){
		$rate = $this -> now_rate();
		return $rate['usd']; 
	}
	
	//韩元转换RMB
	function krw_to_cny($money){
		$money = floatval($money);
		if ($money == 0){return 0;}
		$krw_rate = $this -> krw_rate();
		return round($money * $krw_rate, 0);
	}
	
	//RMB转换韩元
	function cny_to_krw($money){
		$money = floatval($money);
		if ($money == 0){return 0;}
		$krw_rate = $this -> krw_rate();
		return round($money / $krw_rate, 0);
	}
	
	//RMB转换成美元
	function cny_to_usd($money){
		$money = floatval($money);
		if ($money == 0){return 0;}
		$usd_rate = $this -> usd_rate();
		return round($money / $usd_rate, 2);
	}
	
	//查询
	function search($page, $pagesize){
		$sql = 'SELECT * FROM `system_rate` ORDER BY date_line DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$items = $this -> db -> rows($sql);
		
		$sql = 'SELECT COUNT(*) as num FROM `system_rate`';
		$row = $this -> db -> row($sql);
		$total = $row['num']; 
		
		return array('items' => $items, 'total' => $total);
	}
}