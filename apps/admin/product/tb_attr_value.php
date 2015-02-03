<?php
namespace admin\product;

use model as md;
use admin as adm;

/**
 +-------------------------
 *	淘宝属性值控制器
 +-------------------------
 */

class TB_Attr_Value extends adm\Front {
	
	//属性的选项列表
	function items(){
		if (isset($_POST['submit'])){
			$this -> update_submit();
		}
		
		$id = $this -> params[1];
		
		$tb_attr = new md\tb_attr();
		$tb_attr_data = $tb_attr -> info($id);
		if ($tb_attr_data){
			$tb_cat_id  = $tb_attr_data['tb_cat_id'];
			$tb_attr_id = $tb_attr_data['tb_attr_id'];
		} else {
			cpmsg(false, '该淘宝属性不存在或已被删除！', -1);
		}
		
		//查询类目信息
		$tb_cat = new md\tb_cat();
		$tb_cat_data = $tb_cat -> info($tb_cat_id);
		if ($tb_cat_data){} else {
			cpmsg(false, '该淘宝属性所属类目不存在或已被删除！', -1);
		}
		
		//查询该属性的所有子选项
		$tb_attr_value_items = $tb_attr -> items($tb_cat_id, $tb_attr_id, 0);

		
		$more_navs = array(
			'<a href="/'.$this->mod.'/'.$this->col.'/attr/'.$tb_cat_id.'">类目（'.$tb_cat_data['tb_cat_name'].'）普通属性</a>', $tb_attr_data['tb_attr_value'],
		);
		
		$this -> smarty -> assign('more_navs', $more_navs);
		$this -> smarty -> assign('tb_cat_data', $tb_cat_data);
		$this -> smarty -> assign('tb_attr_data', $tb_attr_data);
		$this -> smarty -> assign('tb_attr_value_items', $tb_attr_value_items);
		$this -> smarty -> display('product/tb_attr_value.tpl');
	}
	
	//添加或编辑操作
	private function update_submit(){
		$tb_cat_id = intval($_POST['tb_cat_id']);
		$parent_id = intval($_POST['parent_id']);
		$A_id = intval($_POST['id']);
		$tb_attr_ids = $_POST['tb_attr_id'];
		
		if (!is_array($tb_attr_ids) || $tb_cat_id <= 0 || $parent_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$update_number = 0;
		$tb_attr = new md\tb_attr();
		
		foreach ($tb_attr_ids as $id => $val){
			$tb_attr_value 	= trim($_POST['tb_attr_value'][$id]);
			$tb_attr_id 	= intval($_POST['tb_attr_id'][$id]);
			$displayorder 	= intval($_POST['displayorder'][$id]);
			
			if ($tb_attr_id <= 0 || empty($tb_attr_value)){
				continue;
			}
			
			if ($id == 0){
				//添加
				$f = $tb_attr -> add(array(
					'tb_attr_id' => $tb_attr_id,
					'tb_attr_value' => $tb_attr_value,
					'tb_cat_id' => $tb_cat_id,
					'parent_id' => $parent_id,
					'displayorder' => $displayorder,
				));
			} else {
				//编辑
				$f = $tb_attr -> update($id, array(
					'tb_attr_id' 	=> $tb_attr_id,
					'tb_attr_value' => $tb_attr_value,
					'displayorder' 	=> $displayorder,
				));
			}
			
			if ($f){
				$update_number += 1;
			}
		}
		if ($update_number == 0){
			cpmsg(false, '淘宝属性值更新失败！', -1);
		} else {
			cpmsg(true, '淘宝属性值更新成功！', '/'.$this->mod.'/'.$this->col.'/attr_value/'.$A_id);
		}
	} 
	
	//显示
	function show(){
		$A_id = $this -> params[1];
		$B_id = $this -> params[3];
		
		if (!isint($A_id) || !isint($B_id) || !isint($A_id) || $B_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$tb_attr = new md\tb_attr();
		$tb_attr -> validate($B_id, 1);
		cpurl('/'.$this->mod.'/'.$this->col.'/attr_value/'.$A_id);
	}
	
	//隐藏
	function hide(){
		$A_id = $this -> params[1];
		$B_id = $this -> params[3];
		
		if (!isint($A_id) || !isint($B_id) || !isint($A_id) || $B_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$tb_attr = new md\tb_attr();
		$tb_attr -> validate($B_id, 0);
		cpurl('/'.$this->mod.'/'.$this->col.'/attr_value/'.$A_id);
	}
	
	//删除
	function del(){
		$A_id = $this -> params[1];
		$B_id = $this -> params[3];
		
		if (!isint($A_id) || !isint($B_id) || !isint($A_id) || $B_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$tb_attr = new md\tb_attr();
		$tb_attr -> delete($B_id);
		cpurl('/'.$this->mod.'/'.$this->col.'/attr_value/'.$A_id);
	}
}