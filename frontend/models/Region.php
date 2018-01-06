<?php
namespace frontend\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Region extends ActiveRecord{
  public static function tableName()
  {
      return 'region';
  }
   public function attributeLabels()
   {
        return [
       'id' => 'ID',
       'name' => '省',
       'parent_id' => '市',
       'level' => '区县',
   ];
   }
    public static function gerRegion($parentId=0){
        $result = static::find()->where(['parent_id'=>$parentId])->asArray()->all();
        return ArrayHelper::map($result,'id','name');
    }
    //根据id得出地址名称
    public static function getName($id)
    {
        return self::find()->select('name')->where(['id'=>$id])->scalar();
    }

    //得到完整的地区信息
    public static function getFullArea($province,$city,$district)
    {
        return join(' ',[
            self::getName($province),
            self::getName($city),
            self::getName($district),
        ]);
    }
}