<?php

?>

<table class="table">
<h1>角色管理</h1>
    <?=yii\bootstrap\Html::a('添加',['rbac/role-add'],['class'=>'btn btn-default glyphicon glyphicon-pencil'])?>
    <tr>
        <th>角色名字</th>
        <th>type</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $m): ?>
    <tr>
        <td><?=$m->name?></td>
        <td><?=$m->type?></td>
        <td><?=$m->description?></td>
        <td>

   　　　　<?= yii\bootstrap\Html::a('修改',['rbac/role-edit','id'=>$m->name],['class'=>'btn btn-default glyphicon glyphicon-wrench'])?>
            <?=yii\bootstrap\Html::a('删除',['rbac/role-delete','name'=>$m->name],['class'=>'btn btn-default glyphicon glyphicon-trash'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>

