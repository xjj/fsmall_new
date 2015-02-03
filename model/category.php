<?php
namespace model;

/**
 +------------------------------
 *	商品类目类
 +------------------------------
 */
class category extends Front {
	
	//获取类目信息
	function info($cat_id){
		if (!isint($cat_id) || $cat_id <= 0){return false;}
		$sql = 'SELECT * FROM `product_category` WHERE cat_id = '.$cat_id;
		return $this -> db -> row($sql);
	}
	
	//获取所有子类目信息
	function child_items($cat_id, $status = 1){
		if (!isint($cat_id) || $cat_id <= 0){$cat_id = 0;}
		
		$sql = 'SELECT * FROM `product_category` WHERE parent_id = '.$cat_id.' AND is_delete = 0';
		if ($status == 1){
			$sql .= ' AND status = 1';
		}
		$sql .= ' ORDER BY displayorder ASC, cat_id ASC';
		return $this -> db -> rows($sql);
	}
	
	//获取某一层级类目信息
	function layer_items($layer, $status = 1){
		if (!in_array($layer, array(1,2,3))){return false;}
		
		$sql = 'SELECT * FROM `product_category` WHERE layer = '.$layer.' AND is_delete = 0';
		if ($status == 1){
			$sql .= ' AND status = 1';
		}
		$sql .= ' ORDER BY displayorder ASC, cat_id ASC';
		return $this -> db -> rows($sql);
	}
	
	//查询类目下的所有三级子类目
	function three_layer_items($cat_id){
		if (!isint($cat_id) || $cat_id <= 0){return false;}
		
		$info = $this -> info($cat_id);
		if ($info){
			$result = array();
			if ($info['layer'] == 3){
				//自己就是三级类目
				$result[$cat_id] = $info;
			} elseif ($info['layer'] == 2){
				//二级类目下的所有三级类目
				$rows = $this -> children($cat_id, 1);
				if ($rows){
					foreach ($rows as $row){
						$result[$row['cat_id']] = $row;
					}
				}
			} elseif ($info['layer'] == 1) {
				//一级类目下的所有三级类目
				$sql = 'SELECT * FROM `product_category` WHERE parent_id in (SELECT cat_id FROM `product_category` WHERE parent_id = '.$cat_id.' AND is_delete = 0 AND status = 1 AND layer = 2) AND is_delete = 0 AND status = 1 AND layer = 3';
				$rows = $this -> db -> rows($sql);
				if ($rows){
					foreach ($rows as $row){
						$result[$row['cat_id']] = $row;
					}
				}
			}
			if (!empty($result)){
				return $result;
			}
		}
		return false;
	}
	
	//获取类目的所有同级类目
	function sibling_items($cat_id, $status = 1){
		if (!isint($cat_id) || $cat_id <= 0){$cat_id = 0;}
		
		$sql = 'SELECT t1.* FROM `product_category` t1 INNER JOIN `product_category` t2 ON t1.parent_id = t2.parent_id WHERE t2.cat_id = '.$cat_id.' AND t1.is_delete = 0';
		if ($status == 1){
			$sql .= ' AND t1.status = 1';
		}
		$sql .= ' ORDER BY t1.displayorder ASC, t1.cat_id ASC';
		return $this -> db -> rows($sql);
	}
	
	//获取父类目信息
	function parent_info($cat_id){
		if (!isint($cat_id) || $cat_id <= 0){return false;}
		
		$info = $this -> info($cat_id);
		if ($info){
			$parent_id = $info['parent_id'];
			if ($parent_id > 0){
				return $this -> info($parent_id); 
			}
		}
		return false;
	}
	
	//添加类目信息
	function add($data){
		$parent_id = trim($data['parent_id']);
		$cat_name = trim($data['cat_name']);
		$tb_cat_id = intval($data['tb_cat_id']);
		$tb_cat_name = trim($data['tb_cat_name']);
		$weight = intval($data['weight']);
		$displayorder = intval($data['displayorder']);
		$keywords = trim($data['keywords']);
		$keywords = blank_space_replace($keywords, ',');
		$description = trim($data['description']);
		$is_no_refund = intval($data['is_no_refund']);
				
		if (!isint($parent_id) || $parent_id < 0){return false;}
		if (empty($cat_name)){return false;}
		if ($is_no_refund != 1){$is_no_refund = 0;}
		
		//查询父类目层级
		if ($parent_id == 0){
			$layer = 1;
		} else {
			$layer = $this -> fetch_layer($parent_id);
			if ($layer !== false){
				$layer += 1; 
			}
		}
		
		//类目最大为三级
		if ($layer > 3){return false;}
		if ($layer == 3){
			if ($weight == 0){return false;}
			if ($tb_cat_id <= 0){return false;}
			if (empty($tb_cat_name)){return false;}
			
			//判断 在淘宝类目表中 是否存在该淘宝类目信息
			$tb_cat = new tb_cat();
			$f = $tb_cat -> isExist($tb_cat_id);
			if (!$f){return false;}
		}
		
		return $this -> db -> insert('product_category', array(
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
			'add_time' => time(),
			'status' => 0
		));
	}
	
