<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m171223_145419_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(20)->comment("商品名字"),
            'sn'=>$this->integer(20)->comment("货号"),
            'logo'=>$this->string('255')->comment('商品图片'),
            'goods_category_id'=>$this->integer()->comment('商品分类ID:用于关联商品分类的数据'),
            'brand_id'=>$this->integer()->comment('品牌分类:用于关联'),
            'market_price'=>$this->decimal(10,2)->comment("市场价格"),
            'shop_price'=>$this->decimal(10,2)->comment("商品价格"),
            'stock'=>$this->integer()->comment('库存'),
            'is_on_sale'=>$this->integer(1)->comment("是否上架/下架"),
            'status'=>$this->integer(1)->comment("状态:1正常/2回收站"),
            'sort'=>$this->integer()->comment('排序'),
            'create_time'=>$this->integer()->comment("添加的时间"),
            'view_times'=>$this->integer()->comment("浏览次数")

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods');
    }
}
