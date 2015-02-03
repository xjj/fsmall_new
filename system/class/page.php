<?php
if (!defined('START')) exit('No direct script access allowed');

/**
 +-----------------------------------
 *	通用分页类
 +----------------------------------
 */
class Page {
	public $page 	= 1;			//当前页数
	public $pagesize= 24;			//每页显示数
	public $total	= 0;			//总记录数
	public $number 	= 4;			//页面显示的页面数
	public $url		= '';			//链接地址
	public $params	= '';			//页面其他参数
	public $state	= 0;			//类型0=动态地址 1=静态地址
	public $pvar	= 'page';		//翻页的参数名
	public $input   = 0;			//是否显示输入框
	
	/*
	-----------------------------
		构造函数
	-----------------------------
	*/
	function __construct($data = array()){
		if (!empty($data)){
			if (isset($data['page']) && isint($data['page']) && $data['page'] >= 1){
				$this -> page = $data['page'];
			}
			if (isset($data['pagesize']) && isint($data['pagesize'])){
				$this -> pagesize = $data['pagesize'];
			}
			if (isset($data['total']) && isint($data['total'])){
				$this -> total = $data['total'];
			}
			if (isset($data['url'])){
				$this -> url = $data['url'];
			}
			if (isset($data['state'])){
				$this -> type = $data['state'];
			}
			if (isset($data['pvar'])){
				$this -> pvar = $data['pvar'];
			}
			if (isset($data['params'])){
				if (is_array($data['params'])){
					unset($data['params'][$this->pvar]);
					$this -> params = fetch_url_query($data['params']);
				} else {
					$this -> params = $data['params'];
				}
			}
			if (isset($data['number'])){
				$this -> number = $data['number'];
			}
			if (isset($data['input']) && in_array($data['input'], array(0,1))){
				$this -> input = $data['input'];
			}
		}
	}
	
	/*
	-----------------------------
		计算总页数
	-----------------------------
	*/
	function pageCount(){
		if ( ($this -> total % $this -> pagesize) == 0){
			$pagecount = intval( $this -> total / $this -> pagesize );
		} else {
			$pagecount = ceil( $this -> total / $this -> pagesize );
		}
		return $pagecount;
	}
	
	/*
	-----------------------------
		输出链接
	-----------------------------
	*/
	function show(){
		$page  = $this -> page;
		$count = $this -> number;
		$pagecount = $this -> pageCount();
		
		if ($pagecount <= 1){
			return false;
		}
		
		if ($this -> state == 1){
			if ($this -> params != ''){
				$url = $this -> url.'/'.$this -> params .'/';
			} else {
				$url = $this -> url.'/';
			}
		} else {
			if ($this -> params != ''){
				$url = $this -> url.'?'.$this -> params .'&'.$this -> pvar.'=';
			} else {
				$url = $this -> url.'?'.$this -> pvar.'=';
			}
		}
		
		$html  = '<div class="showpage clearfix">';
		//前一页
		if ($page > 1){
			$html .= '<a href="'.$url.($page-1).'" title="上一页" class="pg-prev">&lt;</a>';
		}
		
		//当前页前count个页码
		if ($page > $count){
			if ($page > $count + 1){
				$html .= '<a href="'.$url.'1" title="1">1</a>';
			}
			if ($page > $count + 2){
				$html .= '<span>...</span>';
			}
			for ($i = $page - $count; $i < $page; $i++){
				$html .= '<a href="'.$url.$i.'" title="'.$i.'">'.$i.'</a>';
			}
		} else {
			for ($i = 1; $i < $page; $i++){
				$html .= '<a href="'.$url.$i.'" title="'.$i.'">'.$i.'</a>';
			}
		}
		
		//当前页
		if ($pagecount > 1){
			$html .= '<a class="current" href="'.$url.$page.'" title="'.$page.'">'.$page.'</a>';
		}
		
		//当前页后的页码
		if ($pagecount > $page + $count){
			for ($i = $page + 1; $i <= $page + $count; $i++){
				$html .= '<a href="'.$url.$i.'" title="'.$i.'">'.$i.'</a>';
			}
			if ($pagecount >= $page + $count + 2){
				$html .= '<span>...</span>';
			}
			if ($pagecount >= $page + $count + 1){
				$html .= '<a href="'.$url.$pagecount.'" title="'.$pagecount.'">'.$pagecount.'</a>';
			}
		} else {
			for ($i = $page + 1; $i <= $pagecount ; $i++){
				$html .= '<a href="'.$url.$i.'" title="'.$i.'">'.$i.'</a>';
			}
		}
		
		
		//后一页
		if ($pagecount > $page){
			$html .= '<a href="'.$url.($page+1).'" title="下一页" class="pg-next">&gt;</a>';
			
		}
		
		$html .= '</div>';
		return $html;
	}
}
?>