<?php
namespace model;

/**
 +---------------------------
 *	商品类
 +---------------------------
 */
class Product extends Front {
	
	private $_styles = array(
		'时尚', '个性', '淑女', '性感', '可爱', '优雅', '高贵', '气质', '经典', '甜美', '复古', '欧美', '简约', '军事', 
		'哈伦', '嬉皮', '百搭', '韩版', '民族', '学院', '通勤', '嘻哈', '中性', '田园', '朋克', '街头', '洛丽塔', 'OL'
	);
	
	function fetch_styles(){
		return $this -> _styles;
	}
	
	//商品信息
	function info($prd_id, $fields = array()){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		$str = empty($fields) ? '*' : $this -> db -> implode_field_key($fields, ',');
		$sql = 'SELECT '.$str.' FROM `product_info` WHERE prd_id = '.$prd_id.'';
		$row = $this -> db -> row($sql);
		return $row;
	}
	
	/**
	 +-----------------------------------------------
	 *	获取商品数据
	 *	$data = 查询的商品数据表数据
	 +-----------------------------------------------
	 */
	function data($prd_id, $uid, $data = false){
		if (empty($data) || !is_array($data)){
			$data = $this -> info($prd_id);
		}
		
		//获取用户会员等级
		$user = new user();
		$user_data = $user -> info($uid, array('grade_id'));
		if ($user_data){
			$grade_id = $user_data['grade_id'];
		} else {
			$grade_id = 0;
		}
		
		$params = array(
			'brand_id' => $data['brand_id'],
			'cat_id' => $data['cat_id'],
			'is_spot' => $data['is_spot'],
			'price' => $data['price'],
			'freight' => $data['freight'],
			'is_freight' => $data['is_freight'],
		);
		
		$prd_price = new product_price();
		
		$ret = $prd_price -> price($prd_id, $params, $uid, $grade_id);
		
		//销售价格
		$data['price_sale'] = $ret['price'];
		if (isset($ret['discount']) && $ret['discount'] > 0) {
			$data['discount'] = $ret['discount'];
		}
		if (isset($ret['is_special'])){
			$data['is_special'] = 1;
		}
		
		//批发价格
		$ret = $prd_price -> price_wholesale($prd_id, $params, $uid);
		$data['price_wholesale'] = $ret['price'];
		
		//零售价格
		$ret = $prd_price -> price_retail($prd_id, $params, $uid);
		$data['price_retail'] = $ret['price'];
		
		return $data;
	}
	
	
	//添加商品
	function add($data){
		$product_name = trim($data['product_name']);
		$product_name_kr = trim($data['product_name_kr']);
		$cat_id = intval($data['cat_id']);
		$brand_id = intval($data['brand_id']);
		$product_sn = trim($data['product_sn']);
		$price_kr = intval($data['price_kr']);
		$price = intval($data['price']);
		$pic = trim($data['pic']);
		$pic_large = trim($data['pic_large']);
		$pic_thumb = trim($data['pic_thumb']);
		$pic_small = trim($data['pic_small']);
		$weight = trim($data['weight']);
		$kr_url = trim($data['kr_url']);
		$content = trim($data['content']);
		$style = $data['style'];
		$is_freight = intval($data['is_freight']);
		$is_spot = intval($data['is_spot']);
		$is_no_refund = intval($data['is_no_refund']);
		$keywords = trim($data['keywords']);
		$description = trim($data['description']);
		$remark = trim($data['remark']);
		$adm_uid = intval($data['adm_uid']);
		
		if ($is_freight != 1){$is_freight = 0;}
		if ($is_spot != 1){$is_spot = 0;}
		if ($is_no_refund != 1){$is_no_refund = 0;}
		
		$keywords = blank_space_replace($keywords);
		$keywords = str_replace(array('，',' '), ',', $keywords);
		
		if ($cat_id <= 0){return false;}
		if (empty($product_name)){return false;}
		if (hasKrWord($product_name)){return false;}
		if (empty($product_name_kr)){return false;}
		if ($brand_id <= 0){return false;}
		if (empty($product_sn)){return false;}
		if ($price_kr <= 0 && $price <= 0){return false;}
		if (empty($pic)){return false;}
		if ($weight <= 0){return false;}
		if ($is_spot == 0 && empty($kr_url)){return false;} //非现货韩网商品地址不能为空
		if (empty($style) || !is_array($style)){
			$product_style = '';
		} else {
			$product_style = implode(',', $style);
		}
		
		//判断商品编号是否重复
		if ($this -> isExist_SN($brand_id, $product_sn)){return false;} //品牌内商品编号是不可以重复的
		
		//查询类目信息
		$cat = new category();
		$cat_data = $cat -> info($cat_id);
		if ($cat_data){} else {return false;}
		
		$cat_id_path = $cat -> parent_catid_path($cat_id);
		$cat_id_path = trim($cat_id_path, '|');
		$cat_id_path = '|'. $cat_id_path .'|';
		
		
		//计算商品RMB价格
		/**
		 *	当仅有韩网价时 -- 计算出中国价
		 *	当仅有中国价时 -- 计算出韩网价
		 *	当两个价格都有时 -- 使用输入值
		 */
		$rate = new rate();
		if ($price_kr > 0 && $price == 0){
			$price = $rate -> krw_to_cny($price_kr);
		} elseif ($price > 0 && $price_kr == 0){
			$price_kr = $rate -> cny_to_krw($price);
		}
		
		//计算国际运费
		$freight = $this -> freight($weight);
		if ($freight){} else {return false;}
		
		//解析韩网商品地址
		$ret = $this -> filter_kr_url($kr_url);
		if ($ret){
			$kr_url = $ret['url'];
			$kr_pid = $ret['pid'];
			if ($this -> isExist_kr_url($kr_url)){return false;}
		}
		
		//写入到数据库
		return $this -> db -> insert('product_info', array(
			'product_name' => $product_name,
			'product_name_kr' => $product_name_kr,
			'cat_id' => $cat_id,
			'cat_id_path' => $cat_id_path,
			'brand_id' => $brand_id,
			'product_sn' => $product_sn,
			'price' => $price,
			'price_kr' => $price_kr,
			'weight' => $weight,
			'freight' => $freight,
			'pic' => $pic,
			'pic_large' => $pic_large,
			'pic_thumb' => $pic_thumb,
			'pic_small' => $pic_small,
			'kr_url' => $kr_url,
			'kr_pid' => $kr_pid,
			'content' => $content,
			'style' => $product_style,
			'keywords' => $keywords,
			'description' => $description,
			'remark' => $remark,
			'is_spot' => $is_spot,
			'is_no_refund' => $is_no_refund,
			'is_freight' => $is_freight,
			'is_on_sale' => 0,
			'add_time' => time(),
			'update_time' => time(),
			'adm_uid' => $adm_uid,
		));
 	}
	
