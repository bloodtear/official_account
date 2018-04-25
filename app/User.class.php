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
                "nickname" => "",
                "sex" => 0,
                "province" => "",
                "city" => "",
                "country" => "",
                "headimgurl" => "",
                "privilege" => ""
            );
        }
        $this->mSummary = $summary;
    }

    public function id() {
        return $this->mSummary["id"];
    }

    public function openid() {
        return $this->mSummary["openid"];
    }

    public function unionid() {
        return $this->mSummary["unionid"];
    }

    public function status() {
        return $this->mSummary["status"];
    }

    public function nickname() {
        return $this->mSummary["nickname"];
    }

    public function sex() {
        return $this->mSummary["sex"];
    }
    
    public function province() {
        return $this->mSummary["province"];
    }
    
    public function city() {
        return $this->mSummary["city"];
    }
    
    public function country() {
        return $this->mSummary["country"];
    }
    
    public function headimgurl() {
        return $this->mSummary["headimgurl"];
    }
    
    public function privilege() {
        return $this->mSummary["privilege"];
    }
    
    public function setNickname($n) {
        $this->mSummary["nickname"] = $n;
    }
    
    public function setStatus($n) {
        $this->mSummary["status"] = $n;
    }
    
    public function setSex($n) {
        $this->mSummary["sex"] = $n;
    }
    
    public function setProvince($n) {
        $this->mSummary["province"] = $n;
    }
    
    public function setCity($n) {
        $this->mSummary["city"] = $n;
    }
    
    public function setCountry($n) {
        $this->mSummary["country"] = $n;
    }
    
    public function setHeadImgUrl($n) {
        $this->mSummary["headimgurl"] = $n;
    }
    
    public function setPrivilege($n) {
        $this->mSummary["privilege"] = $n;
    }

    public function save() {
        $id = $this->id();
        if ($id == 0) {
            $id = database\Db_user::inst()->add($this->openid(), $this->unionid(), $this->status(), $this->nickname(), $this->sex(), $this->province(), $this->city(), $this->country(), $this->headimgurl(), $this->privilege());
            if ($id !== false) {
                $this->mSummary["id"] = $id;
            }
        } else {
            $id = database\Db_user::inst()->modify($id, $this->openid(), $this->unionid(), $this->status(), $this->nickname(), $this->sex(), $this->province(), $this->city(), $this->country(), $this->headimgurl(), $this->privilege());
        }
        return $id;
    }

    public function packInfo() {

        return array(
            "id" => $this->id(),
            "status" => $this->status(), 
            "privilege" => $this->privilege(), 
            "headimgurl" => $this->headimgurl(), 
            "city" => $this->city(), 
            "province" => $this->province(), 
            "country" => $this->country(), 
            "headimgurl" => $this->headimgurl(), 
            "privilege" => $this->privilege()
        );
    }
    
    public static function getByOpenId($openid) {
        $data = database\Db_user::inst()->getByOpenId($openid);
        if (empty($data)) {
            return null;
        }
        return new User($data);
    }
    
    public static function get_by_phone($phone) {
        $data = database\Db_user::inst()->get_by_phone($phone);
        if (empty($data)) {
            return false;
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

