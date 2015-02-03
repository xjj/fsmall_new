<?php
namespace admin;

if (!defined('START')) exit('No direct script access allowed');

load_function('common');

class Front extends \Controller {
	
	static $status = 0;			//静态属性 - 标记是否第一次执行
	public $mod;				//大栏目
	public $col;				//小栏目
	public $navArrs = array(
		'system' => array(
			'text' => '系统', 
			'items' => array(
				'setting' => '网站配置',
				'rate' => '汇率管理',
				'payment' => '支付设置',
				'region' => '配送地区设置',
				'shipping' => '配送方式',
				'user' => '网站管理员',
			),
		),
		'product' => array(
			'text' => '商品',
			'items' => array(
				'items' => '商品列表',
				'add' => '添加商品',
				'brand' => '商品品牌',
				'tb_cat' => '淘宝类目与属性',
				'category' => '商品类目',
				'discount' => '商品促销打折',
				'soldout' => '商品断货记录',
				'recycle' => '商品回收站',
				'tbdata' => '淘宝数据包',
			),
		),
		'order' => array(
			'text' => '订单',
			'items' => array(
				'items' => '订单列表',
				'send' => '订单发货',
				'refund' => '退货管理',
				'krcancle' => '韩国取消列表',
				'message' => '订单留言',
			),
		),
		'user' => array(
			'text' => '会员',
			'items' => array(
				'items' => '会员列表',
				'grade' => '会员等级',
				'score' => '积分日志',
				'money' => '资金日志',
				'otherlogin' => '第三方登录列表',
				'upgrade' => '批发会员审核',
			),
		),
		'report' => array(
			'text' => '报表',
			'items' => array(
				'sale' => '商品销售排行',
				'counter' => '商品销售统计',
				'onsale' => '商品上架统计',
				'order' => '订单统计',
				'users' => '会员统计',
				'user_amount' => '用户储备金',
				'amount' => '资金统计',
			),
		),
		'operate' => array(
			'text' => '运营',
			'items' => array(
				'update' => '数据更新',
				'notice' => '公告管理',
				'article' => '文章管理',
				'event' => '活动管理',
				'adp' => '广告管理',
				'friendlink' => '友情链接',
				
			),
		),
		'suplier' => array(
			'text' => '供应商',
			'items' => array(
				'profile' => '供应商信息',
				'users' => '供应商账号',
				'discount' => '折扣设置',
				'account' => '账目报表',
				
			), 
		),
	);
	
	function __construct(){
		parent::__construct();
		
		//重新设定模版文件夹
		$this -> smarty -> setTemplateDir(TEMPLATE_PATH.'/admin');
		
		//导航
		$this -> smarty -> assign('navArrs', $this->navArrs);
		
		//模块与栏目
		$this -> mod = $this->class;
		$this -> col = $this->method;
		$this -> smarty -> assign('mod', $this -> mod);
		
		if ($this->col == 'index') {
			if (isset($this -> navArrs[$this->mod]['items'])){
				$items = $this -> navArrs[$this->mod]['items'];
				$keys  = array_keys($items);
				$this -> col = current($keys);		
			}
		}
		$this -> smarty -> assign('col', $this -> col);
		
		/**
		 +-----------------------------
		 *	该类被多个类继承时 
		 *	下面的代码只有第一次被运行
		 +-----------------------------
		 */
		if (self::$status == 0) {
			self::$status = 1;
			
			//登录
			$this -> login();
			
			//权限
			$this -> auth();
		}
	}
	
	//自动登录
	private function login(){
		if (isset($_SESSION['admin']['uid'])){
			$islogin = true;
		} else { 
			$mods = array('index');
			foreach ($this->navArrs as $key => $val){
				$mods[] = $key;
			}
			if (in_array($this->mod, $mods)){
				
				$login = new login();
				$islogin = $login -> autologin();
				if (!$islogin){
					cpurl('/login');
				}
			} else {
				$islogin = false;
			}
		}
		
		$this -> smarty -> assign('islogin', $islogin);
	}
	
	//权限验证
	private function auth(){
		//cpmsg(false, '抱歉，您没有访问权限！', -1);
	}
}