<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'description')->textInput();
echo $form->field($model,'permission')->checkboxList($option);
echo  "<button type='submit' class='btn bnt-primary'>提交</button>";
$form = \yii\bootstrap\ActiveForm::end();
