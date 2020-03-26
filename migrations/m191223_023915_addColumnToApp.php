<?php

use yii\db\Migration;

/**
 * Class m191223_023915_addColumnToApp
 */
class m191223_023915_addColumnToApp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('app', 'is_force', $this->tinyInteger()->notNull()->defaultValue(0)->comment('是否强制更新'));
        $this->addColumn('app', 'intro', $this->string()->notNull()->defaultValue('')->comment('应用介绍'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('app', 'is_force');
        $this->dropColumn('app', 'intro');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191223_023915_addColumnToApp cannot be reverted.\n";

        return false;
    }
    */
}
