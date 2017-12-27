<?php

?>
<table class="table">
    <?= yii\bootstrap\Html::a('添加',['userlist/add'],['class'=>'btn btn-default btn btn-default glyphicon glyphicon-pencil'])?>

    <tr>
        <th>用户名</th>
        <th>性别</th>
        <th>密码</th>
        <th>邮箱</th>
        <th>登录时间</th>
        <th>登录ip</th>
        <th>操作</th>
    </tr>
    <?php  foreach ($user as $u):?>
    <tr>
        <td><?=$u->username?></td>
        <td><?=$u->sex?></td>
        <td><?=$u->password?></td>
        <td><?=$u->email?></td>
        <td><?=date('Y-m-d h:i:s',$u->last_login_time)?></td>
        <td><?=$u->last_login_ip?></td>
        <td>
          <?= yii\bootstrap\Html::a('修改',['userlist/edit','id'=>$u->id],['class'=>'btn btn-default  glyphicon glyphicon-tags'])?>
          <?= yii\bootstrap\Html::a('删除',['userlist/delete','id'=>$u->id],['class'=>'btn btn-default  glyphicon glyphicon-trash'])?>
          <?= yii\bootstrap\Html::a('修改密码',['userlist/update','id'=>$u->id],['class'=>'btn btn-default glyphicon  glyphicon glyphicon-wrench'])?>

        </td>
    </tr>
 <?php  endforeach; ?>

</table>

