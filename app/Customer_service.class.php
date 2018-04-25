<?php
namespace official_account\app;
use official_account\database;

class Customer_service {

    public static function send_msg($type, $json) {
        \framework\Logging::l("send_msg", $type);
        \framework\Logging::l("send_msg", json_encode($json));
        switch ($type) {
            case 'text':
                $ret = Wxapi::send_text_msg($json);
            break;
            
            default:
                $ret = Wxapi::send_text_msg($json);
            break;
        }
        return $ret;
    }

    
    
}

