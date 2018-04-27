<?php
namespace official_account\controller\api\v1;
use official_account\app;

class Broadcast_message_controller extends \official_account\controller\api\v1_base {
    public function preaction($action) {
        
    }

    public function broadcast_message() {
        
        // 数据库提取出明天有活动的人和活动数量
        // 轮询发送模板信息
        
        $broadcast_data = app\Broadcast_message::get_broadcast_data();
        
        \framework\Logging::d("data", json_encode($broadcast_data));
        
        foreach ($broadcast_data as $user) {
            $nickname           = $user['nickname'];
            $openid             = $user['openid'];
            $owner_count        = (int)$user['owner_count'];
            $sub_count          = (int)$user['sub_count'];
            $sign_count         = (int)$user['sign_count'];
            $sub_type_act_count = (int)$user['sub_type_act_count'];
            
            $all_count = $owner_count + $sub_count + $sign_count + $sub_type_act_count;
            
            if ($all_count == 0) {
                \framework\Logging::d("no_message_tomorrow", "$nickname all_count is $all_count");
                continue;
            }
            
            $template_id = '';
            $appid = '';
            $url = '';
            $pagepath = '';
            $content = '';
            $color = '';
            
            $json = self::makeBroadCastJson($openid, $template_id, $url, $appid, $pagepath, $content, $color);
            \framework\Logging::d("json", json_encode($json));
            $send = app\Wxapi::send_template_msg($json);
            
            \framework\Logging::d("send", json_encode($send));
        }
        
        
        return false;

    }
    
    
    
    public static function makeBroadCastJson($openid, $template_id, $url, $appid, $pagepath, $content, $color) {
        $arr = array(
            "touser"  => $openid,
            "template_id" => $template_id,
            "url" => $url,
            "miniprogram" => array(
                                "appid" => $appid,
                                "pagepath" => $pagepath
                            ),
            "data" => array(
                        "first" => array(
                            "value" => $content,
                            "color" => $color
                        ),
                        "keyword1" => array(
                            "value" => $content,
                            "color" => $color
                        ),
                        "keyword2" => array(
                            "value" => $content,
                            "color" => $color
                        ),
                        "keyword3" => array(
                            "value" => $content,
                            "color" => $color
                        ),
                        "remark" => array(
                            "value" => $content,
                            "color" => $color
                        )
                    )
        );
        return json_decode(json_encode($arr));
    }

    
        
    public static function posttreat() {

        unset_session('userid');
        unset_session('username');
        unset_session('calendar_session');
        
    }
    
    


}





