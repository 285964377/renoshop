    <?php
    /**
     * @var $this \yii\web\View
     */

    $form = \yii\bootstrap\ActiveForm::begin();
    echo $form->field($modle, 'name')->textInput();//品牌名字
    echo $form->field($modle, 'intro')->textarea();//简介
    echo $form->field($modle, 'logo')->hiddenInput();//图片隐藏

    //注册js 和css文件
    $this->registerCssFile('@web/webuploader/webuploader.css');
    $this->registerJsFile('@web/webuploader/webuploader.js', [
        //指定这个文件依赖Jqurey 文件  在Jqurer文件之后加载
        'depends' => yii\web\JqueryAsset::className()
    ]);
    //写入html代码按钮
    echo <<<HTML
        <div id="uploader-demo">
            <!--用来存放item-->
            <div id="fileList" class="uploader-list"></div>
            <div id="filePicker">选择图片</div>
        </div>
        <img id="img" width="130">
HTML;
    //获得路由 转为UR列地址
    $url = \yii\helpers\Url::to(['brand/uploader']);
    //写入js代码
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
        uploader.on( 'uploadSuccess', function( file,response) {
            //$( '#'+file.id ).addClass('upload-state-done');
            console.debug(response);
            //回显图片
            $("#img").attr('src',response.url);
            //将上传文件的字段存入logo字段 放入数据库
            $("#brand-logo").val(response.url)
        });
JS;
    $this->registerJs($js);


    echo $form->field($modle, 'sort')->textInput();//排序
    echo $form->field($modle, 'status')->radioList([0 => '隐藏', 1 => '正常']);//排序
    echo "<button type='submit' class='btn btn-primary' >提交</button>";
    $form = \yii\bootstrap\ActiveForm::end();


