<?php
namespace admin\product;

use admin as adm;
use model as md;

/**
 +------------------------------
 *	品牌设置类
 +------------------------------
 */
class Brand extends adm\Front {
	
	//品牌列表
	function items(){
		$page = intval($_GET['page']);
		$page = $page <= 0 ? 1 : $page;
		$pagesize = 20;
		
		$brand = new md\brand();
		
		//所有品牌类型
		$brand_types = $brand -> fetch_brand_types();
		
		//查询结果
		$search_data = $brand -> search(array(), $page, $pagesize);
		$brand_items = $search_data['items'];
		$brand_total = $search_data['total'];
		
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $brand_total,
			'url' => '/'.$this->mod.'/'.$this->col
		));
		
		$this -> smarty -> assign('brand_items', $brand_items);
		$this -> smarty -> assign('brand_types', $brand_types);
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> display('product/brand_list.tpl');
	}
	
	//添加品牌
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
		}
		
		$brand = new md\brand();
		$brand_types = $brand -> fetch_brand_types();
		
		$this -> smarty -> assign('brand_types', $brand_types);
		$this -> smarty -> display('product/brand_add.tpl');
	}
	
	//品牌添加操作
	private function add_submit(){
		$brand = new md\brand();
		
		if (empty($_POST['brand_name'])){
			cpmsg(false, '请填写品牌名称！', -1);
		}
		if ($brand->isExist_brand_name($_POST['brand_name'])){
			cpmsg(false, '该品牌已存在，请更换一个谢谢！', -1);
		}
		if (empty($_POST['logo'])){
			cpmsg(false, '请上传品牌LOGO图片！', -1);
		}
		if (empty($_POST['pic'])){
			cpmsg(false, '请上传品牌栏目大图！', -1);
		}
		
		$ret = $brand -> add($_POST);
		if ($ret['error'] == 0){
			cpmsg(true, '品牌信息添加成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, $ret['message'], -1);
		}
	}
	
	
	//品牌信息编辑
	function edit(){
		if (isset($_POST['submit'])){
			$this -> edit_submit();
		}
		
		$brand_id = $this -> params[1];
		if (!isint($brand_id) || $brand_id <= 0){
			cpmsg(false, '缺少重要参数，错误的请求地址！', -1);
		}
		
		$brand = new md\brand();
		$brand_data = $brand -> info($brand_id);
		if ($brand_data){} else {
			cpmsg(false, '该品牌信息不存在或已被删除！', -1);
		}
		
		$brand_types = $brand -> fetch_brand_types();
		
		$this -> smarty -> assign('brand_data', $brand_data);
		$this -> smarty -> assign('brand_types', $brand_types);
		$this -> smarty -> display('product/brand_edit.tpl');
	}
	
	//编辑操作
	function edit_submit(){
		$brand_id = $this -> params[1];
		if (!isint($brand_id) || $brand_id <= 0){
			cpmsg(false, '缺少重要参数，不能获取编辑的品牌信息！', -1);
		}
		
		$brand = new md\brand();
		
		if (empty($_POST['brand_name'])){
			cpmsg(false, '请填写品牌名称！', -1);
		}
		if ($brand->isExist_brand_name($_POST['brand_name'], $brand_id)){
			cpmsg(false, '该品牌已存在，请更换一个谢谢！', -1);
		}
		if (empty($_POST['logo'])){
			cpmsg(false, '请上传品牌LOGO图片！', -1);
		}
		if (empty($_POST['pic'])){
			cpmsg(false, '请上传品牌栏目大图！', -1);
		}
		
		
		$brand_types = $brand -> fetch_brand_types();
		if (!in_array($_POST['type'], array_keys($brand_types))){$_POST['type'] = 1;}
		
		$f = $brand -> update($brand_id, array(
			'brand_name' => trim($_POST['brand_name']),
			'logo' => trim($_POST['logo']),
			'pic' => trim($_POST['pic']),
			'web_url' => trim($_POST['web_url']),
			'content' => trim($_POST['content']),
			'type' => $_POST['type'],
			'displayorder' => intval($_POST['displayorder']),
			'keywords' => trim($_POST['keywords']),
			'description' => trim($_POST['discription']),
		));
		if ($f){
			cpmsg(true, '品牌信息编辑成功！', '/'.$this->mod.'/'.$this->col.'');
		} else {
			cpmsg(false, '品牌信息编辑失败', -1);
		}
	}
	
	//删除品牌
	function del(){
		$brand_id = $this -> params[1];
		if (!isint($brand_id) || $brand_id <= 0){
			cpmsg(false, '缺少重要参数，错误的请求地址！', -1);
		}
		
		$page = intval($_GET['page']);
		$page = $page <= 0 ? 1 : $page;
		
		$brand = new md\brand();
		$f = $brand -> delete($brand_id);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col.'?page='.$page);
		} else {
			cpmsg(false, '品牌删除失败！', -1);
		}
	}
	
	//设置显示
	function show(){
		$brand_id = $this -> params[1];
		if (!isint($brand_id) || $brand_id <= 0){
			cpmsg(false, '缺少重要参数，错误的请求地址！', -1);
		}
		
		$page = intval($_GET['page']);
		$page = $page <= 0 ? 1 : $page;
		
		$brand = new md\brand();
		$brand -> validate($brand_id, 1);
		cpurl('/'.$this->mod.'/'.$this->col.'?page='.$page);
	}
	
	//设置隐藏
	function hide(){
		$brand_id = $this -> params[1];
		if (!isint($brand_id) || $brand_id <= 0){
			cpmsg(false, '缺少重要参数，错误的请求地址！', -1);
		}
		
		$page = intval($_GET['page']);
		$page = $page <= 0 ? 1 : $page;
		
		$brand = new md\brand();
		$brand -> validate($brand_id, 0);
		cpurl('/'.$this->mod.'/'.$this->col.'');
	}
	
	//品牌类目
	function cat(){
		$brand_id = $this -> params[1];
		if (!isint($brand_id) || $brand_id <= 0){
			cpmsg(false, '错误的请求地址！', -1);
		}
		
		$p = $this->params[2];
		if ($p == 'update'){
			$this -> cat_update();	
		} else {
			$this -> cat_items();	
		}
	}
	
	//品牌类目
	function cat_items(){
		if (isset($_POST['submit'])){
			$this -> cat_edit();
			exit();		
		}
		
		$brand_id = $this -> params[1];
		
		$brand = new md\brand();
		$brand_data = $brand -> info($brand_id);
		if ($brand_data){} else {
			cpmsg(false, '该品牌不存在或已被删除！', -1);	
		}
		
		$brand_cat = new md\brand_category();
		$brand_cat_items = $brand_cat -> items($brand_id);
		
		
		$this -> smarty -> assign('brand_data', $brand_data);
		$this -> smarty -> assign('brand_cat_items', $brand_cat_items);
		$this -> smarty -> display('product/brand_cat_list.tpl');	
	}
	
	//更新记录
	function cat_update(){
		$brand_id = $this -> params[1];	
		
		$brand_cat = new md\brand_category();
		$idArr = $brand_cat -> query_catid($brand_id);
		if ($idArr){
			$f = $brand_cat -> update($brand_id, $idArr);
			if ($f){
				cpurl('/'.$this->mod.'/'.$this->col.'/cat/'.$brand_id);	
			}
			cpmsg(false, '更新失败！', -1);
		} else {
			cpmsg(false, '查询到类目结果为空！', -1);	
		}
	}
	
	//更新排序
	function cat_edit(){
		$brand_id = $_POST['brand_id'];
		$displayorder = $_POST['displayorder'];
		if (is_array($displayorder) && !empty($displayorder)){} else {
			cpmsg(false, '错误的请求！', -1);	
		}
		
		$brand_cat = new md\brand_category();
		foreach ($displayorder as $cat_id => $val){
			$brand_cat -> update_displayorder($brand_id, $cat_id, $val);	
		}
		
		cpmsg(true, '排序更新成功！', '/'.$this->mod.'/'.$this->col.'/cat/'.$brand_id);
	}
}