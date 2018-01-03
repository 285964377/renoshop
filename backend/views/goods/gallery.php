

<div id="uploader-demo">
            <!--用来存放item-->
            <div id="fileList" class="uploader-list"></div>
            <div id="filePicker">选择图片</div>

        </div>
<?php

/**
 * @var $this \yii\web\View
 */

$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    'depends' =>\yii\web\JqueryAsset::className()


]);
$url =yii\helpers\Url::to(['goods/galleryup']);//七牛云地址
$save =\yii\helpers\Url::to(['goods/save']);//这里写添加到数据库
$del=  \yii\helpers\Url::to(['goods/del']);
$y=\yii\bootstrap\Html::a("删除",null,["class"=>"btn btn-default glyphicon  glyphicon glyphicon-wrench"]);
$herf=yii\helpers\Url::to(['goods/del']);
//写入JS代码
$js = <<<JS
 // 初始化Web Uploader
        var uploader = WebUploader.create({
            // 选完文件后，是否自动上传。
            auto: true,

            // swf文件路径
            swf: '/webuploader/Uploader.swf',
            // 文件接收服务端。
            server:"{$url}",
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#filePicker',

            // 只允许选择图片文件。
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }

        });

      var html ="";
        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on('uploadSuccess',function( file,response) {
            //$( '#'+file.id ).addClass('upload-state-done');
            // console.debug(response);
            // //回显图片
            // $("#img").attr('src',response.url);
            //将上传文件的字段存入logo字段 放入数据库
            // $("#goods-logo").val(response.url);
            $.getJSON('{$save}',{"id":$id,"url":response.url},function(date) {
                
              html+='<tr id="'+date.id+'" str="{$url}" >\
              <td><img src="'+response.url+'" width="200px" ></td><td>{$y}</td>\
             </tr>';
              
              $("table").append(html);
             
            })
            
        });
        //注册点击事件点击之后则执行删除此行数据
    $("table").on("click","tr td a:last-child",function() {
          if(confirm("亲！真的要删除这一张吗？")){
              //查找ID走tr上面查找
         var id=$(this).closest("tr").attr("id");
         
         //查找URL地址
         //查找tr给予赋值后期用来移除
         var html= $(this).closest("tr"); 
         console.debug(html);
       
        $.get('$herf',{"id":id},function() {
          alert("清理成功");
          //console.debug(1);
          //也就移除此行
          html.remove();
        })}
       
    })
        
JS;
$this->registerJs($js);

?>
<table>
    <tr>
        <th>图片</th>
        <th>操作</th>
    </tr>
    <?php foreach ($Gall as $G) {?>
        <tr id="<?=$G["id"]?>">
            <td><img src="<?=$G->path?>"width="200px" ></td><td><?=\yii\bootstrap\Html::a("删除",null,["class"=>"btn btn-default glyphicon glyphicon-trash"])?></td>
        </tr>
    <?php };?>

</table>



