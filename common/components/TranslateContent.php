<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 10.09.2016
 * Time: 21:46
 */

namespace common\components;

use common\models\forms\ContentForm;
use yii\i18n\DbMessageSource;

class TranslateContent extends DbMessageSource
{
    public function translate($category, $message, $language)
    {
        $cache = \Yii::$app->cache;
        $content = parent::translate($category, $message, $language);
        if (!$content) {
            $key = $cache->get(\Yii::$app->sourceLanguage);
            if (!$key) {
                $model = ContentForm::findOne(['message' => $message]);
                if ($model) {
                    $cache->set(\Yii::$app->sourceLanguage, [$message => $model->message]);
                    $content = $model->message;
                }
            } else {
                $content = $key[$message];
            }
        }
        return $content;
    }
}