<?php

use yii\db\Migration;

/**
 * Class m200326_071440_addColumnToFile
 */
class m200326_071440_addColumnToFile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->addColumn('file', 'domain', $this->string(100)->notNull()->defaultValue('')->comment('七牛域名'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropColumn('file', 'domain');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200326_071440_addColumnToFile cannot be reverted.\n";

        return false;
    }
    */
}