	//编辑商品信息
	function edit($prd_id, $data){
		$product_name = trim($data['product_name']);
		$product_name_kr = trim($data['product_name_kr']);
		$cat_id = intval($data['cat_id']);
		$brand_id = intval($data['brand_id']);
		$product_sn = trim($data['product_sn']);
		$price_kr = intval($data['price_kr']);
		$price = intval($data['price']);
		$pic = trim($data['pic']);
		$pic_large = trim($data['pic_large']);
		$pic_thumb = trim($data['pic_thumb']);
		$pic_small = trim($data['pic_small']);
		$weight = trim($data['weight']);
		$kr_url = trim($data['kr_url']);
		$content = trim($data['content']);
		$style = $data['style']; 						//风格数组
		$is_freight = intval($data['is_freight']);
		$is_spot = intval($data['is_spot']);
		$is_no_refund = intval($data['is_no_refund']);
		$keywords = trim($data['keywords']);
		$description = trim($data['description']);
		$remark = trim($data['remark']);
		$adm_uid = intval($data['adm_uid']);
		
		if ($is_freight != 1){$is_freight = 0;}
		if ($is_spot != 1){$is_spot = 0;}
		if ($is_no_refund != 1){$is_no_refund = 0;}
		
		$keywords = blank_space_replace($keywords);
		$keywords = str_replace(array('，',' '), ',', $keywords);
		
		if ($cat_id <= 0){return false;}
		if (empty($product_name)){return false;}
		if (hasKrWord($product_name)){return false;}
		if (empty($product_name_kr)){return false;}
		if ($brand_id <= 0){return false;}
		if (empty($product_sn)){return false;}
		if ($price_kr <= 0 && $price <= 0){return false;}
		if (empty($pic)){return false;}
		if ($weight <= 0){return false;}
		if ($is_spot == 0 && empty($kr_url)){return false;}
		
		if (empty($style) || !is_array($style)){
			$product_style = '';
		} else {
			$product_style = implode(',', $style);
		}
		
		//判断prd_sn是否重复
		if ($this -> isExist_SN($brand_id, $product_sn, $prd_id)){return false;}
		
		//计算商品RMB价格
		/**
		 *	当仅有韩网价时 -- 计算出中国价
		 *	当仅有中国价时 -- 计算出韩网价
		 *	当两个价格都有时 -- 使用输入值
		 */
		$rate = new rate();
		if ($price_kr > 0 && $price == 0){
			$price = $rate -> krw_to_cny($price_kr);
		} elseif ($price > 0 && $price_kr == 0){
			$price_kr = $rate -> cny_to_krw($price);
		}
		
		//计算国际运费
		$freight = $this -> freight($weight);
		if ($freight){} else {return false;}
		
		//分析韩网地址
		$ret = $this -> filter_kr_url($kr_url);
		if ($ret){
			$kr_url = $ret['url'];
			$kr_pid = $ret['pid'];
		
			//判断韩网地址是否存在
			if ($this -> isExist_kr_url($kr_url, $prd_id)){return false;}
		}
		
		

		//写入到数据库
		return $this -> db -> update('product_info', array(
			'product_name' => $product_name,
			'product_name_kr' => $product_name_kr,
			'brand_id' => $brand_id,
			'product_sn' => $product_sn,
			'price_kr' => $price_kr,
			'price' => $price,
			'weight' => $weight,
			'freight' => $freight,
			'pic' => $pic,
			'pic_large' => $pic_large,
			'pic_thumb' => $pic_thumb,
			'pic_small' => $pic_small,
			'kr_url' => $kr_url,
			'kr_pid' => $kr_pid,
			'content' => $content,
			'style' => $product_style,
			'keywords' => $keywords,
			'description' => $description,
			'remark' => $remark,
			'is_spot' => $is_spot,
			'is_freight' => $is_freight,
			'is_no_refund' => $is_no_refund,
			'update_time' => time(),
		), array(
			'prd_id' => $prd_id
		));
	}
	
	
	//抓取链接地址中的商品ID等信息
	function filter_kr_url($url){
		//列出几种常用的地址类型 进行匹配
		$pattern = array(
			'/http:\/\/.+?\/shop\/shopdetail\.html\?brand(?:uid|code)=([\d]+)/i',
			'/http:\/\/.+?\/front\/php\/product\.php\?product_no=([\d]+)/i',
			'/http:\/\/.+?\/product\/detail\.html\?product_no=([\d]+)/i',
			'/http:\/\/.+?\/product\/product_detail\.asp\?[\s\S]*?product_code=([\d]+)/i',
			'/http:\/\/.+?\/m\/product\.html\?branduid=([\d]+)/i',
			'/http:\/\/.+?\/Front\/Product\/\?url=Product&product_no=(SFSELFAA[\d]+)/i',
			'/http:\/\/.+?\/shop\/detail\.php\?pno=([a-z0-9]+)/i',
			'/http:\/\/.+?\/shop\/view\.php\?index_no=([\d]+)/i',
		);
		
		foreach ($pattern as $reg){
			$f = preg_match($reg, $url, $matches);
			if ($f){
				return array('pid' => $matches[1],'url' => $matches[0]);
			}
		}
		
		return false;
	}
	
