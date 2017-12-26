<?php

$form=yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'username')->textInput()->label("用户名");
echo $form->field($model,'sex')->radioList(['1'=>'男',2=>'女'])->label("性别");
echo $form->field($model,'password')->textInput()->label("密码");
echo $form->field($model,'email')->textInput()->label('邮箱');
echo  "<button type='submit' class='btn bnt-primary'>提交</button>";
$form = yii\bootstrap\ActiveForm::end();


