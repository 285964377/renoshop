<?php
namespace frontend\controllers;
use backend\models\LoginForm;
use backend\models\User;
use frontend\models\Member;
use frontend\models\Userlist;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;

class ApiController extends Controller{
 public  $enableCsrfValidation = false;

 public function init()
{//.
    parent::init();
    //设置相应数据的格式默认是JSon
   \Yii::$app->response->format = Response::FORMAT_JSON;


}

    //用户注册
 public function actionUserRegister(){
    $result = [
         "error_code"=>1,
         'msg'=>'',
         "data"=>[]
    ];
    if(\Yii::$app->request->isPost){
        $user = new Member();
        $user->username = \Yii::$app->request->post('username');
        $user->password_hash = \Yii::$app->request->post('password_hash');
        $user->email = \Yii::$app->request->post('email');
        $user->tel = \Yii::$app->request->post('tel');
        if($user->validate()){
          $user->password_hash = \Yii::$app->security->generatePasswordHash($user->password_hash);
           $user->save();
           //注册成功;
           $result['error_code']=0;
           $result['msg']='注册成功';
        }else{
            //注册失败
            $result['msg']=$user->getErrors();
        }

    }else{
        $result['msg']='请使用POST请求';
    }
     return $result;

    }
    //登录
    public function actionLogin(){
        $result = [

            "error_code"=>1,
            'msg'=>'',
            "data"=>[]
        ];
        //登陆表单
        $model = new LoginForm();

        $request = new Request();
        if($request->isPost){
         $model->load($request->post(),'');

        if($model->login()){
        //登录成功
         $result['error_code'] =0;
         $result['msg']='登录成功';

        }else{
        //失败则打印出错误信息(提示信息)
        $result['msg']=$model->getErrors();

        }

        }else{
         $result['msg']='请使用POST请求';
        }
        return $result;
    }
    //修改密码
  public function actionEditPassword(){
      $result = [
        "error_code"=>1,
        'msg'=>'',
        "data"=>[]
      ];
       $member = Member::findOne(['id'=>\Yii::$app->user->getId()]);
       $requeset = new Request();
       if($requeset->isPost){
       $member->username = $requeset->post('username');
       $member->password_hash = $requeset->post('password_hash');
      if($member->validate()){
         $member->password_hash = \Yii::$app->security->generatePasswordHash($member->password_hash);

      }
       }
    }
}

