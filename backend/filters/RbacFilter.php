<?php
namespace backend\filters;
use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilter extends  ActionFilter{
    //操作执行之前
    public function beforeAction($action)
    {
       //return  \Yii::$app->user->can( $action->uniqueId);//判断当前用户拥有什么权限
      //echo $action->uniqueId;//此乃路由
        //=====================\\
      //为了达到用户体验特写此判断 如果没有权限不会显示空白页 给予出提示 如:403...
      if(\Yii::$app->user->can($action->uniqueId)){
       //如果没有登录 跳转到登录页面
       if(\Yii::$app->user->isGuest){
       //跳转到登录页面  $action=操作..>谁在操作他 就会找到此控制器 控制器即可使用跳转
       //sned --- 本来不运行访问 (没登录的前提) return 一个对象是没拦截的
       // send-> 就没有返回值了  相当于 return ture
        // 比如删除我没权限 我点击了 那么直接给我跳转到登录 那么我触发的删除那个数据也就因此被删除 此是一个bu
        return $action->controller->redirect(\Yii::$app->user->loginUrl)->send();
       }

      //如果没有权限
      throw new HttpException(403,'对不起你无权操作此功能!');
      }
      //有的话
      return true;
    }
}

