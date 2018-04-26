<?php
namespace official_account\app;
use official_account\database;

class User {
    private $mSummary = null;

    public function __construct($summary = array()) {
        if (empty($summary)) {
            $summary = array(
                "id" => 0,
                "openid" => '',
                "unionid" => "",
                "status" => 0,
                "last_login" => 0,
                "nickname" => "",
                "sex" => 0,
                "province" => "",
                "city" => "",
                "language" => "", 
                "country" => "",
                "headimgurl" => "",
                "subscribe" => 0,
                "subscribe_time" => 0,
                "remark" => "",
                "groupid" => 0,
                "tagid_list" => "",
                "subscribe_scene" => "",
                "qr_scene" => "",
                "qr_scene_str" => ""
            );
        }
        if (!isset($summary['id'])) {
            $summary['id'] = 0;
        }
        if (!isset($summary['status'])) {
            $summary['status'] = 0;
        }
        if (!isset($summary['last_login'])) {
            $summary['last_login'] = time();
        }
        $this->mSummary = $summary;
    }
    
    
    public function getAttr($name){
        return $this->mSummary[$name] ?? null;
    }
    
    public function setAttr($name, $value){
        array_key_exists($name, $this->mSummary) ? $this->mSummary[$name] = $value : false;
    }

    public function save() {
        $id = $this->getAttr('id');
        $attrList = $this->mSummary;
        \framework\Logging::d("attrList", json_encode($attrList));
        unset($attrList['id']);
        if ($id == 0) {
            $id = database\Db_user::inst()->add($attrList);
            if ($id !== false) {
                $this->mSummary["id"] = $id;
            }
        } else {
            $id = database\Db_user::inst()->modify($id, $attrList);
        }
        return $id;
    }

    public function packInfo() {
        $black_list = array("unionid", "openid");
        $packInfo = $this->mSummary;
        
        foreach ($black_list as $black) {
            //array_key_exists($black, $packInfo) ? unset($packInfo[$black]) : '';
        }
        
        return $packInfo;
    }
    
    public static function subscribe($openid) {
        
        $token = Wxapi::check_token();
        if (empty($token)) {
            return false;
        }
        
        $userinfo = Wxapi::get_userinfo($token, $openid);
        \framework\Logging::l("userinfo", json_encode($userinfo));
        if (isset($userinfo->errcode)) {
            return false;
        }
        
        $userinfo = json_decode(json_encode($userinfo), true);
        
        $user = User::getByOpenId($openid);
        if (empty($user)) {
            $user = new User($userinfo);
        }
        
        $user->setAttr('openid', $userinfo['openid']);
        $user->setAttr('unionid', $userinfo['unionid']);
        $user->setAttr('status', 0);
        $user->setAttr('last_login', time());
        $user->setAttr('language', $userinfo['language']);
        $user->setAttr('nickname', $userinfo['nickname']);
        $user->setAttr('sex', $userinfo['sex']);
        $user->setAttr('province', $userinfo['province']);
        $user->setAttr('city', $userinfo['city']);
        $user->setAttr('country', $userinfo['country']);
        $user->setAttr('headimgurl', $userinfo['headimgurl']);
        $user->setAttr('subscribe', $userinfo['subscribe']);
        $user->setAttr('subscribe_time', $userinfo['subscribe_time']);
        $user->setAttr('remark', $userinfo['remark']);
        $user->setAttr('groupid', $userinfo['groupid']);
        $user->setAttr('tagid_list', $userinfo['tagid_list']);
        $user->setAttr('subscribe_scene', $userinfo['subscribe_scene']);
        $user->setAttr('qr_scene', $userinfo['qr_scene']);
        $user->setAttr('qr_scene_str', $userinfo['qr_scene_str']);
        
        $save = $user->save();
        return $save ? $user : false;
        
    }
    
    public static function getByOpenId($openid) {
        $data = database\Db_user::inst()->getByOpenId($openid);
        if (empty($data)) {
            return null;
        }
        return new User($data);
    }

    
    public function check_verify($verify_code) {
        \framework\Logging::l('expired',$this->expired());
        \framework\Logging::l('time',time());
        \framework\Logging::l('thisverify_code',$this->verify_code());
        \framework\Logging::l('verify_code', $verify_code);
        return ($this->verify_code() == $verify_code && (time() < $this->expired()) )? true : false;
    }

};