	//计算国际运费
	function freight($weight){
		if ($weight <= 0){return false;}
		$fee_krw = ($weight / 1000) * 10000;
		$rate = new rate();
		return $rate -> krw_to_cny($fee_krw);
	}
	
	//判断商品编号是否存在
	function isExist_SN($brand_id, $product_sn, $prd_id = 0){
		$sql = 'SELECT COUNT(*) as num FROM `product_info` WHERE brand_id = '.$brand_id.' AND product_sn = \''.encode($product_sn).'\' AND is_delete = 0';
		if ($prd_id > 0){
			$sql .= ' AND prd_id != '.$prd_id;
		}
		$row = $this -> db -> row($sql);
		if ($row['num'] > 0){
			return true;
		} else {
			return false;
		}
	}
	
	//判断韩网商品地址是否存在
	function isExist_kr_url($url, $prd_id = 0){
		$sql = 'SELECT COUNT(*) AS num FROM `product_info` WHERE kr_url = \''.encode($url).'\' AND is_delete = 0 AND is_spost = 0';
		if ($prd_id > 0){
			$sql .= ' AND prd_id != '.$prd_id;
		}
		$row = $this -> db -> row($sql);
		if ($row['num'] > 0){
			return true;
		} else {
			return false;
		}
	}
	
	
	
