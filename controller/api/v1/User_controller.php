<?php
namespace official_account\controller\api\v1;
use official_account\app;

class User_controller extends \official_account\controller\api\v1_base {
    public function preaction($action) {
    }

    public function login() {
        $request = $_REQUEST;
        \framework\Logging::l("request", json_encode($request));
    }
    
    //刷新session
    public function refresh_session() { 
       
    }


    public function register() {
    }


    public static function pretreat() {
        
       
        
    }
    
        
    public static function posttreat() {

       
        
    }
    
    


}





