<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * 七牛上传js.
 */
class AdminLteAsset extends AssetBundle
{
    public $sourcePath = null;

    public $css = [
        'http://static.ruikc.com/admin-lte/2.4.18/skins/_all-skins.min.css',
        'http://static.ruikc.com/admin-lte/2.4.18/adminlte.min.css',
    ];
    public $js = [
        'http://static.ruikc.com/admin-lte/2.4.18/adminlte.min.js'
    ];

    public $depends = [
        'rmrevin\yii\fontawesome\AssetBundle',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