	//获取商品的tb_cat_id
	function fetch_tb_cat_id($prd_id){
		$sql = 'SELECT c.tb_cat_id FROM `product_info` p inner join `product_category` c on p.cat_id = c.cat_id WHERE p.prd_id = '.$prd_id.' AND c.layer = 3';
		$row = $this -> db -> row($sql);
		if ($row){
			return $row['tb_cat_id'];
		}
		return false;
	}
	
	//搜索 -- 后台商品
	function search($params, $page = 1, $pagesize = 20){
		$k  = urldecode($params['k']);
		$id = urldecode($params['id']);
		$st = urldecode($params['st']);
		$et = urldecode($params['et']);
		$brand_id = intval($params['brand_id']); 	//品牌ID
		$cat_id = intval($params['cat_id']);		//类目ID
		$status = trim($params['status']);			//状态
		
		$where = 't.is_delete = 0';
		
		//商品id存在则不再查询其他选项
		if (isint($id) && $id > 0){
			$where .= ' AND t.prd_id = '.$id.'';
		} else {
			if (!empty($k)){
				if (hasKrWord($k)){
					$where .= ' AND t.product_name_kr like \'%'.encode($k).'%\'';
				} else {
					$where .= ' AND (t.product_name like \'%'.encode($k).'%\' OR t.product_name_kr like \'%'.encode($k).'%\' OR t.product_sn like \'%'.encode($k).'%\')';
				}
			}
			if ($brand_id > 0){
				$where .= ' AND t.brand_id = '.$brand_id.'';
			}
			if ($cat_id > 0){
				$where .= ' AND t.cat_id_path like \'%|'.$cat_id.'|%\'';
			}
			switch ($status){
				case 'hot':
					$where .= ' AND t.is_hot  = 1';
					break;
				case 'best':
					$where .= ' AND t.is_best = 1';
					break;
				case 'spot':
					$where .= ' AND t.is_spot = 1';
					break;
				case 'sale':
					$where .= ' AND t.is_on_sale = 1';
					break;
				case 'soldout':
					$where .= ' AND t.is_soldout = 1';
					break;
			}
			if (isDate($st)){
				$where .= ' AND t.update_time > '.strtotime($st).'';
			}
			if (isDate($et)){
				$where .= ' AND t.update_time < '.strtotime($et).'';
			}
		}
		
		$sql = 'SELECT t.*, b.brand_name FROM `product_info` t LEFT JOIN `product_brand` b ON t.brand_id = b.brand_id WHERE '.$where.' ORDER BY t.update_time DESC, t.prd_id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$product_items = $this -> db -> rows($sql);
		
		//查询总数
		$sql = 'SELECT COUNT(*) AS num FROM `product_info` t LEFT JOIN `product_brand` b ON t.brand_id = b.brand_id WHERE '.$where.'';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array(
			'items' => $product_items,
			'total' => $total
		);
	}
	
	//更新
	function update($prd_id, $data){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		return $this -> db -> update('product_info', $data, array('prd_id' => $prd_id));
	}
	
	//断货
	function soldout($prd_id){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		return $this -> update($prd_id, array('is_soldout' => 1, 'soldout_time' => time()));
	}
	
	//取消断货
	function soldout_cancle($prd_id){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		return $this -> update($prd_id, array('is_soldout' => 0, 'soldout_time' => 0));
	}
	
	//商品删除--移到回收站
	function delete($prd_id){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		return $this -> update($prd_id, array('is_delete' => 1, 'delete_time' => time()));
	}
	
