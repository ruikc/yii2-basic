<?php

namespace app\models;

use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $password;
    public $mobile;

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'username' => '用户账号',
            'password' => '登录密码',
            'mobile' => '手机号码'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username','mobile'], 'trim'],
            [['username','mobile'], 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\Auth', 'message' => '用户账号已经存在.'],
            ['username', 'string', 'min' => 2, 'max' => 20],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return Auth|null the saved model or null if saving fails
     */
    public function signup() {
        if (!$this->validate()) {
            return null;
        }

        $user = new Auth();
        $user->scenario = 'create';
        $user->username = $this->username;
        $user->mobile = $this->mobile;
        $user->is_admin = 1;
        $user->generateSalt();
        $user->password = $this->password;
        $user->setPassword();
        $user->generateAuthKey();
        if ($user->save()) {
            return $user;
        }
        var_dump($user->errors);
        return null;
    }
}
