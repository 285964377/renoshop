<?php
/**
 * @var $this \yii\web\View
 */
$form= yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();//名字
echo $form->field($model,'sn')->textInput();//货物编号
echo $form->field($model,'logo')->hiddenInput();//图片

//注册JS文件 和 注册 CSS 文件
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    'depends' => yii\web\JqueryAsset::className()
]);
echo <<<HTML
        <div id="uploader-demo">
            <!--用来存放item-->
            <div id="fileList" class="uploader-list"></div>
            <div id="filePicker">选择图片</div>
        </div>

<img src="$model->logo" id="img" width="120">
HTML;
//将路由转为URl
$url = \yii\helpers\Url::to(['goods/uploader']);
//写入JS代码
$js = <<<JS
 // 初始化Web Uploader
        var uploader = WebUploader.create({
            // 选完文件后，是否自动上传。
            auto: true,
        
            // swf文件路径
            swf: '/webuploader/Uploader.swf',
            // 文件接收服务端。
            server:'{$url}',
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
        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on('uploadSuccess', function( file,response) {
            //$( '#'+file.id ).addClass('upload-state-done');
            console.debug(response);
            //回显图片
            $("#img").attr('src',response.url);
            //将上传文件的字段存入logo字段 放入数据库
            $("#goods-logo").val(response.url)
        });
JS;
$this->registerJs($js);
echo $form->field($model,'goods_category_id')->dropDownList($gds);//商品ID
echo $form->field($model,'brand_id')->dropDownList($brand);//品牌ID
echo $form->field($model,'market_price')->textInput();//价格
echo $form->field($model,'shop_price')->textInput();//市场价格
echo $form->field($model,'stock')->textInput();//库存
echo $form->field($model,'is_on_sale')->radioList(['1'=>'在售','0'=>'下架']);//是否在售
echo $form->field($model,'status')->radioList(['1'=>'正常','0'=>'回收']);//状态
echo $form->field($model,'sort')->textInput();//排序
echo $form->field($model,'content')->widget(\common\widgets\ueditor\Ueditor::className());
echo  "<button type='submit' class='btn btn-primary'>提交</button>";

$form=yii\bootstrap\ActiveForm::end();

