<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * 七牛上传js.
 */
class QiniuAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'https://unpkg.com/qiniu-js@2.5.4/dist/qiniu.min.js',
        'js/qiniu.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
