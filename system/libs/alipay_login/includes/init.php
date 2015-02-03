<?php
/**
 * ECSHOP 前台公用文件
 * ============================================================================
 * 版权所有 2005-2008 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: jeffrey $
 * $Id: init.php,v 1.6 2009/06/10 15:24:17 jeffrey Exp $
*/
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
//header("Cache-Control: no-cache, must-revalidate");
//header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}
error_reporting(E_ALL);
if (__FILE__ == '')
{
    die('Fatal error code: 0');
}
/* 取得当前ecshop所在的根目录 */
define('ROOT_PATH', str_replace('includes/init.php', '', str_replace('\\', '/', __FILE__)));
if (!file_exists(ROOT_PATH . 'data/install.lock') && !file_exists(ROOT_PATH . 'includes/install.lock')
    && !defined('NO_CHECK_INSTALL'))
{
    header("Location: ./install/index.php\n");
    exit;
}
/* 初始化设置 */
@ini_set('memory_limit',          '64M');
@ini_set('session.cache_expire',  1800);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies',   1);
@ini_set('session.auto_start',    0);
@ini_set('display_errors',        1);
if (DIRECTORY_SEPARATOR == '\\')
{
    @ini_set('include_path', '.;' . ROOT_PATH);
}
else
{
    @ini_set('include_path', '.:' . ROOT_PATH);
}
require(ROOT_PATH . 'data/config.php');
if (defined('DEBUG_MODE') == false)
{
    define('DEBUG_MODE', 0);
}

if (PHP_VERSION >= '5.1' && !empty($timezone))
{
    date_default_timezone_set("Asia/Chongqing");
}

$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
if ('/' == substr($php_self, -1))
{
    $php_self .= 'index.php';
}
define('PHP_SELF', $php_self);
require(ROOT_PATH . 'includes/inc_constant.php');
require(ROOT_PATH . 'includes/cls_ecshop.php');
require(ROOT_PATH . 'includes/cls_error.php');
require(ROOT_PATH . 'includes/lib_time.php');
require(ROOT_PATH . 'includes/lib_base.php');
require(ROOT_PATH . 'includes/lib_common.php');
require(ROOT_PATH . 'includes/lib_main.php');
require(ROOT_PATH . 'includes/lib_insert.php');
require(ROOT_PATH . 'includes/lib_goods.php');
require(ROOT_PATH . 'includes/lib_article.php');
/* 对用户传入的变量进行转义操作。*/
if (!get_magic_quotes_gpc())
{
    if (!empty($_GET))
    {
        $_GET  = addslashes_deep($_GET);
    }
    if (!empty($_POST))
    {
        $_POST = addslashes_deep($_POST);
    }
    $_COOKIE   = addslashes_deep($_COOKIE);
    $_REQUEST  = addslashes_deep($_REQUEST);
}
/* 创建 ECSHOP 对象 */
$ecs = new ECS($db_name, $prefix);
define('DATA_DIR', $ecs->data_dir());
define('IMAGE_DIR', $ecs->image_dir());
/* 初始化数据库类 */
require(ROOT_PATH . 'includes/cls_mysql.php');
$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
$db->set_disable_cache_tables(array($ecs->table('sessions'), $ecs->table('sessions_data'), $ecs->table('cart')));
$db_host = $db_user = $db_pass = $db_name = NULL;
/* 创建错误处理对象 */
$err = new ecs_error('message.dwt');
/* 载入系统参数 */
$_CFG = load_config();
/* 载入语言文件 */
require(ROOT_PATH . 'languages/' . $_CFG['lang'] . '/common.php');
if ($_CFG['shop_closed'] == 1)
{
    /* 商店关闭了，输出关闭的消息 */
    header('Content-type: text/html; charset='.EC_CHARSET);
    die('<div style="margin: 150px; text-align: center; font-size: 14px"><p>' . $_LANG['shop_closed'] . '</p><p>' . $_CFG['close_comment'] . '</p></div>');
}
if (is_spider())
{
    /* 如果是蜘蛛的访问，那么默认为访客方式，并且不记录到日志中 */
    if (!defined('INIT_NO_USERS'))
    {
        define('INIT_NO_USERS', true);
        /* 整合UC后，如果是蜘蛛访问，初始化UC需要的常量 */
        if($_CFG['integrate_code'] == 'ucenter')
        {
             $user = & init_users();
        }
    }
    $_SESSION = array();
}
if (!defined('INIT_NO_USERS'))
{
    /* 初始化session */
    include(ROOT_PATH . 'includes/cls_session.php');
    $sess = new cls_session($db, $ecs->table('sessions'), $ecs->table('sessions_data'));
    define('SESS_ID', $sess->get_session_id());
}

