<?php
namespace official_account\app;
use official_account\database;

class Session {
    private $mSummary = null;

    public function __construct($summary = array()) {
        if (empty($summary)) {
            $summary = array(
                "id" => 0,
                "calendar_session" => '',
                "tempid" => 0,
                "expired" => 0,
                "last_login" => 0,
            );
        }
        $this->mSummary = $summary;
    }

    public function id() {
        return $this->mSummary["id"];
    }

    public function calendar_session() {
        return $this->mSummary["calendar_session"];
    }

    public function tempid() {
        return $this->mSummary["tempid"];
    }

    public function expired() {
        return $this->mSummary["expired"];
    }
    
    public function type() {
        return $this->mSummary["type"];
    }

    public function last_login() {
        return $this->mSummary["last_login"];
    }
    
    public function set_calendar_session($n) {
        $this->mSummary["calendar_session"] = $n;
    }
    
    public function set_tempid($n) {
        $this->mSummary["tempid"] = $n;
    }
    
    public function set_type($n) {
        $this->mSummary["type"] = $n;
    }
    
    public function set_expired($n) {
        $this->mSummary["expired"] = $n;
    }
    
    public function set_last_login($n) {
        $this->mSummary["last_login"] = $n;
    }

    public function save() {
        $id = $this->id();
        if ($id == 0) {
            $id = database\Db_session::inst()->add($this->calendar_session(), $this->tempid(), $this->expired(), $this->last_login(), $this->type());
            if ($id !== false) {
                $this->mSummary["id"] = $id;
            }
        } else {
            $id = database\Db_session::inst()->modify($this->id(), $this->calendar_session(), $this->tempid(), $this->expired(), $this->last_login(), $this->type());
        }
        return $id;
    }

    public function packInfo() {
       return array(
            "id" => $this->id(),
            "calendar_session" => $this->calendar_session(), 
            //"tempid" => $this->tempid(), 
            "expired" => $this->expired(), 
            "type" => $this->type(), 
            "last_login" => $this->last_login()
        );
    }

    public static function get_by_session($calendar_session) {
        $summary = database\Db_session::inst()->get_by_session($calendar_session);
        if (!empty($summary)) {
            return new Session($summary);
        } 
        return null;
    }
    
    public static function get_exist_by_session($tempid) {
        $summary = database\Db_session::inst()->get_exist_by_session($tempid);
        if (!empty($summary)) {
            return new Session($summary);
        } 
        return null;
    }



    public static function remove($calendar) {
        return database\Db_session::inst()->remove($calendar);
    }
};

