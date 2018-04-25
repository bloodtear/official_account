<?php
namespace official_account\database;
use framework\Database as fdb;

class Db_user extends fdb\Database_table {
    const STATUS_NORMAL = 0;
    const STATUS_DELETED = 1;

    private static $instance = null;
    public static function inst() {
        if (self::$instance == null)
            self::$instance = new Db_user();
        return self::$instance;
    }

    protected function __construct() {
        parent::__construct(MYSQL_PREFIX . "user");
    }

    public function getByOpenId($openid) {
        return $this->get_one("openid = '$openid'");
    }
    
    public function get_by_phone($phone_number) {
        return $this->get_one("phone_number = '$phone_number'");
    }
    

    public function all() {
        return $this->get_all();
    }

    public function add($openid, $unionid, $status, $nickname, $sex, $province, $city, $country, $headimgurl, $privilege) {
        return $this->insert(array("openid" => $openid, "unionid" => $unionid, "status"=> $status, "nickname" => $nickname, "sex" => $sex, "province" => $province, "city" => $city, "country" => $country, "headimgurl" => $headimgurl, "privilege" => $privilege));
    }

    public function modify($id, $openid, $unionid, $status, $nickname, $sex, $province, $city, $country, $headimgurl, $privilege) {
        return $this->update(array("openid" => $openid, "unionid" => $unionid, "status"=> $status, "nickname" => $nickname, "sex" => $sex, "province" => $province, "city" => $city, "country" => $country, "headimgurl" => $headimgurl, "privilege" => $privilege), "id = $id");
    }
	
    public function remove($id) {
        $id = (int)$id;
        return $this->update(array("status" => self::STATUS_DELETED), "id = $id");
    }


};


