<?php
namespace frontend\controllers;
use yii\web\Controller;
use EasyWeChat\Foundation\Application;
//微信开发...
class WechaController extends Controller{
 public $enableCsrfValidation=false;

 //和微信服务器交互
 public function actionIndex(){


// ...

     $app = new Application(\Yii::$app->params['wechat']);

// 从项目实例中得到服务端应用实例。
     $server = $app->server;
//..
     $server->setMessageHandler(function ($message) {
         // $message->FromUserName // 用户的 openid
         // $message->MsgType // 消息类型：event, text....
         return "您好！欢迎关注我!";
     });

     $response = $server->serve();

     $response->send(); // Laravel 里请使用：return $response;


   }

}

