<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/DataTables/cs/jquery.dataTables.css');
$this->registerJsFile('@web/DataTables/js/dataTable.jqueryui.js');
$this->registerJsFile('@web/DataTables/js/jquery.dataTables.js',[
    //依赖关系
    'depends'=>yii\web\JqueryAsset::className()

]);

echo <<<HTML
<!--第二步：添加如下 HTML 代码-->
<table id="table_id_example" class="display">
    <thead>
        <tr>
            <th>Column 1</th>
            <th>Column 2</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
        </tr>
        <tr>
            <td>Row 2 Data 1</td>
            <td>Row 2 Data 2</td>
        </tr>
    </tbody>
</table>
 
 
 

HTML;
 $js= <<<JS
// <!--第三步：初始化Datatables-->
    $(document).ready( function () {
        $('#table_id_example').DataTable();
    } );
JS;
$this->registerJs($js);



