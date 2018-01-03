<?php

?>
<table class="table">
   <?= yii\bootstrap\Html::a('添加',['goods-category/add'],['class'=>'btn btn-default glyphicon glyphicon-pencil'])?>
    <tr>
        <th>名字</th>
        <th>所属分类</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php  foreach ($goods as $g):?>
    <tr id="<?=$g->id?>" url="<?=\yii\helpers\Url::to(['goods-category/delete'])?>">

        <td><?=$g->name?></td>
        <td><?=$g->parent_id==0?'顶级分类':$arr[$g->parent_id] ?></td>
        <td><?=$g->intro?></td>
        <td>
            <?=yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$g->id],['class'=>'btn btn-default glyphicon glyphicon-wrench'])?>
            <?=yii\bootstrap\Html::a('删除',null,['class'=>'btn btn-default glyphicon glyphicon-trash'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
  $js= <<<JS
     $("table").on('click','tr td  a:last-child',function() {
       var id = $(this).closest('tr').attr('id');
       var url = $(this).closest('tr').attr('url');
       $.get(url,{"id":id});
       $(this).closest('tr').remove();_
         
     });

JS;
$this->registerJs($js);

?>

