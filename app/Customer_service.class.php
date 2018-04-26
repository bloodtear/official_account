<?php
namespace official_account\app;
use official_account\database;

class Customer_service {
    
    public static function welcomeMsg($openid) {
        $json = self::makeTextJson(
            $openid, 
            "[欢迎消息]欢迎您的关注，关注此公众号小程序便不会有消息的限制，公众号会发送小程序相关的消息");
        return Wxapi::send_text_msg($json);
    }
    
    public static function autoReply($openid) {
        $json = self::makeTextJson(
            $openid, 
            "[自动回复]欢迎您的关注，关注此公众号小程序便不会有消息的限制，公众号会发送小程序相关的消息");
        return Wxapi::send_text_msg($json);
    }


    public static function makeTextJson($openid, $content) {
        $arr = array(
            "touser"  => $openid,
            "msgtype" => "text",
            "text" => 
            array(
                "content" => $content
            )
        );
        return json_decode(json_encode($arr));
    }
    
    
}

