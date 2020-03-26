<?php

use yii\db\Migration;

/**
 * Class m190523_075730_CreateTableFile
 */
class m190523_075730_CreateTableFile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey()->unsigned()->comment('文件id'),
            'user_id' => $this->integer()->notNull()->unsigned()->defaultValue(0)->comment('用户id'),
            'name' => $this->string(100)->notNull()->defaultValue('')->comment('文件名'),
            'key' => $this->string(100)->notNull()->defaultValue('')->comment('文件key'),
            'ext' => $this->string(10)->notNull()->defaultValue('')->comment('扩展名'),
            'size' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('上传文件大小'),

            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(10)->comment('状态 10-已启用 0-已删除'),
            'created_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('更新时间'),
        ], $tableOptions);

        $this->addCommentOnTable('{{%file}}','文件表');

        $this->createIndex('file_ext','{{%file}}','ext');
        $this->createIndex('file_user_id','{{%file}}','user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%file}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190523_075730_CreateTableFile cannot be reverted.\n";

        return false;
    }
    */
}
