<?php
namespace official_account\controller\api\v1;
use official_account\app;

class Wx_message_controller extends \official_account\controller\api\v1_base {
    public function preaction($action) {
    }


    
    public function receive() {
        $request = $_REQUEST;
        \framework\Logging::l("request", json_encode($request));
        
        $input = file_get_contents('php://input');
        \framework\Logging::l("input", json_encode($input));
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce =  $_GET["nonce"];
        $echostr =  $_GET["echostr"];
        $token = 'qjgj1b9xcyg5gr';

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $signature = $tmpStr ){
            echo $echostr;
        }else{
            return false;
        }
        
    }
        

    


}





