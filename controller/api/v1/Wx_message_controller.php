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
        
        $input = file_get_contents('php://input');
        \framework\Logging::l("input", json_encode($input));
        
        return '';
    }
        

    


}





