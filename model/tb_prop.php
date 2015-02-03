<?php
namespace model;

/**
 +--------------------------------------
 *	淘宝销售属性类
 +--------------------------------------
 */
class TB_Prop extends Front {
	
	//获取属性信息
	function info($tb_prop_key){
		if (isint($tb_prop_key) && $tb_prop_key > 0){
			$where = 'id = '.$tb_prop_key;
		} else {
			$where = 'tb_prop_key = \''.$tb_prop_key.'\'';
		}
		$sql = 'SELECT * FROM `product_tb_prop` WHERE '.$where;
		return $this -> db -> row($sql);
	}
	//获取淘宝属性 根据传入的id
	function get_tb_prop($id,$top=0){
		$where = 'prop_value_id = '.$id;
		if (isint($top) && $top ==0){
			$where.=' and parent_id=0 ';
		} else {
			$where.=' and parent_id<>0 ';
		}
		$sql = 'SELECT * FROM `product_tb_prop` WHERE '.$where;
		//echo $sql.'<hr>';
		return $this -> db -> row($sql);
	}
	
	//类目的所有属性信息
	function items($tb_cat_id, $parent_id = 0, $status = 1){
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){return false;}
		
		$sql = 'SELECT * FROM `product_tb_prop` WHERE tb_cat_id = '.$tb_cat_id.' AND parent_id = '.$parent_id.'';
		if ($status == 1){
			$sql .= ' AND status = 1';
		}
		$sql .= ' ORDER BY displayorder ASC';
		return $this -> db -> rows($sql);
	}
	
	//添加属性
	function add($data){
		$tb_prop_id 	= intval($data['tb_prop_id']);
		$tb_prop_value 	= trim($data['tb_prop_value']);
		$tb_prop_value_kr 	= trim($data['tb_prop_value_kr']);
		$tb_cat_id 		= intval($data['tb_cat_id']);
		$parent_id		= intval($data['parent_id']);
		$displayorder 	= intval($data['displayorder']);
		
		if (empty($tb_prop_value) || empty($tb_prop_value_kr)){return false;}
		if ($tb_prop_id <= 0){return false;}
		if ($tb_cat_id <= 0){return false;}
		
		if ($parent_id > 0){
			$tb_prop_key = $tb_cat_id.':'.$parent_id.':'.$tb_prop_id;
		} else {
			$tb_prop_key = $tb_cat_id.':'.$tb_prop_id;
		}
		
		$f = $this -> isExist($tb_prop_key);
		if ($f){return false;} 
		
		//获取prop_value_id
		$prop_value = new prop_value();
		$prop_value_id = $prop_value -> fetch($tb_prop_value, $tb_prop_value_kr);		
		
		return $this -> db -> insert('product_tb_prop', array(
			'tb_prop_key' => $tb_prop_key,
			'tb_cat_id' => $tb_cat_id,
			'parent_id' => $parent_id,
			'prop_value_id' => $prop_value_id,
			'tb_prop_id' => $tb_prop_id,
			'tb_prop_value' => $tb_prop_value,
			'tb_prop_value_kr' => $tb_prop_value_kr,
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
		
		$tb_prop_id		= trim($data['tb_prop_id']);
		$tb_prop_value 	= trim($data['tb_prop_value']);
		$tb_prop_value_kr = trim($data['tb_prop_value_kr']);
		$displayorder 	= intval($data['displayorder']);
		
		
		if (empty($tb_prop_id) || empty($tb_prop_value) || empty($tb_prop_value_kr)){return false;}
		
		if ($parent_id > 0){
			$tb_prop_key = $tb_cat_id.':'.$parent_id.':'.$tb_prop_id;
		} else {
			$tb_prop_key = $tb_cat_id.':'.$tb_prop_id;
		}
		
		$f = $this -> isExist($tb_prop_key, $id);
		if ($f){return false;} 
		
		//获取prop_value_id
		$prop_value = new prop_value();
		$prop_value_id = $prop_value -> fetch($tb_prop_value, $tb_prop_value_kr);
		
		return $this -> db -> update('product_tb_prop', array(
			'tb_prop_key' => $tb_prop_key,
			'prop_value_id' => $prop_value_id,
			'tb_prop_id' => $tb_prop_id,
			'tb_prop_value' => $tb_prop_value,
			'tb_prop_value_kr' => $tb_prop_value_kr,
			'displayorder' => $displayorder,
		), array(
			'id' => $id
		));
	}
	
		
	//设置有效无效
	function validate($id, $status){
		if (!isint($id) || $id <= 0){return false;}
		if ($status != 1){$status = 0;}
		return $this -> db -> update('product_tb_prop', array('status' => $status), array('id' => $id));
	}
	
	//判断类目下是否存在该淘宝属性
	function isExist($tb_prop_key, $id = 0){
		$sql = 'SELECT COUNT(*) AS num FROM `product_tb_prop` WHERE tb_prop_key = \''.$tb_prop_key.'\'';
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
		if ($info){
			$tb_prop_id = $info['tb_prop_id'];
			$tb_cat_id  = $info['tb_cat_id'];
		} else {
			return false;
		}
		
		$f = $this -> db -> delete('product_tb_prop', array('id' => $id));
		
		//当删除属性时 删除属性值
		if ($f > 0){
			$this -> db -> delete('product_tb_prop', array(
				'parent_id' => $tb_prop_id,
				'tb_cat_id' => $tb_cat_id
			));
		}
		return true;
	}
		
	//删除类目下的所有属性
	function delete_cat_props($tb_cat_id){
		if (!isint($tb_cat_id) || $tb_cat_id <= 0){return false;}
		return $this -> db -> delete('product_tb_prop', array('tb_cat_id' => $tb_cat_id));
	}
	
	
	/**
	 *	获取所有属性与选项信息
	 *	后台添加编辑商品时 -- 根据类目获取所有信息
	 */
	function props_and_items($tb_cat_id){
		$sql = 'SELECT * FROM `product_tb_prop` WHERE tb_cat_id = '.$tb_cat_id.' AND status = 1 ORDER BY parent_id ASC, displayorder ASC';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$data = array();
			foreach ($rows as $row){
				if ($row['parent_id'] == 0){
					$tb_prop_id = $row['tb_prop_id'];
					$data[$tb_prop_id] = $row;
				}
			}
			
			foreach ($rows as $row){
				if ($row['parent_id'] > 0){
					$parent_id = $row['parent_id'];
					if (isset($data[$parent_id])){
						$data[$parent_id]['items'][] = $row;
					}
				}
			}
		}
		return $data; 
	}
}