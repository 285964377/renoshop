<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m180104_063810_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment('收货人地址'),
            'cmbprovince'=>$this->string()->comment('省'),
            'cmbcity'=>$this->string()->comment('城市'),
            'cmbarea'=>$this->string()->comment('区县'),
            'details'=>$this->string()->comment('详情地址'),
            'phone'=>$this->string()->comment('手机号码')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
