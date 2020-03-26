<?php

namespace app\controllers;

use app\models\File;
use Yii;

class QiniuController extends BaseController
{
    public $enableCsrfValidation = false;

    /**
     * 上传文件到七牛空间
     * @name: actionToken
     * @param string $type
     * @return mixed
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 17/10/27 下午1:35
     */
    public function actionToken($type = 'image') {
        if (Yii::$app->getRequest()->isPost) {
            $post = Yii::$app->getRequest()->post();
            $data = [];
            foreach ($post as $key => $val) {
                $data[str_replace('x:', '', $key)] = $val;
            }
            $model = new File();
            $model->load($data, '');
            $model->domain = 'http://' . Yii::$app->qiniu->domain . '/';
            $model->user_id = Yii::$app->user->id;
            $model->save();
            $result = [
                'name' => $model['name'],
                'id' => $model['id'],
                'domain' => $model['domain'],
                'key' => $model['key'],
            ];
            $result['url'] = Yii::$app->qiniu->downFile($result['domain'] . $result['key']);
            return $this->success('上传成功', $result);
        }
        $result = Yii::$app->qiniu->getToken($type);
        return $this->asJson($result);
    }

    /**
     * 编辑器上传图片
     * @name: actionImage
     * @return void
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/9/29 16:45
     */
    public function actionUmeditor() {
        $file = $_FILES['upfile'];
        $qiniu = Yii::$app->qiniu;
        $result = $qiniu->putFile($file['name'], $file['tmp_name']);
        if (is_string($result)) {
            $info = [
                "originalName" => $file['name'],
                "name" => $file['name'],
                "size" => $file['size'],
                "type" => $file['type'],
                "state" => 'FAILING',
            ];
        } else {
            $model = new File();
            $model->name = $file['name'];
            $model->ext = $qiniu->getExt($file['name']);
            $model->domain = $result['domain'];
            $model->key = $result['key'];
            $model->size = $file['size'];
            $model->save();

            $info = [
                "originalName" => $file['name'],
                "name" => $file['name'],
                "url" => '/' . $model->key,
                "size" => $file['size'],
                "type" => $file['type'],
                "state" => 'SUCCESS',
            ];
        }
        echo json_encode($info);
    }
}
