<?php

use yii\db\Migration;

/**
 * Class m190604_054742_createTableAdmin
 */
class m190604_054742_createTableUser extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey()->unsigned()->comment('用户id'),
            'username' => $this->string(50)->notNull()->unique()->defaultValue('')->comment('用户名'),
            'auth_key' => $this->string(32)->notNull()->defaultValue('')->comment('登录授权'),
            'password_hash' => $this->string(100)->notNull()->defaultValue('')->comment('登录密码'),
            'access_token' => $this->string()->notNull()->unique()->defaultValue('')->comment('app登录token'),
            'expired_at' => $this->integer()->notNull()->defaultValue(0)->comment('超时时间'),

            'status' => $this->tinyInteger()->notNull()->defaultValue(10)->unsigned()->comment('用户状态'),
            'created_at' => $this->integer()->notNull()->unsigned()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->unsigned()->defaultValue(0)->comment('更新时间'),
        ], $tableOptions);

        $this->addCommentOnTable('user', '用户表');

        $this->createIndex('user_username', 'user', 'username');
        $this->createIndex('user_status', 'user', 'status');
        $this->createIndex('user_access_token', 'user', 'access_token');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190604_054742_createTableAdmin cannot be reverted.\n";

        return false;
    }
    */
}
