<?php

namespace app\assets;

use yii\web\AssetBundle;

class SliderAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap-slider.css',
    ];
    public $js = [
        'js/bootstrap-slider.min.js'
    ];
    public $depends = [
    ];
}
