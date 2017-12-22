<?php
/**
 * @var $this \yii\web\View
 */
$form= yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name')->textInput();//名称
echo $form->field($model,'parent_id')->hiddenInput();//上级分类
//-----------------------------------------------------!
//加载 需要JS文件和Css文件
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',[
  'depends'=>\yii\web\JqueryAsset::className()

]);
//js
$nodes=\backend\models\GoodsCategory::getNodes();
$js= <<<JS
    var zTreeObj;
      // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
      var setting = {
          data: {
              simpleData: {
                  enable: true,
                  idKey: "id",
                  pIdKey: "parent_id",
                  rootPId: 0
              }
          },
          callback:{
            onClick:function(event,treeId,treeNode) {
              //结点被点击 获取该结点的id
              $("#goodscategory-parent_id").val(treeNode.id);
            }  
          }
      };
      // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
      var zNodes = {$nodes};

          zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
          //展开所有列表
          zTreeObj.expandAll(true);
          //结点回显状态
          var node =zTreeObj.getNodeByParam("id",$model->id,null)
          zTreeObj.selectNode(node);
JS;
$this->registerJs($js);

echo <<<HTML
<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>

HTML;








//-----------------------------------------------------!
echo $form->field($model,'intro')->textarea();//简介
echo  "<button type='submit' class='btn bnt-primary'>提交</button>";

yii\bootstrap\ActiveForm::end();



