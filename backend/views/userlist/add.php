<?php

$form=yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'username')->textInput();
echo $form->field($model,'sex')->radioList(['1'=>'男',2=>'女']);
echo $form->field($model,'password')->textInput();
echo $form->field($model,'email')->textInput();
echo  "<button type='submit' class='btn bnt-primary'>提交</button>";
$form = yii\bootstrap\ActiveForm::end();


