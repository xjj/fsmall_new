<?php
namespace suplier;

use model as md;

class Order extends Front {
	
	function index(){
		$this -> items();	
	}
	
	//查询订单 -- 默认显示未发货的订单
	function items(){
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 20; 
		
		$brand_id = $_SESSION['suplier']['brand_id'];
		if (!isint($brand_id) || $brand_id <= 0){
			cpmsg(false, $this->LANG['ERROR_QUERY_URL']);	
		}
		
		$kr_send = new md\order_send_KR();
		$ret = $kr_send -> search($brand_id, $_GET, $page, $pagesize);
		$order_items = $ret['items'];
		$total = $ret['total'];
		
		$GET = $_GET;
		if (isset($GET['page'])){unset($GET['page']);}
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/order',
			'params' => $GET
		));
		
		$params = fetch_url_query($GET);
		
		
		$this -> smarty -> assign('order_items', $order_items);
		$this -> smarty -> assign('pagebox', $pg -> show());
		$this -> smarty -> assign('params', $params);
		$this -> smarty -> display('order.tpl');
	}
	
	//商品发货操作 -- 单条发货
	function send(){
		$barcode = $this -> params[0];
		
		if (!isint($barcode) || $barcode <= 0){
			cpmsg(false, $this -> LANG['ERROR_QUERY_URL'], -1);
		}
		
		//获取未发货的商品信息
		$kr_shipping = new md\order_product_kr_shipping();
		$kr_shipping_data = $kr_shipping -> info($barcode);
		if ($kr_shipping_data){} else {
			cpmsg(false, $this -> LANG['ERROR_ORDER_PRODUCT_NOT_EXIST'], -1);
		}
		
		if ($kr_shipping_data['status'] != 0){
			cpmsg(false, $this -> LANG['ERROR_ORDER_PRODUCT_STATUS'], -1);	
		}
		
		//获取商品信息
		$bc = new md\order_product_barcode();
		$product_data = $bc -> PRDinfo($barcode);
		if ($product_data){
			$op_id = $product_data['op_id'];
			$order_id = $product_data['order_id'];	
			$prd_id = $product_data['prd_id'];
			$price_kr = $product_data['price_kr'];
			$brand_id = $product_data['brand_id'];
		} else {
			cpmsg(false, $this -> LANG['ERROR_ORDER_BARCODE_NOT_EXIST'], -1);
		}
		
		if ($brand_id != $_SESSION['suplier']['brand_id']){
			cpmsg(false, $this -> LANG['ERROR_ORDER_BRAND'], -1);	
		}
		
		//获取折扣
		$send_kr = new md\order_send_KR();
		$discount = $send_kr -> PRD_discount($brand_id, $prd_id);
		
		//设置发货
		$f = $kr_shipping -> sendUpdate($barcode, array(
			'prd_id' => $prd_id,
			'order_id' => $order_id,
			'op_id' => $op_id,
			'price_kr' => $price_kr,
			'discount' => $discount,
		));
		
		if ($f){
			$params = fetch_url_query($_GET);
			$url = '/order/items';
			if (!empty($params)){$url .= '?'.$params;}
			cpurl($url);
		} else {
			cpmsg(false, $this -> LANG['MESSAGE_SEND_FAILED'], -1);	
		}
	}
	
	
	//多条发货
	function send_multi(){
		$code = $_GET['code'];
		if (empty($code)){
			cpmsg(false, $this -> LANG['ERROR_QUERY_URL'], -1);		
		}
		
		$bcArr = explode(',', $code);
		$bcData = array();
		foreach ($bcArr as $bc){
			if (isint($bc) && $bc > 0) {
				$bcData[] = $bc;	
			}
		}
		$bcData = array_unique($bcData);
		if (empty($bcData)){
			cpmsg(false, $this -> LANG['ERROR_QUERY_URL'], -1);		
		}
		
		//查询条码的商品信息 -- 批量查询
		$kr_shipping = new md\order_product_kr_shipping();
		$kr_shipping_data = $kr_shipping -> info_multi($bcData);
		if ($kr_shipping_data){} else {
			cpmsg(false, $this -> LANG['ERROR_ORDER_BARCODE_NOT_EXIST'], -1);	
		}
		
		//过滤掉不能发货的
		$bcArr2 = array();
		foreach ($kr_shipping_data as $item){
			if ($item['status'] == 0){
				$bcArr2[] = $item['barcode'];	
			}	
		}
		
		if (empty($bcArr2)){
			cpmsg(false, $this -> LANG['ERROR_ORDER_BARCODE_NOT_EXIST'], -1);	
		}
		
		$brand_id = $_SESSION['suplier']['brand_id'];
		
		//获取商品信息
		$bc = new md\order_product_barcode();
		$send_kr = new md\order_send_KR();
		$product_data = $bc -> PRDinfo_multi($bcArr2);
		if ($product_data){
			$data = array();
			foreach ($product_data as $item){
				if ($item['brand_id'] == $brand_id){
					$data[] = array(
						'barcode' => $item['barcode'],
						'op_id' => $item['op_id'],
						'order_id' => $item['order_id'],	
						'prd_id' => $item['prd_id'],
						'price_kr' => $item['price_kr'],
						'discount' => $send_kr -> PRD_discount($brand_id, $item['prd_id'])
					);
				}
			}
		} else {
			cpmsg(false, $this -> LANG['ERROR_ORDER_BARCODE_NOT_EXIST'], -1);	
		}
		
		
		//发货操作
		$num = $kr_shipping -> sendUpdate_multi($data);
		if ($num && $num > 0){
			$params = fetch_url_query($_GET);
			$url = '/order/items';
			if (!empty($params)){$url .= '?'.$params;}
			cpmsg(true, $this->LANG['MESSAGE_SEND_SUCCESS'].$this->LANG['MESSAGE_SEND_NUMBER'].$num, $url);
		} else {
			cpmsg(false, $this -> LANG['MESSAGE_SEND_FAILED'], -1);		
		}
	}
	
	//打印条码
	function printBC(){
		if (empty($_GET['bc'])){
			cpmsg(false, $this->LANG['ERROR_QUERY_URL'], -1);
		}
		
		$bcArr = explode(',', $_GET['bc']);
		$bcData = array();
		foreach ($bcArr as $bc){
			$bc = trim($bc);
			if (isint($bc) && $bc >= 9){
				$bcData[] = $bc;
			}
		}
		
		$sp = intval($_GET['sp']);
		if ($sp > 0){
			for ($i=0; $i<$sp; $i++){
				array_unshift($bcData, "0");
			}
		}
		
		//单元数
		$itemCount = count($bcData);
		
		//行数
		$rowCount = ceil( $itemCount / 3);
		
		
		/**
		 *	一页A4打印纸		210	mm * 297 mm
		 *	一个标签单元		62.7 mm * 30.1 mm
		 *	页眉页脚高度		11.2 mm + 13.8 mm
		 */
		$itemW = 67.1;
		$itemH = 32.2;
		
		//计算文档高度
		$width	= 210; 
		$height = $itemH * $rowCount;
		$marginTop = 8.4;
		$marginLeft = 1.8;
		$padding = 2.7;
		
		//创建pdf文档
		$pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);		//取消文档头部
		$pdf->setPrintFooter(false);		//取消文档尾部
		$pdf->SetMargins(0,0,0);			//设置外边距
		$pdf->SetAutoPageBreak(false);  	//取消自动分页
		
		$pdf->SetFont('cid0kr', '', 9);		//设置字体与大小
		
		$pdf->setCellPaddings(2, 2, 2, 2);	//设置单元格内边距
		$pdf->SetFillColor(255, 255, 255);	//设置单元格背景颜色
		
		$bc = new md\order_product_barcode();
		
		$i = 1;
		foreach ($bcData as $code){
			if ($i == 1){
				$pdf->AddPage();
			}
			
			//当前列数
			$col = (($i-1) % 3) + 1;
			
			//当前行数
			$row = ceil($i / 3);
			
			//计算XY坐标
			$x = ($col - 1) * $itemW + ($col - 1)* $padding + $marginLeft;
			$y = ($row - 1) * $itemH + $marginTop;
			
			//查询商品信息
			if ($code == '0'){
				$content = '';	
			} else {
				//获取订单商品信息
				$prd_data = $bc -> PRDInfo($code); 
				if ($prd_data) {
					$prd_data['product_name_kr'] = preg_replace('/\s+/i', '', $prd_data['product_name_kr']);
					$prop_value = '';
					if ($prd_data['prop_value_kr']){
						foreach ($prd_data['prop_value_kr'] as $v){
							$valArr = explode(':', $v);
							$prop_value .= $valArr[1].';';
						}
					}
					$content = $prd_data['product_name_kr'].' '.$prd_data['product_sn'].'('.$prop_value.')<br />'.$prd_data['order_sn'].', '.$code.'<br /><img src="'.URI_PATH.'/barcode?code='.$code.'&h=20" />';
					$content = strval($content);
				} else {
					$content = '';	
				}
			}
			
			$pdf->writeHTMLCell($itemW, $itemH, $x, $y, $content, 0, 0, 1, true, 'L', true);
			
			
			if ($i % 27 == 0){
				$i = 1;
			} else {
				$i += 1;
			}
		}
		
		$pdf->output('barcode'.date('YmdHi'), 'I');
	}
	
	//韩网地址
	function krurl(){
		$prd_id = $this -> params[0];
		if (!isint($prd_id) || $prd_id <= 0){
			cpmsg(false, $this->LANG['ERROR_QUERY'], -1);		
		}
		
		$product = new md\product();
		$product_data = $product -> info($prd_id, array('kr_url'));
		if ($product_data){
			$kr_url = $product_data['kr_url'];	
		} else {
			$kr_url = '';	
		}
		
		if (empty($kr_url)){
			cpmsg(false, $this->LANG['ERROR_PRODUCT_KR_URL_EMPATY'], -1);	
		} else {
			header('location:'.$kr_url);	
		}
	}
	
	
	//取消订单
	function cancle(){
		$barcode = $this -> params[0];
		
		if (!isint($barcode) || $barcode <= 0){
			cpmsg(false, $this->LANG['ERROR_QUERY_URL'], -1);	
		}
		
		//查询订单信息
		$kr_shipping = new md\order_product_kr_shipping();
		$kr_shipping_data = $kr_shipping -> info($barcode);
		if ($kr_shipping_data){} else {
			cpmsg(false, $this->LANG['ERROR_ORDER_PRODUCT_NOT_EXIST'], -1);
		}
		
		//状态查询
		if ($kr_shipping_data['status'] != 0){
			cpmsg(false, $this->LANG['ERROR_ORDER_PRODUCT_STATUS'], -1);
		}
		
		//查询品牌
		$op_id = $kr_shipping_data['op_id'];
		
		$f = $this -> brand_own_verify($op_id);
		if ($f){} else {
			cpmsg(false, $this->LANG['ERROR_ORDER_BRAND'], -1);	
		}
		
		//取消操作
		$f = $kr_shipping -> cancle($barcode);
		if ($f){
			$params = fetch_url_query($_GET);
			$url = '/order/items';
			if (!empty($params)){$url .= '?'.$params;}
			cpmsg(true, $this->LANG['MESSAGE_CANCLE_SUCCESS'], $url);	
		} else {
			cpmsg(true, $this->LANG['MESSAGE_CANCLE_FAILED'], -1);		
		}
	}	
	
	//撤销取消
	function cancleBack(){
		$barcode = $this -> params[0];
		
		if (!isint($barcode) || $barcode <= 0){
			cpmsg(false, $this->LANG['ERROR_QUERY_URL'], -1);	
		}	
		
		//查询订单信息
		$kr_shipping = new md\order_product_kr_shipping();
		$kr_shipping_data = $kr_shipping -> info($barcode);
		if ($kr_shipping_data){} else {
			cpmsg(false, $this->LANG['ERROR_ORDER_PRODUCT_NOT_EXIST'], -1);
		}
		
		//状态查询
		if ($kr_shipping_data['status'] != 2){
			cpmsg(false, $this->LANG['ERROR_ORDER_PRODUCT_STATUS'], -1);
		}
		
		//判断该商品是否已被退款
		if ($kr_shipping_data['is_refund'] == 1){
			cpmsg(false, $this->LANG['ERROR_ORDER_PRODUCT_REFUND'], -1);	
		}
		
		//查询品牌
		$op_id = $kr_shipping_data['op_id'];
		
		$f = $this -> brand_own_verify($op_id);
		if ($f){} else {
			cpmsg(false, $this->LANG['ERROR_ORDER_BRAND'], -1);	
		}
		
		//取消操作
		$f = $kr_shipping -> cancleBack($barcode);
		if ($f){
			$params = fetch_url_query($_GET);
			$url = '/order/items';
			if (!empty($params)){$url .= '?'.$params;}
			cpmsg(true, $this->LANG['MESSAGE_CANCLEBACK_SUCCESS'], $url);	
		} else {
			cpmsg(true, $this->LANG['MESSAGE_CANCLEBACK_FAILED'], -1);		
		}
	}
	
	//撤销发货
	function cancleSend(){
		$barcode = $this -> params[0];
		
		if (!isint($barcode) || $barcode <= 0){
			cpmsg(false, $this->LANG['ERROR_QUERY_URL'], -1);	
		}	
		
		//查询订单信息
		$kr_shipping = new md\order_product_kr_shipping();
		$kr_shipping_data = $kr_shipping -> info($barcode);
		if ($kr_shipping_data){} else {
			cpmsg(false, $this->LANG['ERROR_ORDER_PRODUCT_NOT_EXIST'], -1);
		}
		
		//状态查询
		if ($kr_shipping_data['status'] != 1){
			cpmsg(false, $this->LANG['ERROR_ORDER_PRODUCT_STATUS'], -1);
		}
		
		//检查是否进入了物流系统
		if ($kr_shipping_data['is_on_tpl'] == 1){
			cpmsg(false, $this->LANG['ERROR_ORDER_PRODUCT_ON_LOGISTICS'], -1);	
		}
		
		//查询品牌
		$op_id = $kr_shipping_data['op_id'];
		$f = $this -> brand_own_verify($op_id);
		if ($f){} else {
			cpmsg(false, $this->LANG['ERROR_ORDER_BRAND'], -1);	
		}
		
		
		//撤销操作
		$f = $kr_shipping -> cancleSend($barcode);
		if ($f){
			$params = fetch_url_query($_GET);
			$url = '/order/items';
			if (!empty($params)){$url .= '?'.$params;}
			cpmsg(true, $this->LANG['MESSAGE_CANCLESEND_SUCCESS'], $url);	
		} else {
			cpmsg(true, $this->LANG['MESSAGE_CANCLESEND_FAILED'], -1);		
		}
	}
	
	//品牌所有者验证
	private function brand_own_verify($op_id){
		$order_PRD = new md\order_product();
		$PRDInfo = $order_PRD -> PRDInfo($op_id);
		if ($PRDInfo && $PRDInfo['brand_id'] == $_SESSION['suplier']['brand_id']){
			return true;
		} else {
			return false;
		}	
	}
	
	//下载-数据准备[ajax]
	function download(){
		set_time_limit(0);
		
		$brand_id = $_SESSION['suplier']['brand_id'];
		if (!isint($brand_id) || $brand_id <= 0){
			echo json_encode(array(
				'error' => -1,
				'message' => $this->LANG['ERROR_QUERY_URL']
			));	
			exit();
		}
		
		$kr_send = new md\order_send_KR();
		$ret = $kr_send -> prepareData($brand_id, $_GET);
		if ($ret){
			$filename = $ret['folder'].'/'.$ret['filename'];
			echo json_encode(array(
				'error' => 0,
				'filename' => $filename
			));	
		} else {
			echo json_encode(array(
				'error' => -1,
				'message' => $this->LANG['ERROR_DOWNLOAD_FAILED']
			));		
		}
	}
	
	//下载
	function download2(){
		set_time_limit(0);
		
		$brand_id = $_SESSION['suplier']['brand_id'];
		$filename = trim($_GET['filename']);
		
		if (!isint($brand_id) || $brand_id <= 0 || empty($filename)){
			cpmsg(false, $this->LANG['ERROR_QUERY_URL'], -1);
		}
		
		$file = ROOT_PATH.'/runtime/suplier/'.$filename.'.php';
		
		if (file_exists($file)){
			$data = include ($file);	
		} else {
			cpmsg(false, $this->LANG['ERROR_PREPARED_DATA_NOT_EXIST'], -1);
		}
		
		$name = date('Ymd-His');
		$xls = new \Excel_XML;
		$xls->addArray($data);
		$xls->generateXML($name);
	}
	
	//sellmate -- 准备数据
	function sellmate(){
		set_time_limit(0);
		
		$brand_id = $_SESSION['suplier']['brand_id'];
		if (!isint($brand_id) || $brand_id <= 0){
			echo json_encode(array(
				'error' => -1,
				'message' => $this->LANG['ERROR_QUERY_URL']
			));	
			exit();
		}
		
		$kr_send = new md\order_send_KR();
		$ret = $kr_send -> prepareData_sellmate($brand_id, $_GET);
		if ($ret){
			$filename = $ret['folder'].'/'.$ret['filename'];
			echo json_encode(array(
				'error' => 0,
				'filename' => $filename
			));	
		} else {
			echo json_encode(array(
				'error' => -1,
				'message' => $this->LANG['ERROR_DOWNLOAD_FAILED']
			));		
		}	
	}
}