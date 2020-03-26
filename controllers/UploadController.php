<?php

namespace app\controllers;

use app\models\File;
use Yii;
use yii\web\UploadedFile;

class UploadController extends BaseController
{
    public $enableCsrfValidation = false;

    /**
     * 上传文件
     * @name: actionUpfile
     * @return \yii\web\Response
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019-06-14 13:37
     */
    public function actionUpfile() {
        return $this->success('上传成功', $this->uploadFile('file'));
    }

    /**
     * 上传文件
     * @name: uploadFile
     * @param string $file
     * @return array
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2019-06-14 13:56
     */
    private function uploadFile($file = 'file') {
//        $returnPath = Yii::$app->request->getBaseUrl() . '/';
        $returnPath = '/';
        $tmp = UploadedFile::getInstanceByName($file);
        if ($tmp) {
            $sub = 'file';
            $ext = $tmp->getExtension();
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                $sub = 'image';
            }
            $path = 'uploads/' . $sub . '/' . date('Ymd', time());
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }
            $patch = $path . '/' . date("YmdHis") . '_' . rand(1000, 9999) . '.' . $ext;
            $tmp->saveAs($patch);
            $returnPath .= $patch;

            $file = new File();
            $file->name = $tmp->name;
            $file->size = $tmp->size;
            $file->ext = $tmp->getExtension();
            $file->key = $returnPath;
            $file->save();
            return ['file_id' => $file->id, 'name' => $file->name, 'url' => Yii::$app->request->getBaseUrl() . '/' . $returnPath];
        }
        return [];
    }

    /**
     * 编辑器上传图片
     * @name: actionImage
     * @return void
     * @author: rickeryu <lhyfe1987@163.com>
     * @time: 2018/9/29 16:45
     */
    public function actionUmeditor($file = 'upfile') {
        $returnPath = '/';
        $tmp = UploadedFile::getInstanceByName($file);
        if ($tmp) {
            $sub = 'image';
            $path = 'uploads/' . $sub . '/' . date('Ymd', time());
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }
            $patch = $path . '/' . date("YmdHis") . '_' . rand(1000, 9999) . '.' . $tmp->getExtension();
            $tmp->saveAs($patch);
            $returnPath .= $patch;

            $file = new File();
            $file->name = $tmp->name;
            $file->size = $tmp->size;
            $file->ext = $tmp->getExtension();
            $file->key = $returnPath;
            $file->save();

            $info = [
                "originalName" => $file['name'],
                "name" => $file['name'],
                "url" =>  $returnPath,
                "size" => $file['size'],
                "state" => 'SUCCESS',
            ];

        } else {
            $info = [
                "state" => 'FAILING',
            ];
        }
        echo json_encode($info);
    }
}
