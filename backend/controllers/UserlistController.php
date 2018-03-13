<?php
namespace backend\controllers;
use backend\models\LoginForm;
use backend\models\RoleForm;
use backend\models\Userlist;
use yii\rbac\Role;
use yii\web\Controller;
use yii\web\Request;

class  UserlistController extends Controller{
 public $enableCsrfValidation=false;
  //用户的列表展示
 public function actionIndex(){
    $user= Userlist::find()->all();
    return $this->render('index',['user'=>$user]);

    }
    //用户修改密码功能此功能作废不用了
 public function actionUpdate($id){
    $model = Userlist::findOne(['id'=>$id]);
    $request = new Request();
    if($request->isPost){
    $model->load($request->post());
    //var_dump($model);exit;
    if($model->validate()){

     }
    //添加密码时候采用 Hash加密
    //$user= new Userlist();
    $model->password=\Yii::$app->security->generatePasswordHash( $model->password);
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
    $role = new RoleForm();
    $authManager = \Yii::$app->authManager;
    //获取所有角色
    $auth=$authManager->getRoles();
     //遍历存入数组 放入多选框
    $arr= [];
    foreach ($auth as $a) {
      $arr[$a->name]= $a->description;
    }
    $model = new Userlist();
    $request =new Request();
    if($request->isPost){

      $model->load($request->post());

    if($model->validate()){
     //添加密码时候采用 Hash加密
     $model->password=\Yii::$app->security->generatePasswordHash($model->password);
     $model->save();
     $authManager = \Yii::$app->authManager;
     $auth2=$authManager->getRoles();
     $arr2= [];
     foreach ($auth2 as $a) {
     $arr2[$a->name]= $a->description;
     $userId = $model->id;
     $authManager->assign($a ,$userId);
     }
     }

         \Yii::$app->session->setFlash('success','添加成功');
         return $this->redirect(['index']);

     }
     return $this->render('add',['model'=>$model,'arr'=>$arr]);
    }
   //修改
 public function actionEdit($id){
     $role = new RoleForm();
     $authManager = \Yii::$app->authManager;
     //获取所有角色
     $auth=$authManager->getRoles();
     //查询出改用户所拥有的角色
     $arr2 = $authManager->getRolesByUser($id);
    //空数组保存
     $Byuser =[];
    //遍历角色到页面上,就是此用户所拥有的角色回显
     foreach ($arr2 as $key=>$v){
       $Byuser[]=$key;
     }
     //遍历存入数组 放入多选框
     $arr= [];
     foreach ($auth as $a) {
         $arr[$a->name]= $a->description;
     }

    $model = Userlist::findOne(['id'=>$id]);
    //赋值回显用户所拥有的角色
    $model->description =$Byuser;
    $request = new Request();
   if($request->isPost){
    $model->load($request->post());
   if($model->validate()){
    //添加密码时候采用 Hash加密
    $model->password=\Yii::$app->security->generatePasswordHash($model->password);
    //$model->save();
    $authManager = \Yii::$app->authManager;
    //获取用户所有的角色
    $auth2 = $authManager->getRolesByUser($id);
    //var_dump($auth2);exit;
    //去除用户的的所有角色

       //关联新的角色
    $arr3= [];
    foreach ($auth2 as $a) {
    $arr3[$a->name]= $a->description;
    //去除用户的所有角色
    $authManager->revokeAll($id);
    // 给用户赋予角色
    $authManager->assign($a,$id);
    }   

   }
     \Yii::$app->session->setFlash('success','添加成功');
     return $this->redirect(['index']);
     }

     return $this->render('edit',['model'=>$model,'arr'=>$arr]);
  }
 public function actionDelete($id){
   ///Userlist::deleteAll("id in ($id)");
     //不会删除用户只回删除持有的角色
    $authManager= \Yii::$app->authManager;
    $authManager->getRolesByUser($id);
    $authManager->revokeAll($id);
    // \Yii::$app->session->setFlash('success','删除成功');
   // return $this->redirect(['index']);

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
     }
     return $this->redirect(['index']);

    }

    //用户注销
 public function actionLogout(){
    \Yii::$app->user->logout();
    return $this->redirect(['login']);

    }


}

