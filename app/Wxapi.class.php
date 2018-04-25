<?php
namespace official_account\app;
use official_account\database;

// 小程序和公众号的Oauth流程类似，但是参数不一样
class Wxapi {
    
    public static function get_userinfo($token, $openid){
        $url = 'https://api.weixin.qq.com/sns/userinfo';
        $postString = array(
            "access_token" => $token,
            "openid" => $openid,
            "lang" => "zh_CN");
        $wx_auth_ret = comm_curl_request($url, $postString);
        return json_decode($wx_auth_ret);
    }

    public static function get_access_token($code) {    
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
        $postString = array(
            "grant_type" => "authorization_code",
            "appid" => WX_APPID,
            "code" => $code,
            "secret" => WX_SECRET);
        $wx_auth_ret = json_decode(comm_curl_request($url, $postString));
        return $wx_auth_ret;
        if (empty($wx_auth_ret->error)) {
            $_SESSION["WX_ACCESS_TOKEN"] = $wx_auth_ret->access_token;
            $_SESSION["WX_ACCESS_TOKEN_EXPIRES_IN"] = $wx_auth_ret->expires_in + time();
            return true;
        }else {
            return false;
        }
    }
    
    public static function check_access_token() {
        $wx_acess_token = isset($_SESSION['WX_ACCESS_TOKEN']) ? $_SESSION['WX_ACCESS_TOKEN'] : null;
        $wx_acess_token_expires_in = isset($_SESSION['WX_ACCESS_TOKEN_EXPIRES_IN']) ? $_SESSION['WX_ACCESS_TOKEN_EXPIRES_IN'] : null;
        if (empty($wx_acess_token) || empty($wx_acess_token_expires_in) || time() > $wx_acess_token_expires_in) {
            Wxapi::get_access_token();
        }
    }
    
        
}







function comm_curl_request($url,$postString='',$httpHeader='')  { 
    $ch = curl_init();  
    curl_setopt($ch,CURLOPT_URL,$url);  
    curl_setopt($ch,CURLOPT_POSTFIELDS,$postString);  
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);  
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //这个是重点。不加这curl报错
    curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);  

    if(!empty($httpHeader) && is_array($httpHeader))  
    {  
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);  
    }  
    $data = curl_exec($ch);  
    $info = curl_getinfo($ch);  
    //var_dump(curl_error($ch)); 
    //var_dump($info);  
    curl_close($ch);  
    return $data;  
}  




?>