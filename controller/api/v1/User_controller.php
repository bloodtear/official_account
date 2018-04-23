<?php
namespace official_account\controller\api\v1;
use official_account\app;

class User_controller extends \official_account\controller\api\v1_base {
    public function preaction($action) {
    }

    public function go_login() {

        $redirect_uri = urlencode("https://acount.xiaoningmengkeji.com/xiaoyu/index.php?action=api.v1.user.login");
        $wx_appid = WX_APPID;
        $scope = 'snsapi_base';
        
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize";
        $url .= "?appid=$wx_appid&";
        $url .= "&redirect_uri=$redirect_uri";
        $url .= "&response_type=code";
        $url .= "&scope=$scope";
        $url .= "&state=STATE";
        $url .= "#wechat_redirect";
        
        \framework\Logging::l("go_login", ($url));
        header("Location: " . $url);
    }
    
    public function login() { 
        $request = $_REQUEST;
        \framework\Logging::l("login", json_encode($request));
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





