<?php
namespace shop;

use model as md;

/**
 +------------------------------
 *	收藏夹
 +------------------------------
 */
class Favorite extends Front {
	
	function add(){
		if (!$this -> islogin){
			echo json_encode(array(
				'error' => -1,
				'message' => '请先登录，执行此操作需要登录！'
			));
			exit();
		}
		
		$uid = $_SESSION['uid'];
		$prd_id = intval($_POST['prd_id']);
		
		if ($prd_id <= 0){
			echo json_encode(array(
				'error' => -1,
				'message' => '错误的请求！'
			));
			exit();
		}

		$favo = new md\Favorite();
		
		$f = $favo -> isExist($uid, $prd_id);
		if ($f){
			echo json_encode(array(
				'error' => -1,
				'message' => '操作失败，该商品已在您的收藏夹中！',
			));
			exit();
		}
		
		$f = $favo -> add(array('uid' => $uid,'prd_id' => $prd_id));
		if ($f > 0){
			echo json_encode(array(
				'error' => 0,
				'message' => 'ok'
			));
		} else {
			echo json_encode(array(
				'error' => -1,
				'message' => '添加收藏失败！'
			));
		}
	}
	
	//我的收藏页面
	function index(){
		if (!$this -> islogin){
			$login = new login();
			$login -> index();
			exit();
		}
		
		$uid = $_SESSION['uid'];
		
		$page = intval($_GET['page']);
		if ($page <= 0){$page = 1;}
		$pagesize = 32;
		
		$uid = $_SESSION['uid'];
		$user = new md\user();
		$user_data = $user -> info($uid);
		
		$favo = new md\favorite();
		$ret = $favo -> search($uid, $page, $pagesize);
		$product_items = $ret['items'];
		$total = $ret['total'];
		
		//分页
		$pg = new \page(array(
			'page' => $page,
			'pagesize' => $pagesize,
			'total' => $total,
			'url' => '/favorite'
		));
		
		$this -> smarty -> assign('title', '我的收藏夹');
		$this -> smarty -> assign('user_data', $user_data);
		$this -> smarty -> assign('product_items', $product_items);
		$this -> smarty -> assign('pagebox', $pg -> show());
		$this -> smarty -> display('user/favorite.tpl');
	}
}