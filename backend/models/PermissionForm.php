<?php
namespace backend\models;
use yii\base\Model;

class PermissionForm extends Model{
  public $name;
  public $description;
  //指定场景为添加的时候
  const SCENARIO_ADD_PERMISSION ='add';
  const SCENARIO_EDIT_PERMISSION = 'edit';

 public function rules()
    {
      return [
       [['name','description'],'required'],
       //权限名称唯一 这里是自定义的 使用场景给指定的方法中验证
       //如果想多个方法中使用一个验证规则的话 那么应该如下写:
       // ['name','weiyi','on'=>[self::SCENARIO_ADD_PERMISSION],[''sxxxxxxxxxxx']],
       ['name','weiyi','on'=>self::SCENARIO_ADD_PERMISSION],
       //判断name是否已经存在了存在就无法继续修改 使用场景指定了
       ['name','validateName','on'=>self::SCENARIO_EDIT_PERMISSION],
      ];
    }
 //添加时n如果已经存在的则无法继续提交 给出提示信息
 public function weiyi(){
    $authManager = \Yii::$app->authManager;
    //获取当前模型下的name $this->name
    $permission = $authManager ->getPermission($this->name);
    if($permission){
    //当前对象调用 addErro 提示错误信息  返回到验证规则上面
    $this->addError('name','权限存在不可重复!');
    }
 }
 //验证名字存在与否!
 public function validateName(){
    $authManager = \Yii::$app->authManager;
    //接收 传的是id命名就是 写id 如果是name 就写name
    $getName =\Yii::$app->request->get('id');
    //接受中的name数据 不等于当前对象下的name
    if($getName != $this->name){
    //获取name 当前对象的name
    $a = $authManager->getPermission($this->name);
    if($a){
    // 前面获取了赋给了变量做---判断:如果存在就给出提示信息...
    $this->addError('name','已经存在请修改没有的权限方可使用');
      }
     }
 }

}

