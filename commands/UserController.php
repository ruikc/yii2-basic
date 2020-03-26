<?php

namespace app\commands;

use Yii;
use app\models\SignupForm;

/**
 * Site controller
 */
class UserController extends \yii\console\Controller
{
    /**
     * 初始化管理员
     * @name: actionInit
     * @return void
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/11/17 13:05
     */
    public function actionInit($username, $password) {
        $s = new SignupForm();
        $s->username = $username;
        $s->password = $password;
        if ($s->signup()) {
            echo '初始化成功';
        } else {
            var_dump($s->errors);
        }
    }
}
