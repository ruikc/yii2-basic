<?php

use yii\db\Migration;

/**
 * Class m191220_064720_createTableApp
 */
class m191220_064720_createTableApp extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%app}}', [
            'id' => $this->primaryKey()->unsigned()->comment('应用id'),
            'version' => $this->string(10)->notNull()->defaultValue('')->comment('版本号'),
            'file_id' => $this->integer()->notNull()->defaultValue(0)->comment('上件文件'),

            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(10)->comment('状态 10-已启用 20-未启用 0-已删除'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('更新时间'),
        ], $tableOptions);

        $this->addCommentOnTable('{{%app}}', '应用更新表');

        $this->createIndex('app_status', '{{%app}}', 'status');
    }

    /**
     * @inheritdoc
     */
    public function safeDown() {
        $this->dropTable('{{%app}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191220_064720_createTableApp cannot be reverted.\n";

        return false;
    }
    */
}