	//批量删除
	function delete_multi($idArr){
		if (!is_array($idArr) || empty($idArr)){return false;}
		
		$arr = array();
		foreach ($idArr as $id){
			if (isint($id) && $id > 0){
				$arr[] = $id;
			}
		}
		
		if (empty($arr)){return false;}
		
		$ids = implode(',', $arr);
		
		$where = 'prd_id IN ('.$ids.')';
		return $this -> db -> update('product_info', array('is_delete' => 1, 'delete_time' => time()), $whre);
	}
	
	//批量下架
	function sale_cancle_multi($idArr){
		if (!is_array($idArr) || empty($idArr)){return false;}
		
		$arr = array();
		foreach ($idArr as $id){
			if (isint($id) && $id > 0){
				$arr[] = $id;
			}
		}
		
		if (empty($arr)){return false;}
		
		$ids = implode(',', $arr);
		$where = 'prd_id IN ('.$ids.')';
		return $this -> db -> update('product_info', array('is_on_sale' => 0, 'update_time' => time()), $whre);
	}
	
	//商品查询
	function search2($params, $page, $pagesize){
		$cat_id = intval($params['cat_id']);
		$type = trim(strtolower($params['type']));		//排序类型  	sale=销量 price=价格 visit=人气
		$order = trim(strtoupper($params['order']));	//排序方式   	DESC	ASC
		$brand_id = intval($params['brand_id']);		//品牌ID
		$start_price = intval($params['start_price']);	//开始价格
		$end_price = intval($params['end_price']);		//结束价格
		
		$uid = intval($params['uid']);					//当前登录用户ID
		
		if (!in_array($type, array('sale', 'price', 'visit'))){$type = '';}
		if ($order != 'ASC'){$order = 'DESC';}
		if ($type == ''){$order = 'desc';}
		if ($start_price <= 0){$start_price = 0;}
		if ($end_price <= 0){$end_price = 0;}
		
		$where = ' WHERE is_delete = 0 AND is_on_sale = 1 AND is_soldout = 0 ';
		if ($cat_id > 0){
			$where .= ' AND cat_id_path like \'%|'.$cat_id.'|%\'';	
		}
		if ($brand_id > 0){
			$where .= ' AND brand_id = '.$brand_id.'';
		}
		if ($start_price > 0){
			$where .= ' AND (price + freight*is_freight) >= '.$start_price;
		}
		if ($end_price > 0){
			$where .= ' AND (price + freight*is_freight) <= '.$end_price;
		}
		
		//排序
		if ($type == 'sale'){
			$order_sql = ' ORDER BY month_sale_number '.$order.', prd_id DESC';
		} elseif ($type == 'price'){
			$order_sql = ' ORDER BY price '.$order.', prd_id DESC';
		} elseif ($type == 'visit'){
			$order_sql = ' ORDER BY visit_count '.$order.', prd_id DESC';	
		} else {
			$order_sql = ' ORDER BY prd_id DESC';	
		}
		
		
		$sql  = 'SELECT prd_id, product_name, price, freight, is_freight, brand_id, cat_id, pic_thumb, is_best, is_spot FROM `product_info` '. $where .' '. $order_sql .' LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$product_items = array();
			foreach ($rows as $row){
				$product_items[] = $this -> data($row['prd_id'], $uid, $row);
			}
			
			$sql = 'SELECT COUNT(*) AS num FROM `product_info` '. $where .'';
			$row = $this -> db -> row($sql);
			$total = $row['num'];
		} else {
			$product_items = false;
			$total = 0;
		}
		
