<?php

namespace backend\controllers;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;
use yii\web\Controller;
use yii\web\Request;

class RbacController extends Controller{
  public $enableCsrfValidation=false;
    //列表页
  public function actionIndex(){

     //$pre = Permission::find()->all();
     $authManager =\Yii::$app->authManager;
     $model=$authManager->getPermissions();
     //var_dump($model);
     //var_dump($authManager);
     return $this->render('index',['model'=>$model]);

  }
  //权限的添加
  public function actionAdd(){
      $model = new PermissionForm();
      $request = \Yii::$app->request;
      //开启场景进行验证规则
      $model->scenario = PermissionForm::SCENARIO_ADD_PERMISSION;
      if($request->isPost){
      $model->load($request->post());
      if($model->validate()){
      //实例化方便使用
      $authManager= \Yii::$app->authManager;
      $Permission =new \yii\rbac\Permission();
      //赋值
      $Permission->name=$model->name;
      $Permission->description = $model->description;
      //添加到数据库
      $authManager->add($Permission);
      \Yii::$app->session->setFlash('success','添加成功');
       return $this->redirect(['index']);
    }else{
      var_dump($model->getErrors());
      }

   }
     return $this->render('add',['model'=>$model]);
  }
  //权限修改
  public function actionEdit($id){
     //实例化一个authManager  用于管理员的修改操作
     $authManager = \Yii::$app->authManager;
     //获得过来的数据(称之为路由)
     $auth= $authManager->getPermission($id);
     //实例化一个
     $model = new PermissionForm();
     //开启场景
     $model->scenario =PermissionForm::SCENARIO_EDIT_PERMISSION;
     //发送过来的数据 和model中的数据复制以用来数据回显
     $model->name = $auth->name;
     $model->description = $auth->description;
     $request = new Request();
     if($request->isPost){
     //加载
     $model->load($request->post());
     if($model->validate()){
     //$authManager= \Yii::$app->authManager;
     //实例化 后面赋值
     $Permission =new \yii\rbac\Permission();
     //复制
     $Permission->name=$model->name;
     $Permission->description = $model->description;
     //修改(路由和实际数据)到数据库
     $authManager->update($id,$Permission);
         //跳转
     \Yii::$app->session->setFlash('success','修改成功');
     return $this->redirect(['index']);
     }

     }

      return $this->render('edit',['model'=>$model]);
     }
     //删除权限功能...
 public function actionDelete($id){
     //实例化
     $authManager = \Yii::$app->authManager;
    //实例化
     $model =new PermissionForm();
     //接受数据
     $model= $authManager ->getPermission($id);
    //删除此数据
     $authManager->remove($model);

     \Yii::$app->session->setFlash('success','删除成功');
     return  $this->redirect(['index']);

  }
 //角色列表
 public function actionRoleIndex(){
   $model= \Yii::$app->authManager->getRoles();
  // var_dump($model);

   return  $this->render('role-index',['model'=>$model]);

   }
   public function actionRoleAdd(){
    $model= new RoleForm();
    $authManager  =\Yii::$app->authManager;
    $auth =$authManager->getPermissions();
    //遍历
     $arr2 =[];
     foreach ($auth as $b){
         $arr2[$b->name] = $b->description;
     }
    $request =new Request();
    if($request->isPost){
    $model->load($request->post());
    if($model->validate()){
    $authManager=\Yii::$app->authManager;
    $role = new Role();
    $role->name =$model->name;
    $role->description = $model->description;
    $authManager->add($role);
    //var_dump($model->permission);exit;
    foreach ($model->permission as  $v){
     // 给角色赋予权限
    $permission  =$authManager->getPermission($v);
    $authManager->addChild($role,$permission);
    }
    \Yii::$app->session->setFlash('success','添加成功');
    return  $this->redirect(['role-index']);
    }

    }
   return $this->render('role-add',['model'=>$model,'arr2'=>$arr2]);
   }

    //角色的修改
 public function actionRoleEdit($id){
    $model= new RoleForm();
    $authManager  =\Yii::$app->authManager;
    $auth =$authManager->getRole($id);
    //获取角色关联的权限
    $arr=$authManager->getPermissionsByRole($id);
    $option= ArrayHelper::map($authManager->getPermissions(),'name','description');
    //赋值数据回显到页面
    $model->name = $auth->name;
    $model->description = $auth->description;
    //==========多选框回显遍历添加时勾选的权限在修改页面回显=========
     $a=[];
          foreach ($arr as $key=>$v){
           $a[]=$key;
          }
    $model->permission=$a;
    $request =new Request();
    if($request->isPost){
    $model->load($request->post());
    if($model->validate()){
    $authManager=\Yii::$app->authManager;
     //$auth = new Role();
     $auth->name =$model->name;
     $auth->description = $model->description;
    //==============保存===================
     $authManager->update($id,$auth);
   //去除角色的所有权限
     $authManager->removeChildren($auth);
    //关联新的权限
    foreach ($model->permission as  $v) {
    // 给角色赋予权限
     $permission = $authManager->getPermission($v);
     $authManager->addChild($auth, $permission);
    }
    \Yii::$app->session->setFlash('success','修改成功');
    return  $this->redirect(['role-index']);
     }

     }
     return $this->render('role-edit',['model'=>$model,'option'=>$option]);
    }
    //删除
    public function actionRoleDelete($name){
    $authManager= \Yii::$app->authManager;
    $role= $authManager->getRole($name);
    $authManager->remove($role);

    }

    //测试datatables插件
   public function actionTable(){
   return  $this->render('table');
   }
}


