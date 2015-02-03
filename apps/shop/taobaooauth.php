<?php
namespace shop;
if (!defined('START')) exit('No direct script access allowed');
use model as md;
/*
--------------------------------
	淘宝 OAuth2.0 SDK
	张国夫
	2014.1.22  c197cb955f544657ccce6bc94a6c9320
--------------------------------
*/
class taobaooauth extends Front{
	public $appid 	= '21768539';//21743117  21768539     21741676
	public $appkey 	= 'c734a4a9ff49e349e7603b2d7972b59f';//a64f17f2712a8f1a31c421dbbb2c2f2a  c734a4a9ff49e349e7603b2d7972b59f     c197cb955f544657ccce6bc94a6c9320
	public $category_id = '12186134997479847';
	public $access_token = '';
	
	public $authorizeURL 	= 'https://oauth.taobao.com/authorize';
	public $accessTokenUrl	= 'https://oauth.taobao.com/token';
	public $apiUrlHttp		= 'http://gw.api.taobao.com/router/rest';
	
	/*function __construct(){
		echo '@@';
	}	*/
	//
	function get_access_token_info($taobao_user_id = 60452186){
		$tb_oauth = new md\tb_oauth();
		$data = $tb_oauth -> search($taobao_user_id );
		//var_dump($data);
		$row=$data['items'][0];
		if ($row){
			return $row;
		} else {
			return false;
		}
	}
	
	//获取授权access_token 60452186 为淘宝帐号curlbe的ID
	function get_access_token($taobao_user_id = 60452186){
		//echo 11;
		$row = $this -> get_access_token_info($taobao_user_id);
		if ($row){
			//判断是否过期
			$seconds = $row['timeline'] + $row['expires_in'] - time();
			if ($seconds > 0){
				$this -> access_token = $row['access_token'];
				return $row;
			} 
		} 
		return false;
	}
	/*
	----------------------------------------
		获取授权URL
		$redirect_uri 	授权成功后的回调地址
		$response_type	授权类型,默认为code
	----------------------------------------
	*/
	function getAuthorizeURL($redirect_uri, $response_type = 'code'){
		$params = array(
            'client_id' 	=> $this -> appid,
            'redirect_uri' 	=> $redirect_uri,
            'response_type' => $response_type,
            'scope' 		=> $this -> scope
        );
		$url = $this -> authorizeURL.'?'.http_build_query($params);
		return $url;
	}
	
	/*
	---------------------------------------
		获取请求授权令牌的地址
		$code			调用authorize返回的code值
		$redirect_uri	回调地址
	---------------------------------------
	*/
	function getAccessToken($code, $redirect_uri){
		$params = array(
            'client_id' 	=> $this -> appid,
            'client_secret' => $this -> appkey,
            'grant_type' 	=> 'authorization_code',
            'code' 			=> $code,
            'redirect_uri' 	=> $redirect_uri
        );
		$rt = $this -> request($this -> accessTokenUrl, $params, 'POST');
        $rt = iconv("utf-8", "utf-8//ignore", $rt); 		//UTF-8转码
		
		return json_decode($rt, true);
	}
	
