<?php
namespace official_account\app;
use official_account\database;

class Customer_service {
    
    public static function welcomeMsg($openid) {
        $json = self::makeTextJson(
            $openid, 
            "由于小程序加了诸多限制，比如只能发一次通知，超过7天也没法通知等等。关注这里可以不受限制的获得活动相关通知！");
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

