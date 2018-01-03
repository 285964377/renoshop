<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput()->label('用户名');
echo $form->field($model,'password')->passwordInput()->label('密码');
echo $form->field($model,'success')->checkbox([1=>1])->label('自动登录');
echo \yii\bootstrap\Html::submitButton('登陆',['class'=>'btn btn-info']);
$form =\yii\bootstrap\ActiveForm::end();

