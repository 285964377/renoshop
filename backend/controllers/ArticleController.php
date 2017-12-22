<?php

namespace backend\controllers;
use backend\models\Article;
use backend\models\Article_category;
use backend\models\Article_detail;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller
{
    //列表
   public function actionIndex()
   {
    $Acticle = Article::find()->all();
    return $this->render('index', ['Acticle' => $Acticle]);
   }
     //添加功能
   public function actionAdd()
   {
    $modle = new Article();//文章实例化
    $request = new Request();
    $detail = new Article_detail();//文章详情实例化
    if ($request->isPost){
        //加载
     $modle->load($request->post());
     //时间戳 传入数据库
     $create_time=time();
     $modle->create_time= $create_time;
     //加载
     if($modle->validate()){
     //保存文章的数据
     $modle->save();
     //复制 文章详情的字段content = model 的这个字段内容
     $detail->content=$modle->content;
     //保存到文章详情表
     $detail->save();
    }
    //添加成功则给出提示信息
    \Yii::$app->session->setFlash('success','添加成功');
     return $this->redirect(['index']);
    }
    $Article_category=Article_category::find()->all();
    $option=ArrayHelper::map($Article_category,'id','name');
    //  var_dump($option);exit;
    return $this->render('add',['modle'=>$modle,'option'=>$option]);
   }


    //富文本编辑器
    public function actions()
    {

    return [
    'ueditor' => [
    'class' => 'common\widgets\ueditor\UeditorAction',
    'config' => [
     //上传图片配置
    'imageUrlPrefix' => "", /* 图片访问路径前缀 */
    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
         ]
        ]
     ];
    }
    //删除 ajax 请求提交删除
    public function actionDelete($id){
    $Article=Article::findOne(['id'=>$id]);
    $Article::updateAll(['status'=>-1],['id'=>$id]);

    }
    public function actionEdit($id){
     $modle =Article::findOne(['id'=>$id]);
     $detail = new Article_detail();//文章详情实例化
     $request = new Request();
     if ($request->isPost) {
            //加载
      $modle->load($request->post());
      //时间戳 传入数据库
      $create_time=time();
      $modle->create_time= $create_time;
      //加载
      if($modle->validate()){
      //保存文章的数据
      $modle->save();
      //复制 文章详情的字段content = model 的这个字段内容
      $detail->content=$modle->content;
      //保存到文章详情表
      $detail->save();
     }
       //添加成功则给出提示信息
      \Yii::$app->session->setFlash('success','修改成功');
      return $this->redirect(['index']);
     }
      //数据遍历用于文章分类
      $Article_category=Article_category::find()->all();
      //数组 id  和name
      $option=ArrayHelper::map($Article_category,'id','name');
      //var_dump($option);exit;
      return $this->render('edit',['modle'=>$modle,'option'=>$option]);
    }
}
