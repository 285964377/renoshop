<?php

namespace backend\models;

use backend\controllers\ArticlecategoryController;
use yii\db\ActiveRecord;

class Article extends ActiveRecord{
    //内容详情 新添加字段 用来和文章详情表关联 添加到文章详情表去.
   public  $content;

   public function rules()
   {
       return [
         [['name','intro','sort','status'],'required','message'=>'不能是空哦'],
         ['content','required','message'=>'不能是空'],
           ['article_category_id','required','message'=>'分类不是空的哦']

       ];
   }

   public static function tableName()
   {
       return 'article';
   }

    //文章分类的的表ID 和文章的的id 关联
   public function getArticle_category(){

   return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);

   }

   //处理成中文
   public function attributeLabels()
    {
     return [
       'name'=>'文章名字',
         'intro'=>'简介',
         'sort'=>'排序',
         'status'=>'状态',

     ];
    }
}

