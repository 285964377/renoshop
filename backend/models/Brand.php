<?php

namespace backend\models;

use yii\db\ActiveRecord;

class Brand extends ActiveRecord {
    public $imgFile;//文件上传

    public function rules()
    {
        return[
          [['name','intro','sort','status'],'required','message'=>'不能是空哦!'],
          ['logo','file','extensions'=>['jpg','png','gif'],'maxSize'=>1024*1024 /*'skipOnEmpty'=>false*/],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'商品名称',
            'intro'=>'简介',
            'logo'=>'图片',
            'sort'=>'排序',
            'status'=>'状态',
            'login'=>'上传图片'
        ];
    }
}

