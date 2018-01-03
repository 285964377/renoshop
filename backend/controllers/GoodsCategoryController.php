<?php
namespace backend\controllers;

use backend\models\Goods_category;
use backend\models\GoodsCategory;
use yii\web\Controller;
use yii\web\Request;

class GoodsCategoryController extends Controller{
   //列表
 public function actionIndex(){
    //列表展示
    $goods= GoodsCategory::find()->all();
    //故而遍历使其列表展示其分类所属
    $arr=[];
    foreach ($goods as $b){
        $arr[$b->id]=$b->name;
    }
    return $this->render('index',['goods'=>$goods,'arr'=>$arr]);
  }
   //添加
 public function actionAdd(){
   $model =new GoodsCategory();
   $request = new Request();
   if($request->isPost){
   //加载
   $model->load($request->post());
   //验证
   if($model->validate()){
   //如果存在了 就创建子结点
   if($model->parent_id){
    $parent=  GoodsCategory::findOne(['id'=>$model->parent_id]);
    $model->appendTo($parent);
   }else{
    //如果没有就创建根结点
     $model->makeRoot();
   }//保存
       $model->save();
    }
    }
    return $this->render('add',['model'=>$model]);
   }
   //修改功能
 public function actionEdit($id){
    $model =GoodsCategory::findOne(['id'=>$id]);
    $request = new Request();
    if($request->isPost){
     //加载
    $model->load($request->post());
    //此判断的意思是: 如果自己经在这个目录下了 那么还要之意孤行是不会被修改成功的;
    if($model->parent_id==$model->parent_id){
    \Yii::$app->session->setFlash('success','已经在自己目录了不能在修改到自己目录里');
    return $this->redirect(['index']);
     }
        //验证
    if($model->validate()){
    //如果存在了 就创建子结点
    if($model->parent_id){
        $parent=  GoodsCategory::findOne(['id'=>$model->parent_id]);
        $model->appendTo($parent);
    }else{
        //如果没有就创建根结点
    $model->makeRoot();
    }//保存

    $model->save();
    }
    \Yii::$app->session->setFlash('success','添加成功');
    return $this->redirect(['index']);
    }
    return $this->render('edit',['model'=>$model]);

   }
    public function actionDelete($id){
    $Goods=GoodsCategory::findOne(['id'=>$id]);
    //如果存在根目录下级则无法被删除...
    if($Goods->parent_id){
        //提示信息
        \Yii::$app->session->setFlash('success','存在下级根无法被删除');
       // return $this->redirect(['index']);
    }else{
        //没有就成功删除
        $Goods= GoodsCategory::deleteAll("id in ($id)");
    }

   }

}

