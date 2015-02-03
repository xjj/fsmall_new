<?php
namespace model;

/**
 +--------------------------------------
 *	淘宝普通属性类
 +--------------------------------------
 */
class TB_Attr extends Front {
	
	//获取属性信息
	//$tb_attr_key 	(id|key)
	function info($tb_attr_key){
		if (isint($tb_attr_key) && $tb_attr_key > 0){
			$where = 'id = '.$tb_attr_key;
		} else {
			$where = 'tb_attr_key = \''.$tb_attr_key.'\'';
		}
		$sql = 'SELECT * FROM `product_tb_attr` WHERE '.$where;
		return $this -> db -> row($sql);
	}
	
	//类目的所有属性信息
	function items($tb_cat_id, $parent_id = 0, $status = 1){
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){return false;}
		
		$sql = 'SELECT * FROM `product_tb_attr` WHERE tb_cat_id = '.$tb_cat_id.' AND parent_id = '.$parent_id.'';
		if ($status == 1){
			$sql .= ' AND status = 1';
		}
		$sql .= ' ORDER BY displayorder ASC';
		return $this -> db -> rows($sql);
	}
	
	
	//所有必选项属性
	function required($tb_cat_id){
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){return false;}
		
		$sql = 'SELECT * FROM `product_tb_attr` WHERE tb_cat_id = '.$tb_cat_id.' AND status = 1 AND required = 1 AND parent_id = 0 ORDER BY displayorder ASC';
		return $this -> db -> rows($sql);
	}
	
	
	//添加属性
	function add($data){
		$tb_attr_id 	= intval($data['tb_attr_id']);
		$tb_attr_value 	= trim($data['tb_attr_value']);
		$tb_cat_id 		= intval($data['tb_cat_id']);
		$type 			= trim($data['type']);
		$parent_id		= intval($data['parent_id']);
		$required 		= intval($data['required']);
		$displayorder 	= intval($data['displayorder']);
		
		if (empty($tb_attr_value)){return false;}
		if ($tb_attr_id <= 0){return false;}
		if ($tb_cat_id <= 0){return false;}
		if ($parent_id > 0){
			$type = 'text';	
		} else {
			if (!in_array($type, array('select','checkbox'))){return false;}
		}
		
		if ($parent_id > 0){
			$tb_attr_key = $tb_cat_id.':'.$parent_id.':'.$tb_attr_id;
		} else {
			$tb_attr_key = $tb_cat_id.':'.$tb_attr_id;
		}
		
		$f = $this -> isExist($tb_attr_key);
		if ($f){return false;} 
		if ($required != 1){$required = 0;}
		
		return $this -> db -> insert('product_tb_attr', array(
			'tb_attr_key' => $tb_attr_key,
			'tb_cat_id' => $tb_cat_id,
			'tb_attr_id' => $tb_attr_id,
			'parent_id' => $parent_id,
			'tb_attr_value' => $tb_attr_value,
			'type' => $type,
			'required' => $required,
			'displayorder' => $displayorder,
			'add_time' => time(),
			'status' => 0
		));
	}
	
	//编辑属性
	function update($id, $data){
		if (!isint($id) || $id <= 0){return false;}
		
		$info = $this -> info($id);
		if ($info){
			$tb_cat_id = $info['tb_cat_id'];
			$parent_id = $info['parent_id'];
		} else {
			return false;
		}
		
		$tb_attr_id		= trim($data['tb_attr_id']);
		$tb_attr_value 	= trim($data['tb_attr_value']);
		$type 			= trim($data['type']);
		$required 		= intval($data['required']);
		$displayorder 	= intval($data['displayorder']);
		
		
		if (empty($tb_attr_id) || empty($tb_attr_value)){return false;}
		if ($parent_id > 0){
			$type = 'text';	
		} else {
			if (!in_array($type, array('select','checkbox'))){return false;}
		}
		if ($required != 1){$required = 0;}
		
		if ($parent_id > 0){
			$tb_attr_key = $tb_cat_id.':'.$parent_id.':'.$tb_attr_id;
		} else {
			$tb_attr_key = $tb_cat_id.':'.$tb_attr_id;
		}
		
		$f = $this -> isExist($tb_attr_key, $id);
		if ($f){return false;} 
		
		return $this -> db -> update('product_tb_attr', array(
			'tb_attr_key' => $tb_attr_key,
			'tb_attr_id' => $tb_attr_id,
			'tb_attr_value' => $tb_attr_value,
			'type' => $type,
			'required' => $required,
			'displayorder' => $displayorder,
		), array(
			'id' => $id
		));
	}
	
		
	//设置有效无效
	function validate($id, $status){
		if (!isint($id) || $id <= 0){return false;}
		if ($status != 1){$status = 0;}
		return $this -> db -> update('product_tb_attr', array(
			'status' => $status
		), array(
			'id' => $id
		));
	}
	
	//判断类目下是否存在该淘宝属性
	function isExist($tb_attr_key, $id = 0){
		$sql = 'SELECT COUNT(*) AS num FROM `product_tb_attr` WHERE tb_attr_key = \''.$tb_attr_key.'\'';
		if ($id > 0){
			$sql .= ' AND id != '.$id.'';
		}
		$row = $this -> db -> row($sql);
		if ($row['num'] > 0){
			return true; 
		} else {
			return false;
		}
	}

	//删除属性
	function delete($id){
		if (!isint($id) || $id <= 0){return false;}
		
		$info = $this -> info($id);
		if ($info){} else {
			return false;
		}
		$f = $this -> db -> delete('product_tb_attr', array('id' => $id));
		
		//当删除属性时 删除属性值
		if ($f > 0){
			$this -> db -> delete('product_tb_attr', array(
				'parent_id' => $info['tb_attr_id'],
				'tb_cat_id' => $info['tb_cat_id']
			));
		}
		return true;
	}
		
	//删除类目下的所有属性
	function delete_cat_attrs($tb_cat_id){
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){return false;}
		return $this -> db -> delete('product_tb_attr', array('tb_cat_id' => $tb_cat_id));
	}
	
	
	/**
	 *	获取所有属性与选项信息
	 *	后台添加编辑商品时 -- 根据类目获取所有信息
	 */
	function attrs_and_values($tb_cat_id){
		$sql = 'SELECT * FROM `product_tb_attr` WHERE tb_cat_id = '.$tb_cat_id.' AND status = 1 ORDER BY parent_id ASC, displayorder ASC';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$data = array();
			foreach ($rows as $row){
				if ($row['parent_id'] == 0){
					$tb_attr_id = $row['tb_attr_id'];
					$data[$tb_attr_id] = $row;
				}
			}
			
			foreach ($rows as $row){
				if ($row['parent_id'] > 0){
					$tb_attr_id = $row['parent_id'];
					if (isset($data[$tb_attr_id])){
						$data[$tb_attr_id]['items'][] = $row;
					}
				}
			}
		}
		return $data; 
	}
}