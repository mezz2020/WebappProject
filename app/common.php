<?php
// 应用公共文件

/**
 * Ajax统一返回JSON数据格式
 */
function ajaxReturn($data = null, $status = 0, $message = "Success", $extra = [], $headers = [])
{
    $return_data    =   [
        "code"      =>  $status,
        "result"    =>  $data,
        "message"   =>  $message,
        "extra"     =>  $extra
    ];

    return json($return_data)->header($headers);
}

 /**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    
    // 增加是否全部截取判断,全部截取不追加"..." --add by Solo
    $str_len		=	mb_strlen($str) - $start;
    $ellipsis_slice	=	$str_len > $length	?	$slice . '...'	:	$slice;
    
    return $suffix ? $ellipsis_slice : $slice;
}

/**
 * 生成动态加密密码
 * @param string $md5_passwd
 * @param string $rand_token
 * @return array("strp_token","password")
 */
function genPasswdToken(string $md5_passwd, string $rand_token = '')
{
        if (empty($rand_token))
            $rand_token =   str_pad(mt_rand(100, 999999), 6, '0', STR_PAD_LEFT);

        $passwd_token   =   config("app.token_passwd");
        $gen_passwd     =   md5(md5($md5_passwd . $rand_token) . $passwd_token);

        return ['strp_token' => $rand_token, 'password' => $gen_passwd];
}

function genSessToken(int $user_id)
{
    $token      =   "mfdaifew02lk";
    $sess_token =   md5($token . $user_id . time());
    
    return  $sess_token;
}

/**
 * 邮箱/手机字符串隐藏
 * @param string $hide_str
 * @return Ambigous <string, mixed>
 */
function hideStar($hide_str = "") {
    if ($hide_str == "")
        return "";

	if (strpos($hide_str, '@')) {
        $rs = substr_replace($hide_str,'****',1,strrpos($hide_str,"@")-3);
    } else {
        $mlen =   strlen($hide_str);
        
        if ($mlen > 5 && $mlen < 9)
            $rs	= substr_replace($hide_str, '***', 2, 3);
        elseif ($mlen >= 9)
            $rs = substr_replace($hide_str, '****', 3, 4);
        else
            $rs = "*****";
    }
        
    return $rs;
}

function httpGet($url, $params){
	if (!empty($params))
		$url = "{$url}?" . http_build_query($params);

	$ch = curl_init ();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	$result = curl_exec($ch);
	curl_close($ch);

	return $result;
}

function httpPost($url, $params = null, $headers = null){
	if (empty($headers))
		$headers	=	["Content-type:application/json;","Accept:application/json"];

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	$result = curl_exec($ch);
	curl_close ($ch);

	return $result;
}

/**
 * 查询IP地区信息
 */
function ipToArea($ip = '')
{
	$area_name	=	'未知地点';
	
	$api_url	=	"http://sp0.baidu.com/8aQDcjqpAAV3otqbppnN2DJv/api.php";
	$params		=	[
		"query"	=>	$ip,
		"co"	=>	'',
		"resource_id"	=>	6006
	];
	$result	=	httpGet($api_url, $params);

	if (!empty($result)) {
		$result	=	mb_convert_encoding($result,'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
		
		$area_data	=	json_decode($result, true);

		if (isset($area_data['status']) && $area_data['status'] == 0) {
			if (isset($area_data['data']) && !empty($area_data['data']))
				$area_name	=	$area_data['data'][0]['location'];
		}
	}

	return $area_name;
}

/**
 * 打印输出调试信息
 */
function mbug($data = null){
	header("Content-type: text/html; charset=utf-8");
	
	echo "<pre/>";
	print_r($data);
	exit;
}