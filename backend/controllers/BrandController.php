<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    //品牌列表展示
    public function actionIndex()
    {
     $Brand = Brand::find()->all();
     return $this->render('index', ['Brand' => $Brand]);
    }

    public function actionAdd()
    {
      $model = new Brand();
      $request = new Request();
      if($request->isPost) {
       //加载
       $model->load($request->post());
       //图片处理
       $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
      //验证
      if($model->validate()) {
       $file = '/upload/' . uniqid() . '.'.$model->imgFile->extension;
        // 判断上传成功 如果成功则赋值
        if ($model->imgFile->saveAs(\Yii::getAlias('@webroot') . $file)) {
            $model->logo = $file;
          }
      }//保存
      $model->save();
      //提示信息
      \Yii::$app->session->setFlash('success', '添加成功');
       return $this->redirect(['index']);
        }
       return $this->render('add', ['modle' => $model]);
    }
       //修改方法
       public function actionEdit($id){
       $model= Brand::findOne(['id'=>$id]);
       $request = new Request();
      if ($request->isPost) {
          //加载
       $model->load($request->post());
       //图片处理
          $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
       //验证
      if ($model->validate()) {
           $file = '/upload/' . uniqid() . '.' . $model->imgFile->extension;
              // 上传成了  赋值在model中的
      if ($model->imgFile->saveAs(\Yii::getAlias('@webroot') . $file)) {
           $model->logo = $file;
              }
          }//保存
       $model->save();
       \Yii::$app->session->setFlash('success', '修改成功');
       return $this->redirect(['index']);
         }
       return $this->render('edit',['model'=>$model]);
     }
      public function actionDelete($id){

//          Brand::deleteAll("id in ($id)");
      $b=  Brand::findOne(['id'=>$id]);
      $b::updateAll(['status'=>-1],['id'=>$id]);
//         \Yii::$app->session->setFlash('success', '删除成功');
      }

 }
