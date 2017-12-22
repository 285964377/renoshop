<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\helpers\Json;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{   public $enableCsrfValidation=false;
    //品牌列表展示
    public function actionIndex()
    {
    $Brand = Brand::find()->all();
    return $this->render('index', ['Brand' => $Brand]);
    }
    //ajax文件上传处理
    public function actionUploader(){
   //标签名字 而不是Model 了
    $img = UploadedFile::getInstanceByName('file');
    $FimagName= '/upload/'.uniqid().'.'.$img->extension;
    //保存路径
    if($img->saveAs(\Yii::getAlias('@webroot').$FimagName,0)){
    //上传成功 返回图片地址 用来回显到图片框子中
      return Json::encode(['url'=>$FimagName]);
     }else{
      return Json::encode(['error'=>1]);
     }
    }
    public function actionAdd()
    {
     $model = new Brand();
     $request = new Request();
     if($request->isPost) {
       //加载
     $model->load($request->post());
     if($model->validate()) {

      }
     //保存
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
       //验证
     if($model->validate()) {
       }//保存
     $model->save();
     \Yii::$app->session->setFlash('success', '修改成功');
     return $this->redirect(['index']);
       }
     return $this->render('edit',['model'=>$model]);
     }
    public function actionDelete($id){
     $b=Brand::findOne(['id'=>$id]);
     $b::updateAll(['status'=>-1],['id'=>$id]);
      // \Yii::$app->session->setFlash('success', '删除成功');
      }
     //远程测试跨域

 }
