<?php
namespace official_account\app;
use official_account\database;

class Wx_message {
    private $mSummary = null;

    public function __construct($summary = array()) {
        if (empty($summary)) {
            $summary = array(
                "id" => 0,
            );
        }
        $this->mSummary = $summary;
    }

    public function id() {
        return $this->mSummary["id"];
    }

    public function name() {
        return $this->mSummary["name"];
    }

    public function setName($n) {
        $this->mSummary["name"] = $n;
    }


    public function save() {
        // $id = $this->id();
        // if ($id == 0) {
        //     $id = db_template::inst()->add();
        //     if ($id !== false) {
        //         $this->mSummary["id"] = $id;
        //     }
        // } else {
        //     $id = db_template::inst()->modify($id);
        // }
        // return $id;
    }

    public function packInfo() {
       return array(
            "id" => $this->id(),
            "name" => $this->name(), 
        );
    }
    
    
    public static function check_sign() {
        
        $signature  = get_request("signature");
        $timestamp  = get_request("timestamp");
        $nonce      = get_request("nonce");
        $token      = WX_ACCOUNT_SERVER_TOKEN;
        
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        return $tmpStr == $signature;
    }



};

