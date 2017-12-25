<?php
namespace backend\models;
use yii\db\ActiveRecord;

class GoodsIntro extends  ActiveRecord{



    //把goods_id 字段设置成主键
    public static function primaryKey()
    {
        return ['goods_id'];
    }



}

