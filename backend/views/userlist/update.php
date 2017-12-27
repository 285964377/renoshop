<?php

$form = \yii\widgets\ActiveForm::begin();
//echo $form->field($model,'username')->label('账户');
echo $form->field($model,'password')->label('密码');
echo $form->field($model,'password2')->label('确认密码');
echo $form->field($model,'username')->label('邮箱');
echo  "<button type='submit' class='btn bnt-primary'>提交</button>";
$form = \yii\widgets\ActiveForm::end();

