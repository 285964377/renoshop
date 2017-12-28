<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/DataTables/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/DataTables/media/js/jquery.js',[
    'depends'=>yii\web\JqueryAsset::className()

]);
$this->registerJsFile('@web/DataTables/media/js/jquery.dataTables.js',[
    'depends'=>yii\web\JqueryAsset::className()
]);
?>

<table class="display" id="table_id_example">
    <h1>权限管理</h1>
    <?=yii\bootstrap\Html::a('添加',['rbac/add'],['class'=>'btn btn-default glyphicon glyphicon-pencil'])?>
  <thead>
  <tr>
      <th>权限</th>
      <th>属性</th>
      <th>说明</th>
      <th>操作</th>
  </tr>
  </thead>

<tbody>

<?php  foreach ($model as $a): ?>
    <tr id="<?=$a->name?>" url="<?=yii\helpers\Url::to(['rbac/delete'])?>">
        <td><?=$a->name?></td>
        <td><?=$a->type?></td>
        <td><?=$a->description?></td>
        <td>

        <?= yii\bootstrap\Html::a('修改',['rbac/edit','id'=>$a->name],['class'=>'btn btn-default glyphicon glyphicon-wrench'])?>
        <?= \yii\helpers\Html::a('删除',null,['class'=>'btn btn-default glyphicon glyphicon-trash'])?>
        </td>
    </tr>

<?php  endforeach; ?>
</tbody>

</table>
<?php
$js = <<<JS
// <!--第三步：初始化Datatables-->
    $(document).ready( function () {
        $('#table_id_example').DataTable();
    } );
    $("table").on("click",'tr td a:last-child',function() {
        //查找tr下面的 id 属性
        var id = $(this).closest("tr").attr('id');
        var url=$(this).closest('tr').attr('url');
        $.get(url,{"id":id});
        $(this).closest("tr").remove();
      
    })

JS;
$this->registerJs($js);



