<?php
namespace frontend\models;
use yii\db\ActiveRecord;

class Order extends  ActiveRecord{

  public  static $deliveries =[
  //配送方式 //名称 价格
    1=>['东风邮政','30','速度很快','服务好','价格贵'],
    2=>['东方快递','400','火箭发射进入大气层','服务美滋滋','价格昂贵适合商业用途'],
    3=>['大江东快递','30','速度一般','服务不错','价格贵'],
    4=>['歼20快递','300','采用隐形战斗机','美滋滋','贵贵贵']
  ];
  //支付方式
  public static  $payment=[
   1=>['支付宝','快速,安全,便捷,全球80亿人都在使用他'],
   2=>['在线支付','即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
   3=>['叶家银行','快速,安全,绝对隐私,全球商业大佬都在使用,在迪拜流水达到9999999亿']

  ];
  public function rules()
  {
       //required
      return [

          [['city','area','total','status','member_id','name','payment_id'],'required'],
      ];
  }


}

