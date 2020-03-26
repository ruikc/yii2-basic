<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class TreeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/tree/bootstrap-treeview.min.css',
    ];
    public $js = [
        'js/tree/bootstrap-treeview.min.js'
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
