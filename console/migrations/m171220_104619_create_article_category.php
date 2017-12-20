<?php

use yii\db\Migration;

class m171220_104619_create_article_category extends Migration
{
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),//主键
            'name' => $this->string('50'),//名称
            'intro' => $this->text(),//简介
            'sort' => $this->integer(11),//排序
            'status' => $this->integer(2)//状态

        ]);
    }

    public function down()
    {
        echo "m171220_104619_create_article_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
