<?php

?>
<table class="table">
    <tr>

    <th>ID</th>
    <th>品牌名字</th>
    <th>简介</th>
    <th>LOGO图片</th>
    <th>排序</th>
    <th>状态</th>
    <th>操作</th>
    </tr>
    <?php foreach ($Brand as $b): ?>

    <tr id="<?=$b->id?>" url="<?= \yii\helpers\Url::to(['brand/delete'])?>"
        <td id="<?=$b->id?>"> <?=$b->id?></td>
        <td><?=$b->name?></td>
        <td><?=$b->intro?></td>
        <td><img src="<?=$b->logo?>" width="90"></td>
        <td><?=$b->sort?></td>
        <td><?=$b->status==1?'正常':"隐藏"?></td>
        <td><?= yii\bootstrap\Html::a('添加',['brand/add'],['class'=>'btn btn-success btn-sm  glyphicon glyphicon-plus'])?>
          <?= yii\bootstrap\Html::a('修改',['brand/edit','id'=>$b->id],['class'=>'btn btn-success btn-sm  glyphicon glyphicon-wrench'])?>
          <?= yii\bootstrap\Html::a('删除',null,['class'=>'btn btn-success btn-sm  glyphicon glyphicon-trash'])?>
        </td>
    </tr>
<?php endforeach;?>
</table>
<?php
/*
 * @this \yii\web\View
 */
$js =
    <<<JS
    // table 中tr td a标签最后一个 并且是子元素 触发 a 标签    
    $("table").on("click",'tr td a:last-child',function(){
     var id = $(this).closest('tr').attr("id");
     var ulr =$(this).closest('tr').attr("url");
     
     $.get(ulr,{"id":id});
     
     $(this).closest("tr").remove();
     
   })
JS;
$this->registerJs($js);
?>

