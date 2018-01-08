<?php
namespace frontend\models;
use yii\db\ActiveRecord;

class Address extends ActiveRecord{
//    public $province;  //省份
//    public $city;       //城市
//    public $district;        //区县
//    public $add_detail;  //详细地址


  //===========\\
    public static function tableName()
    {
        return 'address';
    }
   public function rules()
   {
    return [
        [['name','phone','add_detail','cmbProvince','cmbCity','cmbArea'],'string'],
        ['is_default','safe'],
       ];
   }

}

