<?php
$form =\yii\bootstrap\ActiveForm::begin();
echo $form->field($modle,'name')->textInput();//名称
echo $form->field($modle,'intro')->textarea();//简介
echo $form->field($modle,'sort')->textInput();//排序
echo $form->field($modle,'article_category_id')->dropDownList($option);//排序
echo $form->field($modle,'status')->radioList(['0'=>'隐藏',1=>'正常']);//排序
echo $form->field($modle,'content')->widget(\common\widgets\ueditor\Ueditor::className());
echo  "<button type='submit' class='btn bnt-primary'>提交</button>";
yii\bootstrap\ActiveForm::end();

