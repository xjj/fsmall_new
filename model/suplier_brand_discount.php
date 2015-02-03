<?PHP
namespace model;

/**
 +------------------------------
 *	供货商 品牌折扣信息类
 +------------------------------
 */
class Suplier_Brand_Discount extends Front {
		
	//获取最近的折扣
	function discount($brand_id){
		if (!isint($brand_id) || $brand_id <= 0){return false;}
		
		$sql = 'SELECT discount FROM `suplier_brand_discount` WHERE brand_id = '.$brand_id.' AND status = 1 ORDER BY id DESC LIMIT 0,1';
		$row = $this -> db -> row($sql);
		if ($row){
			return $row['discount'];	
		} else {
			return false;	
		}
	}
	
	//获取所有商品的折扣信息 -- 同一个商品仅取最新的一条
	function items(){
		$sql = 'SELECT brand_id, discount FROM `suplier_brand_discount` WHERE status = 1 ORDER BY id DESC';
		$rows = $this -> db -> rows($sql);
		if ($rows){
			$data = array();
			foreach ($rows as $row){
				$brand_id = $row['brand_id'];
				if (!array_key_exists($brand_id, $data)){
					$data[$brand_id] = $row['discount'];	
				}
			}
			return $data;
		} else {
			return false; 	
		}
	}
	
	//添加品牌折扣信息
	function add($brand_id, $discount){
		if (!isint($brand_id) || $brand_id <= 0){return false;}
		if (!isint($discount) || $discount <= 0 || $discount >= 100){return false;}
		
		return $this -> db -> insert('suplier_brand_discount', array(
			'brand_id' => $brand_id,
			'discount' => $discount,
			'add_time' => time(),
			'status' => 1
		));
	}
	
	//删除品牌折扣信息
	function delete($id){
		if (!isint($id) || $id <= 0){return false;}	
		
		return $this -> db -> delete('suplier_brand_discount', array('id' => $id)); 
	}
	
	//有效设置
	function validate($id, $status){
		if (!isint($id) || $id <= 0){return false;}	
		if ($status != 1){$status = 0;}
		
		return $this -> db -> update('suplier_brand_discount', array('status' => $status), array('id' => $id)); 	
	}
	
	//查询
	function search($params, $page, $pagesize){
		$k = trim($params['k']);
		
		$where = '';
		if (!empty($k)){
			$sql .= ' WHERE b.brand_name like \'%'.encode($k).'%\' ';	
		}
		
		$sql = 'SELECT a.*, b.brand_name FROM `suplier_brand_discount` a LEFT JOIN `product_brand` b ON a.brand_id = b.brand_id '.$where.' ORDER BY a.status DESC, a.id DESC LIMIT '.($page-1)*$pagesize.','.$pagesize;
		
		$items = $this -> db -> rows($sql);	
		
		$sql = 'SELECT COUNT(*) as num FROM `suplier_brand_discount` a LEFT JOIN `product_brand` b ON a.brand_id = b.brand_id '.$where.'';
		
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array('items' => $items, 'total' => $total);
	}
}