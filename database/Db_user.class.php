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

    public function add($attrList) {
        return $this->insert($attrList);
    }

    public function modify($id, $attrList) {
        return $this->update($attrList, "id = $id");
    }
	
    public function remove($id) {
        $id = (int)$id;
        return $this->update(array("status" => self::STATUS_DELETED), "id = $id");
    }


};


