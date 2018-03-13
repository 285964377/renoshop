<?php
namespace frontend\controllers;
use frontend\models\Address;
use frontend\models\Region;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Request;

class AddressController extends Controller
{   public $enableCsrfValidation=false;

 public function actionAdd()
    {
           //如果没登录就跳转到登录
     if (\Yii::$app->user->isGuest) {
            return $this->redirect(['member/login']);
        }
        $arr= Address::find()->where(['user_id'=>\Yii::$app->user->getId()])->all();
        //得到当前会员的id
        $id = \Yii::$app->user->getId();
        $model = new Address();
        $request = new Request();
        //根据会员查出他自己的收获地址 |.;
        $user_id = Address::find()->where(['user_id' =>$id])->all();
        if ($request->isPost) {
       //var_dump($request->post());exit;
        $model->load($request->post(''));
        //复制存入数据库
        $model->user_id = $id;
        $model->name =$request->post('name');
        $model->phone =$request->post('phone');
        $model->add_detail =$request->post('add_detail');
        $model->cmbProvince =$request->post('cmbProvince');
        $model->cmbCity =$request->post('cmbCity');
        $model->cmbArea =$request->post('cmbArea');
        $model->is_default =$request->post('is_default');
        if ($model->validate()) {
        $model->save(false);
       }else{
       //打印出错误信息
       var_dump($model->getErrors());
        }
        }
        //页面显示
      return $this->render('address', ['model' => $model, 'user_id' => $user_id,'arr'=>$arr]);
    }

//地址修改...
 public function actionEdit($id)
    {
        $model = Address::findOne(['id' => $id]);
        $model = new Address();
        $request = new Request();
        //根据会员查出他自己的收获地址 |.;
        $user_id = Address::find()->where(['user_id' => $id])->all();
        if ($request->isPost) {
        $model->load($request->post());
        if ($model->validate()) {
        //拼接得到完整的收获地址
        $model->add_name = Region::getFullArea($model->province, $model->city, $model->district) . ' ' . $model->add_detail;
        //根据登录身份得到当前的会员ID
        $model->user_id = $id;
        $model->save(false);
        $this->refresh();
        }
        }
        return  $this->render('address',['model'=>$model,'user_id'=>$user_id]);
    }
  //收货地址删除
public function actionDelete($id){
 Address::deleteAll("id in ($id)");

 }
 //默认地址修改
 public function actionDefault($is_default,$id){

      if($is_default==1){
        Address::updateAll(['is_default'=>0],['id'=>$id]);
      }else{
        Address::updateAll(['is_default'=>1],['id'=>$id]);

      }



 }
}

