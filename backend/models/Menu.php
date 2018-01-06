<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Menu extends ActiveRecord{

   public function rules()
   {
       return [
         [['label','url','sort','parent_id'],'required','message'=>'不能是空']

       ];
   }

}