	//编辑类目
	function update($cat_id, $data){
		if (!isint($cat_id) || $cat_id <= 0){return false;}
		
		$parent_id = trim($data['parent_id']);
		$cat_name = trim($data['cat_name']);
		$tb_cat_id = intval($data['tb_cat_id']);
		$tb_cat_name = trim($data['tb_cat_name']);
		$weight = intval($data['weight']);
		$displayorder = intval($data['displayorder']);
		$keywords = trim($data['keywords']);
		$keywords = blank_space_replace($keywords, ',');
		$description = trim($data['description']);
		$is_no_refund = intval($data['is_no_refund']);
		
		if (!isint($parent_id) || $parent_id < 0){return false;}
		if (empty($cat_name)){return false;}
		if ($is_no_refund != 1){$is_no_refund = 0;}
		
		//查询父类目层级
		if ($parent_id == 0){
			$layer = 1;
		} else {
			$layer = $this -> fetch_layer($parent_id);
			if ($layer !== false){
				$layer += 1; 
			}
		}
		
		//类目最大为三级
		if ($layer > 3){return false;}
		if ($layer == 3){
			if ($weight == 0){return false;}
			if ($tb_cat_id <= 0){return false;}
			if (empty($tb_cat_name)){return false;}
		}
		
		//查询类目信息
		$info = $this -> info($cat_id);
		if ($info){} else {return false;}
		
		if ($info['tb_cat_id'] == $tb_cat_id){
			$f_update = 0;
		} else {
			$f_update = 1;
		}
		
		return $this -> db -> update('product_category', array(
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
		), array(
			'cat_id' => $cat_id
		));
	}
	
	//删除类目
	function delete($cat_id){
		if (!isint($cat_id) || $cat_id <= 0){return false;}
		return $this -> db -> update('product_category', array('is_delete' => 1), array('cat_id' => $cat_id));
	}
	
	//显示类目
	function validate($cat_id, $status){
		if (!isint($cat_id) || $cat_id <= 0){return false;}
		if ($status != 1){$status = 0;}
		return $this -> db -> update('product_category', array('status' => $status), array('cat_id' => $cat_id)); 
	}
	
	//获取所有类目及其子类目
	function layer_child_items($layer = 2, $status = 1){
		if ($layer != 1 && $layer != 2 && $layer != 3){
			return false;
		}
		
		//查询同级类目 --- 没有结果直接返回
		$data = $this -> layer_items($layer, $status);
		if ($data){} else {return false;}
		
		if ($layer == 3){
			return $data; 
		}
		
		if ($layer == 2){
			$data2 = &$data;
			$data3 = $this -> layer_items(3, $status);
			if ($data3){
				foreach ($data2 as $key => $row){
					foreach ($data3 as $item){
						if ($item['parent_id'] == $row['cat_id']){
							$data2[$key]['items'][] = $item;
						}
					}
				}
			}
			
			return $data2;
		}
		
		if ($layer == 1){
			$data1 = &$data;
			$data2 = $this -> layer_child_items(2, $status);
			if ($data2){
				foreach ($data1 as $key => $row){
					foreach ($data2 as $item){
						if ($item['parent_id'] == $row['cat_id']){
							$data1[$key]['items'][] = $item;
						}
					}
				}
			}
			
			return $data1;
		}
	}
	
	
	//获取类目所在的层级
	function fetch_layer($cat_id){
		$info = $this -> info($cat_id);
		if ($info){
			return $info['layer'];
		} else {
			return false;
		}
	}
	
	//查询类目的父级路径
	function parent_path($cat_id){
		if (!isint($cat_id) || $cat_id <= 0){return false;}
		$info = $this -> info($cat_id);
		if ($info){
			$layer = $info['layer'];
		} else {
			return false;
		}
		
		$data = array();
		if ($layer == 1){
			$data[1] = $info;
		} elseif ($layer == 2){
			$data[2] = $info;
			$info1 = $this -> info($info['parent_id']);
			if ($info1){
				$data[1] = $info1; 
			}
		} elseif ($layer == 3){
			$data[3] = $info;
			$info2 = $this -> info($info['parent_id']);
			if ($info2){
				$data[2] = $info2;
				$info1 = $this -> info($info2['parent_id']);
				if ($info1){
					$data[1] = $info1;
				} 
			}
		}
		if (!empty($data)){
			ksort($data);
			return $data;
		}
		return false;
	}
	
	//查询类目的父级id路径
	function parent_catid_path($cat_id){
		$data = $this -> parent_path($cat_id);
		if ($data){
			$path = $data[1]['cat_id'];
			if (isset($data[2])){
				$path .= '|'.$data[2]['cat_id'];
				if (isset($data[3])){
					$path .= '|'.$data[3]['cat_id'];
				}
			}
			return $path;
		}
		return false;
	}
	
	//查询类目的父级名称路径
	function parent_catname_path($cat_id){
		$data = $this -> parent_path($cat_id);
		if ($data){
			$arr = array();
			$arr[1] = $data[1]['cat_name'];
			if (isset($data[2])){
				$arr[2] = $data[2]['cat_name'];
				if (isset($data[3])){
					$arr[3] = $data[3]['cat_name'];
				}
			}
			return $arr;
		}
		return false;
	}
	
	/**
	 *	查询一组类目信息
	 *	cat_ids = cat_id数组
	 */
	function fetch_info_by_catids($cat_ids){
		if (is_array($cat_ids) && !empty($cat_ids)){
			$where = 'WHERE cat_id  IN (\''.implode(',', $cat_ids).'\')';
		} else {
			return false; 
		}
		$sql = 'SELECT * FROM `product_category` '.$where.' AND status = 1 ORDER BY displayorder ASC, cat_id ASC';
		return $this -> db -> rows($sql);
	}
}