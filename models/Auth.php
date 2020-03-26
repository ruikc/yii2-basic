<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * 用户
 * Class User
 * @package app\services
 */
class Auth extends User implements IdentityInterface
{
    public $password;

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        $label = parent::attributeLabels();
        $label['password'] = '登录密码';
        return $label;
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        $scenarios = parent::scenarios();//本行必填，不写的话就会报如上错误
        $scenarios['create'] = ['username', 'password', 'real_name', 'sex', 'mobile', 'is_admin', 'status', 'dept_id'];
        $scenarios['update'] = ['username', 'password', 'real_name', 'sex', 'mobile', 'is_admin', 'status', 'dept_id'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $rules = parent::rules();
        $rules[] = [['username'], 'trim'];
        $rules[] = [['username','status', 'dept_id'], 'required', 'on' => ['create', 'update']];
        $rules[] = [['password'], 'required', 'on' => ['create']];
        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null) {

        $user = static::find()
            ->andWhere(['access_token' => $token, 'status' => static::STATUS_ACTIVE])
            ->andWhere(['>=', 'expired_at', time()])
            ->one();

        return $user ?: null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by mobile
     *
     * @param string $username
     * @return static|null
     */
    public static function findByMobile($mobile) {
        return static::findOne(['mobile' => $mobile, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates access Token authentication key
     */
    public function generateAccessToken() {
        $this->access_token = Yii::$app->security->generateRandomString(50);
        $this->expired_at = time() + 3600 * 24;
    }


    /**
     * 获取角色
     * @name: getRole
     * @return \yii\db\ActiveQuery
     * @author: Roy<ruixl@soocedu.com>
     * @time: 2019-02-19 13:28
     */
    public function getRole() {
        return $this->hasOne(Role::className(), ['id' => 'auth_id']);
    }
}
