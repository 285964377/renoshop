<?php
namespace frontend\controllers;

use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Brand;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\GoodsCategory;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\Cookie;

/**
 * Site controller
 */
class SiteController extends Controller
{ public  $enableCsrfValidation=false;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        return $this->render('index');
    }

    public function actionGoods($cate_id){
       //判断是二级分类还是三级分类
      $cate =\backend\models\GoodsCategory::findOne(['id'=>$cate_id]);
       if($cate->depth==1){
           //三级分类
           $ids = [$cate_id];
       }else{
               //一级分类
    //           //二级分类 2 = >{3, 4 }
    //           //获取该二级分类下面的三级分类
    //           $categorys = Goods::find()->where(['parent_id'=>$cate_id])->all();

           $categorys= $cate->children()->select('id')->andWhere(['depth'=>2])->asArray()->all();

           $ids= ArrayHelper::map($categorys,'id','id');
           //3,4
           //在根据三级分类id查找商品


       }
        $goods =Goods::find()->where(['in','goods_category_id',$ids])->all();


        return  $this->render('list',['goods'=>$goods]);

    }
    public function actionContent($id){
        $content = GoodsIntro::find()->where(['goods_id'=>$id])->one();
        $goods = Goods::find()->where(['id'=>$id])->all();
        $photh = GoodsGallery::find()->where(['goods_id'=>$id])->all();
                         //修改浏览次数        并且是根据商品的id修改
        Goods::updateAllCounters(['view_times'=>1],['id'=>$id]);

        foreach ($photh  as $pt){

        }
        foreach ($goods as $gds){

        }
        //var_dump($pt);exit;
//        var_dump($content);exit;
        return $this->render('goods',['content'=>$content,'goods'=>$goods,'photh'=>$photh,'pt'=>$pt,'gds'=>$gds]);
    }


    //添加购物成功 页面
     public function actionAddToCart($goods_id,$amount){
       //商品添加到购物车
      //判断是否登录状态如果不是存到cookie
      if(Yii::$app->user->isGuest){
          $cookies = Yii::$app->request->cookies;
           //没登录情况存cookie
          if($cookies->has('cart')){
              $value = $cookies->getValue('cart');
              //反序列化
              $cart = unserialize($value);
          }else{
              $cart = [];
          }

          //$cart = [1=>1]   + 2=>3   $cart[2] = 3 --->    $cart = [1=>1,2=>3]
          //写cookie
          //判断购物中是否存在该商品,存在,数量累加.不存在,直接赋值
          if(array_key_exists($goods_id,$cart)){
              $cart[$goods_id] += $amount;
          }else{
              $cart[$goods_id] = $amount;
          }
          $cookies = Yii::$app->response->cookies;
          $cookie = new Cookie();
          $cookie->name = 'cart';
          $cookie->value = serialize($cart);
          $cookies->add($cookie);

      }else{
         //已登录的话就保存到数据库
         $model =new Cart();
         $member_id= Yii::$app->user->getId();
         $mode = Cart::find()->andWhere(['goods_id'=>$goods_id])->andWhere(['member_id'=>$member_id])->one();
         if(!$mode){
          $model->amount=$amount;
          $model->member_id=$member_id;
          $model->goods_id=$goods_id;
          $model->save();
         }else{
//          $mode->amount +=$amount;
//          $mode->save();
             Cart::updateAll(['amount'=>$mode->amount+$amount],['goods_id'=>$goods_id,'member_id'=>Yii::$app->user->id]);
         }

      }
      return $this->redirect(['site/cart']);
     }
     //购物车页面
 public function actionCart()
 {
     //判断是否登录 没有登录就从cookie上面获得
     if (Yii::$app->user->isGuest) {
         //读cookie
         $cookies = Yii::$app->request->cookies;
         $value = $cookies->getValue('cart');
         $cart = unserialize($value);
         //var_dump($cart);exit;
         //$cart = [1=>2,2=>3]
         $ids = array_keys($cart);

     } else {
         //如果已经登录购物车数据从数据库读取
         $member_id = \Yii::$app->user->id;
         $models = Cart::find()->where(['member_id' => $member_id])->all();
         $ids = [];
         $cart = [];
         foreach ($models as $model) {
             //将得到得goods_id放入数组中
             $ids[] = $model->goods_id;
             //模拟一个数组显示页面用
             $cart[$model->goods_id] = $model->amount;

         }
     }
         $models = Goods::find()->where(['in', 'id', $ids])->all();
         //var_dump($models);exit;
         return $this->renderPartial('cart', ['models' => $models, 'cart' => $cart]);
  }

  //添加商品 + - 或则中间填写
  public function actionChange(){
      //goods_id   and 新数量 amount;
      $goods_id = Yii::$app->request->post('goods_id');
      $amount=Yii::$app->request->post('amount');
      //是否登录
      if(Yii::$app->user->isGuest){
          //没登录情况存cookie
          $cookies = Yii::$app->request->cookies;
          if($cookies->has('cart')){
          $value = $cookies->getValue('cart');
          //反序列化
          $cart = unserialize($value);
          }else{
              $cart = [];
          }
          $cart[$goods_id]=$amount;
          $cookies = Yii::$app->response->cookies;
          $cookie = new Cookie();
          $cookie->name = 'cart';
          $cookie->value = serialize($cart);
          //var_dump($cookie);
          $cookies->add($cookie);

      }else{
          $id = Yii::$app->user->getId();
          //修改记录amount+1
          Cart::updateAll(['amount'=>$amount],['goods_id'=>$goods_id,'member_id'=>$id,]);
             // 表示该会员已添加过该商品，只需要修改纪录即可
              //$model->amount += $amount;
              //$model->save();
      }

  }
  //删除某个商品
  public function actionDelete($id){
      //$amount=Yii::$app->request->get('amount');
      //是否登录
      if(Yii::$app->user->isGuest){
      //没登录情况存cookie
      $cookies = Yii::$app->request->cookies;
      if($cookies->has('cart')){
          $value = $cookies->getValue('cart');
          //反序列化
          $cart = unserialize($value);
      }else{
          $cart = [];
      }
      unset($cart[$id]);
     // var_dump($cart);exit;
      $cookies = Yii::$app->response->cookies;
      $cookie = new Cookie();
      $cookie->name = 'cart';
      //var_dump($cookie);exit;
      $cookie->value = serialize($cart);
      $cookies->add($cookie);

      }else{
             //登录情况下操作数据库删除
          $user_id = Yii::$app->user->getId();
           Cart::deleteAll(['goods_id'=>$id,'member_id'=>$user_id]);

      }

  }


    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
