<?php
namespace backend\ controllers;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class GoodsController extends Controller{
   public $enableCsrfValidation=false;

   public function actionIndex(){
       //商品列表
     $goods =Goods::find()->all();
     return $this->render('index',['goods'=>$goods]);

   }
   //Ajax文件上传
   public function actionUploader()
   {
       //name= file 所以
       $img = UploadedFile::getInstanceByName('file');
       $FimagName = '/upload/' . uniqid() . '.' . $img->extension;
       if ($img->saveAs(\Yii::getAlias('@webroot') . $FimagName, 0)) {
           ///将图片上传到七牛云
//   $accessKye ='qVKexGMs3BO60lgfBFQcnAiZpVym9tJz2k2Qsz8g';
//   $secretKye='T8ffPYvx8pG0WuLZ8W76gVYSDZi2-2r47muh-_Lu';
//   $bucket='php0825';
//   $domain='www.58yq.cn';
//   $auth=new Auth($accessKye,$secretKye);
           //生成上传 Token
//   $token=$auth->uploadToken($bucket);
//   //$fileName='/upload/1.jpg';
//   $filePath= \Yii::getAlias('@webroot').$FimagName;
//   //上传到七牛保存的文件名
//   $key =$FimagName;
//   $uploadMgr=new UploadManager();
//   //调用outfile方法进行文件上传
//   list($ret,$err)=$uploadMgr->putFile($token,$key,$filePath);
//   if($err !==null){
//       //如果错误就打印
//       return Json::encode(['error'=>1]);
//   // var_dump($err);
//   }else{
//       $url="http://{$domain}/{$key}";
//       return Json::encode(['url'=>$url]);

           //如果上传成功则返回 json 数据方便回显
           return Json::encode(['url' => $FimagName]);
       } else {
           //如果没有上传上个则返回出错误信息
           return Json::encode(['error' => 1]);
       }
   }
   public function actionAdd(){
       //实例化
     $model = new Goods();
    $request = new Request();
    $Intor = new GoodsIntro();
    if($request->isPost){

    $model->load($request->post());
     //加载
     ////今天的第几个商品
     //新增商品自动生成sn,规则为年月日+今天的第几个商品,比如2016053000001
    if($model->validate()){
     $date = date('Y-m-d');
     $count=GoodsDayCount::findOne(['day'=>$date]);
     //$count = str_pad('0',5,0,STR_PAD_RIGHT);
//     var_dump($count);die;
     if($count!=null){//如果不是空那么就加1
     //$Goods= new GoodsDayCount();
     // $count=1;
//     var_dump($count+=1);die;
     //$Goods_count= GoodsDayCount::findOne(['day'=>$date]);
     //$a = $count +=1;
     //var_dump($a);
     //$Goods_count::updateAll(['count'=>$Goods_count->count+1],['day'=>$date]);//die;
     $count->count+=1;
     }else{
         //$count = str_pad('0',5,0,STR_PAD_RIGHT);
         $count = new GoodsDayCount();
         $count->count =1;//保持1 不变
         $count->day= $date;
     }
     $st=00000;
     $count->save();
    //$model->sn=date('Ymd').str_pad($count->count,6,0,0);
    ////拼接时间
     $model->sn=date('Ymd').$st;
    //var_dump($model->sn);exit;
     $model->create_time= time();
     $model->save();
     //商品内容详情保存赋值
     $Intor->goods_id= $model->id;
     $Intor->content=$model->content;
    //保存
   //$Goods->save();
     $Intor->save();
        // $Goods->save();
   }
      // die;
       //添加成功则给出提示信息
     \Yii::$app->session->setFlash('success','添加成功');
     return $this->redirect(['index']);
     }

   //商品分类遍历
   $goods = GoodsCategory::find()->all();
   //需要的就是商品分类的 id 并且找到名称
   $gds=   ArrayHelper::map($goods,'id','name');
    //商品品牌遍历
   $brand = Brand::find()->all();
   $brand =ArrayHelper::map($brand,'id','name');

   return $this->render('add',['model'=>$model,'gds'=>$gds,'brand'=>$brand]);

   }
   public function actionDelete($id){
   Goods::deleteAll("id in ($id)");
       //添加成功则给出提示信息
       \Yii::$app->session->setFlash('success','删除成功');
       return $this->redirect(['index']);

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
      public function actionEdit($id){

          //实例化
       $model = Goods::findOne(['id'=>$id]);
       $request = new Request();
       $Intor = new GoodsIntro();
       if($request->isPost){
           //加载
        $model->load($request->post());
        $create_time= time();
        $model->create_time= $create_time;
        if($model->validate()){
        $model->save();
        $Intor->goods_id= $model->id;
        $Intor->content=$model->content;
        }   //保存
        $Intor->save();
        //添加成功则给出提示信息
          \Yii::$app->session->setFlash('success','添加成功');
          return $this->redirect(['index']);
        }
         //商品分类遍历
         $goods = GoodsCategory::find()->all();
         //需要的就是商品分类的 id 并且找到名称
         $gds=   ArrayHelper::map($goods,'id','name');
         //商品品牌遍历
         $brand = Brand::find()->all();
         $brand =ArrayHelper::map($brand,'id','name');

         return $this->render('add',['model'=>$model,'gds'=>$gds,'brand'=>$brand]);

      }
      public function actionGallery($id){

      $Gall= GoodsGallery::find()->where(['goods_id'=>$id])->all();

      //保存

//      echo Json::encode(['id'=>$Gall->id]);

      return $this->render('gallery',['Gall'=>$Gall,'id'=>$id]);
      }
      //相册功能 AJax图片上上传
     public function actionGalleryup(){

     $img = UploadedFile::getInstanceByName('file');
     $FimagName= '/upload/'.uniqid().'.'.$img->extension;
     if($img->saveAs(\Yii::getAlias('@webroot').$FimagName,0)){
     ///将图片上传到七牛云
     $accessKye ='5P9pKobraO45Wy2rKlmxiY1mJvH9I8YtpYRAsUMY';
     $secretKye='T8ffPYvx8pG0WuLZ8W76gVYSDZi2-2r47muh-_Lu';
     $bucket='php0825';
     //七牛云上的域名地址
     $domain='p1gsspstg.bkt.clouddn.com';
     //实例化
     $auth=new Auth($accessKye,$secretKye);

     //生成上传 Token
     $token=$auth->uploadToken($bucket);

    //$fileName='/upload/1.jpg';
     $filePath= \Yii::getAlias('@webroot').$FimagName;

    //上传到七牛保存的文件名
     $key =$FimagName;
     $uploadMgr=new UploadManager();
    //调用outfile方法进行文件上传
    list($ret,$err)=$uploadMgr->putFile($token,$key,$filePath);
    if($err !==null){
    //如果上传出错就将打出其错误信息
    return Json::encode(['error'=>1]);
   // var_dump($err);
   }else{
    //拼接URL地址
    $url="http://{$domain}/{$key}";
    return Json::encode(['url'=>$url]);
   //return Json::encode(['url'=>$FimagName]);
   }

   }else{
   //上传失败
   return Json::encode(['error'=>1]);
  }
}

 //相册的删除
  public function actionDel($id){

 //  $date= \Yii::$app->request->get();
 //  var_dump($date);
 //  $goods = GoodsGallery::findOne(['id'=>$id]);
  GoodsGallery::deleteAll(['id'=>$id]);

  }
    //相册保存到数据库
    public function actionSave(){
     //实例化
     $model = new GoodsGallery();
     //接受数据
     $id=$_GET['id'];
     $url = $_GET['url'];

     //赋值:数据库中的id=传过来的id
     $model->goods_id= $id;
     //赋值:数据库中的path=传过来的url
     $model->path=$url;
     //保存到数据库中
     $model->save();

    }





}
