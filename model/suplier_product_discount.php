<?PHP
namespace model;

/**
 +------------------------------
 *	供货商 商品折扣信息类
 +------------------------------
 */
class Suplier_Product_Discount extends Front {
		
	//获取最近的折扣
	function discount($prd_id){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		
		$sql = 'SELECT discount FROM `suplier_product_discount` WHERE prd_id = '.$prd_id.' AND status = 1 ORDER BY id DESC LIMIT 0,1';
		$row = $this -> db -> row($sql);
		if ($row){
			return $row['discount'];	
		} else {
			return false;	
		}
	}
	
	//获取所有商品的折扣信息 -- 同一个商品仅取最新的一条
	function items(){
		$sql = 'SELECT prd_id, discount FROM `suplier_product_discount` WHERE status = 1 ORDER BY id DESC';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$data = array();
			foreach ($rows as $row){
				$prd_id = $row['prd_id'];
				if (!array_key_exists($prd_id, $data)){
					$data[$prd_id] = $row['discount'];	
				}
			}
			return $data;
		} else {
			return false; 	
		}
	}
	
	//添加品牌折扣信息
	function add($prd_id, $discount){
		if (!isint($prd_id) || $prd_id <= 0){return false;}
		if (!isint($discount) || $discount <= 0 || $discount >= 100){return false;}
		
		return $this -> db -> insert('suplier_product_discount', array(
			'prd_id' => $prd_id,
			'discount' => $discount,
			'add_time' => time(),
			'status' => 1
		));
	}
	
	//删除品牌折扣信息
	function delete($id){
		if (!isint($id) || $id <= 0){return false;}	
		
		return $this -> db -> delete('suplier_product_discount', array('id' => $id)); 
	}
	
	//有效设置
	function validate($id, $status){
		if (!isint($id) || $id <= 0){return false;}	
		if ($status != 1){$status = 0;}
		
		return $this -> db -> update('suplier_product_discount', array('status' => $status), array('id' => $id)); 	
	}
	
	//查询
	function search($params, $page, $pagesize){
		$k = trim($params['k']);
		
		$where = '';
		if (!empty($k)){
			$sql .= ' WHERE p.product_sn like \'%'.encode($k).'%\' ';	
		}
		
		$sql = 'SELECT a.*, p.product_name, p.pic_small, p.product_sn FROM `suplier_product_discount` a LEFT JOIN `product_info` p ON a.prd_id = p.prd_id '.$where.' ORDER BY a.status DESC, a.id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$items = $this -> db -> rows($sql);	
		
		$sql = 'SELECT COUNT(*) as num FROM `suplier_product_discount` a LEFT JOIN `product_info` p ON ON a.prd_id = p.prd_id '.$where.'';
		
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array('items' => $items, 'total' => $total);
	}
	
	//清理断货的商品信息
	function clear(){
		//查询所有有效的商品的断货信息
		$sql = 'SELECT a.prd_id, b.is_delete FROM `suplier_product_discount` a LEFT JOIN `product_info` b ON a.prd_id = b.prd_id WHERE a.status = 1';
		
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$ids = array();
			foreach ($rows as $row){
				if ($row['is_delete'] == 1){
					$ids[] = $row['prd_id'];	
				}	
			}
			
			if (empty($ids)){return 0;}
			
			$ids = array_unique($ids);
			
			$where = 'prd_id IN ('. implode(',', $ids) .') AND status = 1';
			return $this -> db -> update('suplier_product_discount', array('status' => 0), $where);
		}
		
		return 0; 
	}
}