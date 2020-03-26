<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 *
 * @property int $id 用户id
 * @property string $username 用户名
 * @property string $auth_key 登录授权
 * @property string $password_hash 登录密码
 * @property int $role_id 用户角色 0-普通用户 非0-后台管理员
 * @property string $access_token app登录token
 * @property int $expired_at 超时时间
 * @property int $status 用户状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $dept_id 部门ID
 * @property string $real_name 真实姓名
 * @property string $mobile 手机号码
 * @property int $is_admin 后台管理员角色 0-普通用户
 * @property string $sex 性别
 */
class User extends \app\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['expired_at', 'status', 'created_at', 'updated_at', 'dept_id','is_admin'], 'integer'],
            [['username','real_name'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash'], 'string', 'max' => 100],
            [['access_token'], 'string', 'max' => 255],
            [['mobile'], 'string', 'max' => 11],
            [['sex'], 'string', 'max' => 2],
            [['username'], 'unique'],
            [['access_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => '用户id',
            'username' => '员工姓名',
            'auth_key' => '登录授权',
            'real_name' => '真实姓名',
            'sex' => '性别',
            'mobile' => '手机号码',
            'password_hash' => '登录密码',
            'role_id' => '用户角色', // 0-普通用户 非0-后台管理员
            'access_token' => 'app登录token',
            'is_admin' => '用户身份', //后台管理员角色 0-普通用户
            'expired_at' => '超时时间',
            'status' => '用户状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'dept_id' => '部门ID',
        ];
    }

    /**
     * 得到部门ID
     * @name: getDept
     * @return \yii\db\ActiveQuery
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/11/8 11:07 上午
     */
    public function getDept(){
        return $this->hasOne(Dept::className(),['id'=>'dept_id']);
    }

    /**
     * select2得到用户
     * @name: select2
     * @return array|\yii\db\ActiveRecord[]
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/11/7 10:07 上午
     */
    public static function select2() {
        $data = self::find()->andWhere(['>', 'status', 0])->select('id,username as text')->asArray()->all();
        return ArrayHelper::map($data, 'id', 'text');
    }
}
