<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 12.09.2016
 * Time: 10:36
 */

namespace common\models\forms;

use common\models\Setting;

class SettingForm extends Setting
{
    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $cache = \Yii::$app->cache;
        $setting = $cache->get('setting');
        if ($setting) {
            $cache->delete('setting');
        }
    }
}