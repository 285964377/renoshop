<?php
namespace backend\models;
use yii\base\Model;

class LoginForm extends Model{

    public $username;//用户名
    public $password;//密码

    //验证规则
    public function rules(){
        return[
            //不能是空
            [['username','password'],'required']
        ];
    }
   public function login(){
     //验证帐号和密码;
     $user = Userlist::findOne(['username'=>$this->username]);
     if($user){//如果存在 就验证密码
     //用户正确之后就继续验证密码
    if(\Yii::$app->security->generatePasswordHash($this->password)){
     //密码正确就登录
     // 在将用户信息存到session
    \Yii::$app->user->login($user);
    //登录成功获得当前登录时间 添加到数据

    $user->last_login_time= $last_login_time = time();;
    // $user->last_login_time = date('Y-m-d H:i:s',time());
    //登录成功获得用户ip 保存到数据库
    $last_login_time= \Yii::$app->request->userIP;
    $user->last_login_ip=$last_login_time;
    //var_dump($last_login_time);exit;
    $user->save(false);
    return true;
     }else{//密码不正确
             $this->addError('password','密码错误');
         }
     }else{//用户名不正确
         $this->addError('username','用户名不存在');
     }
        return false;

    }
}

