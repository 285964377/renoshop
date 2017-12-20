<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m171220_162757_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment("文章名字"),
            'intro'=>$this->text()->comment("简介"),
            'article_category_id'=>$this->integer()->comment("文章分类ID"),
            'sort'=>$this->integer()->comment('排序'),
            'status'=>$this->integer()->comment('状态(-1删除 0隐藏 1正常'),
            'create_time'=>$this->integer()->comment("创建时间"),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
