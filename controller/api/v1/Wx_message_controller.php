<?php
namespace official_account\controller\api\v1;
use official_account\app;

class Wx_message_controller extends \official_account\controller\api\v1_base {
    public function preaction($action) {
    }


    
    public function receive() {
        
        $check_sign = app\Wx_message::check_sign();
        \framework\Logging::l("check", json_encode($check_sign));
        if (empty($check_sign)) {
            return array('op' => 'fail', "code" => '1002002', "reason" => 'not from wx_account_server');
        }
        
        $input = simplexml_load_string(file_get_contents('php://input'), null, LIBXML_NOCDATA);
        \framework\Logging::l("input", json_encode($input));
        
        $openid = (string)($input->FromUserName);
        $developer = (string)($input->ToUserName);
        $time = time();
        \framework\Logging::l("input", $openid);
        switch ($input->MsgType) {
            case 'event':
                if ($input->Event == 'subscribe') {
                    $ret = app\User::subscribe($openid);
                    return "<xml> <ToUserName>< ![CDATA[$openid] ]></ToUserName> <FromUserName>< ![CDATA[$developer] ]></FromUserName> <CreateTime> $time </CreateTime> <MsgType>< ![CDATA[text] ]></MsgType> <Content>< ![CDATA[你好] ]></Content> </xml>";
                }
            break;
            
            case 'text':
               return "<xml> <ToUserName>< ![CDATA[$openid] ]></ToUserName> <FromUserName>< ![CDATA[$developer] ]></FromUserName> <CreateTime> $time </CreateTime> <MsgType>< ![CDATA[text] ]></MsgType> <Content>< ![CDATA[你好] ]></Content> </xml>";
            break;
            
            default:
                return "<xml> <ToUserName>< ![CDATA[$openid] ]></ToUserName> <FromUserName>< ![CDATA[$developer] ]></FromUserName> <CreateTime> $time </CreateTime> <MsgType>< ![CDATA[text] ]></MsgType> <Content>< ![CDATA[你好] ]></Content> </xml>";
            break;
        }
        
        return '';
    }
        

    


}