if (!defined('INIT_NO_SMARTY'))
{
    header('Cache-control: private');
    header('Content-type: text/html; charset='.EC_CHARSET);
    /* 创建 Smarty 对象。*/
    require(ROOT_PATH . 'includes/cls_template.php');
    $smarty = new cls_template;
    $smarty->cache_lifetime = $_CFG['cache_time'];
    $smarty->template_dir   = ROOT_PATH . 'themes/' . $_CFG['template'];
    $smarty->caching = false;
    $smarty->cache_dir      = staticFunction::shm_path('temp/caches');
    $smarty->compile_dir    =staticFunction::shm_path('temp/compiled');
    if ((DEBUG_MODE & 2) == 2)
    {
        $smarty->direct_output = true;
        $smarty->force_compile = true;
    }
    else
    {
        $smarty->direct_output = false;
        $smarty->force_compile = false;
    }
    function get_brand_list_forindex()
	{
	    $sql = 'SELECT brand_id, brand_name FROM ' . $GLOBALS['ecs']->table('brand') . ' ORDER BY sort_order';
	    //$res = $GLOBALS['db']->getAll($sql);
	    $res = $GLOBALS['db']->getAllCached($sql);
	    $brand_list = array();
	    foreach ($res AS $row)
	    {
	        $brand_list[$row['brand_id']] = $row['brand_name'];
	    }
	    return $brand_list;
	}
	$b_list = get_brand_list_forindex();
	$smarty->assign("b_list",$b_list);
    /**
     * 友情链接
     */
    $links = index_get_links();
	$smarty->assign('txt_links',       $links['txt']);
    $smarty->assign('lang', $_LANG);
    $smarty->assign('ecs_charset', EC_CHARSET);
    $sql = "select cat_name, cat_id from ecs_category where parent_id = 1000 order by sort_order asc";
    $subcats_girls = $GLOBALS['db']->getAll($sql);
    $smarty->assign('subcats_girls', $subcats_girls);
    $sql = "select cat_name, cat_id from ecs_category where parent_id = 1009  order by sort_order asc";
    $subcats_boys = $GLOBALS['db']->getAll($sql);
    $smarty->assign('subcats_boys', $subcats_boys);
}
if (!defined('INIT_NO_USERS'))
{
    /* 会员信息 */
    $user =& init_users();
    if (!isset($_SESSION['user_id']))
    {
        /* 获取投放站点的名称 */
        $site_name = isset($_GET['from'])   ? $_GET['from'] : addslashes($_LANG['self_site']);
        $from_ad   = !empty($_GET['ad_id']) ? intval($_GET['ad_id']) : 0;
        $_SESSION['from_ad'] = $from_ad; // 用户点击的广告ID
        $_SESSION['referer'] = stripslashes($site_name); // 用户来源
        unset($site_name);
        if (1==2&&!defined('INGORE_VISIT_STATS'))
        {
            visit_stats();
        }
    }
	if (!isset($_SESSION['user_id']))
    {
        update_user_info();
	}
    /* 设置推荐会员 */
    if (isset($_GET['u']))
    {
        set_affiliate();
    }
    if (isset($smarty))
    {
        $smarty->assign('ecs_session', $_SESSION);
    }
}
if ((DEBUG_MODE & 1) == 1)
{
    error_reporting(E_ALL);
}
else
{
    error_reporting(E_ALL ^ E_NOTICE);
}
if ((DEBUG_MODE & 4) == 4)
{
    include(ROOT_PATH . 'includes/lib.debug.php');
}
/**
 * 提供给Menu使用的js数据文件
 */
if(!file_exists(ROOT_PATH.'temp/static_caches/pinpai.js'))
	staticFunction::return_pinpai();

function js_alert($message = '', $after_action = '', $url = '')
{
    $out = "<script language=\"javascript\" type=\"text/javascript\">\n";
    if (!empty($message)) {
        $out .= "alert(\"";
        $out .= str_replace("\\\\n", "\\n", t2js(addslashes($message)));
        $out .= "\");\n";
    }
    if (!empty($after_action)) {
        $out .= $after_action . "\n";
    }
    if (!empty($url)) {
        $out .= "document.location.href=\"";
        $out .= $url;
        $out .= "\";\n";
    }
    $out .= "</script>";
    echo $out;
    exit;
}
/**
 * 将任意字符串转换为 JavaScript 字符串（不包括首尾的"）
 *
 * @param string $content
 *
 * @return string
 */
function t2js($content)
{
    return str_replace(array("\r", "\n"), array('', '\n'), addslashes($content));
}
/* 判断是否支持 Gzip 模式 */
if (!defined('INIT_NO_SMARTY') && gzip_enabled())
{
    ob_start('ob_gzhandler');
}
else
{
    ob_start();
}
	if($_REQUEST['type'] == 'main'){
		$type["css_pre"] = 	'main_';
	}
	if($_REQUEST['type'] == 'man'){
		$type["css_pre"] = 	'index_man_';
	}
	if($_REQUEST['type'] == 'women'){
		$type["css_pre"] = 	'index_nv_';
	}
	if($_REQUEST['type'] == 'other'){
		$type["css_pre"] = 	'index_other_';
	}
	if($_REQUEST['type'] == 'mobile'){
		$type["css_pre"] = 	'index_mobile_';
	}

//	if($_REQUEST['type'] == 'kid'){
//		$type["css_pre"] = 	'index_kid_';
//	}
	$css_pre = isset($type["css_pre"])? $type["css_pre"]:'main_';
	if($smarty instanceof cls_template){
		$smarty->assign("css_pre",$css_pre);
	}
?>