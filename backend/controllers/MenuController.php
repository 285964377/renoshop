<?php
namespace backend\controllers;
use backend\models\Menu;
use yii\web\Controller;
use yii\web\Request;

class MenuController extends Controller{
    public  $enableCsrfValidation=false;
   //菜单列表页
    public function actionIndex(){

    $model= Menu::find()->all();
    return $this->render('index',['model'=>$model]);
}
 //添加功能
 public function actionAdd(){

    $model = new Menu();
    $request= new Request();
    $authManager= \Yii::$app->authManager;
    $permission=$authManager->getPermissions();
    $rows=[];
    $rows[]='【顶级分类】';
    $per_id = Menu::find()->where(['parent_id'=>0])->all();
    //var_dump($per_id);exit;
    foreach ($per_id as $v){
    $rows[$v->id] = $v->label;

    }

   //========遍历路由地址 放到下拉框中======\\
   $per=[];
   foreach ($permission as $p){
       $per[$p->name] = $p->name;
   }
   //======遍历描述信息放到add下拉框中========\\
//   $pername = [];
//   foreach ($permission as $p2){
//       $pername[$p2->description] = $p2->description;
//   }
    if($request->isPost){
    $model->load($request->post());
    if($model->validate()){
    //保存
    $model->save();
    \Yii::$app->session->setFlash('success','添加成功');
    return $this->redirect(['index']);
      }
    }
    return  $this->render('add',['model'=>$model,'per'=>$per,'rows'=>$rows]);
   }
   public function actionEdit($id){
   $model = Menu::findOne(['id'=>$id]);
   $request= new Request();
   $authManager= \Yii::$app->authManager;
   $permission=$authManager->getPermissions();
   //========遍历路由地址 放到下拉框中======\\
   $per=[];
   foreach ($permission as $p){
       $per[$p->name] = $p->name;
   }
   //空数组存放顶级分类字样
   $rows=[];
   $rows[]='顶级分类';
   //=====查询panrend_id 是0的=========\\
   $per_id = Menu::find()->where(['parent_id'=>0])->all();
   //var_dump($per_id);exit;
   foreach ($per_id as $v){
   $rows[$v->id] = $v->label;
   }
           //======遍历描述信息放到add页面的下拉框中========\\
//   $pername = [];
//   foreach ($permission as $p2){
//       $pername[$p2->description] = $p2->description;
//   }
     if($request->isPost){
       $model->load($request->post());

      if($model->validate()){
       $model->save();
       \Yii::$app->session->setFlash('success','添加成功');
       return $this->redirect(['index']);
      }
     }
       return  $this->render('add',['model'=>$model,'per'=>$per,'rows'=>$rows]);
   }
   //删除
   public function actionDelete($id){
     Menu::deleteAll("id in ($id)") ;

   }
}


