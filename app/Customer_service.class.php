<?php
namespace official_account\app;
use official_account\database;

class Customer_service {

    public static function send_msg($type, $array) {
        switch ($type) {
            case 'text':
                $ret = Wxapi::send_text_msg($array);
            break;
            
            default:
            
            break;
        }
        return $ret;
    }

    
    
}

