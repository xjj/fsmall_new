<?php
namespace admin;

if (!defined('START')) exit('No direct script access allowed');

use admin\order as odr;

/**
 +------------------------------
 *	订单页面
 +------------------------------
 */

class Order extends Front {
	
	function index(){
		$this -> items();
	}
	
	//列表
	function items(){
		$p = $this -> params[0];
		$ps = array(
			'cancle',			//取消订单
			'detail', 			//显示订单信息
			'confirm',			//订单确认
			'ordered',			//订单订货
			'message',			//发留言
			'op_cancle',		//取消订单商品
			'op_soldout', 		//设置商品断货
		);
		
		if (!in_array($p, $ps)){
			$p = 'items';
		}
		$ord = new odr\order();
		$ord -> $p();
	}
	
	//发货
	function send(){
		$p = $this -> params[0];
		$ps = array(
			'items',			//到货商品列表
			'sent',				//已发货订单商品列表
			'message',			//订单消息
			'send',				//国内发货
			'send_multi',		//批量发货
			'receive',			//国内到货
			'print_waybill',	//快递单打印
			'print_electwaybill',	//电子面单打印
		);
		
		if (!in_array($p, $ps)){
			$p = 'items';
		}
		$send = new odr\send();
		$send -> $p();
	}
	
	//退货
	function refund(){
		$p = $this -> params[0];
		$ps = array('items', 'detail','allow','deny','return_money', 'return_no_money', 'agree', 'disagree');
		if (!in_array($p, $ps)){
			$p = 'items';
		}
		$refund = new odr\refund();
		$refund -> $p();
	}
	
	//取消
	function krcancle(){
		$p = $this -> params[0];
		$ps = array('items', 'del', 'edit', 'return_money');
		if (!in_array($p, $ps)){
			$p = 'items';
		}
		$refund = new odr\krcancel();
		$refund -> $p();
	
	}
}