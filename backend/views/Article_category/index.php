<?php

?>

<table class="table">
    <tr>
        <th>id</th>
        <th>文章名字</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php  foreach ($Article as $A): ?>
    <tr id="<?=$A->id?>" url="<?=\yii\helpers\Url::to(['article_category/delete'])?>">
        <td><?=$A->id?></td>
        <td><?=$A->name?></td>
        <td><?=$A->intro?></td>
        <td><?=$A->sort?></td>
        <td><?=$A->status?></td>
        <td>
            <?= yii\bootstrap\Html::a('添加',['article_category/add'],['class'=>'btn btn-success btn-sm  glyphicon glyphicon-plus']) ?>
            <?= yii\bootstrap\Html::a('修改',['article_category/edit','id'=>$A->id],['class'=>'btn btn-success btn-sm  glyphicon glyphicon-wrench']) ?>
            <?= yii\bootstrap\Html::a('删除',null,['class'=>'btn btn-success btn-sm  glyphicon glyphicon-trash']) ?>

        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php
$js = <<<JS
        $("table").on('click',"tr td a:last-child",function(){
            //查找tr上的id
          var id = $(this).closest('tr').attr('id');
          //查找tr上面的url数据
          var url= $(this).closest('tr').attr('url');
          //ajax传送
          $.get(url,{"id":id});
          //移除对象上的tr
          $(this).closest("tr").remove();
        })



JS;
$this->registerJs($js);




?>
