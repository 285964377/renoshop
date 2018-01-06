<?php

use yii\db\Migration;

/**
 * Handles the creation of table `region`.
 */
class m180104_104448_create_region_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('region', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment('省'),
            'parent_id'=>$this->string()->comment('市'),
            'level'=>$this->string()->comment('县')

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('region');
    }
}
