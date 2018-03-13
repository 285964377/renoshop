<?php
namespace frontend\controllers;

use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use common\models\SphinxClient;
use frontend\models\Address;
use frontend\models\Brand;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\GoodsCategory;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\OrderGoods;
use Yii;
use yii\base\InvalidParamException;
use yii\db\Exception;
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
  public function actionIndex($name){
     //ob缓存

     //开启ob缓存
    $request =Yii::$app->request;
      $cl = new SphinxClient();
      $cl->SetServer ( '127.0.0.1', 9312);
      $cl->SetConnectTimeout ( 10 );
      $cl->SetArrayResult ( true );
      // $cl->SetMatchMode ( SPH_MATCH_ANY);
      $cl->SetMatchMode ( SPH_MATCH_EXTENDED2);
      $cl->SetLimits(0, 1000);
      $info = "{$name}";//查询关键字
      $res = $cl->Query($info, 'mysql');//查询用到的索引
      //存在则执行搜索条件
      //var_dump($res);exit;
      $ids=[];
      if(isset($res['matches'])){
       foreach($res['matches'] as $value){
       $ids[]=$value['id'];
       }
      }

    //var_dump($ids);exit;
    $goodss = Goods::find()->where(["in", "id",$ids])->all();
    //var_dump($goodss);exit;

    return $this->render('index',['goodss'=>$goodss]);



    //file_put_contents('index.html',$contents);

    }

 public function actionGoods($cate_id){
       //判断是二级分类还是三级分类
      $cate =\backend\models\GoodsCategory::findOne(['id'=>$cate_id]);
       if($cate->depth==1){
           //三级分类
           $ids = [$cate_id];
       }else{
      //一级分类
      //二级分类 2 = >{3, 4 }
      //获取该二级分类下面的三级分类
      //$categorys = Goods::find()->where(['parent_id'=>$cate_id])->all();
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
     $request = Yii::$app->request;
     //$name = empty($request->get('name'))?'':$request->get('name');
     $goods = Goods::find()->where(['id'=>$id])->all();;
     $photh = GoodsGallery::find()->where(['goods_id'=>$id])->all();
                      //修改浏览次数        并且是根据商品的id修改
     Goods::updateAllCounters(['view_times'=>1],['id'=>$id]);

     foreach ($photh  as $pt){

     }
     foreach ($goods as $gds){

     }
//      $goodss = Goods::find();
//    //存在则执行搜索条件
//    if($name){
//        $goodss->where(['like','name',$name]);
//    }
//    $goodss = $goodss->all();
//     if ($name){
//         $goods->andWhere(['like','name',$name]);
//     }



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
     //如果不存在的的话那么就赋值新创建新的
     if(!$mode){
     $model->amount=$amount;
     $model->member_id=$member_id;
     $model->goods_id=$goods_id;
     $model->save();
     }else{
//      $mode->amount +=$amount;
//      $mode->save();
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
      if(!$cart){

      return false;
      }
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
      ////Yii::$app->request->cookies主要负责读取
      $cookies = Yii::$app->request->cookies;
      if($cookies->has('cart')){
      $value = $cookies->getValue('cart');
      //反序列化
      $cart = unserialize($value);
       }else{
       $cart = [];
       }
      //Yii::$app->response->cookies主要负责创建
      $cart[$goods_id]=$amount;
      $cookies = Yii::$app->response->cookies;
      $cookie = new Cookie();
      //创建一个名为cart
      $cookie->name = 'cart';
      $cookie->value = serialize($cart);
      //var_dump($cookie);
      $cookies->add($cookie);

      }else{
      //登录状态下 修改 值需要 数量 += 即可 条件中的是赋值修改
      $id = Yii::$app->user->getId();
      //修改记录amount+1
      Cart::updateAll(['amount'=>$amount],['goods_id'=>$goods_id,'member_id'=>$id,]);
      //$model->amount += $amount;
      //return $this->redirect(['site/add-to-cart']);
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
  //确认订单提交页面
   public function actionOrder(){
       //如果没有登录那么跳转到登录
      if(Yii::$app->user->isGuest) {
       return $this->redirect(['member/login']);
      }
      $request = Yii::$app->request;
      //登录之后方可操作
      $address = Address::find()->where(['user_id'=>\Yii::$app->user->getId()])->all();
      //查询出购物车中的商品信息遍历在页面上
      //第一个空数组
      $orders = [];
      $cart = Cart::find()->select("*")->where(['member_id'=>Yii::$app->user->getId()])->all();
      //遍历商品数量 和商品
      foreach ($cart as $c) {
      //attributes数组集合
      $goods = Goods::findOne(['id' => $c['goods_id']])->attributes;
      //商品数量赋值
      $goods['amount'] = $c['amount'];
      $orders[] = $goods;
          }
      // T提交之后的数据处理
      if($request->isPost){
      $order = new Order();
      $order->load($request->post(),'');
      //取出addressID
      //var_dump($order);die;
      $address_id= $request->post('address_id');
      //收获地址表查询 根据POST 传过来的收获地址iD 查询
      $addres= Address::findOne(['id'=>$address_id]);
      //赋值
      $order->name =$addres->name;//收货人
      $order->province =$addres->cmbProvince;//省
      $order->city =$addres->cmbCity;//市
      $order->area =$addres->cmbArea;//县
      $order->address= $addres->add_detail;//详情地址
      $order->tel= $addres->phone;//手机号码
      $create_time= time();//创建时间
      $order->create_time = $create_time;
     //delivery_id
     //送货方式
     //$order->delivery_name = Order::$deliveries[$order->delivery_id][0];//名称 [0]->是名字
     ///$order->delivery_price = Order::$deliveries[$order->delivery_id][1];//价格[1]->价格
     //支付方式
      $order->total=0;//金额
      $order->status=1;//支付方式
      $order->member_id = Yii::$app->user->id;//用户登录ID
      //var_dump($order);exit;
      //开启事物
      $rtaction= Yii::$app->db->beginTransaction();
      try{
      if($order->validate()){//验证保存
      //b保存订单数据
      $order->save();//保存

          }else{
      var_dump($order->getErrors());die;
          }
      //遍历购物车商品信息 保存订单信息详情信息
      $carts = Cart::find()->where(['member_id'=>Yii::$app->user->id])->all();
      //var_dump($carts);exit;
      foreach ($carts as $ca){
      $gods =Goods::findOne(['id'=>$ca->goods_id]);
      //判断库存是否足够
      if($gods->stock >= $ca->amount){
      //如果足够
      $ordergoods =new OrderGoods();
      $ordergoods->order_id=$order->id;
      $ordergoods->goods_id = $gods->id;
      //var_dump($ordergoods);exit;
      $ordergoods->goods_name = $gods->name;
      $ordergoods->logo = $gods->logo;
      $ordergoods->price = $gods->shop_price;
      $ordergoods->amount = $ca->amount;
      $ordergoods->total= $ordergoods->amount*$gods->shop_price;
      //足够的保存
      $ordergoods->save();
      //减掉库存
      $gods->stock -=$ca->amount;
      $gods->save(false);
      //总价格
      $order->total +=$ordergoods->total;
      }else{
      //如果库存不够的话 则抛出异常
      throw new Exception('抱歉.....商品库存不够了请修改数量或者购买其他产品!');
       }
       }
      //运费处理
      $order->total+=$order->delivery_price;
      $order->save();//在保存上去
      //清除购物车数据
      Cart::deleteAll(['member_id'=>Yii::$app->user->id]);
      //获取邮件
      $email = Yii::$app->user->identity->email;
      //发送邮件
      Yii::$app->mailer->compose()
      ->setFrom('18380200885@163.com')//发送者
      ->setTo($email)//发送给用户
      ->setSubject('测试主题')//邮件主题
      ->setHtmlBody('你好嘛?1!@#!@#$#@%$&%^&')//内容
      ->send();
      //提交事物
      $rtaction->commit();
      //捕获异常
      }catch (Exception $e){
      //回滚事物
      $rtaction->rollBack();

      }
      //订单提示成功页面
      return $this->render('order2');

       }
     //订单提交页面显示
     return $this->render('order',['address'=>$address,'cart'=>$cart,'orders'=>$orders]);
   }

    //订单列表
 public function actionList(){
      if(Yii::$app->user->isGuest){
      return $this->redirect(['member/login']);
     }

   //订单查询 根据用户登录ID 查询
   $goods = Order::find()->where(['member_id'=>Yii::$app->user->getId()])->all();
   //空数组后面存放数据
   $orders=[];
   $address=[];
   foreach ($goods as $good){
   //g根据用户ID 查询发货人姓名
   $Address = Address::findOne(['user_id'=>$good->member_id]);
   //订单详情
   $address[]=$Address;
   $order = OrderGoods::findOne(['order_id'=>$good->id]);
   $orders[]=$order;

     }
   //var_dump($orders);exit;
   foreach ($address as $a){

   }
   foreach ($goods as $goo){

   }

  return $this->render('order3',['orders'=>$orders,'address'=>$a,'goo'=>$goo]);
    }
 //redis 测试
 public function actionReids(){
   $redis= new \Redis();
   $redis->connect('127.0.0.1');
   //name => 张三 过期时间为30秒
   $redis->set('name','张三','30');
   $redis->set('age','18');
 //mset //同时设置一个或多个 key-value 对
  //mget //返回所有(一个或多个)给定 key 的值
  //$redis->get('name');
   if($redis->get('age')){
   $redis->incr('age');//存在则+1
   }else{
   $redis->decr('age');//不存在减1
   }

   }
  //商品搜索功能测试
 public function actionSearch(){

     $cl = new SphinxClient();
     $cl->SetServer ( '127.0.0.1', 9312);
     //$cl->SetServer ( '10.6.0.6', 9312);
     //$cl->SetServer ( '10.6.0.22', 9312);
     //$cl->SetServer ( '10.8.8.2', 9312);
     $cl->SetConnectTimeout ( 10 );
     $cl->SetArrayResult ( true );
    // $cl->SetMatchMode ( SPH_MATCH_ANY);
     $cl->SetMatchMode ( SPH_MATCH_EXTENDED2);
     $cl->SetLimits(0, 1000);
     $info = '99A';//查询关键字
     $res = $cl->Query($info, 'mysql');//查询用到的索引
     //print_r($cl);
     print_r($res);

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
