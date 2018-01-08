<?php

namespace frontend\controllers;
use Aliyun\DySDKLite\SignatureHelper;
use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\web\Controller;
use yii\web\Request;

class MemberController extends Controller{
    public $enableCsrfValidation=false;

    public function actionIndex(){


  }
  public function actionAdd(){

     $request = new Request();
     // $post =$request->post();
     // var_dump($post);
     $model = new Member();
     if($request->isPost){

     $model->load($request->post(),'');
     $created_at= time();
     $model->created_at= $created_at;
     if($model->validate()){

     $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
     //var_dump($user);
     $model->save();
      }
     //添加成功则给出提示信息
     //\Yii::$app->session->setFlash('success','注册成功');
     return $this->redirect(['site/index']);
     }

    return $this->render('regist');
  }
  public function actionLogin(){
     //登陆表单
     $model = new LoginForm();
     // var_dump($model);
     $request = new Request();
     if($request->isPost){
     $model->load($request->post(),'');
     if($model->login()){
     //查询cookie
     $cookies = \Yii::$app->request->cookies;
     //没登录情况存cookie
     if($cookies->has('cart')){
     $value = $cookies->getValue('cart');
     //反序列化
     $cart = unserialize($value);
      }else{
        //不存在的情况下就定义成空数组
         $cart = [];
         }
      //var_dump($cart);exit;
      //var_dump($cart);exit;
      //遍历以k value 形式
      foreach ($cart as $k=>$va){
      //查询的时候赋值 goodid =商品中的id  memeberId 是==会员登录ID
      $info = Cart::findOne(['goods_id'=>$k,'member_id'=>\Yii::$app->user->getId()]);
      if($info){
      //如果存在的话数量 += value值
      $num = $info->amount += $va;
      //并且修改数据库中的值 修改amount 条件  goods_id 赋值等于 遍历中的值$k
      Cart::updateAll(['amount'=>$num],['goods_id'=>$k,'member_id'=>\Yii::$app->user->getId()]);
      }else{
      //如果没有的话 就新增一条商品数据
      $models = new Cart();
      $models->goods_id = $k ;
      // var_dump($models);exit;
      $models->amount = $va;
       // var_dump($models);exit;
      $models->member_id =\Yii::$app->user->getId();
      $models->save();
       }

       }
      return $this->redirect(['center']);

      }

      }
      return $this->render('login');
    }
    //是否登录验证
  public function actionCenter(){
     //如果没有登录则提示未登录返回到登录页面
     if(\Yii::$app->user->isGuest){
         echo "未成功";

     }else{
         //登录成功的话
         return $this->redirect(['site/index']);
     }

    }
    //注销
  public function actionLogout(){
       \Yii::$app->user->logout();

       return $this->redirect(['login']);
    }
    //电话号码验证存入redis
  public function actionSms($phone){
     //正则表达式 电话号码验证

      $code=rand(1000,9999);
      $result=\Yii::$app->sms->send($phone,['code'=>$code]);
      //var_dump($result);
      if($result->Code=='OK'){
      //保存到redis
      $redis = new \Redis();
      $redis->connect('127.0.0.1');
      //把电话号码和验证信息关联起来
      $redis->set('code_'.$phone,$code,30*60);
      //短信发送成功;
      return true;
      }else{
      //发送失败
      echo '短信发送失败了';
      }
//
//        $params = array ();
//
//        // *** 需用户填写部分 ***
//
//        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
//        $accessKeyId = "LTAIr9ClLepnffaw";
//        $accessKeySecret = "YUeF0YUxqMlGh6Dhf5w6zsbmrMipxQ";
//
//        // fixme 必填: 短信接收号码
//        $params["PhoneNumbers"] = "18380200885";
//
//        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
//        $params["SignName"] = "叶哥书店";
//
//        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
//        $params["TemplateCode"] = "SMS_120130244";
//
//        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
//        $params['TemplateParam'] = Array (
//            "code" => rand(1000,9000),
////            "product" => "阿里通信"
//        );
//
//        // fixme 可选: 设置发送短信流水号
//        $params['OutId'] = "12345";
//
//        // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
//        $params['SmsUpExtendCode'] = "1234567";
//
//
//        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
//        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
//            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
//        }
//
//        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
//        $helper = new \frontend\models\SignatureHelper();
//
//        // 此处可能会抛出异常，注意catch
//        $content = $helper->request(
//            $accessKeyId,
//            $accessKeySecret,
//            "dysmsapi.aliyuncs.com",
//            array_merge($params, array(
//                "RegionId" => "cn-hangzhou",
//                "Action" => "SendSms",
//                "Version" => "2017-05-25",
//            ))
//        );
//   var_dump($content);
////        return $content;
////        ini_set("display_errors", "on"); // 显示错误提示，仅用于测试时排查问题
////        set_time_limit(0); // 防止脚本超时，仅用于测试使用，生产环境请按实际情况设置
////        header("Content-Type: text/plain; charset=utf-8"); // 输出为utf-8的文本格式，仅用于测试
////
////// 验证发送短信(SendSms)接口
////        print_r(sendSms());

    }
//    //验证用户名是否存在
//    public function actionValidateUser($username){
//        $user= Member::find()->all();
//        if($username==$user->username){
//          //已经存在
//            echo 'false';
//        }else{
//            //没有
//            echo 'true';
//        }
//
//    }
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4
            ],
        ];
    }


}



