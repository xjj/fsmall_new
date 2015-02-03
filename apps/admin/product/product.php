<?php
namespace admin\product;

use model as md;
use admin as adm;

/**
 +--------------------------------
 *	商品信息类
 +--------------------------------
 */
class Product extends adm\Front {
	
	//选择类目 -- 添加商品之前
	function cat(){
		$cat = new md\category();
		$category_items = $cat -> child_items(0, 1);
		$this -> smarty -> assign('category_items', $category_items);
		$this -> smarty -> display('product/product_add_cat.tpl');
	}
	
	//商品列表
	function items(){
		
		$page = intval($_GET['page']);
		$page = $page < 1 ? 1 : $page;
		$pagesize = 20;
		
		$prd = new md\product();
		$ret = $prd -> search($_GET, $page, $pagesize);
		$product_items = $ret['items'];
		$total = $ret['total'];
		
		//分页设置
		$GET = $_GET;
		if (isset($GET['page'])){unset($GET);}
		$params = fetch_url_query($GET);
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this->col,
			'params' => $GET
		));
		
		
		//查询所有类目信息
		$cat = new md\category();
		$cat_items = $cat -> layer_child_items(1, 1);
		
		//查询所有品牌
		$brand = new md\brand();
		$brand_items = $brand -> items();
		//var_dump($product_items);
		$this -> smarty -> assign('cat_items', $cat_items);
		$this -> smarty -> assign('brand_items', $brand_items);
		$this -> smarty -> assign('product_items', $product_items);
		$this -> smarty -> assign('params', $params);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('product/product_list.tpl');
	}
	
	//添加商品
	function add(){
		if (isset($_POST['submit'])){	
			$this -> add_submit();
			exit();
		}
		
		$cat_id = $this -> params[0];
		$cat = new md\category();
		$cat_data = $cat -> info($cat_id);
		if ($cat_data){} else {
			cpmsg(false, '该类目信息不存在或已被删除！', -1);
		}
		if ($cat_data['layer'] != 3){
			cpmsg(false, '商品类目信息不正确，请重新选择！', -1);
		}
		$tb_cat_id = $cat_data['tb_cat_id'];
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){
			cpmsg(false, '商品类目信息不正确，缺少淘宝类目信息，无法获取淘宝属性！', -1);
		}
		
		//查询类目路径
		$pathArr = $cat -> parent_catname_path($cat_id);
		if ($pathArr){
			$cat_data['cat_path'] = implode(' &gt; ',$pathArr);
		} else {
			cpmsg(false, '商品类目信息不正确！', -1);
		}
		
		//查询所有品牌
		$brand = new md\brand();
		$brand_items = $brand -> items(1);
		
		//获取商品风格数据
		$prd = new md\product();
		$product_styles = $prd -> fetch_styles();
		
		//获取淘宝普通属性及其所有属性值
		$tb_attr = new md\tb_attr();
		$tb_attr_items = $tb_attr -> attrs_and_values($tb_cat_id);
		
		//获取淘宝销售属性
		$tb_prop = new md\tb_prop();
		$tb_prop_items = $tb_prop -> items($tb_cat_id, 0, 1);
		if ($tb_prop_items){} else {
			cpmsg(false, '淘宝销售属性没有设置，请与管理员联系！', -1);
		}
		
		$this -> smarty -> assign('cat_data', $cat_data);
		$this -> smarty -> assign('brand_items', $brand_items);
		$this -> smarty -> assign('product_style', $product_styles);
		$this -> smarty -> assign('tb_attr_items', $tb_attr_items);
		$this -> smarty -> assign('tb_prop_items', $tb_prop_items);
		$this -> smarty -> display('product/product_add.tpl');
	}
	
	//商品添加操作
	function add_submit(){
		$product = new md\product();
		
		$product_name = trim($_POST['product_name']);
		$product_name_kr = trim($_POST['product_name_kr']);
		$cat_id = intval($_POST['cat_id']);
		$brand_id = intval($_POST['brand_id']);
		$product_sn = trim($_POST['product_sn']);
		$price_kr = intval($_POST['price_kr']);
		$price = intval($_POST['price']);
		$pic = trim($_POST['pic']);
		$weight = trim($_POST['weight']);
		$kr_url = trim($_POST['kr_url']);
		$content = trim($_POST['content']);
		$style = $_POST['style'];					//风格数组
		$tb_attrs = $_POST['tb_attrs']; 			//淘宝属性数组
		$prop_value_cn = $_POST['prop_value_cn'];	//SKU数组中文
		$prop_value_kr = $_POST['prop_value_kr'];	//SKU数组韩文
		$prop_price = $_POST['prop_price'];			//SKU数组价格
		$prop_price_kr = $_POST['prop_price_kr'];	//SKU数组韩价
		$prop_number = $_POST['prop_number'];
		$prop_stock = $_POST['prop_stock'];
		$prop_soldout = $_POST['prop_soldout'];
		$is_spot = intval($_POST['is_spot']);
		$is_no_refund = intval($_POST['is_no_refund']);
		$is_freight = intval($_POST['is_freight']);
		$keywords = trim($_POST['keywords']);
		$description = trim($_POST['description']);
		$remark = trim($_POST['remark']);
		$adm_uid = intval($_POST['adm_uid']);
		
		if ($is_spot != 1){$is_spot = 0;}
		if ($is_no_refund != 1){$is_no_refund = 0;}
		if ($is_freight != 1){$is_freight = 0;}
		
		if ($cat_id <= 0){
			cpmsg(false, '缺少类目信息，我不知道你怎么提交的！', -1);	
		}
		if (empty($product_name)){
			cpmsg(false, '请填写商品的中文名称！', -1);
		}
		if (hasKrWord($product_name)){
			cpmsg(false, '商品中文名称中不能含有韩文字符！', -1);
		}
		if (empty($product_name_kr)){
			cpmsg(false, '请填写商品的韩文名称！', -1);
		}
		if ($brand_id <= 0){
			cpmsg(false, '请选择商品品牌！', -1);
		}
		if (empty($product_sn)){
			cpmsg(false, '请填写韩网商品编号或货号，以区别不同的商品！', -1);
		}
		if ($price_kr <= 0 && $price <= 0){
			cpmsg(false, '韩网价格与RMB价格，至少要填写一个！', -1);
		}
		if (empty($pic)){
			cpmsg(false, '请上传商品主图，要求宽高不小于500像素！', -1);
		}
		if ($weight <= 0){
			cpmsg(false, '商品重量不能为空或小于等于零，程序要用它来计算国际运费！请按照实际商品重量来填写，太大和太小都不好！', -1);
		}
		if ($is_spot == 0 && empty($kr_url)){
			cpmsg(false, '请填写韩网商品地址，这个很重要！', -1);
		}
		if (hasKrWord($content)){
			cpmsg(false, '商品描述中不能含有韩文字符！', -1);
		}
		
		//查询类目信息
		$cat = new md\category();
		$cat_data = $cat -> info($cat_id);
		if ($cat_data){
			$tb_cat_id = $cat_data['tb_cat_id'];
		} else {
			cpmsg(false, '获取商品类目信息失败，该类目不存在或已被删除！', -1);
		}
		
		//查询所有必选淘宝属性
		$tb_attr = new md\tb_attr();
		$required = $tb_attr -> required($tb_cat_id);
		if ($required){
			//检查是否必选属性都有值	
			foreach ($required as $item){
				$tb_attr_id = $item['tb_attr_id'];
				if (empty($tb_attrs[$tb_attr_id])){
					cpmsg(false, '淘宝属性中有必选项没有选择或填写！', -1);
				}
			}
		}
		
		//淘宝属性信息
		$attr_data = $this -> filter_attrs($tb_attrs);
		if ($attr_data){} else {
			cpmsg(false, '淘宝属性获取失败，请确认是否已经填写！', -1);
		}
		
		//获取销售属性信息
		$prop_data = $this -> filter_props($prop_value_cn, $prop_value_kr);
		if ($prop_data){} else {
			cpmsg(false, '获取销售属性失败，请确认中韩文信息是否都已填写！', -1);
		}
		
		//获取图片缩略图地址
		$p = new md\picture();
		$pic_large = $p -> thumb_url($pic, 500, 500);
		$pic_thumb = $p -> thumb_url($pic, 300, 300);
		$pic_small = $p -> thumb_url($pic, 150, 150);
		
		//计算商品价格 -- 用于销售属性的价格设置
		$rate = new md\rate();
		if ($price_kr > 0 && $price <= 0){
			$price = $rate -> krw_to_cny($price_kr);
		} elseif ($price > 0 && $price_kr <= 0){
			$price_kr = $rate -> cny_to_krw($price);
		}
		
		$prd_id = $product -> add(array(
			'product_name' => $product_name,
			'product_name_kr' => $product_name_kr,
			'cat_id' => $cat_id,
			'brand_id' => $brand_id,
			'product_sn' => $product_sn,
			'price_kr' => $price_kr,
			'price' => $price,
			'weight' => $weight,
			'pic' => $pic,
			'pic_large' => $pic_large,
			'pic_thumb' => $pic_thumb,
			'pic_small' => $pic_small,
			'kr_url' => $kr_url,
			'content' => $content,
			'style' => $style,
			'keywords' => $keywords,
			'description' => $description,
			'remark' => $remark,
			'is_spot' => $isspot,
			'is_no_refund' => $is_no_refund,
			'is_freight' => $is_freight,
			'adm_uid' => $adm_uid,
		));
		
		if ($prd_id){
			
			//保存淘宝属性
			$prd_attr = new md\product_attr();
			$prd_attr -> add_multi($prd_id, $attr_data);
			
			//保存SKU信息
			$data = array(
				'prop_value_cn' => $prop_value_cn,
				'prop_value_kr' => $prop_value_kr,
				'prop_price_kr' => $prop_price_kr,
				'prop_price' 	=> $prop_price,
				'prop_number' 	=> $prop_number,
				'prop_stock' 	=> $prop_stock,
				'prop_soldout' 	=> $prop_soldout,
			);
			$sku_data = $this -> filter_skus($data, $price, $price_kr);
			
			//保存SKU同时保存销售属性
			$sku = new md\product_SKU();
			$sku -> add($prd_id, $sku_data);
			
			
			cpmsg(true, '商品添加成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '商品添加失败，请与管理员联系，查找问题！', -1);	
		}
	}
	
	//商品编辑
	function edit(){
		if (isset($_POST['submit'])){
			$this -> edit_submit();
			exit();
		}
		
		$prd_id = $this -> params[1];
		if (!isint($prd_id) || $prd_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		//查询商品信息
		$prd = new md\Product();
		$prd_data = $prd -> info($prd_id);
		if ($prd_data){} else {
			cpmsg(false, '该商品信息不存在或已被删除！', -1);
		}
		$cat_id = $prd_data['cat_id'];
		
		if (!empty($prd_data['style'])){
			$prd_data['product_style'] = explode(',', $prd_data['style']);
		}
		
		$product_style = $prd -> fetch_styles();
		
		//获取类目信息
		$cat = new md\category();
		$cat_data = $cat -> info($cat_id);
		if ($cat_data){} else {
			cpmsg(false, '该商品类目信息不存在或已被删除！', -1);
		}
		if ($cat_data['layer'] < 3){
			cpmsg(false, '该商品类目信息不正确，该类目不是第三级类目！', -1);
		}
		$tb_cat_id = $cat_data['tb_cat_id'];
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){
			cpmsg(false, '商品类目信息有问题，淘宝类目ID错误！', -1);
		}
		
		//查询类目路径
		$pathArr = $cat -> parent_catname_path($cat_id);
		if ($pathArr && isset($pathArr[3])){
			$cat_data['cat_path'] = implode(' &gt; ',$pathArr);
		} else {
			cpmsg(false, '商品类目信息不正确！', -1);
		}
		
		//获取淘宝普通属性及其选项
		$tb_attr = new md\tb_attr();
		$tb_attr_items = $tb_attr -> attrs_and_values($tb_cat_id);
		
		//获取销售属性
		$tb_prop = new md\tb_prop();
		$tb_prop_items = $tb_prop -> items($tb_cat_id);
		if ($tb_prop_items){} else {
			cpmsg(false, '该商品类目淘宝属性中，销售属性为空，没有设置，请与管理员联系！', -1);
		}
		
		//查询所有品牌
		$brand = new md\brand();
		$brand_items = $brand -> items(1);
		
		//查询商品的淘宝属性信息
		$prd_attr = new md\product_attr();
		$prd_attr_items = $prd_attr -> items($prd_id);
		
		//查询商品的SKU属性信息
		$prd_sku = new md\product_sku();
		$prd_sku_items = $prd_sku -> items($prd_id, 'all');
		
		
		$this -> smarty -> assign('prd_data', $prd_data);
		$this -> smarty -> assign('cat_data', $cat_data);
		$this -> smarty -> assign('brand_items', $brand_items);
		$this -> smarty -> assign('product_style', $product_style);
		$this -> smarty -> assign('tb_attr_items', $tb_attr_items);
		$this -> smarty -> assign('tb_prop_items', $tb_prop_items);
		$this -> smarty -> assign('prd_attr_items', $prd_attr_items);
		$this -> smarty -> assign('prd_sku_items', $prd_sku_items);
		
		$this -> smarty -> display('product/product_edit.tpl');
	}
	
	//编辑的保存操作
	function edit_submit(){
		
		$prd = new md\Product();
		
		$prd_id = intval($_POST['prd_id']);
		$product_name = trim($_POST['product_name']);
		$product_name_kr = trim($_POST['product_name_kr']);
		$cat_id = intval($_POST['cat_id']);
		$brand_id = intval($_POST['brand_id']);
		$product_sn = trim($_POST['product_sn']);
		$price_kr = intval($_POST['price_kr']);
		$price = intval($_POST['price']);
		$pic = trim($_POST['pic']);
		$weight = trim($_POST['weight']);
		$kr_url = trim($_POST['kr_url']);
		$content = trim($_POST['content']);
		$style = $_POST['style']; 						//风格数组
		$tb_attrs = $_POST['tb_attrs']; 				//淘宝属性数组
		$prop_value_cn = $_POST['prop_value_cn'];		//SKU数组中文
		$prop_value_kr = $_POST['prop_value_kr'];		//SKU数组韩文
		$prop_price = $_POST['prop_price'];				//SKU数组价格
		$prop_price_kr = $_POST['prop_price_kr'];		//SKU数组韩价
		$prop_number = $_POST['prop_number'];
		$prop_stock = $_POST['prop_stock'];
		$prop_soldout = $_POST['prop_soldout'];
		$is_spot = intval($_POST['is_spot']);
		$is_no_refund = intval($_POST['is_no_refund']);
		$is_freight = intval($_POST['is_freight']);
		$keywords = trim($_POST['keywords']);
		$description = trim($_POST['description']);
		$remark = trim($_POST['remark']);
		$adm_uid = $_SESSION['admin']['uid'];
		
		if ($is_spot != 1){$is_spot = 0;}
		if ($is_no_refund != 1){$is_no_refund = 0;}
		if ($is_freight != 1){$is_freight = 0;}
		
		if ($prd_id <= 0){
			cpmsg(false, '商品ID信息缺失！', -1);	
		}
		if (empty($product_name)){
			cpmsg(false, '请填写商品的中文名称！', -1);
		}
		if (hasKrWord($product_name)){
			cpmsg(false, '商品中文名称中不能含有韩文字符！', -1);
		}
		if (empty($product_name_kr)){
			cpmsg(false, '请填写商品的韩文名称！', -1);
		}
		if (hasCnWord($product_name_kr)){
			cpmsg(false, '商品韩文名称中含有中文字符！', -1);
		}
		if ($brand_id <= 0){
			cpmsg(false, '不可知的商品品牌！', -1);
		}
		if (empty($product_sn)){
			cpmsg(false, '请填写商品编号或货号，以区别不同的商品！', -1);
		}
		if (empty($pic)){
			cpmsg(false, '请上传商品主图，要求宽高不小于500像素！', -1);
		}
		if ($price_kr <= 0 && $price <= 0){
			cpmsg(false, '韩网价格与RMB价格，至少要填写一个！', -1);
		}
		if ($weight <= 0){
			cpmsg(false, '商品重量不能为空或小于等于零，程序要用它来计算国际运费！请按照实际商品重量来，太大和太小都不好！', -1);
		}
		if (empty($kr_url)){
			cpmsg(false, '请填写韩网商品地址，这个很重要！', -1);
		}
		if (hasKrWord($content)){
			cpmsg(false, '商品描述中不能含有韩文字符！', -1);
		}
		
		$prd_data = $prd -> info($prd_id);
		if ($prd_data){} else {
			cpmsg(false, '你要编辑的商品不存在或已被删除！', -1);
		}
		
		//分析韩网地址
		$ret = $prd -> filter_kr_url($kr_url);
		if ($ret){} else {
			cpmsg(false, '据分析，韩网商品地址好像不对，请检查确认。如果确认自己是正确的，请与管理员联系！', -1);
		}
		
		//查询类目信息
		$cat = new md\category();
		$cat_data = $cat -> info($cat_id);
		if ($cat_data){} else {
			cpmsg(false, '获取商品类目信息失败，该类目不存在或已被删除！', -1);
		}
		$tb_cat_id = $cat_data['tb_cat_id'];
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){
			cpmsg(false, '商品类别淘宝属性信息错误！', -1);
		}
		
		//查询所有必选淘宝属性
		$tb_attr = new md\tb_attr();
		$required = $tb_attr -> required($tb_cat_id);
		if ($required){
			//检查是否必选属性都有值	
			foreach ($required as $item){
				$tb_attr_id = $item['tb_attr_id'];
				if (empty($tb_attrs[$tb_attr_id])){
					cpmsg(false, '淘宝属性中有必选项没有选择！', -1);
				}
			}
		}
		
		//淘宝属性信息
		$attr_data = $this -> filter_attrs($tb_attrs);
		if ($attr_data){} else {
			cpmsg(false, '淘宝属性获取失败，请确认是否已经填写！', -1);
		}
		
		//获取销售属性信息
		$prop_data = $this -> filter_props($prop_value_cn, $prop_value_kr);
		if ($prop_data){
		} else {
			cpmsg(false, '获取销售属性失败，请确认中韩文信息是否都已填写！', -1);
		}
		
		//计算商品价格 -- 用于销售属性的价格设置
		$rate = new md\rate();
		if ($price_kr > 0 && $price <= 0){
			$price = $rate -> krw_to_cny($price_kr);
		} elseif ($price > 0 && $price_kr <= 0){
			$price_kr = $rate -> cny_to_krw($price);
		}
			
		//获取图片缩略图地址
		$p = new md\picture();
		$pic_large = $p -> thumb_url($pic, 500, 500);
		$pic_thumb = $p -> thumb_url($pic, 300, 300);
		$pic_small = $p -> thumb_url($pic, 150, 150);
	
		$num = $prd -> edit($prd_id, array(
			'cat_id' => $cat_id,
			'product_name' => $product_name,
			'product_name_kr' => $product_name_kr,
			'brand_id' => $brand_id,
			'product_sn' => $product_sn,
			'price_kr' => $price_kr,
			'price' => $price,
			'weight' => $weight,
			'pic' => $pic,
			'pic_large' => $pic_large,
			'pic_thumb' => $pic_thumb,
			'pic_small' => $pic_small,
			'kr_url' => $kr_url,
			'content' => $content,
			'style' => $style,
			'keywords' => $keywords,
			'description' => $description,
			'remark' => $remark,
			'is_spot' => $is_spot,
			'is_no_refund' => $is_no_refund,
			'is_freight' => $is_freight
		));
		
		if ($num > 0){
			//更新淘宝属性
			$prd_attr = new md\product_Attr();
			$prd_attr -> update_multi($prd_id, $attr_data);
			
			//更新SKU信息
			$data = array(
				'prop_value_cn' => $prop_value_cn,
				'prop_value_kr' => $prop_value_kr,
				'prop_price_kr' => $prop_price_kr,
				'prop_price' 	=> $prop_price,
				'prop_number' 	=> $prop_number,
				'prop_stock' 	=> $prop_stock,
				'prop_soldout' 	=> $prop_soldout,
			);
			
			
			$sku_data = $this -> filter_skus($data, $price, $price_kr);
			if ($sku_data){
				
				//清除掉所有SKU
				$prd_sku = new md\product_sku();
				$prd_sku -> delete_SKUs($prd_id);
				
				$sku_data_add = $sku_data_edit = array();
				foreach ($sku_data as $item){
					if ($item['sku_id'] > 1000){
						$sku_data_edit[] = $item;
					} else {
						$sku_data_add[] = $item;
					}
				}
				
				//编辑和添加 -- 先执行编辑 -- 后执行添加
				if (!empty($sku_data_edit)){
					$prd_sku -> edit($prd_id, $sku_data_edit);
				}
				if (!empty($sku_data_add)){
					$prd_sku -> add($prd_id, $sku_data_add);
				}
			}

			cpmsg(true, '商品编辑成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '商品编辑失败，请与管理员联系，查找问题！', -1);	
		}
	}
	
	//删除商品
	function del(){
		$prd_id = $this -> params[1];
		if (!isint($prd_id) || $prd_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$params = fetch_url_query();
		$prd = new md\Product();
		$f = $prd -> delete($prd_id);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col.'?'.$params);
		} else {
			cpmsg(false, '商品删除失败，该商品不存在或已经被删除了！', -1);
		}
	}
	
	//批量删除
	function del_multi(){
		$ids = $_POST['prd_id'];
		
		$params = fetch_url_query($_GET);
		
		$prd = new md\product();
		$num = $prd -> delete_multi($ids);
		if ($num > 0){
			echo json_encode(array('error' => 0));
		} else {
			echo json_encode(array('error' => -1));
		}
	}
	
	//设置商品上线
	function sale(){
		$prd_id = $this -> params[1];
		
		if (!isint($prd_id) || $prd_id <= 0){
			echo json_encode(array('error' => -1, 'message' => '错误的请求！'));
			exit();
		}
		
	
		//判断是否有销售属性和淘宝属性
			
			
		//判断商品图片是否已经处理完毕
			
		
		
		$params = fetch_url_query($_GET);
		
		$prd = new md\product();
		$num = $prd -> update($prd_id, array('is_on_sale' => 1, 'update_time' => time()));
		if ($num > 0){
			cpurl('/'.$this -> mod.'/'.$this -> col.'?'.$params);
		} else {
			cpmsg(false, '商品上架失败！', -1);
		}
	}
	
	//设置下线
	function sale_cancle(){
		$prd_id = $this -> params[1];
		
		if (!isint($prd_id) || $prd_id <= 0){
			echo json_encode(array('error' => -1, 'message' => '错误的请求！'));
			exit();
		}
		
		$params = fetch_url_query($_GET);
		
		$prd = new md\product();
		$num = $prd -> update($prd_id, array('is_on_sale' => 0, 'update_time' => time()));
		if ($num > 0){
			cpurl('/'.$this -> mod.'/'.$this -> col.'?'.$params);
		} else {
			cpmsg(false, '商品下架失败！', -1);
		}
	}
	
	//批量下架
	function sale_cancle_multi(){
		$ids = $_POST['prd_id'];
		
		$params = fetch_url_query($_GET);
		
		$prd = new md\product();
		$num = $prd -> sale_cancle_multi($ids);
		if ($num > 0){
			echo json_encode(array('error' => 0));
		} else {
			echo json_encode(array('error' => -1));
		}
	}
	
	//设置断货
	function soldout(){
		$prd_id = $this -> params[1];
		
		if (!isint($prd_id) || $prd_id <= 0){
			echo json_encode(array('error' => -1, 'message' => '错误的请求！'));
			exit();
		}
		
		$soldout = new md\product_soldout();
		$soldout -> add($prd_id);
		cpurl('/'.$this -> mod.'/'.$this -> col.'?'.$params);
	}
	
	//取消断货
	function soldout_cancle(){
		$prd_id = $this -> params[1];
		
		if (!isint($prd_id) || $prd_id <= 0){
			echo json_encode(array('error' => -1, 'message' => '错误的请求！'));
			exit();
		}
		
		$soldout = new md\product_soldout();
		$soldout -> cancle($prd_id);
		cpurl('/'.$this -> mod.'/'.$this -> col.'?'.$params);
	}
	
	
	
	/**
	 +-------------------------------
	 *	淘宝属性过滤分离
	 +-------------------------------
	 */
	private function filter_attrs($data){
		if (empty($data) || !is_array($data)){return false;}
		
		$ret = array();
		foreach ($data as $tb_attr_id => $item){
			if (empty($item)){continue;}
			if (is_array($item)){
				//多选
				foreach ($item as $val){
					if (!empty($val)){
						$ret[] = array('tb_attr_id' => $tb_attr_id, 'tb_attr_value' => $val);
					}
				}
			} else {
				//单选或输入
				$ret[] = array('tb_attr_id' => $tb_attr_id, 'tb_attr_value' => $item);
			}
		}
		if (!empty($ret)){
			return $ret;
		}
		return false;
	}
	
	/**
	 +------------------------------------------
	 *	SKU属性分离
	 +------------------------------------------
	 */
	private function filter_SKUs($data, $prdPrice, $prdPriceKr){
		if (empty($data) || !is_array($data)){return false;}
		
		$prop_value_cn 	= $data['prop_value_cn'];		//中文属性名
		$prop_value_kr 	= $data['prop_value_kr'];		//韩文属性名
		$prop_price_kr 	= $data['prop_price_kr'];		//韩价
		$prop_price 	= $data['prop_price'];			//中价
		$prop_number 	= $data['prop_number'];			//起售数量
		$prop_stock 	= $data['prop_stock'];			//库存数量
		$prop_soldout	= $data['prop_soldout'];		//是否断货
		
		$ret = array();
		foreach ($prop_value_kr as $line => $item){
			if (empty($item) || !is_array($item)){return false;} //数据有问题
			
			$propArr = array();
			foreach ($item as $prop_value_id => $value_kr){
				$value_kr = trim($value_kr);
				$value_cn = trim($prop_value_cn[$line][$prop_value_id]);
				
				if (empty($value_cn) || empty($value_kr)){continue;}
				
				$propArr[] = array('prop_value_id' => $prop_value_id, 'value' => $value_cn, 'value_kr' => $value_kr);
			}
			if (empty($propArr)){continue;}
			
			$price_kr 	= intval($prop_price_kr[$line]);
			$price 		= intval($prop_price[$line]);
			$number 	= intval($prop_number[$line]);
			$stock		= intval($prop_stock[$line]);
			$is_soldout = intval($prop_soldout[$line]);
						
			
			//价格计算
			$rate = new md\rate();
			if ($price <= 0 && $price_kr <= 0){
				$price = $prdPrice;
				$price_kr = $prdPriceKr;
			} elseif ($price > 0 && $price_kr <= 0) {
				$price_kr = $rate -> cny_to_krw($price);
			} elseif ($price_kr > 0 && $price <= 0) {
				$price = $rate -> krw_to_cny($price_kr);
			}
			
			//起售数 库存 断货
			if ($number <= 0){$number = 1;}
			if ($stock <= 0){$stock = 1000;}
			if ($is_soldout != 1){$is_soldout = 0;}
			
			/**
			 *	在数据表中 SKU_ID 的起始ID 为 1000 
			 *	这里的$line 大于1000时是sku_id 小于1000时是新添加的SKU行号
			 */
			if ($line > 1000){
				$sku_id = $line;
			} else {
				$sku_id = 0;
			}
			
			$ret[] = array(
				'sku_id' => $sku_id,
				'props'  => $propArr,
				'price_kr' => $price_kr,
				'price' => $price,
				'number' => $number,
				'stock' => $stock,
				'is_soldout' => $is_soldout,
			);
		}
		if (!empty($ret)){
			return $ret;
		}
		return false;
	}
	
	/**
	 +-------------------------------------------
	 *	分离销售属性 注意分隔标记 -- {:}
	 +-------------------------------------------
	 */
	private function filter_props($prop_value_cn, $prop_value_kr){
		$data = array();
		foreach ($prop_value_kr as $line => $item){
			if (empty($item) || !is_array($item)){return false;} //数据格式错误
			
			foreach ($item as $prop_value_id => $value_kr){
				$value_kr = trim($value_kr);
				$value_cn = trim($prop_value_cn[$line][$prop_value_id]);
				
				if (empty($value_cn) || empty($value_kr)){continue;}
				
				$val = $value_cn. '{:}' .$value_kr;
				if (isset($data[$prop_value_id])){
					if (!in_array($val, $data[$prop_value_id])){
						$data[$prop_value_id][] = $val;
					}
				} else {
					$data[$prop_value_id][] = $val;
				}
			}
		}
		
		if (!empty($data)){
			return $data; 
		}
		return false;
	}
	
	//图片列表 查询操作 xjj  
	function pics(){
		
		$page = intval($_GET['page']);
		$page = $page < 1 ? 1 : $page;
		$pagesize = 20;
		$prd_id = $this -> params[1];
		
		//查已确认图
		$product_pics = new md\product_pics();
		$ret = $product_pics -> search(array('prd_id'=>$prd_id,'is_confirm'=>1));
		
		//查未确认图
		$ret2 = $product_pics -> search_img2(array('prd_id'=>$prd_id,'is_confirm'=>0));
		//dump($ret2);
		$this -> smarty -> assign('items', $ret['items']);
		$this -> smarty -> assign('items_unconfirm', $ret2['items']);
		$this -> smarty -> assign('title', '商品图片详情');
		$this -> smarty -> display('product/product_pics_list.tpl');
	}
	//图片 删除 操作 xjj  
	function pic_del($pic_id_array=0){
		//dumpd($this -> params );
		$pic_id = $this -> params[1];
		if($pic_id_array>0 ){ 
			$pic_id =$pic_id_array;
		}
		if (!isint($pic_id) || $pic_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		$product_pics = new md\product_pics();
		$f = $product_pics -> pic_del_img2($pic_id);
		if ($f){
			cpmsg(true, '操作成功！', -1);
		} else {
			//dump($f); echo mysql_error();
			cpmsg(false, '操作失败！', -1);
		}
	}
		//图片 批量删除 
	function pic_del_multi($data=0){
		$code=0;
		if(isset($_REQUEST['pic_id'] )&&!empty($_REQUEST['pic_id'] )||is_array($data)){
			$pic_ids=  $_REQUEST['pic_id'];
			if(is_array($data) ){$pic_ids=$data;  }
			$str=' pic_id in ( ';
			foreach($pic_ids as $v ){
				$str .=intval($v).',';	
			}
			$str = trim($str,',').') ';
			$product_pics = new md\product_pics();
			$f = $product_pics -> pic_del_img2_multi($str);
			if ($f){
				$code=1;
				$msg='操作成功';
			} else {
				$msg='操作失败';
			}	
		}else{
			$msg='参数不正确';
			
		}
		echo json_encode(array('code'=>$code,'msg'=>$msg)); 
	}
		//图片  确认  
	function pic_confirm(){
		$pic_id = $this -> params[1];
		if (!isint($pic_id) || $pic_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		$condition= array('pic_id'=> $pic_id);
		$product_pics = new md\product_pics();
		$f = $product_pics -> search_img2($condition);
		#插入fs图片表
		$r = $product_pics -> add($f);
		if($r){
			$this->pic_del($pic_id);
		}else{
			cpmsg(false, '操作失败！', -1);
		}

	}
			//图片批量  确认  
	function pic_confirm_multi(){
		$code=0; 
		$msg='操作失败';
		if(isset($_REQUEST['pic_id'] )&&!empty($_REQUEST['pic_id'] )){
			$pic_ids=  $_REQUEST['pic_id'];
			$str=' pic_id in ( ';
			foreach($pic_ids as $v ){
				$str .=intval($v).',';	
			}
			$str = trim($str,',').') ';
			$product_pics = new md\product_pics();
			$f = $product_pics -> search_img2($pic_ids);
			#插入fs图片表
			$r = $product_pics -> add($f);
			if($r){
				$this->pic_del_multi($$pic_ids);
				die;
			}
		}else{
			$msg='参数不正确';
		}
		echo json_encode(array('code'=>$code,'msg'=>$msg));
	}
		//图片 删除fs上的
	function pic_local_del($pic_id_array=0){
		$pic_id = intval($this -> params[1]);
		if($pic_id_array>0 ){ 
			$pic_id = intval($pic_id_array);
		}
		if (!isint($pic_id) || $pic_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		$product_pics = new md\product_pics();
		$f = $product_pics -> pic_local_del($pic_id);
		if ($f){
			cpmsg(true, '操作成功！', -1);
		} else {
			cpmsg(false, '操作失败！', -1);
		}
	}
	//图片批量 删除fs上的
	function pic_local_del_multi($data=0){
		$code=0;
		if(isset($_REQUEST['pic_id'] )&&!empty($_REQUEST['pic_id'] )||is_array($data)){
			$pic_ids=  $_REQUEST['pic_id'];
			if(is_array($data) ){$pic_ids=$data;  }
			$str=' pic_id in ( ';
			foreach($pic_ids as $v ){
				$str .=intval($v).',';	
			}
			$str = trim($str,',').') ';
			$product_pics = new md\product_pics();
			$f = $product_pics -> pic_del_local_multi($str);
			if ($f){
				$code=1;
				$msg='操作成功';
			} else {
				$msg='操作失败';
			}	
		}else{
			$msg='参数不正确';
			
		}
		echo json_encode(array('code'=>$code,'msg'=>$msg)); 
	}
}