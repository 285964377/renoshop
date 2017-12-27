<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput()->label('角色名字');
echo $form->field($model,'description')->textInput()->label('描述');
echo $form->field($model,'permission')->checkboxList($arr2)->label('权限');
echo  "<button type='submit' class='btn bnt-primary'>提交</button>";
$form = \yii\bootstrap\ActiveForm::end();
