<?php
namespace official_account\controller\api\v1;
use official_account\app;

class User_controller extends \official_account\controller\api\v1_base {
    public function preaction($action) {
    }

    public function go_login() {

        $redirect_uri = urlencode("https://acount.xiaoningmengkeji.com/xiaoyu/index.php?action=api.v1.user.login");
        $wx_appid = WX_APPID;
        $scope = 'snsapi_userinfo';
        
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
        
        $code = get_request('code');
        
        $access_token_ret = app\Wxapi::get_access_token($code);
        \framework\Logging::l("access_token_ret", json_encode($access_token_ret));
        if (isset($access_token_ret->errcode)) {
            //return json_encode($access_token_ret);
            $tpl = \framework\Tpl::instance('index/header', 'index/footer');
            $tpl->set("errcode", $access_token_ret->errcode);
            $tpl->set("errmsg", $access_token_ret->errmsg);
            $tpl->view('login/fail');
            return false;
        }
        
        $token = $access_token_ret->access_token;
        $openid = $access_token_ret->openid;
        
        $userinfo = app\Wxapi::get_userinfo($token, $openid);
        \framework\Logging::l("userinfo", json_encode($userinfo));
        if (isset($userinfo->errcode)) {
            $tpl = \framework\Tpl::instance('index/header', 'index/footer');
            $tpl->set("errcode", $access_token_ret->errcode);
            $tpl->set("errmsg", $access_token_ret->errmsg);
            $tpl->view('login/fail');
            return false;
        }
        
        $userinfo = json_decode(json_encode($userinfo), true);
        $openid = $userinfo['openid'];
        
        $user = app\User::getByOpenId($openid);
        if (empty($user)) {
            $user = new app\User($userinfo);
        }
        
        $user->setNickName($userinfo['nickname']);
        $user->setSex($userinfo['sex']);
        $user->setProvince($userinfo['province']);
        $user->setCity($userinfo['city']);
        $user->setCountry($userinfo['country']);
        $user->setHeadImgUrl($userinfo['headimgurl']);
        $user->setPrivilege($userinfo['privilege']);
        
        $save = $user->save();
        
        if (empty($save)) {
            $tpl = \framework\Tpl::instance('index/header', 'index/footer');
            $tpl->set("errcode", "00215");
            $tpl->set("errmsg", "保存失败");
            $tpl->view('login/fail');
            return false;
        }
        
        $tpl = \framework\Tpl::instance('index/header', 'index/footer');
        $tpl->view('login/success');
        
        return false;
        
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





