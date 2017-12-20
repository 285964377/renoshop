<?php

namespace backend\controllers;
use backend\models\Article_category;
use yii\web\Controller;
use yii\web\Request;

class Article_categoryController extends Controller {

    //列表展示
    public function actionIndex(){
       $Article=Article_category::find()->all();
       return $this->render('index',['Article'=>$Article]);
    }
    //添加
     public function actionAdd(){
        //实例化
      $model = new Article_category();
      $request = new Request();
      if($request->isPost){
        //加载
      $model->load($request->post());
       //验证
       if($model->validate()){
        //保存
       $model->save();
        //提示信息
       \Yii::$app->session->setFlash('success','添加成功了');
       //提示之后则跳转到首页
       return $this->redirect(['index']);
       }else{//如果出错则打印出错误信息;
         var_dump($model->getErrors());
         }
       }
       return $this->render('add',['modle'=>$model]);
    }
     public function actionEdit($id){
     //实例化
       $model=Article_category::findOne(['id'=>$id]);
       $request= new Request();
       if($request->isPost){
       //加载
       $model->load($request->post());
        //验证
       if($model->validate()){
        //保存
       $model->save();
       //提示信息
       \Yii::$app->session->setFlash('success','添加成功了');
        //提示之后则跳转到首页
        return $this->redirect(['index']);
        }else{//如果出错则打印出错误信息;
        var_dump($model->getErrors());
        }
        }
       return $this->render('edit',['modle'=>$model]);
    }
     //删除功能
     public function actionDelete($id){
     //页面点击之后数据调用到这里之后则修改其状态值为-1代表删除
      $Atricle= Article_category::findOne(['id'=>$id]);
      //这里这样写也实现了此功能
      //$Atricle= Article_category::updateAll(['status'=>-1],['id'=>$id]);
      //以防万一我还是这样写的
      $Atricle::updateAll(['status'=>-1],['id'=>$id]);
    }
}

