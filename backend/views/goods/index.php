<?php
//$form=\yii\bootstrap\ActiveForm::begin([
//    'method'=>'get',
//    'action'=>\yii\helpers\Url::to(['goods/index']),
//    'options'=>['class'=>'form-inline']
//]);
//echo $form->field("$goods",'name')->textInput(['placeholder'=>'商品名'])->label(false);
//echo $form->field("$goods",'sn')->textInput(['placeholder'=>'货号'])->label(false);
//echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info']);
//$form=\yii\bootstrap\ActiveForm::end();

?>
<!--//搜索-->
<form class="form-inline" action="" method="get">
    <div class="form-group field-goodssearchform-name has-success">
   <input type="text"  name="name" class="form-control"placeholder="商品名查询">
    </div>
    <div class="form-group field-goodssearchform-name has-success">
        <input type="text" name="sn" class="form-control"placeholder="货号">
    </div>
    <div class="form-group field-goodssearchform-name has-success">
        <input type="text" name="shop_price" class="form-control"placeholder="售价">
    </div>
    <button type="submit" class="btn btn-default">
            <span class="glyphicon glyphicon-search"></span>搜索</button>
</form>
<table class="table">
    <h1>商品分类管理</h1>
    <?= yii\bootstrap\Html::a("添加",['goods/add'],['class'=>'btn btn-default glyphicon glyphicon-pencil'])?>
    <tr>

        <th>商品名称</th>
        <th>货号</th>
        <th>商品图片</th>
        <th>商品分类ID</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>操作</th>

    </tr>
    <?php foreach ($goods as $g): ?>

    <tr id="<?=$g->id?>" url="<?=yii\helpers\Url::to(['goods/delete'])?>">
        <td><?=$g->name?></td>
        <td><?=$g->sn?></td>
        <td><img src="<?=$g->logo?> "width="120"></td>
        <td><?=$g->goodsCategory->name?></td>
        <td><?=$g->brand->name?></td>
        <td><?=$g->market_price?></td>
        <td><?=$g->shop_price?></td>
        <td><?=$g->stock?></td>
        <td><?=$g->is_on_sale==1?'在售':"下架"?></td>
        <td><?=$g->status==1?'正常':"回收"?></td>
        <td><?=$g->sort?></td>
        <td><?=date('Y-m-d H:i',($g->create_time))?></td>
        <td>
            <?=yii\bootstrap\Html::a('相册',['goods/gallery','id'=>$g->id],['class'=>'btn btn-default glyphicon glyphicon-picture'])?>
            <?=yii\bootstrap\Html::a('修改',['goods/edit','id'=>$g->id],['class'=>'btn btn-default glyphicon  glyphicon glyphicon-wrench'])?>
            <?=yii\bootstrap\Html::a('删除',null,['class'=>'btn btn-default glyphicon  glyphicon-trash'])?>

        </td>
    </tr>
<?php  endforeach; ?>

</table>
<?php
    $js = <<<JS
    $("table").on("click",'tr td a:last-child',function() {
        //查找tr下面的 id 属性
        var id = $(this).closest("tr").attr('id');
        var url=$(this).closest('tr').attr('url');
        $.get(url,{"id":id});
        $(this).closest("tr").remove();
      
    })
    

JS;
$this->registerJs($js);

?>

