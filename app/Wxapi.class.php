<?php
namespace official_account\app;
use official_account\database;

// 小程序和公众号的Oauth流程类似，但是参数不一样
class Wxapi {
    
    
    public static function check_token() {
        $token = Setting::get_by_name("token");
        if (empty($token) || $token['expired'] < $time()) {
            $access_token_ret = Wxapi::get_access_token();
            \framework\Logging::l("access_token_ret", json_encode($access_token_ret));
            if (isset($access_token_ret->errcode)) {
                return false;
            }
            
            $token = $access_token_ret->access_token;
            $expired = $access_token_ret->expires_in + time();
            
            $setting = new Setting();
            $setting->setName('token');
            $setting->setValue($token);
            $setting->setExpired($expired);
            $setting->setStatus(0);
            $save = $setting->save();
            
            if (empty($save)) {
                return false;
            }
        }
        return $token;
    }    
    
    public static function check_token() {
        $token = Setting::get_by_name("token");
        if (empty($token) || $token['expired'] < $time()) {
            
        }
    }
    
    public static function get_userinfo($token, $openid){
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?';
        $postString = array(
            "access_token" => $token,
            "openid" => $openid,
            "lang" => "zh_CN");
        $wx_auth_ret = comm_curl_request($url, $postString);
        return json_decode($wx_auth_ret);
    }

    public static function get_3rd_userinfo($token, $openid){
        $url = 'https://api.weixin.qq.com/sns/userinfo';
        $postString = array(
            "access_token" => $token,
            "openid" => $openid,
            "lang" => "zh_CN");
        $wx_auth_ret = comm_curl_request($url, $postString);
        return json_decode($wx_auth_ret);
    }

    public static function get_access_token() {    
        $url = 'https://api.weixin.qq.com/cgi-bin/token?';
        $postString = array(
            "grant_type" => "client_credential",
            "appid" => WX_APPID,
            "secret" => WX_SECRET);
        $wx_auth_ret = json_decode(comm_curl_request($url, $postString));
        return $wx_auth_ret;
    }
    
    public static function get_3rd_access_token($code) {    
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
        $postString = array(
            "grant_type" => "authorization_code",
            "appid" => WX_APPID,
            "code" => $code,
            "secret" => WX_SECRET);
        $wx_auth_ret = json_decode(comm_curl_request($url, $postString));
        return $wx_auth_ret;
    }
    
    public static function check_access_token() {
        $wx_acess_token = isset($_SESSION['WX_ACCESS_TOKEN']) ? $_SESSION['WX_ACCESS_TOKEN'] : null;
        $wx_acess_token_expires_in = isset($_SESSION['WX_ACCESS_TOKEN_EXPIRES_IN']) ? $_SESSION['WX_ACCESS_TOKEN_EXPIRES_IN'] : null;
        if (empty($wx_acess_token) || empty($wx_acess_token_expires_in) || time() > $wx_acess_token_expires_in) {
            Wxapi::get_access_token();
        }
    }
    
            
    public static function send_text_msg($array) {

        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?';
        $postString = $array;
        $wx_auth_ret = json_decode(comm_curl_request($url, json_encode($postString)));
        return $wx_auth_ret;
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