		return array('items' => $product_items, 'total' => $total);
	}
	
	
	/**
	 +--------------------------------------
	 *	TOP50 -- 韩发货的
	 +--------------------------------------
	 */
	function Top($params, $count = 50){
		$cat_id = intval($params['cat_id']);
		$brand_id = intval($params['brand_id']);
		$month = intval($params['month']);
		$uid = $params['uid'];
		
		if ($month <= 0 || $month > 6){$month = 3;}
		
		$where = 'WHERE kr.prd_id > 0 AND p.is_delete = 0 AND p.is_on_sale = 1 AND p.is_soldout = 0';
		$where .= ' AND kr.send_time > '.$month*3600*24*30;
		if ($cat_id > 0){
			$where .= ' AND p.cat_id_path like \'%|'.$cat_id.'|%\'';
		}
		if ($brand_id > 0){
			$where .= ' AND p.brand_id = '.$brand_id.'';
		}
		
		$sql = 'SELECT COUNT(kr.prd_id) as number, kr.prd_id, p.product_name, p.price, p.freight, p.is_freight, p.brand_id, p.cat_id, p.pic_thumb, p.is_best, p.is_spot FROM `order_product_kr_shipping` kr LEFT JOIN `product_info` p ON kr.prd_id = p.prd_id '.$where.' GROUP BY kr.prd_id ORDER BY number DESC, kr.prd_id DESC LIMIT 0, '.$count;
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$product_items = array();
			foreach ($rows as $row){
				$product_items[] = $this -> data($row['prd_id'], $uid, $row);
			}
		} else {
			$product_items = false;
		}
		
		return $product_items;
	}
	
	//现货
	function spot_items($params, $page, $pagesize){
		$brand_id = intval($params['brand_id']);
		
		$where = 'WHERE is_delete = 0 AND is_on_sale = 1 AND is_soldout = 0 AND is_spot = 1';
		if ($brand_id > 0){
			$where .= ' AND brand_id = '.$brand_id.'';	
		}
		
		$sql  = 'SELECT prd_id, product_name, price, freight, is_freight, brand_id, cat_id, pic_thumb, is_best, is_spot FROM `product_info` '. $where .' ORDER BY prd_id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$product_items = array();
			foreach ($rows as $row){
				$product_items[] = $this -> data($row['prd_id'], $uid, $row);
			}
			
			$sql = 'SELECT COUNT(*) AS num FROM `product_info` '. $where .'';
			$row = $this -> db -> row($sql);
			$total = $row['num'];
		
		} else {
			$product_items = false;
			$total = 0;
		}
		
		return array('items' => $product_items, 'total' => $total);
	}
	
	//前台搜索
	function search3($params, $page, $pagesize){
		$kw = urldecode(trim($params['kw']));
		$brand_id = intval($params['brand_id']); 	//品牌ID
		$cat_id = intval($params['cat_id']);		//类目ID
		
		$where = ' WHERE is_delete = 0 AND is_on_sale = 1 AND is_soldout = 0';
		
		
		if (!empty($kw)){
			if (hasKrWord($kw)){
				$where .= ' AND product_name_kr like \'%'.encode($kw).'%\'';
			} else {
				$where .= ' AND (product_name like \'%'.encode($kw).'%\' OR product_name_kr like \'%'.encode($kw).'%\' OR product_sn like \'%'.encode($kw).'%\')';
			}
		}
		if ($brand_id > 0){
			$where .= ' AND brand_id = '.$brand_id.'';
		}
		if ($cat_id > 0){
			$where .= ' AND cat_id_path like \'%|'.$cat_id.'|%\'';
		}
		
		$sql = 'SELECT prd_id, product_name, price, freight, is_freight, brand_id, cat_id, pic_thumb, is_best, is_spot FROM `product_info` '.$where.' ORDER BY prd_id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$product_items = array();
			foreach ($rows as $row){
				$product_items[] = $this -> data($row['prd_id'], $uid, $row);
			}
			
			$sql = 'SELECT COUNT(*) AS num FROM `product_info` '.$where.'';
			$row = $this -> db -> row($sql);
			$total = $row['num'];
			
		} else {
			$product_items = false;
			$total = 0;
		}
		
		return array(
			'items' => $product_items,
			'total' => $total
		);	
	}
	//搜索  抓图根据韩网地址查跟以上几个查询都不同 
	function search_by_array($params, $page=1, $pagesize=10){
		$kw = trim($params['kr_url']);
		$where = ' WHERE 1=1 ';
		if (!empty($kw)){
			$where .= ' AND kr_url = \''.$kw.'\'';
		}
		$sql = 'SELECT * FROM `product_info` '.$where.' ORDER BY prd_id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		//echo $sql;
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$product_items = $rows;
			$sql = 'SELECT COUNT(*) AS num FROM `product_info` '.$where.'';
			$row = $this -> db -> row($sql);
			$total = $row['num'];
			
		} else {
			$product_items = false;
			$total = 0;
		}
		return array(
			'items' => $product_items,
			'total' => $total
		);	
	}
}