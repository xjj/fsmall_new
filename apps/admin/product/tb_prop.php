<?php
namespace admin\product;

use model as md;
use admin as adm;

/**
 +------------------------------
 *	淘宝销售属性控制器
 +------------------------------
 */

class TB_Prop extends adm\Front {
	
	//淘宝销售属性列表
	function items(){
		if (isset($_POST['submit'])){
			$this -> update_submit();
		}
		
		$tb_cat_id = $this -> params[1];
		
		//查询类目信息
		$tb_cat = new md\tb_cat();
		$tb_cat_data = $tb_cat -> info($tb_cat_id);
		if ($tb_cat_data){} else {
			cpmsg(false, '该淘宝类目不存在或已被删除！', -1);
		}
		
		//查询属性列表
		$tb_prop = new md\tb_prop();
		$tb_prop_items = $tb_prop -> items($tb_cat_id, 0, 0);
		
		$more_navs = array('类目（'.$tb_cat_data['tb_cat_name'].'）销售属性');
		
		$this -> smarty -> assign('more_navs', $more_navs);
		$this -> smarty -> assign('tb_prop_items', $tb_prop_items);
		$this -> smarty -> assign('tb_cat_data', $tb_cat_data);
		$this -> smarty -> display('product/tb_prop_list.tpl');	
	}
	
	//更新或添加销售属性操作
	private function update_submit(){
		$tb_prop_ids = $_POST['tb_prop_id'];
		$tb_cat_id = intval($_POST['tb_cat_id']);
		
		if (!is_array($tb_prop_ids) || $tb_cat_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$tb_prop = new md\tb_prop();
		$update_number = 0;
		
		foreach ($tb_prop_ids as $id => $val){
			$tb_prop_id = intval($_POST['tb_prop_id'][$id]);
			$tb_prop_value = trim($_POST['tb_prop_value'][$id]);
			$tb_prop_value_kr = trim($_POST['tb_prop_value_kr'][$id]);
			$displayorder = intval($_POST['displayorder'][$id]);
			
			if ($tb_prop_id <= 0 || empty($tb_prop_value) || empty($tb_prop_value_kr)){
				continue;
			}
				
			if ($id == 0){
				//添加
				$f = $tb_prop -> add(array(
					'tb_cat_id' => $tb_cat_id,
					'parent_id'	=> 0,	//必须的
					'tb_prop_id' => $tb_prop_id,
					'tb_prop_value' => $tb_prop_value,
					'tb_prop_value_kr' => $tb_prop_value_kr,
					'displayorder' => $displayorder,
				));
			} else {
				//更新
				$f = $tb_prop -> update($id, array(
					'tb_prop_id' => $tb_prop_id,
					'tb_prop_value' => $tb_prop_value,
					'tb_prop_value_kr' => $tb_prop_value_kr,
					'displayorder' => $displayorder,
				));
			}
			
			if ($f){
				$update_number += 1;
			}
		}
		
		if ($update_number == 0){
			cpmsg(false, '没有销售属性信息更新！', -1);
		} else {
			cpmsg(true, '销售属性信息更新成功！', '/'.$this->mod.'/'.$this->col.'/prop/'.$tb_cat_id);
		}
	}
	
	
	//删除淘宝销售属性及其选项
	function del(){
		$tb_cat_id = $this -> params[1];
		$id = $this -> params[3];
		
		if (!isint($tb_cat_id) || $tb_cat_id <= 0 || !isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$tb_prop = new md\tb_prop();
		$f = $tb_prop -> delete($id);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col.'/prop/'.$tb_cat_id);
		} else {
			cpmsg(false, '属性删除失败，该属性没被删除！', -1);
		}
	}
	
	//显示属性
	function show(){
		$tb_cat_id = $this -> params[1];
		$id = $this -> params[3];
		
		if (!isint($tb_cat_id) || $tb_cat_id <= 0 || !isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$tb_prop = new md\tb_prop();
		$tb_prop -> validate($id, 1);
		cpurl('/'.$this->mod.'/'.$this->col.'/prop/'.$tb_cat_id);
	}
	
	//隐藏属性
	function hide(){
		$tb_cat_id = $this -> params[1];
		$id = $this -> params[3];
		
		if (!isint($tb_cat_id) || $tb_cat_id <= 0 || !isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$tb_prop = new md\tb_prop();
		$tb_prop -> validate($id, 0);
		cpurl('/'.$this->mod.'/'.$this->col.'/prop/'.$tb_cat_id);
	}
}