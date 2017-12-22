<?php

namespace backend\models;
use yii\db\ActiveRecord;

class ArticleCategory extends ActiveRecord{

    public function rules()
      {
          return [
              [['name','intro','status'],'required','message'=>'亲!都是必填的哦!']
          ];
      }


    //处理成中文字段
    public function attributeLabels(){
        return[
            'name'=>'文章名字',
            'intro'=>'简介',
            'status'=>'状态'
        ];
    }
}

