<?php
namespace backend\controllers;

use backend\models\Goods_category;
use backend\models\GoodsCategory;
use yii\web\Controller;
use yii\web\Request;

class GoodsCategoryController extends Controller{
   //列表
  public function actionIndex(){
   $goods= GoodsCategory::find()->all();

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
    public function actionEdit($id){
     $model =GoodsCategory::findOne(['id'=>$id]);
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
     return $this->render('edit',['model'=>$model]);

   }

}

