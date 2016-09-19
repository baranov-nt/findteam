<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 12.09.2016
 * Time: 11:18
 */
namespace common\widgets\Switchery;

use yii\web\AssetBundle;

class SwitcheryAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@bower/switchery/dist';

    /**
     * @inherit
     */
    public $css = [
        'switchery.min.css',
    ];

    /**
     * @inherit
     */
    public $js = [
        'switchery.min.js',
    ];

    public function init()
    {
        $this->registerJs();
        parent::init();
    }

    protected function registerJs()
    {
        $js = <<<SCRIPT
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

            elems.forEach(function(html) {
              var switchery = new Switchery(html, { size: 'small' });
            });
SCRIPT;
        \Yii::$app->view->registerJs($js);
        return $this;
    }
}