<?php
namespace admin\product;

use admin as adm;
use model as md;

/**
 +-----------------------------
 *	商品类目
 +-----------------------------
 */
class Category extends adm\Front {
	
	//类目列表
	function items(){
		$cat = new md\category();
		$category_items = $cat -> layer_child_items(1, 0);
		
		$this -> smarty -> assign('category_items', $category_items);
		$this -> smarty -> display('product/category_list.tpl');
	}
	
	//添加类目
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
		}
		
		//查询一级类目
		$cat = new md\category();
		$category_items = $cat -> child_items(0, 0);
		
		$this -> smarty -> assign('more_navs', '添加类目');
		$this -> smarty -> assign('category_items', $category_items);
		$this -> smarty -> display('product/category_add.tpl');
	}
	
	//添加操作
	function add_submit(){
		$cat_name 		= trim($_POST['cat_name']);
		$cat_id1 		= intval($_POST['cat_id1']);
		$cat_id2 		= intval($_POST['cat_id2']);
		$tb_cat_id 		= intval($_POST['tb_cat_id']);
		$tb_cat_name 	= trim($_POST['tb_cat_name']);
		$weight 		= intval($_POST['weight']);
		$displayorder 	= intval($_POST['displayorder']);
		$keywords 		= trim($_POST['keywords']);
		$description 	= trim($_POST['description']);
		$is_no_refund 	= intval($_POST['is_no_refund']);
		
		if (empty($cat_name)){
			cpmsg(false, '请填写类目名称！', -1);
		}
		if ($cat_id1 == 0 && $cat_id2 == 0){
			$parent_id = 0;
		} elseif ($cat_id1 > 0 && $cat_id2 > 0){
			$parent_id = $cat_id2;
		} elseif ($cat_id1 > 0 && $cat_id2 == 0){
			$parent_id = $cat_id1;
		} else {
			cpmsg(false, '所属类目数据不正确！', -1);
		}
		if ($parent_id > 0){
			if ($tb_cat_id <= 0){
				cpmsg(false, '请填写对应的淘宝类目ID！', -1);
			}
			if (empty($tb_cat_name)){
				cpmsg(false, '请填写对应的淘宝类目名称！', -1);
			}
			if ($cat_id2 > 0 && $weight <= 0){
				cpmsg(false, '重量必须填写，不然没办法计算国际运费哦！', -1);
			}
		}
		
		//判断 在淘宝类目表中 是否存在该淘宝类目信息
		if ($cat_id1 > 0 && $cat_id2 > 0){
			$tb_cat = new md\tb_cat();
			$f = $tb_cat -> info($tb_cat_id);
			if (!$f){
				cpmsg(false, '该淘宝类目信息不存在，请先完善淘宝类目信息后，再来添加！', -1);
			}
		}
		
		$cat = new md\category();
		$cat_id = $cat -> add(array(
			'parent_id' => $parent_id,
			'cat_name' => $cat_name,
			'tb_cat_id' => $tb_cat_id,
			'tb_cat_name' => $tb_cat_name,
			'weight' => $weight,
			'displayorder' => $displayorder,
			'keywords' => $keywords,
			'description' => $description,
			'is_no_refund' => $is_no_refund,
		));	
		if ($cat_id){
			cpmsg(true, '类目添加成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '类目信息写入数据库错误', -1);
		}
	}
	
	//编辑类目页面
	function edit(){
		if (isset($_POST['submit'])){
			$this -> edit_submit();
			exit();
		}
		
		$cat_id = $this -> params[1];
		if (!isint($cat_id) || $cat_id <= 0){
			cpmsg(false, '缺少重要参数，不能查询到要编辑的类目！', -1);
		}
		
		$cat = new md\category();
		
		//查询类目信息
		$cat_data = $cat -> info($cat_id);
		if ($cat_data){
			$layer = $cat_data['layer'];
			$parent_id = $cat_data['parent_id'];
		} else {
			cpmsg(false, '该类目不存在或已被删除！', -1);
		}
		
		//查询一级类目
		$category_items = $cat -> child_items(0, 0);
		
		//查询一级类目ID和二级类目ID
		if ($layer == 1){
			$cat_id1 = $cat_id2 = 0;
		} elseif ($layer == 2) {
			$cat_id1 = $parent_id;
			$cat_id2 = 0;
		} elseif ($layer == 3){
			$cat_id2 = $parent_id;
			//查询所有二级类目
			$category_items2 = $cat -> sibling_items($parent_id, 'all');
			//查询当前类目所属的一级类目
			$info = $cat -> info($parent_id);
			if ($info){
				$cat_id1 = $info['parent_id'];
			}
		}
		
		$this -> smarty -> assign('more_navs', '编辑类目');
		$this -> smarty -> assign('category_data', $cat_data);
		$this -> smarty -> assign('category_items', $category_items);
		$this -> smarty -> assign('category_items2', $category_items2);
		$this -> smarty -> assign('cat_id1', $cat_id1);
		$this -> smarty -> assign('cat_id2', $cat_id2);
		$this -> smarty -> display('product/category_edit.tpl');
	}
	
	//编辑提交操作
	private function edit_submit(){
		$cat_id 		= $_POST['cat_id'];
		$cat_name 		= trim($_POST['cat_name']);
		$cat_id1 		= intval($_POST['cat_id1']);
		$cat_id2 		= intval($_POST['cat_id2']);
		$tb_cat_id 		= intval($_POST['tb_cat_id']);
		$tb_cat_name 	= trim($_POST['tb_cat_name']);
		$weight 		= intval($_POST['weight']);
		$displayorder 	= intval($_POST['displayorder']);
		$keywords 		= trim($_POST['keywords']);
		$description 	= trim($_POST['description']);
		$is_no_refund 	= trim($_POST['is_no_refund']);
		
		if (!isint($cat_id) || $cat_id <= 0){
			cpmsg(false, '缺少重要参数，不能查询到该类目信息！', -1);
		}
		if (empty($cat_name)){
			cpmsg(false, '请填写类目名称！', -1);
		}
		if ($cat_id1 == 0 && $cat_id2 == 0){
			$parent_id = 0;
		} elseif ($cat_id1 > 0 && $cat_id2 > 0){
			$parent_id = $cat_id2;
		} elseif ($cat_id1 > 0 && $cat_id2 == 0){
			$parent_id = $cat_id1;
		} else {
			cpmsg(false, '所属类目数据不正确！', -1);
		}
		if ($parent_id > 0){
			if ($cat_id <= 0){
				cpmsg(false, '请填写对应的淘宝类目ID！', -1);
			}
			if (empty($tb_cat_name)){
				cpmsg(false, '请填写对应的淘宝类目名称', -1);
			}
			if ($cat_id2 > 0 && $weight <= 0){
				cpmsg(false, '重量必须填写，不然没办法计算国际运费哦', -1);
			}
		}
		
		//判断 在淘宝类目表中 是否存在该淘宝类目信息
		if ($cat_id1 > 0 && $cat_id2 > 0){
			$tb_cat = new md\tb_cat();
			$f = $tb_cat -> info($tb_cat_id);
			if (!$f){
				cpmsg(false, '该淘宝类目信息不存在，请先完善淘宝类目信息后，再来添加', -1);
			}
		}
		
		$cat = new md\category();
		$num = $cat -> update($cat_id, array(
			'parent_id' => $parent_id,
			'cat_name' => $cat_name,
			'tb_cat_id' => $tb_cat_id,
			'tb_cat_name' => $tb_cat_name,
			'weight' => $weight,
			'layer' => $layer,
			'displayorder' => $displayorder,
			'keywords' => $keywords,
			'description' => $description,
			'is_no_refund' => $is_no_refund,
		));
		if ($num > 0){
			cpmsg(true, '类目信息编辑成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '类目编辑失败！', -1);
		}
	}
	
	//删除类目
	function del(){
		$cat_id = $this -> params[1];
		if (!isint($cat_id) || $cat_id <= 0){
			cpmsg(false, '缺少重要参数，不能查询到要编辑的类目！', -1);
		}
		
		$cat = new md\category();
		$f = $cat -> delete($cat_id);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '类目删除失败！', -1);
		}
	}
	
	//显示
	function show(){
		$cat_id = $this -> params[1];
		if (!isint($cat_id) || $cat_id <= 0){
			cpmsg(false, '缺少重要参数，不能查询到要编辑的类目！', -1);
		}
		
		$cat = new md\category();
		$cat -> validate($cat_id, 1);
		cpurl('/'.$this->mod.'/'.$this->col);
	}
	
	//隐藏
	function hide(){
		$cat_id = $this -> params[1];
		if (!isint($cat_id) || $cat_id <= 0){
			cpmsg(false, '缺少重要参数，不能查询到要编辑的类目！', -1);
		}
		
		$cat = new md\category();
		$cat -> validate($cat_id, 0);
		cpurl('/'.$this->mod.'/'.$this->col);
	}
	
}