	/*
	---------------------------------------
		刷新授权令牌的地址
		$refresh_token
		$redirect_uri	回调地址
	---------------------------------------
	*/
	function refreshAccessToken($refresh_token){
		$params = array(
            'client_id' 	=> $this -> appid,
            'client_secret' => $this -> appkey,
            'grant_type' 	=> 'refresh_token',
            'refresh_token' => $refresh_token
        );
		$rt = $this -> request($this -> accessTokenUrl, $params, 'POST');
        $rt = iconv("utf-8", "utf-8//ignore", $rt); 		//UTF-8转码
		
		return json_decode($rt, true);
	}
	
	
	/*
	-------------------------------------
		发送请求
		$command		请求接口
		$params			请求参数
		$method			请求方式
		$multi			图片信息
	-------------------------------------
	*/
	function api($params = array(), $method = 'GET', $multi = false){
		//鉴权公共参数
		$params['app_key'] 		= $this -> appid;
		$params['format']		= 'json';
		$params['v']			= '2.0';
		$params['sign_method']	= 'md5';
		$params['parent_id']	= 'top-apitools';
		$params['timestamp']	= date('Y-m-d H:i:s');
		
		$params['sign']			= $this -> generateSign($params);
		
		$url = $this -> apiUrlHttp;
		
		$rt = $this -> request($url, $params, $method, $multi);
        $rt = iconv("utf-8", "utf-8//ignore", $rt); 		//UTF-8转码
		
		return json_decode($rt, true);
	}
	
	
	/*
	-------------------------------------
		 HTTP/HTTPS请求操作 ---- CURL(需要开启)
		 $url			接口地址
		 $param			请求参数
		 $method		请求方式
		 $multi			图片信息
		 $extheaders	扩展的head头
	-------------------------------------
	*/
	function request($url, $params = array(), $method = 'GET', $multi = false, $extheaders = array()){
		$method = strtoupper($method);
		$ci = curl_init();
        curl_setopt($ci, CURLOPT_USERAGENT, 'PHP-SDK OAuth2.0');
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ci, CURLOPT_TIMEOUT, 10);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ci, CURLOPT_HEADER, false);
        $headers = (array)$extheaders;
		switch ($method){
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				if (!empty($params)) {
                    if($multi) {
                        foreach($multi as $key => $file) {
                            $params[$key] = '@' . $file;
                        }
                        curl_setopt($ci, CURLOPT_POSTFIELDS, $params);
                        $headers[] = 'Expect: ';
                    } else {
                        curl_setopt($ci, CURLOPT_POSTFIELDS, http_build_query($params));
                    }
                }
				break;
			case 'GET':
				$method == 'DELETE' && curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($params)){
                    $url = $url . (strpos($url, '?') ? '&' : '?')
                        		. (is_array($params) ? http_build_query($params) : $params);
                }
				break;
		}
		curl_setopt($ci, CURLINFO_HEADER_OUT, true);
        curl_setopt($ci, CURLOPT_URL, $url);
        if ($headers) {
            curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
        }
		$response = curl_exec($ci);
        curl_close($ci);
        return $response;
	}
	
	
	//上传图片到空间
	function upload($pic){
		global $_save_dir;
		
		$savedir = $_save_dir .'/details/';
		$title = str_replace($savedir, '', $pic);
		$title = str_replace('/', '-', $title);
 		
		$params = array(
            'method'	=> 'taobao.picture.upload',
            'session' 	=> $this -> access_token,
			'picture_category_id' => $this -> category_id,
			'image_input_title' => $title,
			'img' => '@'.$pic
        );
		
		
		$resp = $this -> api($params, 'POST', true);	
		return $resp;
	}
	
	//签名
	function generateSign($params) {
		ksort($params);

		$stringToBeSigned = $this->appkey;
		foreach ($params as $k => $v) {
			if("@" != substr($v, 0, 1)){
				$stringToBeSigned .= "$k$v";
			}
		}
		unset($k, $v);
		$stringToBeSigned .= $this->appkey;

		return strtoupper(md5($stringToBeSigned));
	}

	
	//更新access_toekn
	function update_access_token($data){
		$db = getdb();
		
		$db -> update('ecs_taobao_sync', array(
			'access_token' => $data['access_token'],
			'expires_in' => $data['expires_in'],
			'refresh_token' => $data['refresh_token'],
			'timeline' => time()
		), array(
			'taobao_user_id' => $data['taobao_user_id']
		));
	}
	
	//新建access_toekn
	function add_access_token($data){
		$db = getdb();
		
		$db -> insert('ecs_taobao_sync', array(
			'taobao_user_id' => $data['taobao_user_id'],
			'access_token' => $data['access_token'],
			'expires_in' => $data['expires_in'],
			'refresh_token' => $data['refresh_token'],
			'timeline' => time()
		));
	}
}
?>
