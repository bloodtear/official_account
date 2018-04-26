<?php
namespace official_account\controller\api\v1;
use official_account\app;

class Wx_message_controller extends \official_account\controller\api\v1_base {
    public function preaction($action) {
    }


    
    public function receive() {
        
        $check_sign = app\Wx_message::check_sign();
        \framework\Logging::l("check", json_encode($check_sign));
        if (empty($check_sign)) {
            return array('op' => 'fail', "code" => '1002002', "reason" => 'not from wx_account_server');
        }
        
        $input = simplexml_load_string(file_get_contents('php://input'), null, LIBXML_NOCDATA);
        \framework\Logging::l("input", json_encode($input));
        
        $openid = (string)($input->FromUserName);
        \framework\Logging::l("input", $openid);
        
        switch ($input->MsgType) {
            case 'event':
                if ($input->Event == 'subscribe') {
                    $user = app\User::subscribe($openid);
                    $send = app\Customer_service::welcomeMsg($openid);
                }
                if ($input->Event == 'unsubscribe') {
                    $user = app\User::unsubscribe($openid);
                }
            break;
            default:
                $send = app\Customer_service::autoReply($openid);
            break;
        }
        
        \framework\Logging::l("send", json_encode($send));
        return '';
    }
        

    


}





