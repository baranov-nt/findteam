<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 09.09.2016
 * Time: 14:12
 */

namespace common\widgets\MainCarousel;

use yii\base\Widget;

class MainCarousel extends Widget
{
    public $modelLoginForm;
    public $modelSignupForm;

    public function init()
    {
        parent::init();
    }

    public function run() {
        return $this->render(
            'view',
            [
                'widget' => $this,
            ]);
    }
}