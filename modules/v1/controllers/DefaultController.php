<?php

namespace app\modules\v1\controllers;

use app\controllers\RestController;
use app\models\File;
use app\modules\models\Auth;

/**
 * Default controller for the `v1` module
 */
class DefaultController extends RestController
{
    public $except = ['login'];

    /**
     * 用户登录操作
     * @name: actionLogin
     * @return array
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/11/12 5:30 下午
     */
    public function actionLogin() {
        $request = \Yii::$app->request;
        $username = $request->post('username', '');
        $password = $request->post('password', '');
        if (!$username || !$password) {
            $this->error('参数错误');
        }
        $user = Auth::findByUsername($username);
        if (!$user || !$user->validatePassword(md5($password))) {
            $this->error('账号或密码不存在');
        }
        if (time() >= $user->expired_at) {
            $user->generateAccessToken();
            $user->save();
        }
        \Yii::$app->session->set('user_info', $user);
        return $this->success('登录成功', $user);
    }

    /**
     * 用户基本信息
     * @name: actionInfo
     * @return array
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/11/8 3:27 下午
     */
    public function actionInfo() {
        $user = \Yii::$app->session->get('user_info');
        return $this->success('获取用户成功', $user);
    }

    /**
     * 上传文件接口
     * @name: actionUpload
     * @return array
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019/12/9 3:16 下午
     */
    public function actionUpload() {
        $app = \Yii::$app;
        $file = $_FILES['upfile'];
        if (!$file) {
            $this->error('请选择上件的文件');
        }
        $result = $app->qiniu->putFile($file['name'], $file['tmp_name']);
        if ($result) {
            $model = new File();
            $model->load($file, '');
            $model->user_id = $app->user->id;
//            $model->user_id = 1;
            $model->ext = $app->qiniu->getExt($file['name']);
            $model->key = $result['key'];
            $model->domain = 'http://' . $result['domain'] . '/';
            $model->save();
            $result = [
                'id' => $model->id,
                'name' => $model->name,
                'url' => $app->qiniu->downFile($model->domain . $model->key),
            ];
            return $this->success('上传成功', $result);
        }
        $this->error('上件文件失败');
    }
}
