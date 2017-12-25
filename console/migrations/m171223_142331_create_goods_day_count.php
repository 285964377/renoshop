<?php

use yii\db\Migration;

class m171223_142331_create_goods_day_count extends Migration
{
    public function up()
    {
      $this->createTable('goods_day_count',[
       'day'=>$this->date()->comment("日期"),
          'count'=>$this->integer()->comment("商品数量")

      ]);
    }

    public function down()
    {
        echo "m171223_142331_create_goods_day_count cannot be reverted.\n";

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
