<?php
namespace backend\controllers;
use backend\models\LoginForm;
use backend\models\Userlist;
use yii\web\Controller;
use yii\web\Request;

class  UserlistController extends Controller{

    public function actionIndex(){
    $user= Userlist::find()->all();
    return $this->render('index',['user'=>$user]);

    }
    public function actionUpdate($id){
     $model = Userlist::findOne(['id'=>$id]);
     $request = new Request();
     if($request->isPost){
      $model->load($request->post());
      //var_dump($model);exit;
     if($model->validate()){

     }
     //添加密码时候采用 Hash加密
     $user= new Userlist();
     $model->password=\Yii::$app->security->generatePasswordHash($user->password);
     //$model->password2=\Yii::$app->security->generatePasswordHash($user->password2);
     $model->save();
     //提示and跳转
     \Yii::$app->session->setFlash('success','修改密码成功');
      return $this->redirect(['index']);

     }

      return $this->render('update',['model'=>$model]);
    }
   //添加
 public function actionAdd(){
    $model = new Userlist();
    $request =new Request();
    if($request->isPost){
     //$auth_key=  rand(1,20);
     //shuffle($auth_key);
     //$model->auth_key = $auth_key;
    $model->load($request->post());

    if($model->validate()){

      }
      //添加密码时候采用 Hash加密
    $user= new Userlist();
    $model->password=\Yii::$app->security->generatePasswordHash($user->password);
    $model->save();
    \Yii::$app->session->setFlash('success','添加成功');
    return $this->redirect(['index']);
     }
     return $this->render('add',['model'=>$model]);
    }
   //修改
 public function actionEdit($id){
     $model = Userlist::findOne(['id'=>$id]);
     $request = new Request();
     if($request->isPost){
     $model->load($request->post());

     if($model->validate()){

        $model->save();
      }
     \Yii::$app->session->setFlash('success','添加成功');
     return $this->redirect(['index']);
     }

     return $this->render('edit',['model'=>$model]);
    }
    public function actionDelete($id){
     Userlist::deleteAll("id in ($id)");
     \Yii::$app->session->setFlash('success','删除成功');
     return $this->redirect(['index']);

    }
    public function actionLogin(){
     //登陆表单
     $model = new LoginForm();
     $request = \Yii::$app->request;
     if($request->isPost){
     $model->load($request->post());
     //var_dump($model);exit;
     if($model->login()){

      //提示信息
     \Yii::$app->session->setFlash('success','登陆成功');
      //跳转
      //return   \$this->redirect(['index']);
      return $this->redirect(['center']);
     }
     }
      return $this->render('login',['model'=>$model]);
    }
    //是否登录验证
    public function actionCenter(){
     //如果没有登录则提示未登录返回到登录页面
     if(\Yii::$app->user->isGuest){
         //
     return $this->redirect(['login']);

     }else{
     return $this->redirect(['index']);
     }
    }

    //注销点击之后
   public function actionLogout(){
    \Yii::$app->user->logout();
    return $this->redirect(['login']);

    }
    //修改密码
    public function actionEdpassword(){

    }
}

