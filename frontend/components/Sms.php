<?php
namespace frontend\components;

use yii\base\Component;
use frontend\models\SignatureHelper;

class Sms extends Component{
   public $ak;
   public $sk;
   public $sign;
   public $template;

public function send($phone,$param){
    $params=[];
    $params['PhoneNumbers']= $phone;
    $params['TemplateParam']=$param;
    $params['SignName']= $this->sign;
    $params['TemplateCode']=$this->template;
    if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
        $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
    }
    $helper = new SignatureHelper();

    // 此处可能会抛出异常，注意catch
    $content = $helper->request(
        $this->ak,
        $this->sk,
        //$accessKeySecret,
        "dysmsapi.aliyuncs.com",
        array_merge($params, array(
            "RegionId" => "cn-hangzhou",
            "Action" => "SendSms",
            "Version" => "2017-05-25",
        ))
    );
    return $content;
}


}

