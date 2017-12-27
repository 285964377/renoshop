<?php

namespace backend\models;
use yii\base\Model;

class  RoleForm extends Model{
public $name;
public $description;
public $permission=[];

    public function rules()
    {
        return [
            [['name','description','permission'],'required'],
        ];
    }

}