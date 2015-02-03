<?php
namespace admin\product;

use model as md;
use admin as adm;

/**
 +-----------------------------
 *	淘宝属性控制器
 +-----------------------------
 */
class TB_Attr extends adm\Front {
	
	//淘宝属性列表
	function items(){
		if (isset($_POST['submit'])){
			$this -> update_submit();
		}
		
		$tb_cat_id  = $this -> params[1];
		
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		//查询类目信息
		$tb_cat = new md\tb_cat();
		$tb_cat_data = $tb_cat -> info($tb_cat_id);
		if ($tb_cat_data){} else {
			cpmsg(false, '该类目不存在或已被删除！', -1);
		}
		
		//查询属性列表
		$tb_attr = new md\tb_attr();
		$tb_attr_items = $tb_attr -> items($tb_cat_id, 0, 0);
		
		$more_navs = array('类目（'.$tb_cat_data['tb_cat_name'].'）普通属性');
		
		$this -> smarty -> assign('more_navs', $more_navs);
		$this -> smarty -> assign('tb_attr_items', $tb_attr_items);
		$this -> smarty -> assign('tb_cat_data', $tb_cat_data);
		$this -> smarty -> display('product/tb_attr_list.tpl');	
	}
	
	//添加或更新操作
	private function update_submit(){
		$tb_attr_ids = $_POST['tb_attr_id'];
		$tb_cat_id = intval($_POST['tb_cat_id']);
		$parent_id = 0;
		
		if (!is_array($tb_attr_ids) || $tb_cat_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$tb_attr = new md\tb_attr();
		$update_number = 0;
		
		foreach ($tb_attr_ids as $id => $val){
			$tb_attr_value 	= trim($_POST['tb_attr_value'][$id]);
			$tb_attr_id 	= intval($_POST['tb_attr_id'][$id]);
			$type 			= trim($_POST['type'][$id]);
			$required 		= intval($_POST['required'][$id]);
			$displayorder 	= intval($_POST['displayorder'][$id]);
			
			
			if ($tb_attr_id <= 0 || empty($tb_attr_value)){
				continue;
			}

			if ($id == 0){
				//添加操作
				$f = $tb_attr -> add(array(
					'tb_attr_value' => $tb_attr_value,
					'tb_attr_id' 	=> $tb_attr_id,
					'tb_cat_id' 	=> $tb_cat_id,
					'parent_id'		=> 0,				//必须的
					'type' 			=> $type,
					'required' 		=> $required,
					'displayorder' 	=> $displayorder,
				));
			} else {
				//更新操作
				$f = $tb_attr -> update($id, array(
					'tb_attr_id' 	=> $tb_attr_id,
					'tb_attr_value' => $tb_attr_value,
					'type' 			=> $type,
					'required' 		=> $required,
					'displayorder' 	=> $displayorder,
				));
			}
			
			if ($f){
				$update_number += 1;
			}
		}
		if ($update_number == 0){
			cpmsg(false, '没有属性信息更新！', -1);
		} else {
			cpmsg(true, '属性信息更新成功！', '/'.$this->mod.'/'.$this->col.'/attr/'.$tb_cat_id);
		}
	}
	
	//删除淘宝属性及其选项值
	function del(){
		$tb_cat_id  = $this -> params[1];
		$id = $this -> params[3];
		
		if (!isint($tb_cat_id) || $tb_cat_id <= 0 || !isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$tb_attr = new md\tb_attr();
		$f = $tb_attr -> delete($id);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col.'/attr/'.$tb_cat_id.'');
		} else {
			cpmsg(false, '淘宝属性删除失败！', -1);
		}
	}
	
	//显示属性
	function show(){
		$tb_cat_id  = $this -> params[1];
		$id = $this -> params[3];
		
		if (!isint($tb_cat_id) || $tb_cat_id <= 0 || !isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$tb_attr = new md\tb_attr();
		$tb_attr -> validate($id, 1);
		cpurl('/'.$this->mod.'/'.$this->col.'/attr/'.$tb_cat_id.'');
	}
	
	//隐藏属性
	function hide(){
		$tb_cat_id  = $this -> params[1];
		$id = $this -> params[3];
		
		if (!isint($tb_cat_id) || $tb_cat_id <= 0 || !isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$tb_attr = new md\tb_attr();
		$tb_attr -> validate($id, 0);
		cpurl('/'.$this->mod.'/'.$this->col.'/attr/'.$tb_cat_id.'');
	}
}