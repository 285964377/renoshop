<?php
////
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();//品牌名字
echo $form->field($model,'intro')->textarea();//简介
echo $form->field($model,'imgFile')->fileInput();//图片
//echo $form->field($model,'sort')->textInput();//排序
echo $form->field($model,'status')->radioList([0=>'隐藏',1=>'正常']);//排序
echo "<button type='submit' class='btn btn-primary' >提交</button>";
$form=\yii\bootstrap\ActiveForm::end();

