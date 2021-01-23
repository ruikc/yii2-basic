<?php

namespace app\models;

use app\models\Auth;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $verifyCode;

    private $_user;

    public function attributeLabels()
    {
        return [
            'username' => '登录账号',
            'password' => '登录密码',
            'verifyCode' => '验证码',
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'verifyCode'], 'required'],
            ['password', 'validatePassword'],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword(md5($this->password))) {
                $this->addError($attribute, '账号或密码不正确.');
            }
            if ($user['is_admin'] == 0) {
                $this->addError($attribute, '非管理员账号不能登录.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser());
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return Auth|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Auth::findByUsername($this->username);
        }

        return $this->_user;
    }
}
