<?php
namespace frontend\controllers;
use frontend\models\Address;
use frontend\models\Region;
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
        //得到当前会员的id
        $id = \Yii::$app->user->getId();
        $model = new Address();
        $request = new Request();
        //根据会员查出他自己的收获地址 |.;
        $user_id = Address::find()->where(['user_id' => $id])->all();
        if ($request->isPost) {
        // var_dump($model);exit;
        $model->load($request->post(''));

        if ($model->validate()) {
         //拼接得到完整的收获地址
         //\$model->add_name = Region::getFullArea($model->province, $model->city, $model->district) . ' ' . $model->add_detail;
         //根据登录身份得到当前的会员ID
           // var_dump($model);exit;
         $model->user_id = $id;

         $model->save(false);
         //$this->refresh();
            }
        }

        return $this->render('address', ['model' => $model, 'user_id' => $user_id]);

    }

//    public function actions()
//    {
//        $actions = parent::actions();
//        $actions['get-region'] = [
//            'class' => \chenkby\region\RegionAction::className(),
//            'model' => \frontend\models\Region::className()
//        ];
//        return $actions;
//    }

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
//                //拼接得到完整的收获地址
//                $model->add_name = Region::getFullArea($model->province, $model->city, $model->district) . ' ' . $model->add_detail;
//                //根据登录身份得到当前的会员ID
//                $model->user_id = $id;
                $model->save(false);
                $this->refresh();
            }
        }
        return  $this->render('address',['model'=>$model,'user_id'=>$user_id]);
    }
}

