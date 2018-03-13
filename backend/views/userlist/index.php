<?php

?>
<table class="table">
    <?= yii\bootstrap\Html::a('添加',['userlist/add'],['class'=>'btn btn-default btn btn-default glyphicon glyphicon-pencil'])?>

    <tr>
        <th>用户名</th>
        <th>性别</th>
        <th>邮箱</th>
        <th>登录时间</th>
        <th>登录ip</th>
        <th>操作</th>
    </tr>
    <?php  foreach ($user as $u):?>
    <tr id="<?=$u->id?>" url="<?= yii\helpers\Url::to(['userlist/delete'])?> ">
        <td><?=$u->username?></td>
        <td><?=$u->sex?></td>
        <td><?=$u->email?></td>
        <td><?=date('Y-m-d h:i:s',$u->last_login_time)?></td>
        <td><?=$u->last_login_ip?></td>
        <td>
          <?= yii\bootstrap\Html::a('修改',['userlist/edit','id'=>$u->id],['class'=>'btn btn-default  glyphicon glyphicon-tags'])?>

          <?= yii\bootstrap\Html::a('删除',null,['class'=>'btn btn-default  glyphicon glyphicon-trash'])?>
        </td>
    </tr>
 <?php  endforeach; ?>


</table>
<?php
$js = <<<JS
  
       $("table").on("click",'tr td a:last-child',function() {
       //查找tr下面的 id 属性
       //alert("确认删除?删除后不可恢复");
       var id = $(this).closest("tr").attr('id');
       var url=$(this).closest('tr').attr('url');
       confirm('确认删除吗?');
       if(id &&url){
       $.get(url,{"id":id});
       $(this).closest("tr").remove();
       }
       
      
    })

JS;
$this->registerJs($js);


?>

