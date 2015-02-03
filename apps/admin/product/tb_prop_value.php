<?php
namespace admin\product;

use model as md;
use admin as adm;

/**
 +-------------------------
 *	淘宝属性值类
 +-------------------------
 */

class TB_Prop_Value extends adm\Front {
	
	//属性的选项列表
	function items(){
		if (isset($_POST['submit'])){
			$this -> update_submit();
		}
		
		$id = $this -> params[1];
		
		//查询属性信息
		$tb_prop = new md\tb_prop();
		$tb_prop_data = $tb_prop -> info($id);
		if ($tb_prop_data){
			$tb_cat_id  = $tb_prop_data['tb_cat_id'];
			$tb_prop_id = $tb_prop_data['tb_prop_id'];
		} else {
			cpmsg(false, '该淘宝销售属性不存在或已被删除！', -1);
		}
		
		//查询类目信息
		$tb_cat = new md\tb_cat();
		$tb_cat_data = $tb_cat -> info($tb_cat_id);
		if ($tb_cat_data){} else {
			cpmsg(false, '该淘宝销售属性所属类目不存在或已被删除！', -1);
		}
		
		//查询该属性的所有子选项
		$tb_prop_value_items = $tb_prop -> items($tb_cat_id, $tb_prop_id, 0);

		
		$more_navs = array('<a href="/'.$this->mod.'/'.$this->col.'/prop/'.$tb_cat_id.'">类目（'.$tb_cat_data['tb_cat_name'].'）销售属性</a>',$tb_prop_data['tb_prop_value']);
		
		$this -> smarty -> assign('more_navs', $more_navs);
		$this -> smarty -> assign('tb_cat_data', $tb_cat_data);
		$this -> smarty -> assign('tb_prop_data', $tb_prop_data);
		$this -> smarty -> assign('tb_prop_value_items', $tb_prop_value_items);
		$this -> smarty -> display('product/tb_prop_value.tpl');
	}
	
	//添加或编辑操作
	private function update_submit(){
		$tb_cat_id = intval($_POST['tb_cat_id']);
		$parent_id = intval($_POST['parent_id']);
		$tb_prop_ids = $_POST['tb_prop_id'];
		
		$A_id = $_POST['id']; //属性名的ID
		
		if (!is_array($tb_prop_ids) || $tb_cat_id <= 0 || $parent_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$update_number = 0;
		$tb_prop = new md\tb_prop();
		
		foreach ($tb_prop_ids as $id => $val){
			$tb_prop_value 	= trim($_POST['tb_prop_value'][$id]);
			$tb_prop_value_kr = trim($_POST['tb_prop_value_kr'][$id]);
			$tb_prop_id 	= intval($_POST['tb_prop_id'][$id]);
			$displayorder 	= intval($_POST['displayorder'][$id]);
			
			
			if ($tb_prop_id <= 0 || empty($tb_prop_value) || empty($tb_prop_value_kr)){
				continue;
			}
			
			if ($id == 0){
				//添加
				$f = $tb_prop -> add(array(
					'tb_cat_id' => $tb_cat_id,
					'parent_id' => $parent_id,
					'tb_prop_id' => $tb_prop_id,
					'tb_prop_value' => $tb_prop_value,
					'tb_prop_value_kr' => $tb_prop_value_kr,
					'displayorder' => $displayorder,
				));
			} else {
				//编辑
				$f = $tb_prop -> update($id, array(
					'tb_prop_id' => $tb_prop_id,
					'tb_prop_value' => $tb_prop_value,
					'tb_prop_value_kr' => $tb_prop_value_kr,
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
			cpmsg(true, '淘宝属性值更新成功！', '/'.$this->mod.'/'.$this->col.'/prop_value/'.$A_id);
		}
	} 
	
	//显示
	function show(){
		$A_id = $this -> params[1];
		$B_id = $this -> params[3];
		
		if (!isint($A_id) || !isint($A_id) || !isint($B_id) || $B_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$tb_prop = new md\tb_prop();
		$tb_prop -> validate($B_id, 1);
		cpurl('/'.$this->mod.'/'.$this->col.'/prop_value/'.$A_id);
	}
	
	//隐藏
	function hide(){
		$A_id = $this -> params[1];
		$B_id = $this -> params[3];
		
		if (!isint($A_id) || !isint($A_id) || !isint($B_id) || $B_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$tb_prop = new md\tb_prop();
		$tb_prop -> validate($B_id, 0);
		cpurl('/'.$this->mod.'/'.$this->col.'/prop_value/'.$A_id);
	}
	
	//删除
	function del(){
		$A_id = $this -> params[1];
		$B_id = $this -> params[3];
		
		if (!isint($A_id) || !isint($A_id) || !isint($B_id) || $B_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$tb_prop = new md\tb_prop();
		$tb_prop -> delete($B_id);
		cpurl('/'.$this->mod.'/'.$this->col.'/prop_value/'.$A_id);
	}
}