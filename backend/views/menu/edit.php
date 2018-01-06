<?php

$form = yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'menu_name')->textInput()->label('菜单名字');
echo $form->field($model,'top_menu')->dropDownList($pername)->label('上级菜单');
echo $form->field($model,'menu_url')->dropDownList($per)->label('地址/路由');
echo $form->field($model,'sort')->textInput()->label('排序');
echo "<button type='submit' class='btn btn-primary' >提交</button>";

$form = yii\bootstrap\ActiveForm::end();

