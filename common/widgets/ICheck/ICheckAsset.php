<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 22.04.2016
 * Time: 13:47
 */

namespace common\widgets\ICheck;

use yii\web\AssetBundle;

/**
 * Class AssetBundle
 * @package rmrevin\yii\fontawesome
 */
class ICheckAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@bower/icheck-wnd';

    /**
     * @inherit
     */
    public $css = [
        'skins/all.css',
    ];

    /**
     * @inherit
     */
    public $js = [
        'icheck.min.js',
    ];

    public function init()
    {
        $this->registerJs();
        parent::init();
    }

    protected function registerJs()
    {
        $js = <<<SCRIPT
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
SCRIPT;
        \Yii::$app->view->registerJs($js);
        return $this;
    }
}