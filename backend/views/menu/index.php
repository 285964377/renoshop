<?php

?>

<table class="table">
    <h1>菜单管理</h1>
    <?=  yii\bootstrap\Html::a('添加菜单',['menu/add'],['class'=>'btn btn-default glyphicon glyphicon-pencil']) ?>
    <tr>
        <th>名称</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php  foreach ($model as $u):?>
    <tr id="<?=$u->id?>" url="<?=yii\helpers\Url::to(['menu/delete'])?>">
        <td><?php
           if($u->parent_id ==0){
           echo "$u->label";
            }else{
           echo '——'. "$u->label";
           }
         ?></td>
        <td><?=$u->url?></td>
        <td>
            <?= yii\bootstrap\Html::a('修改',['menu/edit','id'=>$u->id],['class'=>'btn btn-default glyphicon  glyphicon glyphicon-wrench'])?>
            <?= yii\bootstrap\Html::a('删除',null,['class'=>'btn btn-default glyphicon  glyphicon-trash'])?>
        </td>
    </tr>
    <?php  endforeach;?>
</table>

<?php
$js = <<<JS
   $("table").on("click",'tr td a:last-child',function() {
       
      var id = $(this).closest('tr').attr('id');
      var url=$(this).closest('tr').attr('url');
      $.get(url,{"id":id});
      $(this).closest('tr').remove();
   })

JS;
$this->registerJs($js);



?>

