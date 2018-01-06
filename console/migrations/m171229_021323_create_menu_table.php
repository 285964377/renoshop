<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171229_021323_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
         'id' => $this->primaryKey(),
         'menu_name'=>$this->string()->comment('菜单名字'),
         'top_menu'=>$this->string()->comment('上级菜单'),
         'menu_url'=>$this->string()->comment('路由地址'),
         'sort'=>$this->string()->comment('排序')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
