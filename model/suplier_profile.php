<?PHP
namespace model;

/**
 +------------------------------
 *	供货商 信息类
 +------------------------------
 */
class Suplier_profile extends Front {
	
	//获取一条信息
	function info($brand_id){
		$sql = 'SELECT * FROM `suplier_profile` WHERE brand_id = '.$brand_id.'';
		return $this -> db -> row($sql);	
	}
		
	//获取
	function search($params, $page, $pagesize){
		$where = '';
		if(isset($params['brand_id']) && isint($params['brand_id']) && $params['brand_id'] > 0){
			$where =' WHERE sp.brand_id='.$params['brand_id'];
		}
		$sql = 'SELECT sp.*, b.brand_name FROM `suplier_profile` sp LEFT JOIN `product_brand` b on sp.brand_id = b.brand_id '.$where.' LIMIT '.($page-1)*$pagesize.','.$pagesize;
		$items = $this -> db -> rows($sql);
		
		$sql = 'SELECT COUNT(*) AS num FROM `suplier_profile` sp '.$where.'';
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		
		return array(
			'items' => $items,
			'total' => $total
		);
	}
	
	//添加
	function add($data){
		if (empty($data) ){return false;}
		return $this -> db -> insert('suplier_profile', array(
			'brand_id' => $data['brand_id'],
			'company_name' => trim($data['company_name']),
			'company_address' => trim($data['company_address']),
			'contact' => trim($data['contact']),
			'telphone' => trim($data['telphone']),
			'bank_name' => trim($data['bank_name']),
			'bank_username' => trim($data['bank_username']),
			'bank_account' => trim($data['bank_account']),
			'update_time' => time(),
			'adm_uid' => $data['adm_uid'],
		));
	}
	
	//删除
	function delete($brand_id){
		if (!isint($brand_id) || $brand_id <= 0){return false;}	
		
		return $this -> db -> delete('suplier_profile', array('brand_id' => $brand_id)); 
	}
	
	//更新
	function update($brand_id, $data){
		if (!isint($brand_id) || $brand_id <= 0){return false;}
		return $this -> db -> update('suplier_profile', $data, array('brand_id' => $brand_id));
	}

	
}