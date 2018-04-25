<?php
namespace official_account\app;
use official_account\database;

class Customer_service {

    public static function send_msg($type, $array) {
        \framework\Logging::l("send_msg", $type);
        \framework\Logging::l("send_msg", json_encode($array));
        switch ($type) {
            case 'text':
                $ret = Wxapi::send_text_msg($array);
            break;
            
            default:
                $ret = Wxapi::send_text_msg($array);
            break;
        }
        return $ret;
    }

    
    
}

