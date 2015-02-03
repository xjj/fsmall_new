<?php
namespace admin\suplier;

use model as md;
use admin as adm;

class user extends adm\Front {
	
	//所有供应商信息
	function items(){
		$page = intval($_GET['page']);
		$pagesize = 20;
		if ($page <= 0){$page = 1;}
		
		$user = new md\suplier_user();
		$ret = $user -> search($_GET, $page, $pagesize);
		$user_items = $ret['items'];
		$total = $ret['total'];
		
		$params = fetch_url_query($_GET);
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this -> col.'',
			'params' =>$params
		));
		
	
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> assign('user_items', $user_items);
		$this -> smarty -> assign('params', $params);
		$this -> smarty -> display('suplier/user_list.tpl');
	}
	
	
	//添加
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
			exit();	
		}
		//获取品牌下拉
		$brand = new md\brand();
		$ret = $brand -> items(0);	
		$this -> smarty -> assign('brands',$ret);
		$this -> smarty -> display('suplier/suplier_account_add.tpl');
	}	
	
	//添加操作
	private function add_submit(){
		$brand_id = intval($_POST['brand_id']);
		
		$sp_uname = trim($_POST['sp_uname']);
		
		$upwd = trim($_POST['upwd']);
		$upwd2 = trim($_POST['upwd2']);
		if($upwd!=$upwd2){
			cpmsg(false, '2次输入的密码不一致！', -1);	
		}
		if( empty($sp_uname)){
			cpmsg(false, '请填写有效的用户名！', -1);	
		}
		$data=array(
					'brand_id'=>$brand_id,
					'sp_uname'=>$sp_uname,
					'sp_upass'=>md5($upwd),
					'add_time' => time(),
					);
		if ($brand_id <= 0){
			cpmsg(false, '请填写正确的品牌id！', -1);	
		}
		$suplier_account = new md\suplier_account();
		$f = $suplier_account -> add($data);//
		if ($f){
			cpmsg(true, '供应商添加成功！', '/'.$this->mod.'/'.$this->col.'');	
		} else {
			cpmsg(false, '添加失败！', -1);	
		}
	}
	
	function update(){
		if (isset($_POST['submit'])){
			$this -> update_submit();
			exit();	
		}

		$id = $this -> params[1];
		
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);	
		}	
		$suplier_account = new md\suplier_account();
		$_GET['sp_uid']=$id; 
		$pagesize = 20;
		$page = 1;
		$result= $suplier_account -> search($_GET, $page, $pagesize);
		//$ret = $suplier_account -> search($_GET, $page, $pagesize);
		//echo mysql_error();
		//var_dump($result);die;
		$suplier_item= $result['items'][0];
		$this -> smarty -> assign('suplier_item',$suplier_item);
		$this -> smarty -> display('suplier/suplier_account_edit.tpl');
		
	}
	private function update_submit(){
		$id = intval($_POST['id']);
		
		$upwd = trim($_POST['upwd']);
		$upwd2 = trim($_POST['upwd2']);
		if($upwd!=$upwd2){
			cpmsg(false, '2次输入的密码不一致！', -1);	
		}
		$data=array(
					//'sp_uid'=>$id,
					'sp_upass'=>md5($upwd),

					'update_time' => time(),
					);
		$suplier_account = new md\suplier_account();
		$f = $suplier_account -> update($id,$data);//
		if ($f){
			cpmsg(true, '操作成功！', '/'.$this->mod.'/'.$this->col.'');	
		} else {
			//echo mysql_error();
			cpmsg(false, '操作失败！', -1);	
		}
	}
	function show(){
		$id = $this -> params[1];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);	
		}	
		
		$suplier_account = new md\suplier_account();
		$suplier_account -> validate($id, 1);
		
		$params = fetch_url_query($_GET);
		$url = '/'.$this->mod.'/'.$this->col.'';
		if (!empty($params)){$url .= '?'.$params;}
		cpurl($url);
	}
	
	function hide(){
		$id = $this -> params[1];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);	
		}	
		
		$suplier_account = new md\suplier_account();
		$suplier_account -> validate($id, 0);
		
		$params = fetch_url_query($_GET);
		$url = '/'.$this->mod.'/'.$this->col.'';
		if (!empty($params)){$url .= '?'.$params;}	
		cpurl($url);
	}
		
	function del(){
		//var_dump( $this -> params );
		$id = $this -> params[1];
		if (!isint($id) || $id <= 0){
			cpmsg(false, '错误的请求！', -1);	
		}	
		
		$suplier_account = new md\suplier_account();
		$suplier_account -> delete($id);
		
		$params = fetch_url_query($_GET);
		$url = '/'.$this->mod.'/'.$this->col.'';
		if (!empty($params)){$url .= '?'.$params;}	
		cpurl($url);
	}

}