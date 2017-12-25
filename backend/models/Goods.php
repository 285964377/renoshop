<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Goods extends ActiveRecord{
    //用于保存商品内容详情 付文本编辑器
  public $content;


//  public static function tableName()
//  {
//      return 'goods';
//
//  }

    public function rules()
  {
      return [
          //
        [['name','content','logo','goods_category_id','brand_id','market_price','shop_price','stock','is_on_sale','status','sort'],'required','message'=>'不能为空必填']
//        [['stock','is_on_sale','status','sort'],'required','message'=>'不能是空必填']
      ];
  }
  public function attributeLabels()
  {
      return [
          'name'=>'商品名字',

          'logo'=>'图片',
          'goods_category_id'=>'商品所属分类',
          'brand_id'=>'品牌ID',
          'market_price'=>'市场价格',
          'shop_price'=>'商品价格',
          'is_on_sale'=>'是否在售',
          'status'=>'状态',
          'sort'=>'排序',
          'stock'=>'库存',
          'content'=>'商品详情'


      ];
  }
  public function getGoodsCategory(){
      //商品分类的id 和 商品的 id 中的goods_Category_id 互相关联彼此
                                                    //解释 :商品分类的id 和这边的一个goods_xxx_id关联
  return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);

  }
    //品牌的 id 和商品表 的 brand_id(猪表) 管理
    public function getBrand(){
        //
     return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }
   
}

