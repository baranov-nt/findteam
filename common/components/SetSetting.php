<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 12.09.2016
 * Time: 9:46
 */

namespace common\components;

use common\models\forms\SettingForm;
use yii\base\Object;
use common\models\Setting;

class SetSetting extends Object
{
    public $show_all_cities;
    public $show_all_countries;

    public function init()
    {
        /* @var $setting \common\models\forms\SettingForm */
        parent::init();
        $cache = \Yii::$app->cache;
        $setting = $cache->get('setting');
        if (!$setting) {
            $model = Setting::findOne(1);
            $cache->set('setting', $model);
            $this->show_all_cities      = $model->show_all_cities;
            $this->show_all_countries   = $model->show_all_countries;
        } else {
            $this->show_all_cities      = $setting->show_all_cities;
            $this->show_all_countries   = $setting->show_all_countries;
        }
    }
}