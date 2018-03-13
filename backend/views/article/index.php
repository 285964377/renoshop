<?php


?>

<table class="table">
    <?= yii\bootstrap\Html::a("添加",['article/add'],['class'=>'btn btn-default glyphicon glyphicon-pencil'])?>
    <tr>
        <th>id</th>
        <th>文章名字</th>
        <th>简介</th>
        <th>文章分类ID</th>
        <th>排序</th>
        <th>状态</th>
        <th>发布时间</th>
        <th>操作</th>
    </tr>
    <?php  foreach ($Acticle as $A):?>
    <tr id="<?=$A->id?>" url="<?=yii\helpers\Url::to(['article/delete'])?>">
        <td><?=$A->id?></td>
        <td><?=$A->name?></td>
        <td><?=$A->intro?></td>
        <td><?=$A->article_category->name?></td>
        <td><?=$A->sort?></td>
        <td><?=$A->status==1?'正常':"隐藏"?></td>
        <td><?=date('Y-m-d',($A->create_time))?></td>
        <td>
            <?= yii\bootstrap\Html::a('修改',['article/edit','id'=>$A->id],['class'=>'btn btn-default glyphicon glyphicon glyphicon glyphicon-wrench'])?>
            <?= yii\bootstrap\Html::a('删除',null,['class'=>'btn btn-default glyphicon glyphicon glyphicon-trash'])?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php
$js= <<<JS

//注册点击事件
$("table").on('click','tr td a:last-child',function() {
     //获取ID
     var id = $(this).closest('tr').attr("id");
     //获取URL
     var url= $(this).closest('tr').attr('url');
     //Get形式传值
     $.get(url,{"id":id});
     //找到并且移除
     $(this).closest("tr").remove()
    
})



JS;
$this->registerJs($js);




?>