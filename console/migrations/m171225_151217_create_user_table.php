<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m171225_151217_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'sex'=>$this->integer()->comment("性别"),
            'username'=>$this->string()->comment("用户名"),
            'password'=>$this->string()->comment("密码"),
            'email'=>$this->string()->comment("邮箱"),
            'last_login_time'=>$this->integer()->comment("登录时间"),
            'last_login_ip'=>$this->integer()->comment("登录ip")

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
