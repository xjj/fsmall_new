<?php
namespace model;
/**
 +-----------------------------
 *	获取商品图片模型 fs后台 这个和214上那个不同
 +-----------------------------
 */
class Product_pics extends front {
	function search($array, $page=1, $pagesize=10){
		$where='1=1 ';
		if(isset($array['prd_id'])){
			$prd_id=  intval($array['prd_id']);
			if (!isint($prd_id) || intval($prd_id) <= 0){return false;}
			$where .=' and prd_id = '.$prd_id;
		}
		if(isset($array['add_time'])){
			$where.=' and  add_time='.$array['add_time'];
		}
		if(isset($array['is_delete'])){
			$where.=' and  is_delete = '.$array['is_delete'];
		}
		if(isset($array['is_confirm'])){
			$where.=' and  is_confirm= '.$array['is_confirm'];
		}
		$where.=' limit 20';//
		$sql = 'SELECT * FROM `product_pics` WHERE '.$where;
		$res = $this -> db -> rows($sql);
		$sql = 'SELECT COUNT(*) AS num FROM `product_pics` WHERE '.$where;
		$row = $this -> db -> row($sql);
		$total = $row['num'];
		return array(
			'items' => $res,
			'num' => $total
		);	
	}
	//查询图片服务器上
	function search_img2($array, $page=1, $pagesize=10){
		$prd_id = $array['prd_id'];
		$where='1=1 and status=3 ';#
		if(isset($array['prd_id'])){
			if (!isint($prd_id) || $prd_id <= 0){
				return false;
			}
			$where .=' and prd_id = '.$prd_id;
		}
		if(isset($array['timeline'])){
			$where.=' and  LEFT (FROM_UNIXTIME(timeline), 10) >= LEFT (NOW(), 10)';
		}
		if(isset($array['is_delete'])){
			$where.=' and  is_delete <>1';
		}
		if(isset($array['is_confirm'])){
			$where.=' and  is_confirm<>1 ';
		}
		if(isset($array['pic_id'])){
			if(!is_array( $array['pic_id'] )){#单个
				$where.=' and  pic_id='.$array['pic_id'];
			}else{#多个
				$str='';
				$where.=' and  pic_id in (';
				foreach($array['pic_id'] as $v){
					$str .=intval($v).',';		
				}
				$str = trim($str,',').') ';
				$where.=$str; 
			}
		}
		$db2= 	 fetch_instance('DB',2);
		$sql = 'SELECT * FROM `product_pics` WHERE '.$where.'  order by pic_id asc limit 20 ';
		$res = $db2 -> rows($sql);
		$sql = 'SELECT COUNT(*) AS num FROM `product_pics` WHERE '.$where;
		$row =$db2 -> row($sql);
		$total = $row['num'];
		return array(
			'items' => $res,
			'num' => $total
		);	
	}
		//删除
	function pic_del_img2($id){
		$db2  =  fetch_instance('DB',2);
		if (!isint($id) || $id <= 0){
			return false;
		}
		 $r= $db2-> delete('product_pics', array('pic_id' => $id)); 
		 return $r;
	}
			//批量删除
	function pic_del_img2_multi($str){
		$db2  =  fetch_instance('DB',2);
		 $r= $db2-> delete('product_pics', $str); 
		 return $r;
	}
	//添加
	function add($data){
		if(isset($data['items'])&&is_array($data['items'] )){
			$items= $data['items'];
			foreach($items as $k=>$v){
				unset($v['pic_id']);
				unset($v['add_time']);
				unset($v['is_confirm']);
				$v['add_time']=time();
				$v['is_confirm']=1;
				$items[$k]=$v;	
			}
			return $this -> db -> insert_multi('product_pics',$items);	
		}else{
			return false;
		}
	}
		//删除fs表中图片
	function pic_local_del($id){
		if (!isint($id) || $id <= 0){
			return false;
		}
		 $r= $this->db-> delete('product_pics', array('pic_id' => $id)); 
		 return $r;
	}	
			//批量删除fs上的图片
	function pic_del_local_multi($str){
		
		 $r= $this->db-> delete('product_pics', $str); 
		 return $r;
	}
}