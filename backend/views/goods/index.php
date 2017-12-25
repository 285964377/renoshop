<?php

?>
<form class="">


</form>
<table class="table">
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

    <tr>
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
          <?=yii\bootstrap\Html::a('删除',['goods/delete','id'=>$g->id],['class'=>'btn btn-default glyphicon  glyphicon-trash'])?>
            <?=yii\bootstrap\Html::a('修改',['goods/edit','id'=>$g->id],['class'=>'btn btn-default glyphicon  glyphicon glyphicon-wrench'])?>


        </td>
    </tr>
<?php  endforeach; ?>

</table>

