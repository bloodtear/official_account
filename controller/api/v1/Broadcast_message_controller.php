<?php
namespace official_account\controller\api\v1;
use official_account\app;

class Broadcast_message_controller extends \official_account\controller\api\v1_base {
    public function preaction($action) {
        
    }

    public function broadcast_message() {
        
        // 数据库提取出明天有活动的人和活动数量
        // 轮询发送模板信息
        
        $broadcast_data = app\Message::get_broadcast_data();
        
        \framework\Logging::d("data", json_encode($broadcast_data));
        
        foreach ($broadcast_data as $message) {
            
        }
        
        
        return false;

    }

    
        
    public static function posttreat() {

        unset_session('userid');
        unset_session('username');
        unset_session('calendar_session');
        
    }
    
    


}





