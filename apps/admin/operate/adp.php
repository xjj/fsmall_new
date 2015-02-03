<?php
namespace admin\operate;

if (!defined('START')) exit('No direct script access allowed.');

use admin as adm;
use model as md;

/**
 +----------------------------
 *	广告页面
 +----------------------------
 */
class ADP extends adm\Front {
	
	//广告位列表
	function items(){
		$sql = 'SELECT * FROM `adp` ORDER BY adp_id ASC';
		$adp_items = $this -> db -> rows($sql);
		if ($adp_items){
			$adp = new md\adp();
			foreach ($adp_items as $key => $item){
				$adp_items[$key]['time_status'] = $adp -> time_status($item['start_time'], $item['end_time']);
			}
		}
		
		$this -> smarty -> assign('adp_items', $adp_items);
		$this -> smarty -> display('operate/adp_list.tpl');
	}
	
	//添加广告位
	function add(){
		if (isset($_POST['submit'])){
			$this -> add_submit();
		}
		
		$this -> smarty -> assign('more_navs', '添加广告位');
		$this -> smarty -> display('operate/adp_add.tpl');
	}
	
	//添加操作
	private function add_submit(){
		$adp_code = trim($_POST['adp_code']);
		$content = trim($_POST['content']);
		$width = intval($_POST['width']);
		$height = intval($_POST['height']);
		$start_time = trim($_POST['start_time']);
		$end_time = trim($_POST['end_time']);
		
		$adp = new md\adp();
		if (empty($adp_code)){
			cpmsg(false, '请填写广告代码，广告调用就靠这个代码呢。', -1);
		}
		$f = $adp -> isExist_adpcode($adp_code);
		if ($f){
			cpmsg(false, '广告位代码已存在，请更换一个。', -1);
		}
		if ($width <= 0){
			cpmsg(false, '请填写广告位的宽度。', -1);
		}
		if ($height <= 0){$height = 0;}
		
		$f = $adp -> add(array(
			'adp_code' => $adp_code,
			'content' => $content,
			'width' => $width,
			'height' => $height,
			'start_time' => $start_time,
			'end_time' => $end_time,
		));
		
		if ($f > 0){
			cpmsg(true, '广告位添加成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '广告位添加失败！', -1);
		}
	}
	
	//编辑广告位
	function edit(){
		if (isset($_POST['submit'])){
			$this -> edit_submit();
		}
		
		//查询广告位信息
		$adp_id = $this -> params[1];
		if (!isint($adp_id) || $adp_id <= 0){
			cpmsg(false, '缺少重要参数，请检查链接是否正确！', -1);
		}
		
		$adp = new md\adp();
		$adp_data = $adp -> info($adp_id);
		if ($adp_data){} else {
			cpmsg(false, '该广告位不存在或已被删除！', -1);
		}
		
		$this -> smarty -> assign('more_navs', '编辑广告位');
		$this -> smarty -> assign('adp_data', $adp_data);
		$this -> smarty -> display('operate/adp_edit.tpl');
	}
	
	//编辑操作
	private function edit_submit(){
		$adp_id = trim($_POST['adp_id']);
		$adp_code = trim($_POST['adp_code']);
		$content = trim($_POST['content']);
		$width = intval($_POST['width']);
		$height = intval($_POST['height']);
		$start_time = trim($_POST['start_time']);
		$end_time = trim($_POST['end_time']);
		
		if (!isint($adp_id) || $adp_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		if (empty($adp_code)){
			cpmsg(false, '请填写广告代码，广告调用就靠这个代码呢。', -1);
		}
		
		$adp = new md\adp();
		$f = $adp -> isExist_adpcode($adp_code, $adp_id);
		if ($f){
			cpmsg(false, '广告位代码已存在，请更换一个。', -1);
		}
		if ($width <= 0){
			cpmsg(false, '请填写广告位的宽度。', -1);
		}
		if ($height <= 0){$height = 0;}
		
		
		$num = $adp -> edit($adp_id, array(
			'adp_code' => $adp_code,
			'content' => $content,
			'width' => $width,
			'height' => $height,
			'start_time' => $start_time,
			'end_time' => $end_time,
		));
		
		if ($num > 0){
			cpmsg(true, '广告位信息编辑成功！', '/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '广告位信息编辑失败，没有信息被更新！', -1);
		}
	}
	
	
	//删除广告位
	function del(){
		$adp_id = $this -> params[1];
		if (!isint($adp_id) || $adp_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$adp = new md\adp();
		$f = $adp -> delete($adp_id);
		if ($f){
			cpurl('/'.$this->mod.'/'.$this->col);
		} else {
			cpmsg(false, '删除广告位失败！', -1);	
		}
	}
	
	
	//广告
	function ads(){
		$p = $this -> params[2];
		$ps = array('items', 'add', 'edit', 'del', 'show', 'hide');
		if (!in_array($p, $ps)){$p = 'items';}
		
		$method = 'ads_'.$p;
		$this -> $method();
	}
	
	//广告图片列表
	function ads_items(){
		$adp_id = $this -> params[1];
		
		$adp = new md\adp();
		$adp_data = $adp -> info($adp_id);
		if ($adp_data){} else {
			cpmsg(false, '该广告位不存在或已被删除！', -1);
		}
		
		$ads = new md\ads();
		$ad_items = $ads -> items($adp_id, 'all');
		
		$more_navs = $adp_data['adp_code'];
		$this -> smarty -> assign('more_navs', $more_navs);
		$this -> smarty -> assign('ad_items', $ad_items);
		$this -> smarty -> assign('adp_data', $adp_data);
		$this -> smarty -> display('operate/ads_list.tpl');
	}
	
	//添加广告图片
	function ads_add(){
		if (isset($_POST['submit'])){
			$this -> ads_add_submit();
		}
	
		$adp_id = $this -> params[1];
		
		$adp = new md\adp();
		$adp_data = $adp -> info($adp_id);
		if ($adp_data){} else {
			cpmsg(false, '该广告位不存在或已被删除！', -1);
		}
		
		$more_navs = array(
			$adp_data['adp_code'],
			'添加图片'
		);
		$this -> smarty -> assign('more_navs', $more_navs);
		$this -> smarty -> assign('adp_data', $adp_data);
		$this -> smarty -> display('operate/ads_add.tpl');
	}
	
	//添加图片操作
	function ads_add_submit(){
		$adp_id = trim($_POST['adp_id']);
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		$pic = trim($_POST['pic']);
		$url = trim($_POST['url']);
		$displayorder = intval($_POST['displayorder']);
		
		if (!isint($adp_id) || $adp_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		if (empty($pic)){
			cpmsg(false, '请上传广告图片！', -1);
		}
		if (!empty($url)){
			$url2 = strtolower($url);
			if (strpos($url2, 'http://') === 0){} else {
				cpmsg(false, '链接地址必须以 (http://) 开头。', -1);
			}
		}
			
		$ads = new md\ads();
		$f = $ads -> add(array(
			'adp_id' => $adp_id,
			'title' => $title,
			'content' => $content,
			'pic' => $pic,
			'url' => $url,
			'displayorder' => $displayorder,
		));
		
		if ($f > 0){
			cpmsg(true, '广告图片添加成功！', '/'.$this->mod.'/'.$this->col.'/ads/'.$adp_id);
		} else {
			cpmsg(false, '广告图片添加失败！', -1);
		}
	}
	
	
	//编辑
	function ads_edit(){
		if (isset($_POST['submit'])){
			$this -> ads_edit_submit();
		}
		
		$ad_id = $this -> params[3];
		if (!isint($ad_id) || $ad_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$ads = new md\ads();
		$ads_data = $ads -> info($ad_id);
		if ($ads_data){} else {
			cpmsg(false, '该广告图片不存在或已被删除！', -1);
		}
		
		$adp_id = $ads_data['adp_id'];
		$adp = new md\adp();
		$adp_data = $adp -> info($adp_id);
		if ($adp_data){} else {
			cpmsg(false, '该广告位已经不存在或被删除了。', -1);
		}
		
		$more_navs = array(
			$adp_data['adp_code'],
			'编辑图片'
		);
		$this -> smarty -> assign('more_navs', $more_navs);
		$this -> smarty -> assign('adp_data', $adp_data);
		$this -> smarty -> assign('ads_data', $ads_data);
		$this -> smarty -> display('operate/ads_edit.tpl');
	}
	
	//编辑操作
	private function ads_edit_submit(){
		$ad_id = trim($_POST['ad_id']);
		$adp_id = trim($_POST['adp_id']);
		$title = trim($_POST['title']);
		$content = trim($_POST['content']);
		$pic = trim($_POST['pic']);
		$url = trim($_POST['url']);
		$displayorder = intval($_POST['displayorder']);
		
		if (!isint($adp_id) || $adp_id <= 0 || !isint($ad_id) || $ad_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		if (empty($pic)){
			cpmsg(false, '请上传广告图片！', -1);
		}
		if (!empty($url)){
			$url2 = strtolower($url);
			if (strpos($url2, 'http://') === 0){} else {
				cpmsg(false, '链接地址必须以 (http://) 开头。', -1);
			}
		}
			
		$ads = new md\ads();
		$num = $ads -> update($ad_id, array(
			'title' => $title,
			'content' => $content,
			'pic' => $pic,
			'url' => $url,
			'displayorder' => $displayorder,
		));
		
		if ($num > 0){
			cpmsg(true, '广告图片编辑成功！', '/'.$this->mod.'/'.$this->col.'/ads/'.$adp_id);
		} else {
			cpmsg(false, '广告图片编辑失败！', -1);
		}
	}
	
	//删除广告
	function ads_del(){
		$ad_id = $this -> params[3];
		$adp_id = $this -> params[1];
		if (!isint($ad_id) || $ad_id <= 0 || !isint($adp_id) || $adp_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$ads = new md\ads();
		$f = $ads -> delete($ad_id);
		if ($f > 0){
			cpurl('/'.$this->mod.'/'.$this->col.'/ads/'.$adp_id);
		} else {
			cpmsg(false, '广告信息删除失败！', -1);
		}
	}
	
	//显示广告
	function ads_show(){
		$ad_id = $this -> params[3];
		$adp_id = $this -> params[1];
		if (!isint($ad_id) || $ad_id <= 0 || !isint($adp_id) || $adp_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$ads = new md\ads();
		$ads -> show($ad_id);
		cpurl('/'.$this->mod.'/'.$this->col.'/ads/'.$adp_id);
	}
	
	//隐藏广告
	function ads_hide(){
		$ad_id = $this -> params[3];
		$adp_id = $this -> params[1];
		if (!isint($ad_id) || $ad_id <= 0 || !isint($adp_id) || $adp_id <= 0){
			cpmsg(false, '错误的请求！', -1);
		}
		
		$ads = new md\ads();
		$ads -> hide($ad_id);
		cpurl('/'.$this->mod.'/'.$this->col.'/ads/'.$adp_id);
	}
}