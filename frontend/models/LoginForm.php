<?php
namespace frontend\models;
use yii\base\Model;

class LoginForm extends Model{

    public $password_hash;//验证密码
    public $username;//用户;
    public $success;//自动登录
    //public $password_hash;
    public function rules()
    {
        return [
            [['username','password_hash'],'required'],
            ['success','safe'],
        ];
    }

    public function login(){

      $user = Member::findOne(['username'=>$this->username]);

      // var_dump($user);
      if($user){
      //如果存在 就验证密码\
//          //$this->password_hash = '';
//          var_dump(\Yii::$app->security->generatePasswordHash($this->password_hash));
//          var_dump($user['password_hash']);
//          var_dump(\Yii::$app->security->validatePassword($this->password_hash,$user['password_hash']));
//          exit;
      if(\Yii::$app->security->validatePassword($this->password_hash,$user['password_hash'])){
          //自动登录..
          $success = $this->success;
          //如果存在的话 那么给他赋上时间
          if($success){
          $success =7*24*3600;

          }
         //如果选择了自动登录那么把他传入进去如果没有的话那么也不会出错 默认是0 所以不会报错的.
        \Yii::$app->user->login($user,$success);
          $last_login_time=time();
          $user->last_login_time=$last_login_time;
          $last_login_ip=\Yii::$app->request->userIP;
          $user->last_login_ip=$last_login_ip;
          $user->save();
          return true;
      }else{//密码不正确
          $this->addError('password_hash','密码错误');
         echo "密码错误";
      }
      } else{//用户名不正确
          $this->addError('username','用户名不存在');
      }
      return false;
    }
}

