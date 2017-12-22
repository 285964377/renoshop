<?php
namespace backend\models;

use yii\base\Action;
use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\helpers\Json;

class GoodsCategory extends ActiveRecord{

   public static function tableName()
   {
       return 'goods_category';
   }
    public function rules()
    {
        return[
          [['tree','lft','rgt','depth','parent_id'],'integer'],
          ['name','required','message'=>'不能是空'],
          ['intro','string'],

        ];
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',//打开能支持多颗树
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
    public static function find()
    {
        return new  CategoryQuery(get_called_class());
    }
    public function attributeLabels()
    {
        return[
            'name'=>'名称',
            'parent_id'=>'上级分类ID',
            'intro'=>'简介'
        ];
    }
    //返回数据 到列表 json方式
    public static function getNodes(){
        $nodes= self::find()->select(['id','parent_id','name'])->asArray()->all();
        array_unshift($nodes,['id'=>0,'parent_id'=>0,'name'=>'顶级分类']);

        return Json::encode($nodes);
    }

}
