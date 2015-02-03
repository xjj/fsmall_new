<?php
namespace admin\suplier;

use model as md;
use admin as adm;

class Profile extends adm\Front {
	
	//所有供应商信息
	function items(){
		$page = intval($_GET['page']);
		$pagesize = 20;
		if ($page <= 0){$page = 1;}
		
		$prof = new md\suplier_profile();
		$ret = $prof -> search($_GET, $page, $pagesize);
		$profile_items = $ret['items'];
		$total = $ret['total'];	

		$params = fetch_url_query($GET);
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/'.$this->mod.'/'.$this -> col.'',
			'params' => $_GET
		));
		
		$this -> smarty -> assign('pagebox', $pg->show());
		$this -> smarty -> assign('profile_items', $profile_items);
		$this -> smarty -> display('suplier/profile_list.tpl');
	}
	
	
	//添加
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
			exit();	
		}
		//获取品牌下拉
		$brand = new md\brand();
		$brand_items = $brand -> items(0);	
		$this -> smarty -> assign('brand_items',$brand_items);
		$this -> smarty -> display('suplier/profile_add.tpl');
	}	
	
	//添加操作
	private function add_submit(){
		$brand_id = intval($_POST['brand_id']);
		$company_name = trim($_POST['company_name']);
		$company_address = trim($_POST['company_address']);
		$contact = trim($_POST['contact']);
		$telphone = trim($_POST['telphone']);
		$bank_username = trim($_POST['bank_username']);
		$bank_name = trim($_POST['bank_name']);
		$bank_account = trim($_POST['bank_account']);
		
		if ($brand_id <= 0){
			cpmsg(false, '请选择品牌！', -1);	
		}
		
		
		$prof = new md\suplier_profile();
		$f = $prof -> info($brand_id);
		if ($f){
			cpmsg(false, '此品牌信息已经存在！', -1);
		}
		
		$f = $prof -> add(array(
			'brand_id'=>$brand_id,
			'bank_account'=>$bank_account,
			'bank_name'=>$bank_name,
			'bank_username'=>$bank_username,
			'company_address'=>$company_address,
			'company_name'=>$company_name,
			'contact'=>$contact,
			'telphone'=>$telphone,
			'adm_uid'=>$_SESSION['admin']['uid']
		));
		if ($f){
			cpmsg(true, '供应商资料添加成功！', '/'.$this->mod.'/'.$this->col.'');	
		} else {
			cpmsg(false, '供应商资料添加失败！', -1);	
		}
	}
	
	//编辑
	function edit(){
		if (isset($_POST['submit'])){
			$this -> edit_submit();
			exit();	
		}

		$brand_id = $this -> params[1];
		
		if (!isint($brand_id) || $brand_id <= 0){
			cpmsg(false, '错误的请求！', -1);	
		}	
		
		//获取品牌下拉
		$brand = new md\brand();
		$brand_items = $brand -> items(0);	
		
		
		$prof = new md\suplier_profile();
		$profile_data = $prof -> info($brand_id);

		$this -> smarty -> assign('profile_data',$profile_data);
		$this -> smarty -> assign('brand_items', $brand_items);
		$this -> smarty -> display('suplier/profile_edit.tpl');

	}
	
	
	private function edit_submit(){
		$brand_id = intval($_POST['brand_id']);
		$bank_account = trim($_POST['bank_account']);
		$company_name = trim($_POST['company_name']);
		$company_address = trim($_POST['company_address']);
		$contact = trim($_POST['contact']);
		$telphone = trim($_POST['telphone']);
		$bank_username = trim($_POST['bank_username']);
		$bank_name = trim($_POST['bank_name']);

		$prof = new md\suplier_profile();
		$f = $prof -> update($brand_id, array(
			  'bank_account'=>$bank_account,
			  'bank_name'=>$bank_name,
			  'bank_username'=>$bank_username,
			  'company_address'=>$company_address,
			  'company_name'=>$company_name,
			  'contact'=>$contact,
			  'telphone'=>$telphone,
			  'update_time' => time(),
			  'adm_uid'=>$_SESSION['admin']['uid']
		));
		if ($f){
			cpmsg(true, '操作成功！', '/'.$this->mod.'/'.$this->col.'');	
		} else {
			cpmsg(false, '操作失败！', -1);	
		}
	}

	//删除
	function del(){
		$brand_id = $this -> params[1];
		if (!isint($brand_id) || $brand_id <= 0){
			cpmsg(false, '错误的请求！', -1);	
		}	
		
		$prof = new md\suplier_profile();
		$prof -> delete($brand_id);
		
		$params = fetch_url_query($_GET);
		$url = '/'.$this->mod.'/'.$this->col.'';
		if (!empty($params)){$url .= '?'.$params;}	
		cpurl($url);
	}
}