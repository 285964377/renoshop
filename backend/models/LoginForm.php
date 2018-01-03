<?php
namespace backend\models;
use yii\base\Model;

class LoginForm extends Model{

    public $username;//用户名
    public $password;//密码
    //public $auth_key;//自动登录值
    public $success;//自动登录
    //public $password2;

    //验证规则
    public function rules(){
        return[
            //不能是空
            [['username','password'],'required','message'=>'必填不能为空哦'],
            ['success','safe'],

        ];
    }
   public function login(){

    //验证帐号和密码;
    $user = Userlist::findOne(['username'=>$this->username]);
  // var_dump($user);exit;
   //var_dump($user->password);exit;
    if($user){
    //如果存在 就验证密码
    //用户正确之后就继续验证密码
    if(\Yii::$app->security->validatePassword($this->password,$user['password'])){

    //密码正确就登录
        //return true;
  //获取是否自动登录使用this才行
    $success= $this->success;
    if($success){
    //如果里面存在则为有值 就给他加上生命周期 意味着保存cooki自动登录了
      $success= 7*24*3600;
    }
    // 在将用户信息存到session
    //var_dump($user);exit;
    // 写入变量储存 如果不存在就默认为0 不会增加时间周期也不会报错.
    \Yii::$app->user->login($user,$success);
    //登录成功获得当前登录时间 添加到数据
    $user->last_login_time= $last_login_time = time();;
    //登录成功获得用户ip 保存到数据库
    $last_login_time= \Yii::$app->request->userIP;
    $user->last_login_ip=$last_login_time;
   // var_dump($user);exit;
    $user->save(false);
    return true;
     }else{//密码不正确
             $this->addError('password','密码错误');
            // return false;
         }
     }else{//用户名不正确
         $this->addError('username','用户名不存在');
     }
     return false;
   }
}

