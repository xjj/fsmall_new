<?php
namespace admin\order;

use model as md;
use admin as adm;

/**
 +-----------------------------
 *	发货控制器
 +-----------------------------
 */
class Send extends adm\front {
	public $debugmode=1;
	/**
	 +-----------------------------------
	 *	到货列表
	 +-----------------------------------
	 */
	function items(){
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 5;
		
		$GET = $_GET;
		if (isset($GET['page'])){unset($GET['page']);}
		
		$order_send = new md\order_send();
		$ret = $order_send -> search($_GET, $page, $pagesize);
		$order_items = $ret['items'];
		$total = $ret['total'];
		
		//翻页设置
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this->col.'',
			'params' => $GET,
		));
		
		//所有快递
		$shipping = new md\shipping();
		$shipping_items = $shipping -> items();
				
		$this -> smarty -> assign('more_navs', '到货扫描与发货');
		$this -> smarty -> assign('order_items', $order_items);
		$this -> smarty -> assign('shipping_items', $shipping_items);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('order/send_list.tpl');
	}
	
	//订单消息
	function message(){
		$order_id = $_POST['order_id'];
		
		$order = new md\order();
		
		//获取订单备注
		$order_data = $order -> info($order_id);
		if ($order_data){} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '该订单不存在或已被删除！'
			));
			exit();
		}
		
		$remark = trim($order_data['message']);
		if (empty($remark)){
			$remark = false;
		}
		
		//获取订单消息
		$order_message = new md\order_message();
		$order_message_items = $order_message -> items($order_id);
		if ($order_message_items){
			$message = array();
			foreach ($order_message_items as $item){
				if ($item['adm_uname'] != ''){
					$content = '管理员：'.$item['content'];
				} else {
					$content = ''.$item['uname'].'：'.$item['content'];
				}
				$message[] = array(
					'addtime' => date('m-d H:i'),
					'content' => $content,  
				);
			}
		} else {
			$message = false;
		}
		
		
		if ($remark || $message){
			echo json_encode(array(
				'error' => 0,
				'order_id' => $order_id,
				'remark' => $remark,
				'message' => $message,
			));
			exit();
		} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '订单没有备注或留言！'
			));	
		}
	}
	
	//快递单打印
	function print_waybill(){
		$order_id = $this -> params[1];
		if (!isint($order_id) || $order_id <= 0){return false;}
		
		$order = new md\order();
		$order_product = new md\order_product();
		
		//查询配送信息
		$order_data = $order -> info($order_id);
		if ($order_data){
			$address = $order_data['province_name'].' '.$order_data['city_name'].' '.$order_data['county_name'].' '.$order_data['address']; 
			$consignee = $order_data['consignee'];
			$mobile = $order_data['mobile'];
			
			$sender = '윌인터내셔날（will international korea）';
		} else {
			exit('未查询到该订单！');
		}
		
		/*$pdf = new \TCPDF('P', 'pt', 'A4', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetMargins(0,0,0);
		
		$pdf->SetFont('cid0cs', '', 11);
		$pdf->AddPage();
		$pdf->writeHtml('<div style="padding:20px;">我不去啊我不谁打啊打死的去啊我不谁打啊打死的去啊我不谁打啊打死的去啊我不谁打啊打死的去啊</div>');
		$pdf->writeHtml('<div style="padding:20px;">我不去啊</div>');
		$pdf->writeHtml('<div style="padding:20px;">我不谁打啊打死的去啊</div>');
		$pdf->writeHtml('<div style="padding:20px;">我不规划方法去啊</div>');
		$pdf->writeHtml('<div style="padding:20px;">我不去啊</div>');
		
		$style['position'] = 'L';
		$pdf->write1DBarcode('RIGHT', 'C128A', '', '', '', 15, 0.4, $style, 'N');
		
		$pdf->Output('xx.pdf', 'I');*/
	}
	

	//国内发货 -- 
	function send_multi(){
		$barcode = trim($_POST['barcode']); //字符串 -- 用逗号分隔的
		$spno = trim($_POST['spno']);
		$adm_uid = $_SESSION['admin']['uid'];
		
		if (empty($barcode) || empty($spno)){
			echo json_encode(array('error' => -1, 'message' => '错误的请求!'));
			exit();
		}
		
		//多行数据更新
		$order_send = new md\order_send();
		$number = $order_send -> cn_send_multi($barcode, $spno, $adm_uid);
		if ($number > 0){
			echo json_encode(array('error' => 0, 'number' => $number));
		} else {
			echo json_encode(array('error' => -1, 'message' => '发货失败!'));
		}
	}
	
	function send(){
		$barcode = trim($_POST['barcode']);
		$spno = trim($_POST['spno']);
		$adm_uid = $_SESSION['admin']['uid'];
		$spcode = trim($_POST['spcode']);
		
		if (empty($barcode) || empty($spno) || empty($spcode)){
			echo json_encode(array('error' => -1, 'message' => '错误的请求!'));
			exit();
		}
		
		//多行数据更新
		$order_send = new md\order_send();
		$number = $order_send -> cn_send($barcode, $spno, $spcode, $adm_uid);
		if ($number > 0){
			echo json_encode(array('error' => 0, 'number' => $number));
		} else {
			echo json_encode(array('error' => -1, 'message' => '发货失败!'));
		}	
	}
	
	//国内到货
	function receive(){
		$barcode = trim($_POST['barcode']);
		if (empty($barcode)){
			echo json_encode(array(
				'error' => -1,
				'message' => '错误的请求！',
			));
			exit();
		}
		
		$order_send = new md\order_send();
		$data = $order_send -> cn_receive($barcode);
		if ($data){
			$order_id = $data['order_id'];
			$op_id = $data['op_id'];
			
			//查询订单信息
			$order = new md\order();
			$order_data = $order -> info($order_id);
			if ($order_data){} else {
				echo json_encode(array(
					'error' => -1,
					'message' => '没有查询到该订单信息！',
				));
				exit();
			}
			
			$item_data = $this -> receive_items($order_id);
			$order_data['items'] = $item_data['items'];
			$order_data['total_number'] = $item_data['total_number'];
			$order_data['spot_number'] = $item_data['spot_number'];
			$order_data['kr_send_number'] = $item_data['kr_send_number'];
			$order_data['cn_receive_number'] = $item_data['cn_receive_number'];
			$order_data['cn_send_number'] = $item_data['cn_send_number'];
			
			//获取订单模版
			$tpl = $this -> smarty -> createTemplate('order/send_item.tpl');
			$tpl -> assign('order_data', $order_data);
			$data = $tpl -> fetch();
			
			echo json_encode(array(
				'error' => 0,
				'order_id' => $order_id,
				'sku_id' => $sku_id,
				'os_id' => $os_id,
				'data' => $data
			));
		} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '商品到货失败，在韩方已发商品中没有查询到与该条码！'
			));
		}
	}
	
	//已发货订单商品
	function sent(){
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 5;
		
		$order_send = new md\order_send();
		$ret = $order_send -> search_sent($_GET, $page, $pagesize);
		$order_items = $ret['items'];
		$total = $ret['total'];
		
		$GET = $_GET;
		if (isset($GET['page'])){unset($GET['page']);}
		
		
		//翻页设置
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this->col.'',
			'params' => fetch_url_query($GET),
		));
		
		//所有快递
		$shipping = new md\shipping();
		$shipping_items = $shipping -> items();
		
		$this -> smarty -> assign('more_navs', '已发货订单');
		$this -> smarty -> assign('order_items', $order_items);
		$this -> smarty -> assign('shipping_items', $shipping_items);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('order/sent_list.tpl');
	}
	
	//获取到货的SKU信息 与到货数量等信息
	private function send_items($order_id){
		if (!isint($order_id) || $order_id <= 0){return false;}
		
		$sql = 'SELECT *, COUNT(sku_id) as number, group_concat(barcode) as barcodes, group_concat(os_id) as os_ids FROM `order_skus` WHERE order_id = '.$order_id.' AND order_status IN (1,4,5,6) GROUP BY sku_id, order_status';
		
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$total_number = 0;
			$cn_send_number = 0;
			$kr_send_number = 0;
			$cn_receive_number = 0;
			$spot_number = 0;
			$send_items = array();
			
			$product = new md\product();
			foreach ($rows as $row){
				$number = $row['number'];
				$total_number += $number;
				if ($row['order_status'] == 4){ //韩国已发货
					$kr_send_number += $number;
				} elseif ($row['order_status'] == 5){ //国内已到货
					$cn_receive_number += $number;
				} elseif ($row['order_status'] == 1){ // 现货
					$spot_number += $number;
				} elseif ($row['order_status'] == 6){ //国内已发货
					$price = $row['price'];
					
					$total_price = $price*$number;
				
					$row['picurl'] = $product -> pic_thumb_url($row['pic']);
					$row['total_price'] = format_money($total_price);
					$row['price'] = format_money($price);
					$row['order_amount'] = format_money($row['order_amount']);
			
					$send_items[] = $row;
					$cn_send_number += $number;
				}
			}
			
			return array(
				'items' => $send_items,
				'total_number' => $total_number,
				'spot_number' => $spot_number,
				'kr_send_number' => $kr_send_number,
				'cn_receive_number' => $cn_receive_number,
				'cn_send_number' => $cn_send_number,
			);
		} else {
			return false;
		}
	}
	/*-----------------------
		电子面单打印
	-------------------------*/
	function print_electwaybill(){
		$order_id = $this -> params[1];
		if (!isint($order_id) || $order_id <= 0){return false;}
		
		$order = new md\order();
		$order_product = new md\order_product();
		
		//查询配送信息
		$order_data = $order -> info($order_id);//
		if ($order_data){
			$address = $order_data['province_name'].' '.$order_data['city_name'].' '.$order_data['county_name'].' '.$order_data['address']; 
			$consignee = $order_data['consignee'];
			$mobile = $order_data['mobile'];
			$sender = '윌인터내셔날（will international korea）';
		} else {
			exit('未查询到该订单！');
		}
		if($order_data['shipping_code']=='YTO'){
			$this->ytoupload($order_data);
		}
		if($order_data['shipping_code']=='STO'){
			$this->stoupload($order_data);
		}
	}
	//圆通上传
	function ytoupload($order_data){
		if(empty($order_data)){return false;}
		//接口参数写公共的 里面
		$customerCode='K51207757';#拉取用 
		$parternId='Kg9W6jcB';//公共
		$clientId=$customerCode;
		$order_yundanno = new md\order_yundanno();
		$num = $order_yundanno -> count_yundanno('yto');
		if(intval($num)<1){
			//无单号则请求单号
			$this->fetch_yundanno_yto($customerCode,$parternId,$clientId  );
		}
		#分配单号
		$mailNo = $order_yundanno -> get_yundanno('yto');
		if(!$mailNo){
			echo mysql_error();
			die('无可用的单号!');	
		}
		//上传订单信息到圆通 返回商品信息
		$products=$this->upload_order2yto($customerCode,$parternId,$clientId,$mailNo,$order_data);
		$order_data['transportNo']= $mailNo;
		$order_data['products']= $products;
		//打印
		$this->ytoprint_pdf($order_data);	
	}
	//获取圆通电子面单号
	function fetch_yundanno_yto($customerCode,$parternId,$clientId){
		$urltest= 'http://service.yto56.net.cn/api!synWaybill.action';//
		$ordernum=2;//
		$con= '<MailNoRequest><customerCode>'.$customerCode.'</customerCode><quantity>'.$ordernum.'</quantity><materialCode>DZ100301</materialCode></MailNoRequest>';//电子面单
		$dig= $con.$parternId;     
		$dig2= md5($dig,true);
		$dig5=  \Bytes::toStr(\Bytes::getBytes($dig2));
		$dig3= base64_encode( $dig5);  
		$dig4=urlencode($dig3);////	 
		$con_url= urlencode($con);
		$tar= 'logistics_interface='.$con_url.'&data_digest='.$dig4.'&clientId='.$clientId;
		$tmpInfo= curl_request($urltest,$tar);// 地址
		
		$xml = simplexml_load_string($tmpInfo);
		$success = strval($xml->success);
		$sequence = strval($xml->sequence);//批次号
		$ordernum_r = strval($xml->quantity);
		if($success=='true'){
			$pattern='/<mailNo>([0-9a-zA-Z]{6,20})<\/mailNo>/';
			preg_match_all($pattern, $tmpInfo,$matcheBigs);
			$ordersns=$matcheBigs[1];  
			foreach($ordersns as $k=>$v){
				$v2=substr($v,0,15);
				$v3=array('yundanno'=>$v2,'logiser'=>'yto','add_time'=>time()); 
				$ordersns[$k]= $v3;
			}
			//单号插入库
			$order_yundanno = new md\order_yundanno();
			$ret = $order_yundanno -> add($ordersns);
			//拉取成功回传给它信息 
			$con= '<MailNoRequest><customerCode>'.$customerCode.'</customerCode><sequence>'.$sequence.'</sequence><success>true</success></MailNoRequest>';
			$dig5= urlencode( base64_encode(\Bytes::toStr(\Bytes::getBytes(md5($con.$parternId,true)))));
			$tar= 'logistics_interface='.urlencode($con).'&data_digest='.$dig5.'&clientId='.$clientId;
			$tmpInfo= curl_request($urltest,$tar);//
		}else{#请求失败记录log 
			$data2=$xml;		
			$con=addslashes( var_export($data2,TRUE)); 
			$con='请求单号'.$con;
			$t=time();
			$timet= date('Y-m-d H:i:s');
			$data=array('title'=>'批量请求圆通运单号失败',
						'logcontent'=> $con,
						'time'=> $t);
			$result= $this->add_log($data);
			echo '<br>批量请求圆通运单号失败 无法上传订单信息 请联系当地网点发放单号';die;
		}	
	}
	//上传订单信息到圆通
	function upload_order2yto($customerCode,$parternId,$clientId,$mailNo,$array){
		$a="http://service.yto56.net.cn/CommonOrderServlet.action";
		$logisticProviderID='YTO';
		$customerId= $clientId; //也是账号?
		$txLogisticID=$clientId.$array['order_sn'] ;//物流订单号
		$type=1;
		$orderType=0;
		$serviceType=1;
		$name;
		$postCode;
		$mobile;
		$prov;
		$city;
		$rc="<RequestOrder><clientID>".$clientId."</clientID><logisticProviderID>".$logisticProviderID."</logisticProviderID>";
		$rc.="<customerId>".$customerId."</customerId>";//
		$rc.="<txLogisticID>".$txLogisticID."</txLogisticID>";
		$rc.="<mailNo>".$mailNo."</mailNo>";
		$rc.="<totalServiceFee>0.0</totalServiceFee>";
		$rc.="<codSplitFee>0.0</codSplitFee>";
		$rc.="<orderType>".$orderType."</orderType>";
		$rc.="<serviceType>".$serviceType."</serviceType>";
		//发件人信息根据表查?
		$array['sender']='Will International';
		$array['sendertel']='+82 070 4005 8602';
		$array['senderaddr']='Nangye-ro169, Hwanghak-dong, Jung-gu, Seoul, Korea';
		$array['senderpro']='江苏';
		$array['sendercity']='苏州';
		$array['senderarea']='吴中区';
		
		$rc.="<sender><name>".$array['sender']."</name>";//??kong?
		$rc.="<postCode>215124</postCode>";
		//读取  直接按发货人信息 里的  
		/*$sql_sender= "select * from admin_users where uname= 'fsmall' and status=1";
		$this->db->usedb('threepl');
		$r_sender= $this->db->row( $sql_sender);*/
		/*---------------
			检查参数 不为空 问默认值
		------------*/
		if( empty($array['sendertel'])){  #寄件人电话不能为空  
			
		}
		if( empty($array['senderaddr'])){ //寄件人地址不能为空  
			
		}
		$rc.="<mobile>".$array['sendertel']."</mobile>";//$rc.="<mobile>13575745195</mobile>";
		$rc.="<prov>".$array['senderpro']."</prov>";
		$rc.="<city>".$array['sendercity'].",".$array['senderarea']."</city>";
		$rc.="<address>".$array['senderaddr']."</address>";
		$rc.="</sender>";
		$rc.="<receiver>";
		//收货人字段
		$rc.="<name>".$array['consignee']."</name>";
		$rc.="<postCode>".$array['zipcode']."</postCode>";
		if( empty($array['mobile'])){
			$array['mobile']=' 1';
		}
		$rc.="<phone>".$array['mobile']."</phone>";
		if($array['province_name']==''||$array['city_name']==''){
			header("Content-type:text/html;charset=utf-8");#仅在输出错误信息时输出头部
			die('此订单省市区信息为空!');	
		}
		$rc.="<prov>".$array['province_name']."</prov>";
		$rc.="<city>".$array['city_name']."</city>";
		$rc.="<address>".trim($array['address'])."</address>";
		$rc.="</receiver>";
		$rc.="<items>";
		//多个商品都发送上去 获取商品信息 
		$order_yundanno = new md\order_yundanno();
		$products = $order_yundanno -> get_order_product($array['order_id']);
		//var_dump($products); die;
		if(is_array($products)){
			foreach($products as $k=>$v){
				if(is_array($v)){
					if($v['product_name']==''){
						$v['product_name']='暂无名称';	
					}
					//$v['product_name']='韩文名称不支持 暂无名称';
					$rc.="<item>";
					$rc.="<itemName>".$v['product_name']."</itemName>";
					$rc.="<number>1</number>";
					$rc.="<itemValue>2</itemValue>";//价格必须写?YTO说的
					$rc.="</item>";
				}
			}
		}
		$rc.="</items>";
		$rc.="</RequestOrder>";
		//dump($rc);
		$dig_base64= base64_encode(  \Bytes::toStr(\Bytes::getBytes(md5( $rc.$parternId,true))));
		$dig4=urlencode($dig_base64);////	 
		$con_url= urlencode($rc);
		$tar= 'logistics_interface='.$con_url.'&data_digest='.$dig4.'&clientId='.$clientId;
		$tmpInfo= curl_request($a,$tar);//
		$xml = simplexml_load_string($tmpInfo); //  起始标签和结束标签不匹配  因为数组不是XML格式的 才会这样提示  返回404了

		$success = strval($xml->success);
		if($success=='true'){//上传成功  让手动填进去
			if($this->debugmode==1){
				$data=array('title'=>$mailNo,
					'logcontent'=> 'upload success',
					'time'=> time());
			}else{
				$result = $order_yundanno -> update_yundanno($mailNo);
			}
			return $products;
			//echo '上传成功 ';
		}else{ //s01 非法的xml格式
			$data2=$xml;		
			$con=addslashes( var_export($data2,TRUE)); 
			$con='上传订单到圆通 失败'.$con;//
			$t=time();
			$timet= date('Y-m-d H:i:s');
			$data=array('title'=>$mailNo,
					'logcontent'=> $con,
					'time'=> $t);
			$result= $this->add_log($data);
			echo '上传订单到圆通 失败<br>  ';#xjj   ($tmpInfo)
			die;
			//调试乱码
			/*header("Content-type:text/html;charset=utf-8");
			dump($tmpInfo);
			echo '<hr>';
			echo htmlentities($rc);
			echo '<hr>';
			dump(  htmlentities($rc)  );
			echo '<hr>';
			dump($xml);
			echo '<hr>';
			dump($mailNo);
			echo '<hr>';
			dump($tar);
			die;*/
		}	
	}
	function ytoprint_pdf($order_data){#圆通生成pdf 
		if (empty($order_data)){
			cpmsg(false, '数据为空无法打印', -1);
		}
		//print_r($order_data);die;
		$products=$order_data['products'];
		// 应该考虑到面单logo占用的位置
		$marginTop = 8.4;
		$lbase= 8;
		$fontsize=9;
		$part1H=82;
		$logoH=21;//虚线到下方的距离
		$date=date('Y-m-d');
		$enfont='courier';
		$cnfont='cid0cs';
		$pdf=new \TCPDF('P', 'mm', array(104,152), true, 'UTF-8', false);
		$pdf->setPrintHeader(false);		//取消文档头部
		$pdf->setPrintFooter(false);		//取消文档尾部
		$pdf->SetMargins(8,$marginTop,8);			//设置外边距
		$pdf->SetAutoPageBreak(false);  	//取消自动分页
		
		$pdf->setCellPaddings(0, 0, 0, 0);	//设置单元格内边距
		$pdf->SetFillColor(255, 255, 255);	//设置单元格背景颜色

		$pdf->AddPage();
		
		$pdf->SetFont('cid0cs', 'B', 25);		//设置字体与大小
		$yundanno= $order_data['transportNo'];//
		$bigname =  $order_data['bigname'];
		if(empty($bigname)){
			$bigname= $order_data['city_name'];			
		}
		$pdf->writeHTMLCell(0, 10, 8, $marginTop, $bigname, 0, 1, 1, true, 'C', true);
		$y= $pdf->GetY();
		$pdf->SetFont('cid0cs', '', 9);		//设置字体与大小
		$content = '<img src="'.URI_PATH.'/barcode?code='.$yundanno.'&h=20" width="220" /><br /><b>'.$yundanno.'</b>';
		$pdf->writeHTMLCell(0, 0, 8, $y, $content, 0, 1, 1, true, 'C', true);
		/*$content = '<br><hr /><hr />'.$v['product_sn'].'<hr />('.$prop_value.')<hr />'.$v['order_sn'].'<hr />, '.$v['barcode'].'<hr />'.$v['transportNo'].'<hr><br><img height="30" src="'.URI_PATH.'/barcode?code='.$v['barcode'].'&h=20" /><br><br><br>';*/
		$v=  $order_data['products'][0];
		$pdf->SetFont('cid0cs', '', 9);
		
		$content = '收件地址：'.$order_data['province_name'].$order_data['city_name'].$order_data['county_name'].$order_data['address'].'<br />收件人：'.$order_data['consignee'].'<br />收件人电话：'.$order_data['mobile'].'<br />';
		
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, 8, $y, $content, 0, 1, 1, true, 'L', true);
		
		$content='寄件人：';
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, 8, $y, $content,0, 0, 1, true, 'L', true);
		//英文
		$content='Will International';
		$pdf->SetFont($enfont, '', $fontsize);
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, 8+14, $y, $content, 0, 1, 1, true, 'L', true);
		
		
		$content='寄件地址：';
		$pdf->SetFont($cnfont, '', $fontsize);
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, $lbase, $y, $content,0, 0, 1, true, 'L', true);
		
		$content='Nangye-ro169, Hwanghak-dong, Jung-gu, Seoul, Korea';
		$pdf->SetFont($enfont, '', $fontsize);
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, $lbase+16, $y, $content, 0, 1, 1, true, 'L', true);
		
		$content='电话：+82 070 4005 8602';
		$pdf->SetFont($cnfont, '', $fontsize);
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, $lbase, $y, $content, 0, 1, 1, true, 'L', true);
		$content='内附件名: ';
		foreach($products as $k=>$v){
			if($k<=2){
				$content.=$v['product_name'].$v['product_sn'].',';
			}
		}
		$content=rtrim($content,',');
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, $lbase, $y, $content, 0, 1, 1, true, 'L', true);
		
		$content= '<br /><br />收件人签名: _ _ _ _ _ _ _ _ _ _ _';
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, 8+40, $y, $content,0, 1, 1, true, 'R', true);
		//副联
		$pdf->SetY($part1H+$logoH);
		$content = '收件地址：'.$order_data['province_name'].$order_data['city_name'].$order_data['county_name'].$order_data['address'].'<br />收件人：'.$order_data['consignee'].'<br />收件人电话：'.$order_data['mobile'].'<br/><br/>'.$yundanno.'<br/>  '.$date;
		
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, 8, $y, $content, 0, 1, 1, true, 'L', true);
		//保存文件备份
		$tardir= 'elect_waybill/';
		!file_exists( $tardir ) && mkdir( $tardir ) && chmod( $tardir, 0777 );
		
		$pdf->output($tardir.$yundanno.'_'.date('YmdHis').'.pdf', 'F');
		$pdf->output('waybill'.date('YmdHi'), 'I');
		die;
	}
		//sto通上传
	function stoupload($order_data){
		if(empty($order_data)){return false;}
		//接口参数写公共的 里面
		$key='vip';
		$user='市场部-非尚服饰';
		$vsto= 'f4bcec5fce1d1c00e3651f79b6a72c0e';
		$cusname=$user;
		$cusite='江苏苏州公司';
		$bigname =$order_data['bigname'];

		$order_yundanno = new md\order_yundanno();
		$num = $order_yundanno -> count_yundanno('sto');
		if(intval($num)<1){
			//无单号则请求单号
			$this->fetch_yundanno_sto($vsto ,$cusname,$cusite  );
		}
		#分配单号
		$mailNo = $order_yundanno -> get_yundanno('sto');
		if(!$mailNo){
			echo mysql_error();
			die('无可用的单号!');	
		}
		//上传订单信息 返回商品信息
		$dazi=$bigname;
		$products=$this->upload_order2sto($vsto,$cusite,$cusname,$mailNo,$dazi,$order_data);//2
		$order_data['transportNo']= $mailNo;
		$order_data['products']= $products;
		//打印
		$this->stoprint_pdf($order_data);//	
	}
	function fetch_yundanno_sto($vsto,$cusname,$cusite){
		//获取热敏单 3
		$yundannum=2; //一次最多500可以成功
		$url2= 'http://vip.sto.cn/PreviewInterfaceAction.action?code=vip0009&data_digest='.$vsto.'&cusname='.$cusname.'&cusite='.$cusite.'&len='.$yundannum.'';
		$data2= file_get_contents($url2);//
		$data2= json_decode($data2);
		if($data2->success==true){
			$horder=$data2->data ; //热敏单号  入库
			$yundans= explode(',',$horder);
			foreach($yundans as $k=>$v){
				$v2=trim($v);
				$v3=array('yundanno'=>$v2,'logiser'=>'sto','add_time'=>time()); 
				$ordersns[$k]= $v3;
			}
			//单号插入库
			$order_yundanno = new md\order_yundanno();
			$ret = $order_yundanno -> add($ordersns);
			
		}else{//错误保存进日志表
			$err_msg= $data2->message;
			$con='请求单号@'.addslashes( var_export($data2,TRUE)); 
			$t=time();
			$timet= date('Y-m-d H:i:s');
			$con='请求单号'.$con;
			$data=array('title'=>'批量请求STO运单号失败',
						'logcontent'=> $con,
						'time'=> $t);
			$result= $this->add_log($data);
			echo '<br>批量请求申通运单号失败 无法上传订单信息 请联系网点发放单号';
			die;
		}	
	}
	/*---------------上传订单信息到STO----------------*/
	function upload_order2sto($vsto,$cusite,$cusname ,$mailNo,$dazi,$order_data){
		//运单信息上传 sto总部  url里不能有空格! 中文要先编码再使用 多件商品  对utf8全角空格1     2
		$date= date('Y-m-d');
		//多个商品都发送上去 获取商品信息 
		$order_yundanno = new md\order_yundanno();
		$products = $order_yundanno -> get_order_product($order_data['order_id']);
		//print_r($order_data);print_r($products);//die;
		
		$od= $order_data;
		$horder =$mailNo;
		$uid= $_SESSION['admin']['uid'];
		$r=$dazi ;
		
		$url3="http://vip.sto.cn/PreviewInterfaceAction.action?code=vip0007&data_digest=".$vsto."&data=[";
		$url_zb="http://vip.sto.cn/PreviewInterfaceAction.action";//必须参数
		$pro2= str_replace("c2a0","",bin2hex($od['province_name']));
		$pro= $this->hex2bin($pro2);
		$city=$this->hex2bin(str_replace("c2a0","",bin2hex($od['city_name'])));
		$district= $this->hex2bin(str_replace("c2a0","",bin2hex($od['county_name'])));
		$goodsname='';
		#多个商品都发过去 
		foreach($products as $k=>$v){
			$goodsname.=$this->hex2bin(str_replace("c2a0","",bin2hex($v['product_name']))).',';
		}
		$goodsname= rtrim($goodsname,',');
		$goodsname=$this->hex2bin(str_replace("c2a0","",bin2hex($goodsname)));
		//读取   直接按 发货人信息里的
		/*$sql_sender= "select * from admin_users where uname= 'fsmall' and status=1";
		$this->db->usedb('threepl');
		$r_sender= $this->db->row( $sql_sender);*/
		$od['sender']='Will International';
		$od['sendertel']='+82 070 4005 8602';
		$od['senderaddr']='Nangye-ro169, Hwanghak-dong, Jung-gu, Seoul, Korea';
		$od['senderpro']='江苏';
		$od['sendercity']='苏州';
		$od['senderarea']='吴中区';
		
		//$r_sender= $this->row( $sql_sender);
		if( empty($od['sendertel'])){  #寄件人电话不能为空  
			
		}
		if( empty($od['senderaddr'])){ //寄件人地址不能为空  
			
		}
		$par="{'billno':'".$horder."','senddate':'".$date."','sendsite':'".$cusite."','sendcus':'".$cusname."','sendperson':'".$od['sender']."','sendtel':'".$od['sendertel']."','receivecus':'".$od['consignee']."','receiveperson':'".$od['consignee']."','receivetel':'".$od['mobile']."','goodsname':'".$goodsname."','inputdate':'','inputperson':'".$cusname."','inputsite':'".$cusite."','lasteditdate':'','lasteditperson':'','lasteditsite':'','remark':'','receiveprovince':'".$pro."','receivecity':'".$city."','receivearea':'".$district."','receiveaddress':'".$od['address']."','sendprovince':'".$od['senderpro']."','sendcity':'".$od['sendercity']."','sendarea':'".$od['senderarea']."','sendaddress':'".$od['senderaddr']."','weight':'','productcode':'','sendpcode':'','sendccode':'','sendacode':'','receivepcode':'','receiveccode':'','receiveacode':'','bigchar':'".$dazi."','orderno':'".$od['order_sn']."'}";
		$url3.=$par;
		$url3.=']';
		$fields= 'code=vip0007&data_digest='.$vsto.'&data=['.$par.']';
		$yundanup= curl_request($url_zb,$fields);
		$yundanup= json_decode($yundanup);
		if($yundanup->success==true){ //上传成功 修改状态 
			if($this->debugmode==1){
				$data=array('title'=>$mailNo,
					'logcontent'=> 'sto upload order success',
					'time'=> time());
			}else{
				$result = $order_yundanno -> update_yundanno($mailNo);
			}
			return $products;
		}else{//失败则计入日志表
			$con=addslashes( var_export($yundanup,TRUE)); 
			$t=time();
			$con='上传订单到总部@'.$con;
			$data=array('title'=>$mailNo,
					'logcontent'=> $con,
					'time'=> $t);
			$result= $this->add_log($data);

			echo '上传订单到sto总部失败<br><br>';
			dump($yundanup);
			echo '<br><br>'.$url3;
			die('');
			if($this->debugmode==true){
				var_dump($yundanup);	
			}
		}		
	}	
	/*-----------------STO打印-----------------*/
	function stoprint_pdf($order_data){
		if (empty($order_data)){
			cpmsg(false, '数据为空无法打印', -1);
		}
		//print_r($order_data);die;
		$products=$order_data['products'];
		// 应该考虑到面单logo占用的位置
		$marginTop = 8.4;
		$lbase= 8;
		$fontsize=9;
		$part1H=82;
		$logoH=21;//虚线到下方的距离
		$date=date('Y-m-d');
		$enfont='courier';
		$cnfont='cid0cs';
		$pdf=new \TCPDF('P', 'mm', array(104,152), true, 'UTF-8', false);
		$pdf->setPrintHeader(false);		//取消文档头部
		$pdf->setPrintFooter(false);		//取消文档尾部
		$pdf->SetMargins(8,$marginTop,8);			//设置外边距
		$pdf->SetAutoPageBreak(false);  	//取消自动分页
		
		$pdf->setCellPaddings(0, 0, 0, 0);	//设置单元格内边距
		$pdf->SetFillColor(255, 255, 255);	//设置单元格背景颜色

		$pdf->AddPage();
		
		$pdf->SetFont('cid0cs', 'B', 25);		//设置字体与大小
		$yundanno= $order_data['transportNo'];//
		$bigname =  $order_data['bigname'];
		if(empty($bigname)){
			$bigname= $order_data['city_name'];			
		}
		$pdf->writeHTMLCell(0, 10, 8, $marginTop, $bigname, 0, 1, 1, true, 'C', true);
		$y= $pdf->GetY();
		$pdf->SetFont('cid0cs', '', 9);		//设置字体与大小
		$content = '<img src="'.URI_PATH.'/barcode?code='.$yundanno.'&h=20" width="220" /><br /><b>'.$yundanno.'</b>';
		$pdf->writeHTMLCell(0, 0, 8, $y, $content, 0, 1, 1, true, 'C', true);
		/*$content = '<br><hr /><hr />'.$v['product_sn'].'<hr />('.$prop_value.')<hr />'.$v['order_sn'].'<hr />, '.$v['barcode'].'<hr />'.$v['transportNo'].'<hr><br><img height="30" src="'.URI_PATH.'/barcode?code='.$v['barcode'].'&h=20" /><br><br><br>';*/
		$v=  $order_data['products'][0];
		$pdf->SetFont('cid0cs', '', 9);
		
		$content = '收件地址：'.$order_data['province_name'].$order_data['city_name'].$order_data['county_name'].$order_data['address'].'<br />收件人：'.$order_data['consignee'].'<br />收件人电话：'.$order_data['mobile'].'<br />';
		
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, 8, $y, $content, 0, 1, 1, true, 'L', true);
		
		$content='寄件人：';
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, 8, $y, $content,0, 0, 1, true, 'L', true);
		//英文
		$content='Will International';
		$pdf->SetFont($enfont, '', $fontsize);
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, 8+14, $y, $content, 0, 1, 1, true, 'L', true);
		
		
		$content='寄件地址：';
		$pdf->SetFont($cnfont, '', $fontsize);
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, $lbase, $y, $content,0, 0, 1, true, 'L', true);
		
		$content='Nangye-ro169, Hwanghak-dong, Jung-gu, Seoul, Korea';
		$pdf->SetFont($enfont, '', $fontsize);
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, $lbase+16, $y, $content, 0, 1, 1, true, 'L', true);
		
		$content='电话：+82 070 4005 8602';
		$pdf->SetFont($cnfont, '', $fontsize);
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, $lbase, $y, $content, 0, 1, 1, true, 'L', true);
		$content='内附件名: ';
		foreach($products as $k=>$v){
			if($k<=2){
				$content.=$v['product_name'].$v['product_sn'].',';
			}
		}
		$content=rtrim($content,',');
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, $lbase, $y, $content, 0, 1, 1, true, 'L', true);
		
		$content= '<br /><br />收件人签名: _ _ _ _ _ _ _ _ _ _ _';
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, 8+40, $y, $content,0, 1, 1, true, 'R', true);
		//副联
		$pdf->SetY($part1H+$logoH);
		$content = '收件地址：'.$order_data['province_name'].$order_data['city_name'].$order_data['county_name'].$order_data['address'].'<br />收件人：'.$order_data['consignee'].'<br />收件人电话：'.$order_data['mobile'].'<br/><br/>'.$yundanno.'<br/>  '.$date;
		
		$y= $pdf->GetY();
		$pdf->writeHTMLCell(0, 0, 8, $y, $content, 0, 1, 1, true, 'L', true);
		//保存文件备份
		$tardir= 'elect_waybill/';
		!file_exists( $tardir ) && mkdir( $tardir ) && chmod( $tardir, 0777 );
		
		$pdf->output($tardir.$yundanno.'_'.date('YmdHis').'.pdf', 'F');
		$pdf->output('waybill'.date('YmdHi'), 'I');
		die;
	}
	public function hex2bin($h){
        if (!is_string($h)) return null;
            $r='';
            for ($a=0; $a<strlen($h); $a+=2) { $r.=chr(hexdec($h{$a}.$h{($a+1)})); }
            return $r;
    }
}