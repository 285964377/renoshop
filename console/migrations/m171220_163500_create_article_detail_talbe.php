<?php

use yii\db\Migration;

class m171220_163500_create_article_detail_talbe extends Migration
{
    public function up()
    {   $this->createTable('article_detail',[
         'article_id'=>$this->primaryKey(),//ID
        'content'=>$this->text(),//简介


    ]);

    }

    public function down()
    {
        echo "m171220_163500_create_article_detail_talbe cannot be reverted.\n";

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
