<?php

namespace app\models;

use app\models\Auth;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property Auth|null $user This property is read-only.
 *
 */
class ResetForm extends Model
{
    public $oldPassword;
    public $password;
    public $confirmPassword;

    /**
     * @return array the column name.
     */
    public function attributeLabels()
    {
        return [
            'oldPassword' => '*原密码',
            'password' => '*新密码',
            'confirmPassword' => '*确认密码',
        ];
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['oldPassword', 'password', 'confirmPassword'], 'required'],
            [['password', 'confirmPassword'], 'string', 'length' => [6, 20]],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password', 'message' => '两次输入的密码不一致！'],
            ['oldPassword', 'validateOldPassword'],
        ];
    }

    /**
     * 验证旧密码
     * @name: validateOldPassword
     * @param $attribute
     * @param $params
     * @return void
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/11/15 4:11 下午
     */
    public function validateOldPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Auth::findOne(['id' => Yii::$app->user->id]);
            if (!$user || !$user->validatePassword(md5($this->oldPassword))) {
                $this->addError($attribute, '原密码不正确.');
            }
        }
    }


    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function reset()
    {
        if ($this->validate()) {
            $user = Auth::findOne(['id' => Yii::$app->user->id]);
            $user->password = md5($this->password);
            $user->setPassword();
            return $user->save();
        }
        return false;
    }
}
